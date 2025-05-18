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
#[Title('Manage Admin')]
class ManageAdmin extends Component
{
    public function render()
    {
        $admins = User::where('role', 'admin')->get();
        return view('livewire.admin.manage-admin', [
            'admins' => $admins,
        ]);
    }

    public function createAdmin()
    {
        $this->reset();
        $this->js("$('#createAdminModal').modal('show')");
    }
    public $name;
    public $contactNumber;
    public $email;
    public $password;
    public $confirmPassword;
 
    public function saveAdmin()
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
                'role' => 'admin',
            ]);
            $this->js("Swal.fire('Success!', 'Admin Created Successfully', 'success')");
        } catch (Exception $e) {
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }

        $this->js('$("#createAdminModal").modal("hide")');
    }

    public $editName;
    public $editContactNumber;
    public $editEmail;
    
    public $editAdminId;
    public $editPassword;
    public $editConfirmPassword;
    public function editAdmin($id)
    {
        $user = User::find($id);
        if(! $user) {
            $this->js("Swal.fire('Error!', 'Admin Not Found', 'error')");
            return;
        }
        $this->editName = $user->name;
        $this->editContactNumber = $user->contact;
        $this->editEmail = $user->email;
        $this->editAdminId = $user->id;
        // Don't set password fields - leave them blank
        $this->editPassword = '';
        $this->editConfirmPassword = '';

        $this->dispatch('edit-admin-modal');
    }

    public function updateAdmin()
    {
        $validationRules = [
            'editName' => 'required',
            'editContactNumber' => 'required',
            'editEmail' => 'required|email|unique:users,email,' . $this->editAdminId,
        ];
        
        // Only validate password if it's provided
        if (!empty($this->editPassword)) {
            $validationRules['editPassword'] = 'required|min:8';
            $validationRules['editConfirmPassword'] = 'required|same:editPassword';
        }
        
        $this->validate($validationRules);

        try {
            $user = User::find($this->editAdminId);
            if ($user) {
                $user->name = $this->editName;
                $user->contact = $this->editContactNumber;
                $user->email = $this->editEmail;
                
                // Only update password if a new one was provided
                if (!empty($this->editPassword)) {
                    $user->password = Hash::make($this->editPassword);
                }
                
                $user->save();
                $this->js("Swal.fire('Success!', 'Admin Updated Successfully', 'success')");
            } else {
                $this->js("Swal.fire('Error!', 'Admin Not Found', 'error')");
            }
        } catch (Exception $e) {
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }

        $this->js('$("#editAdminModal").modal("hide")');
    }
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteAdmin()
    {
        $user = User::find($this->deleteId);
        if ($user) {
            $user->delete();
            // $this->js("Swal.fire('Success!', 'Admin Deleted Successfully', 'success')");
        } else {
            $this->js("Swal.fire('Error!', 'Admin Not Found', 'error')");
        }
    }
    
}
