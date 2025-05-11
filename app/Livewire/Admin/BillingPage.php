<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use Exception;
use App\Models\WatchStock;
use App\Models\WatchDetail;
use App\Models\WatchPrice;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;

#[Layout('components.layouts.admin')]
#[Title('Billing Page')]
class BillingPage extends Component
{
    public $search = '';
    public $searchResults = [];
    public $cart = [];
    public $quantities = [];
    public $discounts = [];
    public $watchDetails = null;
    public $subtotal = 0;
    public $totalDiscount = 0;
    public $grandTotal = 0;
    
    protected $listeners = ['quantityUpdated' => 'updateTotals'];
    
    public function mount()
    {
        $this->updateTotals();
    }
    
    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->searchResults = WatchDetail::join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
                ->join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
                ->select('watch_details.*', 'watch_prices.selling_price', 'watch_prices.discount_price', 'watch_stocks.available_stock')
                ->where('status', '=','active')
                ->where('code', 'like', '%' . $this->search . '%')
                ->orWhere('model', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->orWhere('brand', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->take(10)
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
            ->select('watch_details.*', 'watch_prices.selling_price', 'watch_prices.discount_price', 'watch_stocks.available_stock')
            ->first();
        
        if (!$watch) {
            return;
        }
        
        $existingItem = collect($this->cart)->firstWhere('id', $watchId);
        
        if ($existingItem) {
            // Just increment quantity if already in cart
            $this->quantities[$watchId]++;
        } else {
            $discountPrice = $watch->selling_price - $watch->discount_price ?? 0;
            // Add new item to cart
            $this->cart[$watchId] = [
                'id' => $watch->id,
                'code' => $watch->code,
                'name' => $watch->name,
                'model' => $watch->model,
                'brand' => $watch->brand,
                'image' => $watch->image,
                'price' => $watch->selling_price ?? 0,
                'discountPrice' => $discountPrice?? 0,
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
        $quantity = max(1, min($quantity, $this->cart[$watchId]['inStock']));
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
        // $this->showDetail = $this->showDetail === $watchId ? null : $watchId;
        $this->watchDetails = WatchDetail::join('watch_prices', 'watch_prices.watch_id', '=', 'watch_details.id')
        ->join('watch_stocks', 'watch_stocks.watch_id', '=', 'watch_details.id')
        ->join('watch_suppliers', 'watch_suppliers.id', '=', 'watch_details.supplier_id')
        ->select('watch_details.*', 'watch_prices.*',  'watch_stocks.*','watch_suppliers.*', 'watch_suppliers.name as supplier_name')
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
    
    public function render()
    {
        return view('livewire.admin.billing-page');
    }
}
