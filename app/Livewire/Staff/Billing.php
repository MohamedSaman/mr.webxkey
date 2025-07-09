<?php

namespace App\Livewire\Staff;

use Exception;
use App\Models\Sale;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SaleItem;
use App\Models\WatchPrice;
use App\Models\WatchStock;
use App\Models\WatchDetail;
use App\Models\StaffProduct;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.staff')]
#[Title('Billing Page')]
class Billing extends Component
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

    // Add these properties to your existing properties list
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

    // serach showing 2 or more time same product issue solved 6/24/2025
public function updatedSearch()
{
    if (strlen($this->search) >= 2) {
        $this->searchResults = WatchDetail::join('staff_products', 'staff_products.watch_id', '=', 'watch_details.id')
            ->join('staff_sales', 'staff_sales.id', '=', 'staff_products.staff_sale_id')
            ->select(
                'watch_details.id',
                'watch_details.name',
                'watch_details.code',
                'watch_details.model',
                'watch_details.brand',
                'watch_details.barcode',
                'watch_details.image', // ✅ Fixed: include image field
                DB::raw('SUM(staff_products.quantity - staff_products.sold_quantity) as available_stock'),
                DB::raw('MIN(staff_products.unit_price) as selling_price'),
                DB::raw('MIN(staff_products.discount_per_unit) as discount_price'),
                DB::raw('MIN(staff_products.id) as staff_product_id')
            )
            ->where('staff_products.staff_id', auth()->id())
            ->where('staff_products.status', '!=', 'completed')
            ->whereRaw('staff_products.quantity > staff_products.sold_quantity')
            ->where(function($query) {
                $query->where('watch_details.code', 'like', '%' . $this->search . '%')
                    ->orWhere('watch_details.model', 'like', '%' . $this->search . '%')
                    ->orWhere('watch_details.barcode', 'like', '%' . $this->search . '%')
                    ->orWhere('watch_details.brand', 'like', '%' . $this->search . '%')
                    ->orWhere('watch_details.name', 'like', '%' . $this->search . '%');
            })
            ->groupBy(
                'watch_details.id',
                'watch_details.name',
                'watch_details.code',
                'watch_details.model',
                'watch_details.brand',
                'watch_details.barcode',
                'watch_details.image' // ✅ Group by image too (required in strict SQL modes)
            )
            ->having('available_stock', '>', 0)
            ->take(50)
            ->get();
    } else {
        $this->searchResults = [];
    }
}

 //add cart modify 6/24/2025

    /**
     * Add a watch to the cart
     *
     * @param int $watchId Watch ID
     */
public function addToCart($watchId)
{
    // Get total available stock across all assigned entries for this product
    $watch = WatchDetail::join('staff_products', 'staff_products.watch_id', '=', 'watch_details.id')
        ->where('watch_details.id', $watchId)
        ->where('staff_products.staff_id', auth()->id())
        ->where('staff_products.status', '!=', 'completed')
        ->whereRaw('staff_products.quantity > staff_products.sold_quantity')
        ->select(
            'watch_details.id',
            'watch_details.name',
            'watch_details.code',
            'watch_details.model',
            'watch_details.brand',
            'watch_details.image',
            DB::raw('SUM(staff_products.quantity - staff_products.sold_quantity) as available_stock'),
            DB::raw('MIN(staff_products.unit_price) as selling_price'),
            DB::raw('MIN(staff_products.discount_per_unit) as discount_price'),
            DB::raw('MIN(staff_products.id) as staff_product_id')
        )
        ->groupBy(
            'watch_details.id',
            'watch_details.name',
            'watch_details.code',
            'watch_details.model',
            'watch_details.brand',
            'watch_details.image'
        )
        ->having('available_stock', '>', 0)
        ->first();

    if (!$watch || $watch->available_stock <= 0) {
        $this->dispatch('showToast', ['type' => 'danger', 'message' => 'This product is not available or not assigned to you.']);
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
            'staff_product_id' => $watch->staff_product_id,
            'code' => $watch->code,
            'name' => $watch->name,
            'model' => $watch->model,
            'brand' => $watch->brand,
            'image' => $watch->image,
            'price' => $watch->selling_price ?? 0,
            'discountPrice' => $discountPrice,
            'inStock' => $watch->available_stock,
        ];

        $this->quantities[$watchId] = 1;
        $this->discounts[$watchId] = $discountPrice;
    }

    $this->search = '';
    $this->searchResults = [];
    $this->updateTotals();
}

    /**
     * Validate and restrict quantity input
     *
     * @param int $watchId Watch ID
     */
    public function validateQuantity($watchId)
    {
        if (!isset($this->cart[$watchId]) || !isset($this->quantities[$watchId])) {
            return;
        }
        
        $maxAvailable = $this->cart[$watchId]['inStock'];
        $currentQuantity = (int)$this->quantities[$watchId];
        
        // Enforce the minimum and maximum limits
        if ($currentQuantity <= 0) {
            $this->quantities[$watchId] = 1;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => 'Minimum quantity is 1'
            ]);
        } elseif ($currentQuantity > $maxAvailable) {
            // Cap the quantity to available stock
            $this->quantities[$watchId] = $maxAvailable;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }
        
        $this->updateTotals();
    }

    /**
     * Update quantity with validation
     *
     * @param int $watchId Watch ID
     * @param int $quantity Requested quantity
     */
    public function updateQuantity($watchId, $quantity)
    {
        if (!isset($this->cart[$watchId])) {
            return;
        }
        
        $maxAvailable = $this->cart[$watchId]['inStock'];
        
        // Apply limits and validation
        if ($quantity <= 0) {
            $quantity = 1;
        } elseif ($quantity > $maxAvailable) {
            $quantity = $maxAvailable;
            $this->dispatch('showToast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }
        
        // Update the quantity with the validated value
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
        $this->watchDetails = WatchDetail::join('staff_products', 'staff_products.watch_id', '=', 'watch_details.id')
            ->join('watch_suppliers', 'watch_suppliers.id', '=', 'watch_details.supplier_id')
            ->select(
                'watch_details.*', 
                'staff_products.unit_price as selling_price',
                'staff_products.discount_per_unit as discount_price',
                'staff_products.quantity as total_stock',
                'staff_products.sold_quantity as sold_stock',
                DB::raw('(staff_products.quantity - staff_products.sold_quantity) as available_stock'),
                'watch_suppliers.*',
                'watch_suppliers.name as supplier_name'
            )
            ->where('watch_details.id', $watchId)
            ->where('staff_products.staff_id', auth()->id())
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
            // Default to 50% initial payment when switching to partial
            $this->initialPaymentAmount = round($this->grandTotal / 2, 2);
            $this->calculateBalanceAmount();
        } else {
            // Reset partial payment fields when switching back to full
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

    public function updatedPaymentReceiptImage()
    {
        $this->validate([
            'paymentReceiptImage' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->paymentReceiptImage) {
            try {
                $extension = strtolower($this->paymentReceiptImage->getClientOriginalExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $this->paymentReceiptImagePreview = $this->paymentReceiptImage->temporaryUrl();
                } else {
                    $this->paymentReceiptImagePreview = 'pdf';
                }
            } catch (Exception $e) {
                // If temporary URL generation fails, mark the file type but don't set URL
                $this->paymentReceiptImagePreview = $extension == 'pdf' ? 'pdf' : 'image';
            }
        }
    }

    public function updatedInitialPaymentReceiptImage()
    {
        $this->validate([
            'initialPaymentReceiptImage' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->initialPaymentReceiptImage) {
            $extension = $this->initialPaymentReceiptImage->getClientOriginalExtension();
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                $this->initialPaymentReceiptImagePreview = $this->initialPaymentReceiptImage->temporaryUrl();
            } else {
                // For PDF we'll just set a flag that it's a PDF
                $this->initialPaymentReceiptImagePreview = 'pdf';
            }
        }
    }

    public function updatedBalancePaymentReceiptImage()
    {
        $this->validate([
            'balancePaymentReceiptImage' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->balancePaymentReceiptImage) {
            $extension = $this->balancePaymentReceiptImage->getClientOriginalExtension();
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                $this->balancePaymentReceiptImagePreview = $this->balancePaymentReceiptImage->temporaryUrl();
            } else {
                // For PDF we'll just set a flag that it's a PDF
                $this->balancePaymentReceiptImagePreview = 'pdf';
            }
        }
    }

    // Add this method with your other updater methods
    public function updatedDuePaymentAttachment()
    {
        $this->validate([
            'duePaymentAttachment' => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        if ($this->duePaymentAttachment) {
            $extension = $this->duePaymentAttachment->getClientOriginalExtension();
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                $this->duePaymentAttachmentPreview = $this->duePaymentAttachment->temporaryUrl();
            } else {
                // For PDF we'll just set a flag that it's a PDF
                $this->duePaymentAttachmentPreview = 'pdf';
            }
        }
    }

    protected $validationAttributes = [
        'paymentMethod' => 'payment method',
        'paymentReceiptImage' => 'payment receipt',
        'bankName' => 'bank name',
        'initialPaymentMethod' => 'initial payment method',
        'initialPaymentReceiptImage' => 'initial payment receipt',
        'initialBankName' => 'initial bank name',
        'balancePaymentMethod' => 'balance payment method',
        'balancePaymentReceiptImage' => 'balance payment receipt',
        'balanceBankName' => 'balance bank name',
        'balanceDueDate' => 'balance due date'
    ];

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
        
        if ($this->paymentType == 'full') {
            if (empty($this->paymentMethod)) {
                $this->js('swal.fire("Error", "Please select a payment method.", "error")');
                return;
            }

            if ($this->paymentMethod == 'cheque') {
                if (empty($this->paymentReceiptImage)) {
                    $this->js('swal.fire("Validation Error", "Payment Receipt is required", "error")');
                    return;
                } elseif ($this->paymentReceiptImage && $this->paymentReceiptImage->getSize() > 1024 * 1024) {
                    $this->js('swal.fire("Validation Error", "Receipt size must be less than 1MB", "error")');
                    return;
                } elseif (empty($this->bankName)) {
                    $this->js('swal.fire("Validation Error", "Bank Name is required", "error")');
                    return;
                }
            } elseif ($this->paymentMethod == 'bank_transfer') {
                if (empty($this->paymentReceiptImage)) {
                    $this->js('swal.fire("Validation Error", "Payment Receipt is required", "error")');
                    return;
                } elseif ($this->paymentReceiptImage && $this->paymentReceiptImage->getSize() > 1024 * 1024) {
                    $this->js('swal.fire("Validation Error", "Receipt size must be less than 1MB", "error")');
                    return;
                }
            }
        } elseif ($this->paymentType == 'partial') {
            if ($this->initialPaymentAmount > 0) {

                if (empty($this->initialPaymentMethod)) {
                    $this->js('swal.fire("Error", "Please select an initial payment method.", "error")');
                    return;
                }

                if ($this->initialPaymentMethod == 'cheque') {
                    if (empty($this->initialPaymentReceiptImage)) {
                        $this->js('swal.fire("Validation Error", "Payment Receipt is required", "error")');
                        return;
                    } elseif ($this->initialPaymentReceiptImage && $this->initialPaymentReceiptImage->getSize() > 2048 * 1024) { // Change from 1024*1024 to 2048*1024
                        $this->js('swal.fire("Validation Error", "Receipt size must be less than 2MB", "error")');
                        return;
                    } elseif (empty($this->initialBankName)) {
                        $this->js('swal.fire("Validation Error", "Bank Name is required", "error")');
                        return;
                    }
                } elseif ($this->initialPaymentMethod == 'bank_transfer') {
                    if (empty($this->initialPaymentReceiptImage)) {
                        $this->js('swal.fire("Validation Error", "Payment Receipt is required", "error")');
                        return;
                    } elseif ($this->initialPaymentReceiptImage && $this->initialPaymentReceiptImage->getSize() > 2048 * 1024) { // Change from 1024*1024 to 2048*1024
                        $this->js('swal.fire("Validation Error", "Receipt size must be less than 2MB", "error")');
                        return;
                    }
                }
            }

            if ($this->balanceAmount > 0) {
                if (empty($this->balancePaymentMethod)) {
                    $this->js('swal.fire("Error", "Please select a balance payment method.", "error")');
                    return;
                }

                if (empty($this->balanceDueDate)) {
                    $this->js('swal.fire("Error", "Please select a due date for the balance payment.", "error")');
                    return;
                }

                if ($this->balancePaymentMethod == 'cheque') {
                    if ($this->balancePaymentReceiptImage){
                        $this->js('swal.fire("Validation Error", "Payment Receipt is required11", "error")');
                        return;
                    } elseif ($this->balancePaymentReceiptImage && $this->balancePaymentReceiptImage->getSize() > 1024 * 1024) {
                        $this->js('swal.fire("Validation Error", "Receipt size must be less than 1MB", "error")');
                        return;
                    }
                    // } elseif (empty($this->balanceBankName)) {
                    //     $this->js('swal.fire("Validation Error", "Bank Name is required", "error")');
                    //     return;
                    // }
                } elseif ($this->balancePaymentMethod == 'bank_transfer') {
                    if (empty($this->balancePaymentReceiptImage)) {
                        $this->js('swal.fire("Validation Error", "Payment Receipt is required", "error")');
                        return;
                    } elseif ($this->balancePaymentReceiptImage && $this->balancePaymentReceiptImage->getSize() > 1024 * 1024) {
                        $this->js('swal.fire("Validation Error", "Receipt size must be less than 1MB", "error")');
                        return;
                    }
                }
            }
        }
    

        try {
            DB::beginTransaction();

            $invoiceNumber = Sale::generateInvoiceNumber();

            $paymentStatus = 'paid';
            if ($this->paymentType == 'partial') {
                $paymentStatus = $this->balanceAmount > 0 ? 'partial' : 'paid';
            }

            $customer = Customer::find($this->customerId);

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $this->customerId,
                'user_id' => auth()->id(),
                'customer_type' => $customer->type,
                'subtotal' => $this->subtotal,
                'discount_amount' => $this->totalDiscount,
                'total_amount' => $this->grandTotal,
                'payment_type' => $this->paymentType,
                'payment_status' => $paymentStatus,
                'notes' => $this->saleNotes,
                'due_amount' => $this->balanceAmount,
            ]);
foreach ($this->cart as $id => $item) {
    $requestedQty = $this->quantities[$id];

    // 🔎 Get total available stock for this watch_id from all staff_products
    $availableStock = StaffProduct::where('watch_id', $item['id'])
        ->where('staff_id', auth()->id())
        ->where('status', '!=', 'completed')
        ->selectRaw('SUM(quantity - sold_quantity) as total_available')
        ->value('total_available');

    if ($requestedQty > $availableStock) {
        throw new Exception("Not enough stock available for item: {$item['name']}. Requested: {$requestedQty}, Available: {$availableStock}");
    }

    $remainingQty = $requestedQty;
    $itemDiscount = $this->discounts[$id] ?? 0;
    $unitPrice = $item['price'];

    // 🧠 Loop through all staff_product entries and consume from them
    $staffProducts = StaffProduct::where('watch_id', $item['id'])
        ->where('staff_id', auth()->id())
        ->where('status', '!=', 'completed')
        ->orderBy('id') // or priority field
        ->get();

    foreach ($staffProducts as $staffProduct) {
        $available = $staffProduct->quantity - $staffProduct->sold_quantity;
        if ($available <= 0) continue;

        $useQty = min($remainingQty, $available);
        $total = ($unitPrice * $useQty) - ($itemDiscount * $useQty);

        // 💾 Save SaleItem
        SaleItem::create([
            'sale_id' => $sale->id,
            'watch_id' => $item['id'],
            'watch_code' => $item['code'],
            'watch_name' => $item['name'],
            'quantity' => $useQty,
            'unit_price' => $unitPrice,
            'discount' => $itemDiscount,
            'total' => $total,
            'staff_product_id' => $staffProduct->id,
        ]);

        // 🧾 Update staffProduct
        $staffProduct->sold_quantity += $useQty;
        $staffProduct->sold_value += $total;
        $staffProduct->status = $staffProduct->sold_quantity >= $staffProduct->quantity ? 'completed' : 'partial';
        $staffProduct->save();

        $remainingQty -= $useQty;
        if ($remainingQty <= 0) break;
    }


                // Update the parent staff sale record
                $staffSale = $staffProduct->staffSale;
                if ($staffSale) {
                    $staffSale->sold_quantity += $this->quantities[$id];
                    $staffSale->sold_value += $total;
                    
                    // Update parent status if all items sold
                    $totalAssigned = $staffSale->products->sum('quantity');
                    $totalSold = $staffSale->products->sum('sold_quantity');
                    
                    if ($totalSold >= $totalAssigned) {
                        $staffSale->status = 'completed';
                    } else {
                        $staffSale->status = 'partial';
                    }
                    
                    $staffSale->save();
                }
                
                // Also update the main inventory
                $watchStock = WatchStock::where('watch_id', $item['id'])->first();
                if ($watchStock) {
                    $watchStock->sold_count += $this->quantities[$id];
                    $watchStock->save();
                }
            }

            if ($this->paymentType == 'full') {
                $receiptPath = null;
                if ($this->paymentReceiptImage && ($this->paymentMethod == 'cheque' || $this->paymentMethod == 'bank_transfer')) {
                    $receiptPath = $this->paymentReceiptImage->store('payment-receipts', 'public');
                }

                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => $this->grandTotal,
                    'payment_method' => $this->paymentMethod,
                    'payment_reference' => $receiptPath,
                    'bank_name' => $this->paymentMethod == 'cheque' ? $this->bankName : null,
                    'is_completed' => true,
                    'payment_date' => now(),
                    'status' => 'Paid', // Set the status to 'approved' for full payments
                ]);
            } else {
                if ($this->initialPaymentAmount > 0) {
                    $initialReceiptPath = null;
                    if ($this->initialPaymentReceiptImage && ($this->initialPaymentMethod == 'cheque' || $this->initialPaymentMethod == 'bank_transfer')) {
                        $initialReceiptPath = $this->initialPaymentReceiptImage->store('payment-receipts', 'public');
                    }
                    
                    // Store the due payment attachment if provided
                    $dueAttachmentPath = null;
                    if ($this->duePaymentAttachment) {
                        $dueAttachmentPath = $this->duePaymentAttachment->store('due-payments-receipts', 'public'); // Consistent path naming
                    }

                    Payment::create([
                        'sale_id' => $sale->id,
                        'amount' => $this->initialPaymentAmount,
                        'payment_method' => $this->initialPaymentMethod,
                        'payment_reference' => $initialReceiptPath,
                        'bank_name' => $this->initialPaymentMethod == 'cheque' ? $this->initialBankName : null,
                        'is_completed' => true,
                        'payment_date' => now(),
                        'due_payment_method' => $this->duePaymentMethod,
                        'due_payment_attachment' => $dueAttachmentPath,
                        'status' => 'Paid', // Set the status to 'approved' for initial payments
                    ]);
                }

                if ($this->balanceAmount > 0) {
                    $balanceReceiptPath = null;
                    if ($this->duePaymentAttachment) {
                        $balanceReceiptPath = $this->duePaymentAttachment->store('payments-receipts', 'public'); // Consistent path naming
                    }
                    // if ($this->balancePaymentReceiptImage && ($this->balancePaymentMethod == 'cheque' || $this->balancePaymentMethod == 'bank_transfer')) {
                    //     $balanceReceiptPath = $this->balancePaymentReceiptImage->store('payment-receipts', 'public');
                    // }

                    Payment::create([
                        'sale_id' => $sale->id,
                        'amount' => $this->balanceAmount,
                        'payment_method' => $this->balancePaymentMethod,
                        'payment_reference' => $balanceReceiptPath,
                        'bank_name' => $this->balancePaymentMethod == 'cheque' ? $this->balanceBankName : null,
                        'is_completed' => false,
                        'due_date' => $this->balanceDueDate,
                        
                    ]);
                }
            }

            DB::commit();

            $this->lastSaleId = $sale->id;
            $this->showReceipt = true;

            $this->js('swal.fire("Success", "Sale completed successfully! Invoice #' . $invoiceNumber . '", "success")');

            $this->clearCart();
            $this->resetPaymentInfo();

            $this->js('$("#receiptModal").modal("show")');
        } catch (Exception $e) {
            DB::rollBack();
            $this->js('swal.fire("Error", "An error occurred while completing the sale: ' . $e->getMessage() . '", "error")');
            Log::error('Sale completion error: ' . $e->getMessage());
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
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));
        $this->balancePaymentReceiptImage = null;
        $this->balancePaymentReceiptImagePreview = null;
        $this->balanceBankName = '';

        // Add these lines to the resetPaymentInfo method
        $this->duePaymentMethod = '';
        $this->duePaymentAttachment = null;
        $this->duePaymentAttachmentPreview = null;

        $this->saleNotes = '';
    }

    public function viewReceipt($saleId = null)
    {
        if ($saleId) {
            $this->lastSaleId = $saleId;
        }

        if ($this->lastSaleId) {
            $this->showReceipt = true;
            $this->js('$("#receiptModal").modal("show")');
        }
    }

    public function printReceipt()
    {
        $this->dispatch('printReceipt');
    }

    public function downloadReceipt()
    {
        return redirect()->route('receipts.download', $this->lastSaleId);
    }

    /**
     * Get file type icon or preview based on file object and preview URL
     *
     * @param mixed $file The uploaded file object
     * @param string|null $previewUrl The temporary preview URL
     * @return array File information with type, icon, and URL
     */
    public function getFilePreviewInfo($file, $previewUrl = null)
    {
        if (!$file) {
            return null;
        }
        
        $fileInfo = [
            'name' => $file->getClientOriginalName(),
            'type' => 'unknown',
            'icon' => 'bi-file',
            'url' => null
        ];
        
        // Determine file type
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Handle images
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $fileInfo['type'] = 'image';
            $fileInfo['icon'] = 'bi-file-image';
            
            // Only set URL if we have a valid preview
            try {
                $fileInfo['url'] = $previewUrl && $previewUrl !== 'pdf' ? $previewUrl : null;
            } catch (\Exception $e) {
                $fileInfo['url'] = null;
            }
        }
        // Handle PDFs
        elseif ($extension === 'pdf') {
            $fileInfo['type'] = 'pdf';
            $fileInfo['icon'] = 'bi-file-earmark-pdf';
        }
        
        return $fileInfo;
    }

    public function render()
    {
        return view(
            'livewire.staff.billing',
            [
                'receipt' => $this->showReceipt && $this->lastSaleId ? Sale::with(['customer', 'items', 'payments'])->find($this->lastSaleId) : null,
            ]
        );
    }
}
