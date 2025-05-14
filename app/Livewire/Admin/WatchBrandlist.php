<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;

use App\Models\BrandList;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Brand List")]
#[Layout('components.layouts.admin')]

class WatchBrandlist extends Component
{
    public $brandName;
    public function render()
    {
        $brands = BrandList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-brandlist',[
            'brands'=> $brands,
        ]);
    }

    public function createBrand(){
        $this->reset();
        $this->js("$('#createBrandModal').modal('show')");
    }

    public function saveBrand(){
        $this->validate([
            'brandName' => 'required|unique:brand_lists,brand_name'
        ]);
        try{
            
            BrandList::create([
                'brand_name' => $this->brandName,
            ]);
            $this->js("Swal.fire('Success!', 'Brand Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createBrandModal").modal("hide")');
        
    }

    public $editBrandId;
    public $editBrandName;
  
    public function editBrand($id){
        $brand = BrandList::find($id);
        $this->editBrandName = $brand->brand_name;
        $this->editBrandId = $brand->id;
        
        // $this->js("$('#editBrandModal').modal('show')");
        $this->dispatch('edit-brand');
    }

    public function updateBrand($id){
        $this->validate([
            'editBrandName' => 'required|unique:brand_lists,brand_name'.$id
        ]);
        try{
            BrandList::where('id', $id)->update([
                'brand_name' => $this->editBrandName,
            ]);
            $this->js('$("#editBrandModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Brand Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editBrandModal").modal("hide")');
    }
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteBrand(){
        try{
            BrandList::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}
