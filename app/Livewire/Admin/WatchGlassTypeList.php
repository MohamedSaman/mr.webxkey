<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use Exception;
use App\Models\GlassTypeList;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Brand List")]
#[Layout('components.layouts.admin')]

class WatchGlassTypeList extends Component
{
    public $glassTypeName;
    public function render()
    {
        $glassTypes = GlassTypeList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-glass-type-list',[
            'glassTypes'=> $glassTypes,
        ]);
    }
    public function createGlassType(){
        $this->reset();
        $this->js("$('#createGlassTypeModal').modal('show')");
    }
    public function saveGlassType(){
        $this->validate([
            'glassTypeName' => 'required|unique:glass_type_lists,glass_type_name',
        ]);
        try{
            GlassTypeList::create([
                'glass_type_name' => $this->glassTypeName,
            ]);
            $this->js("Swal.fire('Success!', 'Glass Type Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createGlassTypeModal").modal("hide")');
        
    }
    public $editGlassTypeId;
    public $editGlassTypeName;
    public function editGlassType($id){
        $glassType = GlassTypeList::find($id);
        $this->editGlassTypeName = $glassType->glass_type_name;
        $this->editGlassTypeId = $glassType->id;
        
        $this->js("$('#editGlassTypeModal').modal('show')");
    }
    public function updateGlassType($id){

        try{
            $this->validate([
                'editGlassTypeName' => 'required|unique:glass_type_lists,glass_type_name,'.$id,
            ]);
            GlassTypeList::where('id', $id)->update([
                'glass_type_name' => $this->editGlassTypeName,
            ]);
            $this->js('$("#editGlassTypeModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Glass Type Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editGlassTypeModal").modal("hide")');        
    }
    public $deleteId;
    #[On('confirmDelete')]
    public function deleteGlassType(){
        try{
            GlassTypeList::where('id',  $this->deleteId)->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        $this->js('$("#editGlassTypeModal").modal("hide")');
    }
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
  
       
}
