<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Sale;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SaleItem;
use App\Models\WatchStock;
use App\Models\WatchDetail;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Models\AdminSale;
#[Layout('components.layouts.admin')]
#[Title('Store Billing Page')]
class StoreBilling extends Component
{
    use WithFileUploads;

    public $search = '';
    public $searchResults = [];
    public $cart = [];
    public $quantities = [];
    public $discounts = [];
    public $watchDetails = null;
    public $subtotal = 0;
    public $totalDiscount = 0;
    public $grandTotal = 0;

    public $customers = [];
    public $customerId = null;
    public $customerType = 'retail';

    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerEmail = '';
    public $newCustomerType = 'retail';
    public $newCustomerAddress = '';
    public $newCustomerNotes = '';

    public $saleNotes = '';
    public $paymentType = 'full';
    public $paymentMethod = '';
    public $paymentReceiptImage;
    public $paymentReceiptImagePreview = null;
    public $bankName = '';

    public $initialPaymentAmount = 0;
    public $initialPaymentMethod = '';
    public $initialPaymentReceiptImage;
    public $initialPaymentReceiptImagePreview = null;
    public $initialBankName = '';

    public $balanceAmount = 0;
    public $balancePaymentMethod = '';
    public $balanceDueDate = '';
    public $balancePaymentReceiptImage;
    public $balancePaymentReceiptImagePreview = null;
    public $balanceBankName = '';

    public $lastSaleId = null;
    public $showReceipt = false;

    public $duePaymentMethod = '';
    public $duePaymentAttachment;
    public $duePaymentAttachmentPreview = null;

    protected $listeners = ['quantityUpdated' => 'updateTotals'];

    public function mount()
    {
        $this->loadCustomers();
        $this->updateTotals();
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));
    }

    public function loadCustomers()
    {
        $this->customers = Customer::orderBy('name')->get();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            // Search directly from main store inventory (not staff stock)
            $this->searchResults = WatchDetail::join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
                ->join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
                ->select(
                    'watch_details.*',
                    'watch_prices.selling_price as selling_price',
                    'watch_prices.discount_price as discount_price',
                    'watch_stocks.available_stock'
                )
                ->where('watch_stocks.available_stock', '>', 0)
                ->where(function($query) {
                    $query->where('watch_details.code', 'like', '%' . $this->search . '%')
                        ->orWhere('watch_details.model', 'like', '%' . $this->search . '%')
                        ->orWhere('watch_details.barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('watch_details.brand', 'like', '%' . $this->search . '%')
                        ->orWhere('watch_details.name', 'like', '%' . $this->search . '%');
                })
                ->take(50)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addToCart($watchId)
    {
        // Get product details from store inventory
        $watch = WatchDetail::join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
            ->where('watch_details.id', $watchId)
            ->select(
                'watch_details.*',
                'watch_prices.selling_price as selling_price',
                'watch_prices.discount_price as discount_price',
                'watch_stocks.available_stock'
            )
            ->first();

        if (!$watch || $watch->available_stock <= 0) {
            $this->dispatch('showToast', ['type' => 'danger', 'message' => 'This product is not available in store.']);
            return;
        }

        $existingItem = collect($this->cart)->firstWhere('id', $watchId);

        if ($existingItem) {
            if (($this->quantities[$watchId] + 1) > $watch->available_stock) {
                $this->dispatch('showToast', ['type' => 'warning', 'message' => "Maximum available quantity ({$watch->available_stock}) reached."]);
                return;
            }
            $this->quantities[$watchId]++;
        } else {
            $discountPrice = $watch->discount_price ?? 0;
            $this->cart[$watchId] = [
                'id' => $watch->id,
                'code' => $watch->code,
                'name' => $watch->name,
                'model' => $watch->model,
                'brand' => $watch->brand,
                'image' => $watch->image,
                'price' => $watch->selling_price ?? 0,
                'discountPrice' => $discountPrice ?? 0,
                'inStock' => $watch->available_stock ?? 0,
            ];

            $this->quantities[$watchId] = 1;
            $this->discounts[$watchId] = $discountPrice;
        }

        $this->search = '';
        $this->searchResults = [];
        $this->updateTotals();
    }

    public function validateQuantity($watchId)
    {
        if (!isset($this->cart[$watchId]) || !isset($this->quantities[$watchId])) {
            return;
        }

        $maxAvailable = $this->cart[$watchId]['inStock'];
        $currentQuantity = (int)$this->quantities[$watchId];

        if ($currentQuantity <= 0) {
            $this->quantities[$watchId] = 1;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => 'Minimum quantity is 1'
            ]);
        } elseif ($currentQuantity > $maxAvailable) {
            $this->quantities[$watchId] = $maxAvailable;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }

        $this->updateTotals();
    }

    public function updateQuantity($watchId, $quantity)
    {
        if (!isset($this->cart[$watchId])) {
            return;
        }

        $maxAvailable = $this->cart[$watchId]['inStock'];

        if ($quantity <= 0) {
            $quantity = 1;
        } elseif ($quantity > $maxAvailable) {
            $quantity = $maxAvailable;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }

        $this->quantities[$watchId] = $quantity;
        $this->updateTotals();
    }

    public function updateDiscount($watchId, $discount)
    {
        $this->discounts[$watchId] = max(0, min($discount, $this->cart[$watchId]['price']));
        $this->updateTotals();
    }

    public function removeFromCart($watchId)
    {
        unset($this->cart[$watchId]);
        unset($this->quantities[$watchId]);
        unset($this->discounts[$watchId]);
        $this->updateTotals();
    }

    public function showDetail($watchId)
    {
        $this->watchDetails = WatchDetail::join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->select(
                'watch_details.*',
                'watch_stocks.selling_price as selling_price',
                'watch_stocks.discount_per_unit as discount_price',
                'watch_stocks.quantity as total_stock',
                'watch_stocks.sold_count as sold_stock',
                DB::raw('(watch_stocks.quantity - watch_stocks.sold_count) as available_stock')
            )
            ->where('watch_details.id', $watchId)
            ->first();

        $this->js('$("#viewDetailModal").modal("show")');
    }

    public function updateTotals()
    {
        $this->subtotal = 0;
        $this->totalDiscount = 0;

        foreach ($this->cart as $id => $item) {
            $price = $item['price'] ?: $item['price'];
            $this->subtotal += $price * $this->quantities[$id];
            $this->totalDiscount += $this->discounts[$id] * $this->quantities[$id];
        }

        $this->grandTotal = $this->subtotal - $this->totalDiscount;
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->quantities = [];
        $this->discounts = [];
        $this->updateTotals();
    }

    public function saveCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required|min:3',
            'newCustomerPhone' => 'required',
        ]);

        $customer = Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
            'email' => $this->newCustomerEmail,
            'type' => $this->newCustomerType,
            'address' => $this->newCustomerAddress,
            'notes' => $this->newCustomerNotes,
        ]);

        $this->loadCustomers();

        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->newCustomerEmail = '';
        $this->newCustomerAddress = '';
        $this->newCustomerNotes = '';

        $this->js('$("#addCustomerModal").modal("hide")');
        $this->js('swal.fire("Success", "Customer added successfully!", "success")');
    }

    public function calculateBalanceAmount()
    {
        if ($this->paymentType == 'partial') {
            if ($this->initialPaymentAmount > $this->grandTotal) {
                $this->initialPaymentAmount = $this->grandTotal;
            }

            $this->balanceAmount = $this->grandTotal - $this->initialPaymentAmount;
        } else {
            $this->initialPaymentAmount = 0;
            $this->balanceAmount = 0;
        }
    }

    public function updatedPaymentType($value)
    {
        if ($value == 'partial') {
            $this->initialPaymentAmount = round($this->grandTotal / 2, 2);
            $this->calculateBalanceAmount();
        } else {
            $this->initialPaymentAmount = 0;
            $this->initialPaymentMethod = '';
            $this->initialPaymentReceiptImage = null;
            $this->initialPaymentReceiptImagePreview = null;
            $this->initialBankName = '';

            $this->balanceAmount = 0;
            $this->balancePaymentMethod = '';
            $this->balancePaymentReceiptImage = null;
            $this->balancePaymentReceiptImagePreview = null;
            $this->balanceBankName = '';
        }
    }

    // ...Keep all file upload, validation, and payment logic as in staff billing...

    // (Copy all methods for payment receipt handling, due payment, etc. from staff Billing.php)

    // Only change logic that references staff_products to watch_stocks

public function completeSale()
{
    if (empty($this->cart)) {
        $this->js('swal.fire("Error", "Please add items to the cart.", "error")');
        return;
    }

    $this->validate([
        'customerId' => 'required',
        'paymentType' => 'required|in:full,partial',
    ]);

    // Validate full or partial payments
    // ... (re-use your same validation logic from `completeSale()`)
// Validate full or partial payments
if ($this->paymentType === 'full') {
    if ($this->grandTotal <= 0 || !$this->paymentMethod) {
        $this->js('swal.fire("Error", "Please enter a valid amount and select a payment method for full payment.", "error")');
        return;
    }

    if ($this->paymentMethod === 'cheque' && !$this->bankName) {
        $this->js('swal.fire("Error", "Please provide a bank name for the cheque payment.", "error")');
        return;
    }
} elseif ($this->paymentType === 'partial') {
    if ($this->initialPaymentAmount === null || $this->initialPaymentAmount < 0 || !$this->initialPaymentMethod) {
        $this->js('swal.fire("Error", "Please enter a valid initial payment amount and select a payment method.", "error")');
        return;
    }

    if ($this->initialPaymentMethod === 'cheque' && !$this->initialBankName) {
        $this->js('swal.fire("Error", "Please provide a bank name for the initial cheque payment.", "error")');
        return;
    }

    if ($this->balanceAmount > 0) {
        if (!$this->balancePaymentMethod) {
            $this->js('swal.fire("Error", "Please select a payment method for the balance amount.", "error")');
            return;
        }

        // Only require bank name if payment method is cheque, otherwise allow null
        if ($this->balancePaymentMethod === 'cheque' && !$this->balanceBankName) {
            $this->balanceBankName = null; // Set to null if not provided
            // Do not show error, just proceed
        }

        if (!$this->balanceDueDate) {
            $this->js('swal.fire("Error", "Please provide a due date for the balance payment.", "error")');
            return;
        }
    }
}

    try {
        DB::beginTransaction();

        // 1. Create Sale record
        $sale = Sale::create([
            'invoice_number'   => Sale::generateInvoiceNumber(),
            'customer_id'      => $this->customerId,
            'user_id'          => auth()->id(),
            'customer_type'    => Customer::find($this->customerId)->type,
            'subtotal'         => $this->subtotal,
            'discount_amount'  => $this->totalDiscount,
            'total_amount'     => $this->grandTotal,
            'payment_type'     => $this->paymentType,
            'payment_status'   => $this->paymentType === 'full' ? 'paid' : 'partial',
            'notes'            => $this->saleNotes,
            'due_amount'       => $this->balanceAmount,
        ]);

        // 2. Create AdminSale record
        $adminSale = AdminSale::create([
            'sale_id'        => $sale->id,
            'admin_id'       => auth()->id(),
            'total_quantity' => array_sum($this->quantities),
            'total_value'    => $this->grandTotal,
            'sold_quantity'  => 0, // will update below
            'sold_value'     => 0, // will update below
            'status'         => 'partial', // will update below
        ]);

        $totalSoldQty = 0;
        $totalSoldVal = 0;

        foreach ($this->cart as $id => $item) {
           $watchStock = WatchStock::where('watch_id', $item['id'])->first();
if (!$watchStock || $watchStock->available_stock < $this->quantities[$id]) {
    throw new Exception("Insufficient stock for item: {$item['name']}. Available: {$watchStock->available_stock}");
}

            $price = $item['price'] ?: 0;
            $itemDiscount = $this->discounts[$id] ?? 0;
            $total = ($price * $this->quantities[$id]) - ($itemDiscount * $this->quantities[$id]);

            // Insert sale item (linked to sales table)
            SaleItem::create([
                'sale_id'    => $sale->id,
                'watch_id'   => $item['id'],
                'watch_code' => $item['code'],
                'watch_name' => $item['name'],
                'quantity'   => $this->quantities[$id],
                'unit_price' => $price,
                'discount'   => $itemDiscount,
                'total'      => $total,
            ]);

            // Update stock
 $watchStock->sold_count += $this->quantities[$id];
$watchStock->available_stock -= $this->quantities[$id];
$watchStock->save();

            $totalSoldQty += $this->quantities[$id];
            $totalSoldVal += $total;
        }

        // Update admin sale status and sold values
        $adminSale->sold_quantity = $totalSoldQty;
        $adminSale->sold_value = $totalSoldVal;
        $adminSale->status = $totalSoldQty == $adminSale->total_quantity ? 'completed' : 'partial';
        $adminSale->save();

        // Handle payment (link to sale)
        if ($this->paymentType == 'full') {
            $receiptPath = null;
            if ($this->paymentReceiptImage) {
                $receiptPath = $this->paymentReceiptImage->store('admin-payment-receipts', 'public');
            }

            Payment::create([
                'sale_id'         => $sale->id,
                'admin_sale_id'   => $adminSale->id,
                'amount'          => $this->grandTotal,
                'payment_method'  => $this->paymentMethod,
                'payment_reference' => $receiptPath,
                'bank_name'       => $this->paymentMethod == 'cheque' ? $this->bankName : null,
                'is_completed'    => true,
                'payment_date'    => now(),
                'status'          => 'Paid',
            ]);
        } else {
            // Initial partial payment
            if ($this->initialPaymentAmount > 0) {
                $initialReceiptPath = null;
                if ($this->initialPaymentReceiptImage) {
                    $initialReceiptPath = $this->initialPaymentReceiptImage->store('admin-payment-receipts', 'public');
                }

                Payment::create([
                    'sale_id'         => $sale->id,
                    'admin_sale_id'   => $adminSale->id,
                    'amount'          => $this->initialPaymentAmount,
                    'payment_method'  => $this->initialPaymentMethod,
                    'payment_reference' => $initialReceiptPath,
                    'bank_name'       => $this->initialPaymentMethod == 'cheque' ? $this->initialBankName : null,
                    'is_completed'    => true,
                    'payment_date'    => now(),
                    'status'          => 'Paid',
                ]);
            }

            // Balance due payment
            if ($this->balanceAmount > 0) {
                $balanceReceiptPath = null;
                if ($this->balancePaymentReceiptImage) {
                    $balanceReceiptPath = $this->balancePaymentReceiptImage->store('admin-payment-receipts', 'public');
                }

                Payment::create([
                    'sale_id'         => $sale->id,
                    'admin_sale_id'   => $adminSale->id,
                    'amount'          => $this->balanceAmount,
                    'payment_method'  => $this->balancePaymentMethod,
                    'payment_reference' => $balanceReceiptPath,
                    'bank_name'       => $this->balancePaymentMethod == 'cheque' ? $this->balanceBankName : null,
                    'is_completed'    => false,
                    'due_date'        => $this->balanceDueDate,
                ]);
            }
        }

        DB::commit();

        $this->lastSaleId = $sale->id;
        $this->showReceipt = true;
        $this->js('swal.fire("Success", "Sale completed successfully! Invoice #' . $sale->invoice_number . '", "success")');
        $this->clearCart();
        $this->resetPaymentInfo();
        $this->js('$("#receiptModal").modal("show")');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Admin sale error: ' . $e->getMessage());
        $this->js('swal.fire("Error", "An error occurred: ' . $e->getMessage() . '", "error")');
    }
}


    public function resetPaymentInfo()
    {
        $this->paymentType = 'full';
        $this->paymentMethod = '';
        $this->paymentReceiptImage = null;
        $this->paymentReceiptImagePreview = null;
        $this->bankName = '';

        $this->initialPaymentAmount = 0;
        $this->initialPaymentMethod = '';
        $this->initialPaymentReceiptImage = null;
        $this->initialPaymentReceiptImagePreview = null;
        $this->initialBankName = '';

        $this->balanceAmount = 0;
        $this->balancePaymentMethod = '';
        $this->balanceDueDate = '';
        $this->balancePaymentReceiptImage = null;
        $this->balancePaymentReceiptImagePreview = null;
        $this->balanceBankName = '';
    }

    // ...rest of the methods (viewReceipt, printReceipt, downloadReceipt, getFilePreviewInfo)...

    public function render()
    {
        return view(
            'livewire.admin.store-billing',
            [
                'receipt' => $this->showReceipt && $this->lastSaleId
                    ? Sale::with(['customer', 'items', 'payments'])->find($this->lastSaleId)
                    : null,
            ]
        );
    }
}