<?php

namespace App\Livewire\Admin;
use App\Models\StaffProduct;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\StaffSale;
use App\Models\User;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
#[Title('Staff Watch Stock')]

class StaffStockDetails extends Component
{

    public $stockDetails;

    public function viewStockDetails($id)
    {
        try {
            $this->stockDetails = StaffProduct::join('users','staff_products.staff_id','=','users.id')
                ->join('watch_details','staff_products.watch_id','=','watch_details.id')
                ->where('staff_products.staff_id', $id)
                ->select(
                    
                    'staff_products.*',
                    'users.name as staff_name',
                    'users.email as staff_email',
                    'watch_details.name as watch_name',
                    'watch_details.brand as watch_brand',
                    'watch_details.model as watch_model',
                    'watch_details.code as watch_code',
                    'watch_details.image as watch_image'
                )
                ->get();
                // dd($this->stockDetails);
                $this->dispatch('open-stock-details-modal');
        } catch (Exception $e) {
            $this->js('swal.fire("Error", "Unable to fetch stock details: '. $e->getMessage().'", "error")');
            return;
        }
    }
    public function render()
    {
        // Get aggregated staff stock data
        $staffStocks = DB::table('staff_sales')
            ->join('users', 'staff_sales.staff_id', '=', 'users.id')
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
                // DB::raw('ROUND(SUM(staff_sales.total_value) - SUM(staff_sales.sold_value), 2) as available_value'),
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.contact')
            ->get();
            
        return view('livewire.admin.staff-stock-details', [
            'staffStocks' => $staffStocks,
        ]);
    }
}
