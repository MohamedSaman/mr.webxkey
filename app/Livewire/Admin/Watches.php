<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\WatchColors;
use App\Models\WatchDetail;

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title("Watches")]
#[Layout('components.layouts.admin')]
class Watches extends Component
{
    public $code;
    public $name;
    public $brand;
    public $model;
    public $color;
    public $made_by;
    public $category;
    public $gender;
    public $type;
    public $movement;
    public $dialColor;
    public $strapColor;
    public $strapMaterial;
    public $caseDiameter;
    public $caseThickness;
    public $glassType;
    public $waterResistance;
    public $features;
    public $image;
    public $warranty;
    public $description;
    public $barcode;
    public $supplier;
    public $supplierContact;
    public $supplierPrice;
    public $sellingPrice;
    public $discountPrice;
    public $shopStock;
    public $storeStock;
    public $damageStock;
    public $status;
    public $location;
 
    public function render()
    {
        $watches = WatchDetail::orderBy('created_at','desc')->get();
        $watchColors = WatchColors::orderBy('id','asc')->get();
        return view('livewire.admin.watches',[
            'watches' => $watches,
            'watchColors'=> $watchColors
        ]);
    }

    public function  createWatch(){
        $this->js("$('#createWatchModal').modal('show')");
        
    }

    public function editWatch($id){
        $this->js("$('#editWatchModal').modal('show')");
    }

    public function deleteWatch($id){
        dd($id);
    }
}
