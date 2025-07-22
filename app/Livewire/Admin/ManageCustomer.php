<?php

namespace App\Livewire\Admin;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('components.layouts.admin')]
#[Title('Manage Customer')]
class ManageCustomer extends Component
{
    public function render()
    {
        $customers = Customer::all();
        return view('livewire.admin.manage-customer',[
            'customers'=> $customers
        ]);
    }
    public $name;
    public $contactNumber;
    public $address;
    public $email;
    public $customerType;
    public $bussinessName;

    public function createCustomer(){
        // $this->reset();
        $this->js("$('#createCustomerModal').modal('show')");
    }
    public function saveCustomer(){
        $this->validate([
            'name' => 'required',
            'customerType' => 'required',
            'contactNumber' => 'required',
            'address' => 'required',
            'email' => 'email|unique:customers,email',
            'bussinessName' => 'nullable',
        ]);
        try{
            Customer::create([
                'name' => $this->name,
                'phone' => $this->contactNumber,
                'address' => $this->address,
                'email' => $this->email,
                'type' => $this->customerType,
                'business_name' => $this->bussiness_name,
            ]);
            $this->js("Swal.fire('Success!', 'Customer Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createCustomerModal").modal("hide")');
        
    }
    public $editCustomerId;
    public $editName;
    public $editContactNumber;
    public $editAddress;
    public $editEmail;
    public $editCustomerType;
    public $editBussinessName;
    public function editCustomer($id){
        $customer = Customer::find($id);
        // dd($customer);
        $this->editName = $customer->name;
        $this->editContactNumber = $customer->phone;
        $this->editBussinessName = $customer->business_name;
        $this->editCustomerType = $customer->type;
        $this->editAddress = $customer->address;
        $this->editEmail = $customer->email;
        $this->editCustomerId = $customer->id;

        // dd(''.$customer->id,$customer->name,$customer->phone,$customer->business_name,$customer->type,$customer->address,$customer->email);
        // $this->js("$('#editCustomerModal').modal('show')");
        $this->dispatch('open-edit-modal');
    }
    public function updateCustomer($id){
        $this->validate([
            'editName' => 'required',
            'editCustomerType' => 'required',
            'editBussinessName' => 'nullable',
            'editContactNumber' => 'required',
            'editAddress' => 'required',
            'editEmail' => 'email|unique:customers,email,'.$id
        ]);
        try{
            $customer = Customer::find($id);
            $customer->name = $this->editName;
            $customer->phone = $this->editContactNumber; // <-- fixed here
            $customer->business_name = $this->editBussinessName;
            $customer->type = $this->editCustomerType;
            $customer->address = $this->editAddress;
            $customer->email = $this->editEmail;
            $customer->save();
            $this->js("Swal.fire('Success!', 'Customer Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editCustomerModal").modal("hide")');
        
    }
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteDialColor(){
        try{
            Customer::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}

