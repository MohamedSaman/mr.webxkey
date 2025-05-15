<?php

namespace App\Livewire\Admin;
use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.admin')]
#[Title('Manage Staff')]
class ManageStaff extends Component
{
    public function render()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('livewire.admin.manage-staff', [
            'staffs' => $staffs,
        ]);

    }
    public function createStaff()
    {
        $this->reset();
        $this->js("$('#createStaffModal').modal('show')");
    }
    public $name;
    public $contactNumber;
    public $email;
    public $password;
    public $confirmPassword;
 
    public function saveStaff()
    {
        $this->validate([
            'name' => 'required',
            'contactNumber' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|min:8|same:password',
        ]);

        try {
            User::create([
                'name' => $this->name,
                'contact'=> $this->contactNumber,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'staff',
            ]);
            $this->js("Swal.fire('Success!', 'Staff Created Successfully', 'success')");
        } catch (Exception $e) {
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }

        $this->js('$("#createStaffModal").modal("hide")');
    }

    public $editName;
    public $editContactNumber;
    public $editEmail;
    
    public $editStaffId;
    public $editPassword;
    public $editConfirmPassword;
    public function editStaff($id)
    {
        $user = User::find($id);
        if(! $user) {
            $this->js("Swal.fire('Error!', 'Staff Not Found', 'error')");
            return;
        }
        $this->editName = $user->name;
        $this->editContactNumber = $user->contact;
        $this->editEmail = $user->email;
        $this->editStaffId = $user->id;
        // Don't set password fields - leave them blank
        $this->editPassword = '';
        $this->editConfirmPassword = '';

        $this->dispatch('edit-staff-modal');
    }

    public function updateStaff()
    {
        $validationRules = [
            'editName' => 'required',
            'editContactNumber' => 'required',
            'editEmail' => 'required|email|unique:users,email,' . $this->editStaffId,
        ];
        
        // Only validate password if it's provided
        if (!empty($this->editPassword)) {
            $validationRules['editPassword'] = 'required|min:8';
            $validationRules['editConfirmPassword'] = 'required|same:editPassword';
        }
        
        $this->validate($validationRules);

        try {
            $user = User::find($this->editStaffId);
            if ($user) {
                $user->name = $this->editName;
                $user->contact = $this->editContactNumber;
                $user->email = $this->editEmail;
                
                // Only update password if a new one was provided
                if (!empty($this->editPassword)) {
                    $user->password = Hash::make($this->editPassword);
                }
                
                $user->save();
                $this->js("Swal.fire('Success!', 'Staff Updated Successfully', 'success')");
            } else {
                $this->js("Swal.fire('Error!', 'Staff Not Found', 'error')");
            }
        } catch (Exception $e) {
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }

        $this->js('$("#editStaffModal").modal("hide")');
    }
    public $deleteId;
    #[On('confirmDelete')]
    public function deleteStaff()
    {
        $user = User::find($this->deleteId);
        if ($user) {
            $user->delete();
            $this->js("Swal.fire('Success!', 'Staff Deleted Successfully', 'success')");
        } else {
            $this->js("Swal.fire('Error!', 'Staff Not Found', 'error')");
        }
    }
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
}
