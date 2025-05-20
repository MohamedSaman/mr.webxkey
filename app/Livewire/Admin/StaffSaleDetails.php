<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;
use App\Models\StaffSale;
use App\Models\StaffProduct;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
#[Title('Staff Sale Details')]
class StaffSaleDetails extends Component
{
    use WithPagination;
    
    public $isViewModalOpen = false;
    public $staffId;
    public $staffName;
    public $staffDetails;
    public $productDetails;
    public $summaryStats;
    
    public function viewSaleDetails($userId)
    {
        $this->staffId = $userId;
        
        // Get staff details
        $this->staffDetails = DB::table('users')
            ->where('id', $userId)
            ->first();
        
        $this->staffName = $this->staffDetails->name;
        
        // Get summary statistics
        // $this->summaryStats = StaffSale::where('staff_id', $userId)
        //     ->select(
        //         DB::raw('SUM(total_quantity) as total_quantity'),
        //         DB::raw('SUM(sold_quantity) as sold_quantity'),
        //         DB::raw('SUM(total_quantity) - SUM(sold_quantity) as available_quantity'),
        //         DB::raw('SUM(total_value) as total_value'),
        //         DB::raw('SUM(sold_value) as sold_value'),
        //         DB::raw('SUM(total_value) - SUM(sold_value) as available_value')
        //     )
        //     ->first();
            
        // Get product-wise details
        $this->productDetails = StaffProduct::join('watch_details', 'staff_products.watch_id', '=', 'watch_details.id')
            ->where('staff_products.staff_id', $userId)
            ->select(
                'staff_products.*',
                'watch_details.name as watch_name',
                'watch_details.brand as watch_brand',
                'watch_details.model as watch_model',
                'watch_details.code as watch_code',
                'watch_details.image as watch_image'
            )
            ->get();
        
        //    $this->js("$('#salesDetails').modal('show');");
        $this->dispatch('open-sales-modal');
        
    }

    public function getSummaryStats($staffId)
    {
        $summaryStats = [];
        try{
            $summaryStats = StaffSale::where('staff_id', $staffId)
                ->select(
                    DB::raw('SUM(total_quantity) as total_quantity'),
                    DB::raw('SUM(sold_quantity) as sold_quantity'),
                    DB::raw('SUM(total_quantity) - SUM(sold_quantity) as available_quantity'),
                    DB::raw('SUM(total_value) as total_value'),
                    DB::raw('SUM(sold_value) as sold_value'),
                    DB::raw('SUM(total_value) - SUM(sold_value) as available_value')
                )
                ->first();
        } catch (Exception) {
            $summaryStats = [];
        }
        return $summaryStats;
    }
    
    public function exportToCsv()
    {
        return redirect()->route('staff-sales.export');
    }
    
    public function printStaffDetails($staffId = null)
    {
        // If no staffId provided, use the currently selected staff
        $staffId = $staffId ?? $this->staffId;
        
        if (!$staffId) {
            return redirect()->back()->with('error', 'No staff selected for printing');
        }
        
        // Get all the necessary data for printing
        $staffDetails = DB::table('users')->where('id', $staffId)->first();
        $summaryStats = $this->getSummaryStats($staffId);
        $productDetails = StaffProduct::join('watch_details', 'staff_products.watch_id', '=', 'watch_details.id')
            ->where('staff_products.staff_id', $staffId)
            ->select(
                'staff_products.*',
                'watch_details.name as watch_name',
                'watch_details.brand as watch_brand',
                'watch_details.model as watch_model',
                'watch_details.code as watch_code',
                'watch_details.image as watch_image'
            )
            ->get();
        
        // Return a view optimized for printing
        return view('admin.print.staff-details', compact('staffDetails', 'summaryStats', 'productDetails'));
    }
    
    public function render()
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
                DB::raw('SUM(staff_sales.sold_value) as sold_value')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.contact')
            ->orderBy('total_value', 'desc')
            ->paginate(10);

        return view('livewire.admin.staff-sale-details', [
            'staffSales' => $staffSales
        ]);
    }
}
