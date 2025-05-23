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

    public function exportToCSV()
    {
        // Get data
        $watchStocks = WatchStock::join('watch_details', 'watch_stocks.watch_id', '=', 'watch_details.id')
            ->select('watch_details.name', 'watch_details.code', 'watch_details.brand', 'watch_details.model', 
                    'watch_stocks.total_stock', 'watch_stocks.available_stock', 
                    'watch_stocks.sold_count', 'watch_stocks.damage_stock')
            ->get();

        if ($watchStocks->isEmpty()) {
            $this->dispatch('banner-message', [
                'style' => 'danger',
                'message' => 'No data available to export'
            ]);
            return;
        }
        
        // Generate filename with date
        $fileName = 'watch_stock_' . date('Y-m-d_His') . '.csv';
        
        // Create CSV content with headers
        $headers = [
            'Name',
            'Code',
            'Brand',
            'Model',
            'Total Stock',
            'Available Stock',
            'Sold Count',
            'Damage Stock'
        ];
        
        $callback = function() use($watchStocks, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($watchStocks as $stock) {
                $row = [
                    $stock->name ?? '-',
                    $stock->code ?? '-',
                    $stock->brand ?? '-',
                    $stock->model ?? '-',
                    $stock->total_stock ?? '0',
                    $stock->available_stock ?? '0',
                    $stock->sold_count ?? '0',
                    $stock->damage_stock ?? '0'
                ];
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        // Create response with headers for browser download
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}
