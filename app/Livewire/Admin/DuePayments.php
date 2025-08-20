<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Psy\Readline\Hoa\_Protocol\Readline;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Exception;



#[Title("Due Payments")]
#[Layout('components.layouts.admin')]

class DuePayments extends Component
{
   use WithPagination, WithFileUploads;
    
    public $search = '';
    public $selectedPayment = null;
    public $paymentDetail = null;
    public $duePaymentAttachment;
    public $paymentId;
    public $duePaymentMethod = '';
    public $paymentNote = '';
    public $duePaymentAttachmentPreview;
    public $receivedAmount = '';
    public $filters = [
        'status' => '',
        'dateRange' => '',
    ];

    // Add these properties to your existing properties list
    public $extendDuePaymentId;
    public $newDueDate;
    public $extensionReason = '';

    protected $listeners = ['refreshPayments' => '$refresh'];

    public function mount()
    {
        // Initialize component
    }

    public function updatedDuePaymentAttachment()
    {
        $this->validate([
            'duePaymentAttachment' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->duePaymentAttachment) {
            $previewInfo = $this->getFilePreviewInfo($this->duePaymentAttachment);
            $this->duePaymentAttachmentPreview = $previewInfo;
        }
    }

    public function getPaymentDetails($paymentId)
    {
        $this->paymentId = $paymentId;
        $this->paymentDetail = Payment::with(['sale.customer', 'sale.items'])->find($paymentId);
        $this->duePaymentMethod = $this->paymentDetail->due_payment_method ?? '';
        $this->paymentNote = '';
        $this->duePaymentAttachment = null;
        $this->duePaymentAttachmentPreview = null;
        
        $this->dispatch('openModal', 'payment-detail-modal');
    }

    public function submitPayment()
    {
        $this->validate([
            'receivedAmount' => 'required|numeric|min:0.01',
            'duePaymentMethod' => 'required',
            'duePaymentAttachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($this->paymentId);
            
            // Store attachment if provided
            $attachmentPath = $payment->due_payment_attachment;
            if ($this->duePaymentAttachment) {
                $receiptName = time() . '-payment-' . $payment->id . '.' . $this->duePaymentAttachment->getClientOriginalExtension();
                $this->duePaymentAttachment->storeAs('public/due-receipts', $receiptName);
                $attachmentPath = "due-receipts/{$receiptName}";
            }
            $receivedAmount = floatval($this->receivedAmount);
            $remainingAmount = $payment->amount - $receivedAmount;
            if($payment->amount >= $receivedAmount) {
                 $payment->update([
                'amount' => $receivedAmount,
                'due_payment_method' => $this->duePaymentMethod,
                'due_payment_attachment' => $attachmentPath,
                'status' => 'pending',  // Change status to pending for admin approval
                'payment_date' => now(),
            ]);
                // If the received amount is less than the total amount, update the payment
            } else {
                // If the received amount is equal to or greater than the total amount, mark as completed
                DB::rollBack();
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Entered amount is too large. Please enter an amount less than or equal to the due amount.'
                ]);
                return;
            }
            $payment->update([
                'amount' => $receivedAmount,
                'due_payment_method' => $this->duePaymentMethod,
                'due_payment_attachment' => $attachmentPath,
                'status' => 'pending',  // Change status to pending for admin approval
                'payment_date' => now(),
            ]);

            // Add a note to track this payment submission
            if ($this->paymentNote) {
                $payment->sale->update([
                    'notes' => ($payment->sale->notes ? $payment->sale->notes . "\n" : '') . 
                        "Payment received on " . now()->format('Y-m-d H:i') . ": " . $this->paymentNote
                ]);
            }

            // If there is a remaining amount, create a new Payment record with status null
            if ($remainingAmount > 0.01) { // Use a small threshold to avoid floating point issues
                Payment::create([
                    'sale_id' => $payment->sale_id,
                    'amount' => $remainingAmount,
                    'due_date' => $payment->due_date, // or set a new due date if needed
                    'status' => null,
                    'is_completed' => false,
                ]);
            }

            DB::commit();
            
            $this->dispatch('closeModal', 'payment-detail-modal');
            $this->dispatch('showToast', [
                'type' => 'success', 
                'message' => 'Payment submitted successfully and sent for admin approval'
            ]);
            
            $this->reset(['paymentDetail', 'duePaymentMethod', 'duePaymentAttachment', 'paymentNote']);
            
        } catch (Exception $e) {
            DB::rollback();
            $this->dispatch('showToast', [
                'type' => 'error', 
                'message' => 'Failed to submit payment: ' . $e->getMessage()
            ]);
        }
    }

    public function openExtendDueModal($paymentId)
    {
        $this->extendDuePaymentId = $paymentId;
        $payment = Payment::findOrFail($paymentId);
        
        // Set initial new due date to 7 days from current due date
        $this->newDueDate = $payment->due_date->addDays(7)->format('Y-m-d');
        $this->extensionReason = '';
        
        $this->dispatch('openModal', 'extend-due-modal');
    }

    public function extendDueDate()
    {
        $this->validate([
            'newDueDate' => 'required|date|after:today',
            'extensionReason' => 'required|min:5',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($this->extendDuePaymentId);
            $oldDueDate = $payment->due_date->format('Y-m-d');
            
            // Update the due date
            $payment->update([
                'due_date' => $this->newDueDate,
            ]);

            // Add a note to track this extension
            $payment->sale->update([
                'notes' => ($payment->sale->notes ? $payment->sale->notes . "\n" : '') . 
                    "Due date extended on " . now()->format('Y-m-d H:i') . " from {$oldDueDate} to {$this->newDueDate}. Reason: {$this->extensionReason}"
            ]);
            
            DB::commit();
            
            $this->dispatch('closeModal', 'extend-due-modal');
            $this->dispatch('showToast', [
                'type' => 'success', 
                'message' => 'Due date extended successfully'
            ]);
            
            $this->reset(['extendDuePaymentId', 'newDueDate', 'extensionReason']);
            
        } catch (Exception $e) {
            DB::rollback();
            $this->dispatch('showToast', [
                'type' => 'error', 
                'message' => 'Failed to extend due date: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get file info with appropriate preview or icon
     * 
     * @param mixed $file Uploaded file object
     * @return array File information with type, name, and preview
     */
    private function getFilePreviewInfo($file)
    {
        if (!$file) {
            return null;
        }
        
        $result = [
            'name' => $file->getClientOriginalName(),
            'type' => 'unknown',
            'icon' => 'bi-file-earmark',
            'color' => 'text-secondary',
            'preview' => null
        ];
        
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $result['type'] = 'image';
            $result['icon'] = 'bi-file-earmark-image';
            $result['color'] = 'text-primary';
            
            try {
                $result['preview'] = $file->temporaryUrl();
            } catch (\Exception $e) {
                $result['preview'] = null;
            }
        } elseif ($extension === 'pdf') {
            $result['type'] = 'pdf';
            $result['icon'] = 'bi-file-earmark-pdf';
            $result['color'] = 'text-danger';
        }
        
        return $result;
    }

    public function render()
    {
        $query = Payment::query()
            ->where('is_completed', false)
            // Remove the whereNull('status') line to show all statuses
            ->whereHas('sale', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['sale.customer']);
            
        // Apply search filter
        if ($this->search) {
            $query->whereHas('sale', function($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhereHas('customer', function($q2) {
                      $q2->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                  });
            });
        }
        
        // Apply status filter - modify to include null values when needed
        if ($this->filters['status'] === 'null') {
            $query->whereNull('status');
        } elseif ($this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }
        
        // Apply date range filter
        if ($this->filters['dateRange']) {
            [$startDate, $endDate] = explode(' to ', $this->filters['dateRange']);
            $query->whereBetween('due_date', [$startDate, $endDate]);
        }
        
        $duePayments = $query->orderBy('due_date', 'asc')->paginate(10);
        
        return view('livewire.admin.due-payments', [
            'duePayments' => $duePayments
        ]);
    }
}
