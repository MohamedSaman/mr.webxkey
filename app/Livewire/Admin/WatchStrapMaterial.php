<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;
use App\Models\StrapMaterialList;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Brand List")]
#[Layout('components.layouts.admin')]

class WatchStrapMaterial extends Component
{
    public $strapMaterialName;
    public $materialQuality;
    public $editStrapMaterialId;
    public $editStrapMaterialName;
    public $editMaterialQuality;
    public $deleteId;
    public function render()
    {
        $strapMaterials = StrapMaterialList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-strap-material',[
            'strapMaterials'=> $strapMaterials,
        ]);
    }

    public function createStrapMaterial(){
        $this->reset();
        $this->js("$('#createStrapMaterialModal').modal('show')");
    }
    public function saveStrapMaterial(){
        // dd('here');
        $this->validate([
            'strapMaterialName' => 'required|unique:strap_material_lists,strap_material_name'
        ]);
        // dd('here');
        try{
            StrapMaterialList::create([
                'strap_material_name' => $this->strapMaterialName,
                'material_quality' => $this->materialQuality,
            ]);
            $this->js("Swal.fire('Success!', 'Strap Material Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createStrapMaterialModal").modal("hide")');
        
    }
    public function editStrapMaterial($id){
        $strapMaterial = StrapMaterialList::find($id);
        $this->editStrapMaterialName = $strapMaterial->strap_material_name;
        $this->editMaterialQuality = $strapMaterial->material_quality;
        $this->editStrapMaterialId = $strapMaterial->id;
        
        // $this->js("$('#editStrapMaterialModal').modal('show')");
        $this->dispatch('edit-strap-material');
    }
    public function updateStrapMaterial($id){
        $this->validate([
            'editStrapMaterialName' => 'required|unique:strap_material_lists,strap_material_name,'.$id,
        ]);
        try{
            StrapMaterialList::where('id',$id)->update([
                'strap_material_name' => $this->editStrapMaterialName,
                'material_quality' => $this->editMaterialQuality,
            ]);
            $this->js("Swal.fire('Success!', 'Strap Material Updated Successfully', 'success')");
        }catch(Exception $e){
            // logger('Error updating strap material: ' . $e->getMessage());
            $this->js("Swal.fire('Error!', 'Failed to update strap material', 'error')");
        }
        
        $this->js('$("#editStrapMaterialModal").modal("hide")');
    }

    public function confirmDelete($id){
        $this->deleteId = $id;
        $this->dispatch("confirm-delete");
    }
    #[On('confirmDelete')]
    public function deleteStrapMaterial(){
        try{
            StrapMaterialList::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}
