<?php

namespace App\Livewire\Staff;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title("Staff Dashboard")]
#[Layout("components.layouts.staff")]
class StaffDashboard extends Component
{
    public function render()
    {
        return view('livewire.staff.staff-dashboard');
    }
}
