<?php

namespace App\Livewire\Admin;
use Exception;
use App\Models\WatchTypeList;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Type List")]
#[Layout('components.layouts.admin')]

class WatchTypes extends Component
{
    public $typeName;

    public function render()
    {
        $types = WatchTypeList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-types',[
            'types'=> $types,
        ]);
    }

    public function createType(){
        $this->dispatch('create-type-modal');
    }
    public function resetForm(){
        $this->reset([
            'typeName',
        ]);
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function saveType(){
        $this->validate([
            'typeName' => 'required|unique:watch_type_lists,type_name'
        ]);
        try{
            
            WatchTypeList::create([
                'type_name' => $this->typeName,
            ]);
            $this->js("Swal.fire('Success!', 'Type Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createTypeModal").modal("hide")');
        
    }

    public $editTypeId;
    public $editTypeName;
  
    public function editType($id){
        $type = WatchTypeList::find($id);
        $this->editTypeName = $type->type_name;
        $this->editTypeId = $type->id;
        
        // $this->js("$('#editTypeModal').modal('show')");
        $this->dispatch('edit-type-modal');
    }

    public function updateType($id){
        // dd($id);
        $this->validate([
            'editTypeName' => 'required|unique:watch_type_lists,type_name,'.$id
        ]);
        try{
            WatchTypeList::where('id', $id)->update([
                'type_name' => $this->editTypeName,
            ]);
            $this->js('$("#editTypeModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Type Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editTypeModal").modal("hide")');
    }
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteType(){
        try{
            WatchTypeList::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}
