<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CategoryList;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Category List")]
#[Layout('components.layouts.admin')]
class WatchCategorylist extends Component
{
    public $categoryName;
    public function render()
    {
        $categories = CategoryList::orderBy('id','desc')->get();
        return view('livewire.admin.watch-categorylist',[
            'categories'=> $categories,
        ]);
    }

    public function createCategory(){
        $this->dispatch('create-category');
    }

    public function resetForm(){
        $this->categoryName = '';
    }

    public function saveCategory(){
        $this->validate([
            'categoryName' => 'required|unique:category_lists,category_name'
        ]);
        try{
            
            CategoryList::create([
                'category_name' => $this->categoryName,
            ]);
            $this->js("Swal.fire('Success!', 'Category Created Successfully', 'success')");
        }catch(Exception $e){
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createCategoryModal").modal("hide")');
        
    }

    public $editCategoryId;
    public $editCategoryName;
  
    public function editCategory($id){
        $category = CategoryList::find($id);
        $this->editCategoryName = $category->category_name;
        $this->editCategoryId = $category->id;
        
        $this->dispatch('edit-category');
        // $this->js("$('#editCategoryModal').modal('show')");
    }

    public function updateCategory($id){
        $this->validate([
            'editCategoryName' => 'required|unique:category_lists,category_name,'.$id
        ]);
        try{
            CategoryList::where('id', $id)->update([
                'category_name' => $this->editCategoryName,
            ]);
            $this->js('$("#editCategoryModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Category Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editCategoryModal").modal("hide")');
    }
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteCategory(){
        try{
            CategoryList::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}
