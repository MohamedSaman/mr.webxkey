<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\WatchStock;
use App\Models\WatchDetails;

#[Layout('components.layouts.admin')]
#[Title('Watch Stock Details')]
class WatchStockDetails extends Component
{
    public function render()
    {
        $watchStocks = WatchStock::join('watch_details', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->select('watch_stocks.*', 'watch_details.name as watch_name', 'watch_details.brand as watch_brand','watch_details.model as watch_model', 'watch_details.code as watch_code', 'watch_details.image as watch_image')
            ->get();
        return view('livewire.admin.watch-stock-details',[
            'watchStocks'=> $watchStocks
        ]);
    }
}
