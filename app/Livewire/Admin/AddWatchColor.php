<?php

namespace App\Livewire\Admin;

use Exception;
use Livewire\Component;
use App\Models\WatchColors;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Color")]
#[Layout('components.layouts.admin')]

class AddWatchColor extends Component
{
public $modalKey = 1;
    public $colorName;
    public $colorCode = '#000000';
    public function render()
    {
        $colors = WatchColors::orderBy('id','asc')->get();
        return view('livewire.admin.add-watch-color',[
            'colors' => $colors,
        ]);
    }

    public function createColor(){
        // $this->reset();
        // $this->js("$('#createColorModal').modal('show')");
        $this->dispatch('create-color-modal');
    }
    public function saveColor(){
        $this->validate([
            'colorName' => 'required|unique:watch_colors,name',
            'colorCode' => 'required|unique:watch_colors,hex_code'
        ]);
        try{
            WatchColors::create([
                'name' => $this->colorName,
                'hex_code' => $this->colorCode,
            ]);
            $this->modalKey++;
            $this->js("Swal.fire('Success!', 'Color Created Successfully', 'success')");
        
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createColorModal").modal("hide")');
        
    }

    public $editColorId;
    public $editColorName;
    public $editColorCode;
    public function editColor($id){
        $color = WatchColors::find($id);
        $this->editColorName = $color->name;
        $this->editColorCode = $color->hex_code;
        $this->editColorId = $color->id;
        
        $this->dispatch('open-edit-modal');
        // $this->js("$('#editColorModal').modal('show')");
    }

    public function updateColor($id){
        $this->validate([
            'editColorName' => 'required|unique:watch_colors,name,'.$id,
            'editColorCode' => 'required|unique:watch_colors,hex_code,'.$id
        ]);
        try{
            
            WatchColors::where('id', $id)->update([
                'name' => $this->editColorName,
                'hex_code' => $this->editColorCode,
            ]);
            $this->js('$("#editColorModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Color Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editColorModal").modal("hide")');
    }

    
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteColor(){
        try{
            WatchColors::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }

    public function resetForm()
    {
        $this->reset([
            'colorName',
            'colorCode',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
