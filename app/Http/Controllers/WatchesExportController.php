<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WatchDetail;

class WatchesExportController extends Controller
{
    public function export()
    {
        $watches = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select(
                'watch_details.id',
                'watch_details.code',
                'watch_details.name as watch_name',
                'watch_details.model',
                'watch_details.brand',
                'watch_details.color',
                'watch_details.made_by',
                'watch_details.category',
                'watch_details.gender',
                'watch_details.type',
                'watch_details.movement',
                'watch_details.dial_color',
                'watch_details.strap_color',
                'watch_details.strap_material',
                'watch_details.case_diameter_mm',
                'watch_details.case_thickness_mm',
                'watch_details.glass_type',
                'watch_details.water_resistance',
                'watch_details.warranty',
                'watch_details.barcode',
                'watch_details.status',
                'watch_prices.supplier_price',
                'watch_prices.selling_price',
                'watch_prices.discount_price',
                'watch_stocks.shop_stock',
                'watch_stocks.store_stock',
                'watch_stocks.damage_stock',
                'watch_stocks.total_stock',
                'watch_stocks.available_stock',
                'watch_suppliers.name as supplier_name'
            )
            ->orderBy('watch_details.created_at', 'desc')
            ->get();

        $filename = 'watches_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($watches) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Code', 'Name', 'Model', 'Brand', 'Color', 'Made By',
                'Category', 'Gender', 'Type', 'Movement', 'Dial Color',
                'Strap Color', 'Strap Material', 'Case Diameter (mm)',
                'Case Thickness (mm)', 'Glass Type', 'Water Resistance',
                'Warranty', 'Barcode', 'Status', 'Supplier Price',
                'Selling Price', 'Discount Price', 'Shop Stock',
                'Store Stock', 'Damage Stock', 'Total Stock',
                'Available Stock', 'Supplier'
            ]);
            foreach ($watches as $watch) {
                fputcsv($handle, [
                    $watch->id,
                    $watch->code,
                    $watch->watch_name,
                    $watch->model,
                    $watch->brand,
                    $watch->color,
                    $watch->made_by,
                    $watch->category,
                    $watch->gender,
                    $watch->type,
                    $watch->movement,
                    $watch->dial_color,
                    $watch->strap_color,
                    $watch->strap_material,
                    $watch->case_diameter_mm,
                    $watch->case_thickness_mm,
                    $watch->glass_type,
                    $watch->water_resistance,
                    $watch->warranty,
                    $watch->barcode,
                    $watch->status,
                    $watch->supplier_price,
                    $watch->selling_price,
                    $watch->discount_price,
                    $watch->shop_stock,
                    $watch->store_stock,
                    $watch->damage_stock,
                    $watch->total_stock,
                    $watch->available_stock,
                    $watch->supplier_name
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
