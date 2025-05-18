<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
#[Title('Staff Due Details')]
class StaffDueDetails extends Component
{
    use WithPagination;

    public function render()
    {
        $staffDues = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.contact',
                DB::raw('SUM(sales.total_amount) as total_amount'),
                DB::raw('SUM(sales.due_amount) as due_amount'),
                DB::raw('SUM(sales.total_amount) - SUM(sales.due_amount) as collected_amount')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.contact')
            ->orderBy('total_amount', 'desc')
            ->paginate(10);

        return view('livewire.admin.staff-due-details', [
            'staffDues' => $staffDues
        ]);
    }
}
