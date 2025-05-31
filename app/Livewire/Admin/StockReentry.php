<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\StaffProduct;
use App\Models\WatchStock;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.admin')]
#[Title('Staff Watch Stock Reentry')]

class StockReentry extends Component
{
    public $staffId;
    public $staff;
    public $selectedProduct;
    public $damagedQuantity = 0;
    public $restockQuantity = 0;

    public function mount($staffId)
    {
        $this->staffId = $staffId;
        $this->staff = User::findOrFail($staffId);
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = StaffProduct::with('watchDetail')->find($productId);
        $this->damagedQuantity = 0;
        $this->restockQuantity = 0;
    }

    public function submitReentry()
    {
        $this->validate([
            'damagedQuantity' => 'nullable|integer|min:0',
            // 'restockQuantity' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () {
            $product = StaffProduct::find($this->selectedProduct->id);

            $available = $product->quantity - $product->sold_quantity;
            $totalToRemove = $this->damagedQuantity;

            if ($totalToRemove > $available) {
                throw new \Exception("Entered quantities exceed available stock.");
            }

            $product->quantity -= $totalToRemove;
            $product->save();

            $stock = WatchStock::where('watch_id', $product->watch_id)->first();
            if (!$stock) {
                $stock = new WatchStock();
                $stock->watch_id = $product->watch_id;
                $stock->damage_stock = 0;
                // $stock->restocked_quantity = 0;
            }

            $stock->damage_stock += $this->damagedQuantity;
            // $stock->restocked_quantity += $this->restockQuantity;
            $stock->save();

            $this->dispatch('notify', 'Stock updated successfully.');
            $this->reset('selectedProduct', 'damagedQuantity', 'restockQuantity');
        });
    }

    public function render()
    {
        $products = StaffProduct::with('watchDetail')
            ->where('staff_id', $this->staffId)
            ->get();

        return view('livewire.admin.stock-reentry', [
            'products' => $products,
        ]);
    }
}
