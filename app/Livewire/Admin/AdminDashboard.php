<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class AdminDashboard extends Component
{
    public $totalRevenue = 0;
    public $totalDueAmount = 0;
    public $totalSales = 0;
    public $revenuePercentage = 0;
    public $duePercentage = 0;
    public $previousMonthRevenue = 0;
    public $revenueChangePercentage = 0;
    public $fullPaidCount = 0;
    public $fullPaidAmount = 0;

    public $partialPaidCount = 0;
    public $partialPaidAmount = 0;

    public $totalStock = 0;
    public $assignedStock = 0;
    public $soldStock = 0;
    public $assignmentPercentage = 0;
    public $soldPercentage = 0;
    public $damagedStock = 0;
    public $damagedValue = 0;
    public $totalInventoryValue = 0;
    public $totalStaffCount = 0;
    public $staffWithAssignmentsCount = 0;
    public $staffAssignmentPercentage = 0;
    public $totalStaffSalesValue = 0;

    public $recentSales = [];
    public $watchInventory = [];
    public $brandSales = [];
    public $staffSales = [];

    public function mount()
    {
        // Get sales statistics
        $salesStats = Sale::select(
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('SUM(due_amount) as total_due'),
            DB::raw('COUNT(*) as sales_count')
        )->first();

        // Calculate total revenue (total_amount - due_amount)
        $this->totalSales = $salesStats->total_sales ?? 0;
        $this->totalDueAmount = $salesStats->total_due ?? 0;
        $this->totalRevenue = $this->totalSales - $this->totalDueAmount;

        // Calculate percentages
        if ($this->totalSales > 0) {
            $this->revenuePercentage = round(($this->totalRevenue / $this->totalSales) * 100, 1);
            $this->duePercentage = round(($this->totalDueAmount / $this->totalSales) * 100, 1);
        }

        // Get previous month's revenue for comparison
        $previousMonthSales = Sale::whereMonth(
            'created_at',
            '=',
            now()->subMonth()->month
        )->select(
                DB::raw('SUM(total_amount - due_amount) as revenue')
            )->first();

        $this->previousMonthRevenue = $previousMonthSales->revenue ?? 0;

        // Calculate month-over-month change percentage
        if ($this->previousMonthRevenue > 0) {
            $this->revenueChangePercentage = round((($this->totalRevenue - $this->previousMonthRevenue) / $this->previousMonthRevenue) * 100, 1);
        }

        // Get fully paid invoices data
        $fullPaidData = Sale::where('payment_status', 'paid')
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as amount')
            )->first();

        $this->fullPaidCount = $fullPaidData->count ?? 0;
        $this->fullPaidAmount = $fullPaidData->amount ?? 0;

        // Get partially paid invoices data
        $partialPaidData = Sale::where('payment_status', 'partial')
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(due_amount) as amount')
            )->first();

        $this->partialPaidCount = $partialPaidData->count ?? 0;
        $this->partialPaidAmount = $partialPaidData->amount ?? 0;

        // Get inventory statistics
        $stockStats = DB::table('watch_stocks')
            ->select(
                DB::raw('SUM(total_stock) as total_stock'),
                DB::raw('SUM(assigned_stock) as assigned_stock'),
                DB::raw('SUM(sold_count) as sold_stock'),
                DB::raw('SUM(damage_stock) as damaged_stock')
            )->first();

        $this->totalStock = $stockStats->total_stock ?? 0;
        $this->assignedStock = $stockStats->assigned_stock ?? 0;
        $this->soldStock = $stockStats->sold_stock ?? 0;
        $this->damagedStock = $stockStats->damaged_stock ?? 0;

        // Calculate percentages
        if ($this->assignedStock > 0) {
            $this->soldPercentage = round(($this->soldStock / $this->assignedStock) * 100, 1);
        }

        if ($this->totalStock > 0) {
            $this->assignmentPercentage = round(($this->assignedStock / $this->totalStock) * 100, 1);
        }

        // Calculate damaged inventory value
        $damagedValue = DB::table('watch_stocks')
            ->join('watch_prices', 'watch_stocks.watch_id', '=', 'watch_prices.watch_id')
            ->select(DB::raw('SUM(watch_stocks.damage_stock * watch_prices.supplier_price) as damaged_value'))
            ->first();

        $this->damagedValue = $damagedValue->damaged_value ?? 0;

        // Calculate total inventory value (all stocks)
        $totalInventoryValue = DB::table('watch_stocks')
            ->join('watch_prices', 'watch_stocks.watch_id', '=', 'watch_prices.watch_id')
            ->select(DB::raw('SUM(watch_stocks.available_stock * watch_prices.supplier_price) as total_value'))
            ->first();
            
        $this->totalInventoryValue = $totalInventoryValue->total_value ?? 0;

        $this->totalStaffCount = DB::table('users')->where('role', 'staff')->count();
        $this->staffWithAssignmentsCount = DB::table('staff_sales')
            ->select('staff_id')
            ->distinct()
            ->count('staff_id');

        // Calculate assignment percentage
        if ($this->totalStaffCount > 0) {
            $this->staffAssignmentPercentage = round(($this->staffWithAssignmentsCount / $this->totalStaffCount) * 100, 1);
        }
        $staffSalesTotal = DB::table('staff_sales')
            ->select(DB::raw('SUM(total_value) as total_value'))
            ->first();
            
        $this->totalStaffSalesValue = $staffSalesTotal->total_value ?? 0;

        // Fetch recent sales
        $this->loadRecentSales();

        // Fetch watch inventory data
        $this->loadWatchInventory();

        // Fetch brand sales data
        $this->loadBrandSales();

        // Fetch staff sales data
        $this->loadStaffSales();
    }

    public function loadRecentSales()
    {
        // Join customers table to get customer details
        $this->recentSales = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'sales.id',
                'sales.invoice_number',
                'sales.total_amount',
                'sales.payment_status',
                'sales.created_at',
                'customers.name',
                'customers.email',
                'sales.due_amount',
            )
            ->orderBy('sales.created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadWatchInventory()
    {
        // Join watches and stock tables to get full inventory data
        $this->watchInventory = DB::table('watch_details')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select(
                'watch_details.id',
                'watch_details.code',
                'watch_details.name',
                'watch_details.model',
                'watch_details.brand',
                'watch_stocks.available_stock',
                'watch_stocks.total_stock',
                'watch_stocks.damage_stock'
            )
            ->orderBy('watch_stocks.available_stock', 'asc')
            ->limit(5)
            ->get();
    }

    public function loadBrandSales()
    {
        // Get total sales per brand
        $this->brandSales = DB::table('sale_items')
            ->join('watch_details', 'sale_items.watch_id', '=', 'watch_details.id')
            ->select('watch_details.brand', DB::raw('SUM(sale_items.total) as total_sales'))
            ->groupBy('watch_details.brand')
            ->orderBy('total_sales', 'desc')
            ->get()
            ->toArray();
    }

    public function loadStaffSales()
    {
        $this->staffSales = DB::table('users')
            ->where('role', 'staff')
            ->leftJoin('staff_sales', 'users.id', '=', 'staff_sales.staff_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(SUM(staff_sales.total_value), 0) as assigned_value'),
                DB::raw('COALESCE(SUM(staff_sales.sold_value), 0) as sold_value'),
                DB::raw('COALESCE(SUM(staff_sales.total_quantity), 0) as assigned_quantity'),
                DB::raw('COALESCE(SUM(staff_sales.sold_quantity), 0) as sold_quantity')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get()
            ->map(function($staff) {
                // Calculate due amount from sales table
                $salesInfo = DB::table('sales')
                    ->where('user_id', $staff->id)
                    ->select(
                        DB::raw('COALESCE(SUM(total_amount), 0) as total_sales'),
                        DB::raw('COALESCE(SUM(due_amount), 0) as total_due')
                    )
                    ->first();
                
                $staff->total_sales = $salesInfo->total_sales ?? 0;
                $staff->total_due = $salesInfo->total_due ?? 0;
                $staff->collected_amount = $staff->total_sales - $staff->total_due;
                
                // Calculate percentages for progress bars
                $staff->sales_percentage = $staff->assigned_value > 0 ? 
                    round(($staff->sold_value / $staff->assigned_value) * 100, 1) : 0;
                
                $staff->payment_percentage = $staff->total_sales > 0 ? 
                    round(($staff->collected_amount / $staff->total_sales) * 100, 1) : 0;
                    
                return $staff;
            });
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}
