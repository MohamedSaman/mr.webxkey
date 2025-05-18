<?php

namespace App\Livewire\Admin;
use Exception;
use App\Models\WatchMadeBy;
use Livewire\Component;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watch Country List")]
#[Layout('components.layouts.admin')]
class MadeByList extends Component
{
    public $countryName;
    
    public function render()
    {
        $countries = WatchMadeBy::orderBy('id','desc')->get();
        return view('livewire.admin.made-by-list',[
            'countries'=> $countries,
        ]);
    }

    public function createCountry(){
        $this->reset();
        $this->js("$('#createCountryModal').modal('show')");
    }

    public function saveCountry(){
        $this->validate([
            'countryName' => 'required|unique:watch_made_bies,country_name'
        ]);
        try{
            
            WatchMadeBy::create([
                'country_name' => $this->countryName,
            ]);
            $this->js("Swal.fire('Success!', 'Country Created Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#createCountryModal").modal("hide")');
        
    }

    public $editCountryId;
    public $editCountryName;
  
    public function editCountry($id){
        $country = WatchMadeBy::find($id);
        $this->editCountryName = $country->country_name;
        $this->editCountryId = $country->id;
        
        // $this->js("$('#editCountryModal').modal('show')");
        $this->dispatch('edit-country-modal');
    }

    public function updateCountry($id){
        // dd($id);
        $this->validate([
            'editCountryName' => 'required|unique:watch_made_bies,country_name,'.$id
        ]);
        try{
            WatchMadeBy::where('id', $id)->update([
                'country_name' => $this->editCountryName,
            ]);
            $this->js('$("#editCountryModal").modal("hide")');
            $this->js("Swal.fire('Success!', 'Country Updated Successfully', 'success')");
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
        
        $this->js('$("#editCountryModal").modal("hide")');
    }
    
    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete');
    }
    #[On('confirmDelete')]
    public function deleteCountry(){
        try{
            WatchMadeBy::where('id', $this->deleteId )->delete();
        }catch(Exception $e){
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '".$e->getMessage()."', 'error')");
        }
    }
}
