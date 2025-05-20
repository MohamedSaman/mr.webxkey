<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StaffSale;
use App\Models\StaffProduct;
use Illuminate\Support\Facades\DB;

class StaffSaleExportController extends Controller
{
    public function export()
    {
        $staffSales = StaffSale::join('users', 'staff_sales.staff_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.contact',
                DB::raw('SUM(staff_sales.total_quantity) as total_quantity'),
                DB::raw('SUM(staff_sales.sold_quantity) as sold_quantity'),
                DB::raw('SUM(staff_sales.total_quantity) - SUM(staff_sales.sold_quantity) as available_quantity'),
                DB::raw('SUM(staff_sales.total_value) as total_value'),
                DB::raw('SUM(staff_sales.sold_value) as sold_value'),
                DB::raw('SUM(staff_sales.total_value) - SUM(staff_sales.sold_value) as available_value')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.contact')
            ->orderBy('total_value', 'desc')
            ->get();

        $filename = 'staff_sales_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($staffSales) {
            $handle = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($handle, [
                'Staff ID',
                'Name',
                'Email',
                'Contact',
                'Assigned Quantity',
                'Sold Quantity',
                'Available Quantity',
                'Total Value (Rs)',
                'Sold Value (Rs)',
                'Available Value (Rs)'
            ]);
            
            // Add data rows
            foreach ($staffSales as $sale) {
                fputcsv($handle, [
                    $sale->user_id,
                    $sale->name,
                    $sale->email,
                    $sale->contact,
                    $sale->total_quantity,
                    $sale->sold_quantity,
                    $sale->available_quantity,
                    $sale->total_value,
                    $sale->sold_value,
                    $sale->available_value
                ]);
            }
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportStaffProducts($staffId)
    {
        $staffDetails = DB::table('users')->where('id', $staffId)->first();
        $staffName = $staffDetails ? $staffDetails->name : 'Unknown';
        
        $products = StaffProduct::join('watch_details', 'staff_products.watch_id', '=', 'watch_details.id')
            ->where('staff_products.staff_id', $staffId)
            ->select(
                'staff_products.*',
                'watch_details.name as watch_name',
                'watch_details.brand as watch_brand',
                'watch_details.model as watch_model',
                'watch_details.code as watch_code'
            )
            ->get();
        
        $filename = 'staff_products_' . $staffName . '_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($products) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Product Name',
                'Brand',
                'Model',
                'Code',
                'Unit Price',
                'Quantity',
                'Sold Quantity',
                'Available Quantity',
                'Total Value',
                'Sold Value',
                'Status'
            ]);
            
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->watch_name,
                    $product->watch_brand,
                    $product->watch_model,
                    $product->watch_code,
                    $product->unit_price,
                    $product->quantity,
                    $product->sold_quantity,
                    $product->quantity - $product->sold_quantity,
                    $product->total_value,
                    $product->sold_value,
                    $product->status
                ]);
            }
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
