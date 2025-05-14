<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.admin')]
#[Title('Manage Admin')]
class ManageAdmin extends Component
{
    public function render()
    {
        return view('livewire.admin.manage-admin');
    }
}
