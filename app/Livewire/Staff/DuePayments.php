<?php

namespace App\Livewire\Staff;

use Exception;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

#[Layout('components.layouts.staff')]
#[Title('Due Payments')]
class DuePayments extends Component
{
    use WithPagination, WithFileUploads;

    /** -----------------------------
     * UI / State
     * ------------------------------*/
    public string $search = '';
    public ?int $paymentId = null;
    public ?Payment $paymentDetail = null;

    public ?string $duePaymentMethod = '';
    public ?string $paymentNote = '';
    public $duePaymentAttachment = null;               // Livewire tmp uploaded file
    public ?array $duePaymentAttachmentPreview = null;  // preview metadata
    public string $receivedAmount = '';

    public array $filters = [
        'status' => '',
        'dateRange' => '',
    ];

    // Extend-due-date modal state
    public ?int $extendDuePaymentId = null;
    public ?string $newDueDate = null;
    public string $extensionReason = '';

    protected $listeners = ['refreshPayments' => '$refresh'];

    /** Max upload size in KB (2_048 = 2MB). Increase if you want bigger receipts. */
    public int $maxAttachmentKb = 2048;

    /** Allowed mime/extension pairs */
    private array $allowedMimes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

    /** -----------------------------
     * Validation
     * ------------------------------*/
    protected function rules(): array
    {
        return [
            'receivedAmount'        => ['required', 'numeric', 'min:0.01'],
            'duePaymentMethod'      => ['required', 'string', 'max:100'],
            'paymentNote'           => ['nullable', 'string', 'max:2000'],
            'duePaymentAttachment'  => ['nullable', 'file', 'mimes:' . implode(',', $this->allowedMimes), 'max:' . $this->maxAttachmentKb],

            // Extend due date
            'newDueDate'            => ['nullable', 'date', 'after:today'],
            'extensionReason'       => ['nullable', 'string', 'min:5', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'duePaymentAttachment.max' => "The due payment attachment must not be greater than {$this->maxAttachmentKb} kilobytes.",
            'duePaymentAttachment.mimes' => 'Supported file types: jpg, jpeg, png, gif, pdf.',
        ];
    }

    /** -----------------------------
     * Lifecycle
     * ------------------------------*/
    public function mount(): void
    {
        // no-op for now
    }

    /** -----------------------------
     * File handling
     * ------------------------------*/
    public function updatedDuePaymentAttachment(): void
    {
        $this->validateOnly('duePaymentAttachment');

        if ($this->duePaymentAttachment) {
            $this->duePaymentAttachmentPreview = $this->getFilePreviewInfo($this->duePaymentAttachment);
        } else {
            $this->duePaymentAttachmentPreview = null;
        }
    }

    private function getFilePreviewInfo($file): ?array
    {
        if (!$file) {
            return null;
        }

        $ext = strtolower($file->getClientOriginalExtension());

        $info = [
            'name'   => $file->getClientOriginalName(),
            'type'   => 'unknown',
            'icon'   => 'bi-file-earmark',
            'color'  => 'text-secondary',
            'preview'=> null,
        ];

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $info['type']  = 'image';
            $info['icon']  = 'bi-file-earmark-image';
            $info['color'] = 'text-primary';
            try {
                $info['preview'] = $file->temporaryUrl();
            } catch (\Throwable $e) {
                $info['preview'] = null;
            }
        } elseif ($ext === 'pdf') {
            $info['type']  = 'pdf';
            $info['icon']  = 'bi-file-earmark-pdf';
            $info['color'] = 'text-danger';
        }

        return $info;
    }

    /** -----------------------------
     * Modals / Actions
     * ------------------------------*/
    public function getPaymentDetails(int $paymentId): void
    {
        $this->resetValidation();

        $this->paymentId   = $paymentId;
        $this->paymentDetail = Payment::with(['sale.customer', 'sale.items'])->findOrFail($paymentId);

        $this->duePaymentMethod = (string)($this->paymentDetail->due_payment_method ?? '');
        $this->paymentNote      = '';
        $this->receivedAmount   = ''; // let user enter a partial or full amount
        $this->duePaymentAttachment = null;
        $this->duePaymentAttachmentPreview = null;

        $this->dispatch('openModal', 'payment-detail-modal');
    }

    public function submitPayment(): void
    {
        $this->validate([
            'receivedAmount'       => ['required', 'numeric', 'min:0.01'],
            'duePaymentMethod'     => ['required', 'string', 'max:100'],
            'duePaymentAttachment' => ['nullable', 'file', 'mimes:' . implode(',', $this->allowedMimes), 'max:' . $this->maxAttachmentKb],
            'paymentNote'          => ['nullable', 'string', 'max:2000'],
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::lockForUpdate()->findOrFail($this->paymentId);

            $due = (float) $payment->amount;                // current due on this row
            $received = (float) $this->receivedAmount;

            if ($received > $due) {
                DB::rollBack();
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Entered amount is too large. Please enter an amount less than or equal to the due amount.'
                ]);
                return;
            }

            // Handle optional attachment
            $attachmentPath = $payment->due_payment_attachment; // keep existing if none uploaded
            if ($this->duePaymentAttachment) {
                // (Optional) delete previous file if you want to prevent orphan files:
                // if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                //     Storage::disk('public')->delete($attachmentPath);
                // }

                $fileExt = $this->duePaymentAttachment->getClientOriginalExtension();
                $receiptName = now()->timestamp . '-payment-' . $payment->id . '-' . Str::random(6) . '.' . $fileExt;
                $stored = $this->duePaymentAttachment->storeAs('public/due-receipts', $receiptName);
                // $stored returns "public/due-receipts/filename" → save the path relative to public disk
                $attachmentPath = 'due-receipts/' . $receiptName;
            }

            // Update current payment row to the received amount and mark as pending for approval
            $payment->update([
                'amount'                => $received,
                'due_payment_method'    => $this->duePaymentMethod,
                'due_payment_attachment'=> $attachmentPath,
                'status'                => 'pending',
                'payment_date'          => now(),
            ]);

            $remaining = round($due - $received, 2);

            // Create a new payment row for the leftover due (if any)
            if ($remaining > 0.00) {
                Payment::create([
                    'sale_id'      => $payment->sale_id,
                    'amount'       => $remaining,
                    'due_date'     => $payment->due_date, // keep same due date or set a new one if needed
                    'status'       => null,
                    'is_completed' => false,
                ]);
            }

            // Append note on the Sale (optional)
            if (!empty($this->paymentNote)) {
                $sale = $payment->sale()->lockForUpdate()->first();
                $existing = (string)($sale->notes ?? '');
                $noteLine = "Payment submitted on " . now()->format('Y-m-d H:i') . ": " . $this->paymentNote;
                $sale->update(['notes' => trim($existing . "\n" . $noteLine)]);
            }

            DB::commit();

            $this->dispatch('closeModal', 'payment-detail-modal');
            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Payment submitted and sent for admin approval.'
            ]);

            // Reset form state
            $this->reset([
                'paymentDetail',
                'duePaymentMethod',
                'duePaymentAttachment',
                'duePaymentAttachmentPreview',
                'paymentNote',
                'receivedAmount',
                'paymentId',
            ]);

            // refresh list
            $this->dispatch('refreshPayments');

        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to submit payment: ' . $e->getMessage(),
            ]);
        }
    }

    public function openExtendDueModal(int $paymentId): void
    {
        $this->resetValidation();

        $this->extendDuePaymentId = $paymentId;
        $payment = Payment::findOrFail($paymentId);

        $dueDate = $payment->due_date instanceof Carbon
            ? $payment->due_date
            : Carbon::parse($payment->due_date);

        $this->newDueDate = $dueDate->copy()->addDays(7)->format('Y-m-d');
        $this->extensionReason = '';

        $this->dispatch('openModal', 'extend-due-modal');
    }

    public function extendDueDate(): void
    {
        $this->validate([
            'newDueDate'      => ['required', 'date', 'after:today'],
            'extensionReason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::lockForUpdate()->findOrFail($this->extendDuePaymentId);

            $oldDue = ($payment->due_date instanceof Carbon)
                ? $payment->due_date->format('Y-m-d')
                : Carbon::parse($payment->due_date)->format('Y-m-d');

            $payment->update([
                'due_date' => $this->newDueDate,
            ]);

            $sale = $payment->sale()->lockForUpdate()->first();
            $existing = (string)($sale->notes ?? '');
            $noteLine = "Due date extended on " . now()->format('Y-m-d H:i') .
                        " from {$oldDue} to {$this->newDueDate}. Reason: {$this->extensionReason}";
            $sale->update(['notes' => trim($existing . "\n" . $noteLine)]);

            DB::commit();

            $this->dispatch('closeModal', 'extend-due-modal');
            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Due date extended successfully.',
            ]);

            $this->reset(['extendDuePaymentId', 'newDueDate', 'extensionReason']);
            $this->dispatch('refreshPayments');

        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to extend due date: ' . $e->getMessage(),
            ]);
        }
    }

    /** -----------------------------
     * Listing / Render
     * ------------------------------*/
    public function render()
    {
        $query = Payment::query()
            ->where('is_completed', false)
            ->whereHas('sale', fn($q) => $q->where('user_id', auth()->id()))
            ->with(['sale.customer']);

        // Search (by invoice number / customer name / phone)
        if ($this->search !== '') {
            $term = '%' . $this->search . '%';
            $query->whereHas('sale', function ($q) use ($term) {
                $q->where('invoice_number', 'like', $term)
                  ->orWhereHas('customer', function ($q2) use ($term) {
                      $q2->where('name', 'like', $term)
                         ->orWhere('phone', 'like', $term);
                  });
            });
        }

        // Status filter
        if (($this->filters['status'] ?? '') === 'null') {
            $query->whereNull('status');
        } elseif (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // Date range filter: "YYYY-MM-DD to YYYY-MM-DD"
        if (!empty($this->filters['dateRange']) && str_contains($this->filters['dateRange'], 'to')) {
            [$startDate, $endDate] = array_map('trim', explode('to', $this->filters['dateRange'], 2));
            if ($startDate && $endDate) {
                $query->whereBetween('due_date', [$startDate, $endDate]);
            }
        }

        $duePayments = $query->orderBy('due_date', 'asc')->paginate(10);

        return view('livewire.staff.due-payments', [
            'duePayments' => $duePayments,
        ]);
    }
}
