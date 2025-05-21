<?php

namespace App\Livewire\Admin;
use Exception;
use App\Models\WatchSupplier;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Title("Watch Supplier")]
#[Layout('components.layouts.admin')]
class SupplierList extends Component
{
    use WithPagination;
    public function render()
    {
        $suppliers = WatchSupplier::orderBy('id','asc')->paginate(10);
        return view('livewire.admin.supplier-list',[
            'suppliers'=> $suppliers
        ]);
    }
    public $name = '';
    public $contactNumber = '';
    public $address = '';
    public $email = '';


    public function createSupplier(){
        $this->name = '';
        $this->contactNumber = '';
        $this->address = '';
        $this->email = '';
        $this->dispatch('create-supplier');
        // $this->js("$('#createSupplierModal').modal('show')");
    }
    public function saveSupplier(){
        $this->validate([
            'name' => 'required|unique:watch_suppliers,name',
            'contactNumber' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:watch_suppliers,email'
        ]);
        try{
            WatchSupplier::create([
                'name' => $this->name,
                'contact' => $this->contactNumber,
                'address' => $this->address,
                'email' => $this->email
            ]);
            
            // Reset form fields after successful creation
            $this->reset(['name', 'contactNumber', 'address', 'email']);
            
            $this->js("Swal.fire('Success!', 'Supplier Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createSupplierModal").modal("hide")');
    }
    public $editSupplierId;
    public $editName;
    public $editContactNumber;
    public $editAddress;
    public $editEmail;
    public function editSupplier($id){
        $supplier = WatchSupplier::find($id);
        // dd($supplier);
        $this->editName = $supplier->name;
        $this->editContactNumber = $supplier->contact;
        $this->editAddress = $supplier->address;
        $this->editEmail = $supplier->email;
        $this->editSupplierId = $supplier->id;
        
        // $this->js("$('#editSupplierModal').modal('show')");
        $this->dispatch('edit-supplier');
    }
    public function updateSupplier($id){
        $this->validate([
            'editName' => 'required|unique:watch_suppliers,name,'.$id,
            'editContactNumber' => 'required',
            'editAddress' => 'required',
            'editEmail' => 'required|email|unique:watch_suppliers,email,'.$id
        ]);
        try{
            $supplier = WatchSupplier::find($id);
            $supplier->name = $this->editName;
            $supplier->contact = $this->editContactNumber;
            $supplier->address = $this->editAddress;
            $supplier->email = $this->editEmail;
            $supplier->save();
            $this->js("Swal.fire('Success!', 'Supplier Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editSupplierModal").modal("hide")');
        
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
            WatchSupplier::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'contactNumber', 'address', 'email']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
