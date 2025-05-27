<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Sale;
use App\Models\User;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SaleItem;
use App\Models\StaffSale;
use App\Models\WatchPrice;
use App\Models\WatchStock;
use App\Models\WatchDetail;
use App\Models\StaffProduct;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
#[Title('Billing Page')]
class BillingPage extends Component
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

    public $selectedStaffId = null;

    protected $listeners = ['quantityUpdated' => 'updateTotals'];

    protected function rules()
    {
        return [
            'selectedStaffId' => 'required',
            // Add any other validation rules you need
        ];
    }

    protected $messages = [
        'selectedStaffId.required' => 'Please select a staff member to assign this sale.',
    ];

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
            $this->searchResults = WatchDetail::join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
                ->join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
                ->select('watch_details.*', 'watch_prices.selling_price', 'watch_prices.discount_price', 'watch_stocks.available_stock')
                ->where('watch_details.status', '=', 'active')
                ->where('watch_stocks.available_stock', '>', 0) // Only show products with stock > 0
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
        $watch = WatchDetail::join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
            ->join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->where('watch_details.id', $watchId)
            ->select('watch_details.*', 'watch_prices.selling_price', 'watch_prices.discount_price', 
                     'watch_stocks.available_stock')
            ->first();

        if (!$watch || $watch->available_stock <= 0) {
            $this->js('swal.fire("Error", "This product is out of stock.", "error")');
            return;
        }

        $existingItem = collect($this->cart)->firstWhere('id', $watchId);

        if ($existingItem) {
            // Check if adding one more would exceed stock
            if (($this->quantities[$watchId] + 1) > $watch->available_stock) {
                $this->js('swal.fire("Warning", "Maximum available quantity reached.", "warning")');
                return;
            }
            $this->quantities[$watchId]++;
        } else {
            $discountPrice = $watch->selling_price - $watch->discount_price ?? 0;
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
            $this->discounts[$watchId] = 0;
        }

        $this->search = '';
        $this->searchResults = [];
        $this->updateTotals();
    }

    public function updateQuantity($watchId, $quantity)
    {
        if (!isset($this->cart[$watchId])) {
            return;
        }

        $maxAvailable = $this->cart[$watchId]['inStock'];
        
        // Ensure quantity is valid
        $quantity = (int)$quantity;
        if ($quantity < 1) {
            $quantity = 1;
        } elseif ($quantity > $maxAvailable) {
            $quantity = $maxAvailable;
            $this->js('swal.fire("Warning", "Quantity limited to maximum available (' . $maxAvailable . ')", "warning")');
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
        $this->watchDetails = WatchDetail::join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
            ->join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->join('watch_suppliers', 'watch_suppliers.id', '=', 'watch_details.supplier_id')
            ->select('watch_details.*', 'watch_prices.*', 'watch_stocks.*', 'watch_suppliers.*', 'watch_suppliers.name as supplier_name')
            ->where('watch_details.id', $watchId)
            ->first();

        $this->js('$("#viewDetailModal").modal("show")');
    }

    public function updateTotals()
    {
        $this->subtotal = 0;
        $this->totalDiscount = 0;

        foreach ($this->cart as $id => $item) {
            $price = $item['discountPrice'] ?: $item['price'];
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

    public function completeSale()
    {
        if (empty($this->cart)) {
            $this->js('swal.fire("Error", "Please add items to the cart.", "error")');
            return;
        }
        
        // Add stock validation
        $invalidItems = [];
        foreach ($this->cart as $id => $item) {
            // Get the latest stock directly from database
            $currentStock = WatchStock::where('watch_id', $id)->value('available_stock');
            
            if ($currentStock < $this->quantities[$id]) {
                $invalidItems[] = $item['name'] . " (Requested: {$this->quantities[$id]}, Available: {$currentStock})";
            }
        }
        
        if (!empty($invalidItems)) {
            $errorMessage = "Cannot complete sale due to insufficient stock:<br><ul>";
            foreach ($invalidItems as $item) {
                $errorMessage .= "<li>{$item}</li>";
            }
            $errorMessage .= "</ul>";
            
            $this->js('swal.fire({
                title: "Stock Error",
                html: "' . $errorMessage . '",
                icon: "error"
            })');
            return;
        }
        
        // Validate staff selection
        $this->validate();
        
        try {
            // Start a database transaction
            DB::beginTransaction();
            
            // Create a new StaffSale record
            $staffSale = new StaffSale();
            $staffSale->staff_id = $this->selectedStaffId;
            $staffSale->admin_id = auth()->id();
            $staffSale->total_quantity = array_sum($this->quantities);
            $staffSale->total_value = $this->grandTotal;
            $staffSale->sold_quantity = 0; // Initially 0 as products are just being assigned
            $staffSale->sold_value = 0; // Initially 0 as products are just being assigned
            $staffSale->status = 'assigned';
            $staffSale->save();
            
            // Create records for each product assigned
            foreach ($this->cart as $watchId => $item) {
                $unitPrice = $item['discountPrice'] ?: $item['price'];
                $totalDiscount = $this->discounts[$watchId] * $this->quantities[$watchId];
                $totalValue = ($unitPrice * $this->quantities[$watchId]) - $totalDiscount;
                
                $staffProduct = new StaffProduct();
                $staffProduct->staff_sale_id = $staffSale->id;
                $staffProduct->watch_id = $watchId;
                $staffProduct->staff_id = $this->selectedStaffId;
                $staffProduct->quantity = $this->quantities[$watchId];
                $staffProduct->unit_price = $unitPrice;
                $staffProduct->discount_per_unit = $this->discounts[$watchId];
                $staffProduct->total_discount = $totalDiscount;
                $staffProduct->total_value = $totalValue;
                $staffProduct->sold_quantity = 0; // Initially 0
                $staffProduct->sold_value = 0; // Initially 0
                $staffProduct->status = 'assigned';
                $staffProduct->save();
                
                // Update watch stock
                $watchStock = WatchStock::where('watch_id', $watchId)->first();
                if ($watchStock) {
                    $watchStock->available_stock -= $this->quantities[$watchId];
                    $watchStock->assigned_stock = ($watchStock->assigned_stock ?? 0) + $this->quantities[$watchId];
                    $watchStock->save();
                }
            }
            
            DB::commit();
            
            // Show success message
            $this->js('swal.fire("Success", "Products successfully assigned to staff.", "success")');
            
            // Reset the form
            $this->clearCart();
            $this->selectedStaffId = null;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error assigning products to staff: ' . $e->getMessage());
            $this->js('swal.fire("Error", "'.$e->getMessage().'", "error")');
        }
    }

    public function render()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('livewire.admin.billing-page', [
            'staffs' => $staffs,
        ]);
    }
}
