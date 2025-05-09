<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;
use App\Models\StrapColorList;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Strap Color List")]
#[Layout('components.layouts.admin')]
class WatchStrapColorlist extends Component
{
    public $strapColorName;
    public function render()
    {
        $strapColors = StrapColorList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-strap-colorlist',[
            'strapColors'=> $strapColors,
        ]);
    }

    public function createStrapColor(){
        $this->reset();
        $this->js("$('#createStrapColorModal').modal('show')");
    }
    public function saveStrapColor(){
        $this->validate([
            'strapColorName' => 'required|unique:strap_color_lists,strap_color_name'
        ]);
        try{
            StrapColorList::create([
                'strap_color_name' => $this->strapColorName,
            ]);
            $this->js("Swal.fire('Success!', 'Strap Color Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createStrapColorModal").modal("hide")');
    }

    public $editStrapColorId;
    public $editStrapColorName;
    public function editStrapColor($id){
        $strapColor = StrapColorList::find($id);
        $this->editStrapColorName = $strapColor->strap_color_name;
        $this->editStrapColorId = $strapColor->id;
        
        $this->js("$('#editStrapColorModal').modal('show')");
    }

    public function updateStrapColor($id){
        $this->validate([
            'editStrapColorName' => 'required|unique:strap_color_lists,strap_color_name,'.$id
        ]);
        try{
            StrapColorList::where('id',$id)->update([
                'strap_color_name' => $this->editStrapColorName,
            ]);
            $this->js("Swal.fire('Success!', 'Strap Color Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editStrapColorModal").modal("hide")');
    }

    public $deleteId;
    public function confirmDelete($id){
        $this->deleteId = $id;
        $this->dispatch("confirm-delete");
    }

    #[On('confirmDelete')]
    public function deleteStrapColor(){
        try{
            StrapColorList::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }   
}
