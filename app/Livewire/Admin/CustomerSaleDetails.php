<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Customer;

#[Layout('components.layouts.admin')]
#[Title('Customer Sales Details')]
class CustomerSaleDetails extends Component
{
    use WithPagination;
    
    public $modalData = null;
    
    public function viewSaleDetails($customerId)
    {
        // Get customer details
        $customer = Customer::findOrFail($customerId);
        
        // Get customer sales summary
        $salesSummary = DB::table('sales')
            ->where('customer_id', $customerId)
            ->select(
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('SUM(due_amount) as total_due'),
                DB::raw('SUM(total_amount) - SUM(due_amount) as total_paid')
            )
            ->first();
            
        // Get individual invoices
        $invoices = Sale::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get product-wise sales with watch details
        $productSales = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('watch_details', 'sale_items.watch_id', '=', 'watch_details.id')
            ->where('sales.customer_id', $customerId)
            ->select(
                'sale_items.*',
                'sales.invoice_number',
                'sales.created_at as sale_date',
                'watch_details.name as watch_name',
                'watch_details.brand as watch_brand',
                'watch_details.model as watch_model',
                'watch_details.image as watch_image'
            )
            ->orderBy('sales.created_at', 'desc')
            ->get();
            
        $this->modalData = [
            'customer' => $customer,
            'salesSummary' => $salesSummary,
            'invoices' => $invoices,
            'productSales' => $productSales
        ];
        
        $this->dispatch('open-customer-sale-details-modal');
    }
    
    public function render()
    {
        $customerSales = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'customers.id as customer_id',
                'customers.name',
                'customers.email',
                'customers.business_name',
                'customers.type',
                DB::raw('COUNT(DISTINCT sales.invoice_number) as invoice_count'),
                DB::raw('SUM(sales.total_amount) as total_sales'),
                DB::raw('SUM(sales.due_amount) as total_due')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.business_name', 'customers.type')
            ->orderBy('total_sales', 'desc')
            ->paginate(10);

        return view('livewire.admin.customer-sale-details', [
            'customerSales' => $customerSales
        ]);
    }
}
