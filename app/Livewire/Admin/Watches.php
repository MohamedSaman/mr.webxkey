<?php

namespace App\Livewire\Admin;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\BrandList;
use App\Models\WatchPrice;
use App\Models\WatchStock;
use App\Models\WatchColors;
use App\Models\WatchDetail;
use App\Models\WatchMadeBy;
use App\Models\CategoryList;
use App\Models\DialColorList;
use App\Models\GlassTypeList;
use App\Models\WatchSupplier;
use App\Models\WatchTypeList;
use App\Models\StrapColorList;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\StrapMaterialList;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title("Watches")]
#[Layout('components.layouts.admin')]
class Watches extends Component
{
    use WithFileUploads, WithPagination;

    public $code;
    public $name;
    public $brand;
    public $model;
    public $color;
    public $madeBy;
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
    public $supplierPrice;
    public $sellingPrice;
    public $discountPrice;
    public $shopStock = 0;
    public $storeStock = 0;
    public $damageStock = 0;
    public $status;
    public $location;
    public $search = '';

    public function render()
    {
        $watches = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select('watch_details.*', 'watch_suppliers.*', 'watch_prices.*', 'watch_stocks.*','watch_details.name as watch_name')
            ->where('watch_details.name', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.code', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.model', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.brand', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.status', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.barcode', 'like', '%' . $this->search . '%')
            ->orderBy('watch_details.created_at', 'desc')
            ->paginate(10);
        // dd($watches);
        $watchColors = WatchColors::orderBy('id', 'asc')->get();
        $watchStrapColors = StrapColorList::orderBy('id', 'asc')->get();
        $watchStrapMaterials = StrapMaterialList::orderBy('id', 'asc')->get();
        $watchBarnds = BrandList::orderBy('id', 'asc')->get();
        $watchCategories = CategoryList::orderBy('id', 'asc')->get();
        $watchDialColors = DialColorList::orderBy('id', 'asc')->get();
        $watchGlassTypes = GlassTypeList::orderBy('id', 'asc')->get();
        $watchMadeins = WatchMadeBy::orderBy('id', 'asc')->get();
        $watchType = WatchTypeList::orderBy('id', 'asc')->get();
        $watchSuppliers = WatchSupplier::orderBy('id', 'asc')->get();
        // dd($watchSuppliers);
        return view('livewire.admin.watches', [
            'watches' => $watches,
            'watchColors' => $watchColors,
            'watchStrapColors' => $watchStrapColors,
            'watchStrapMaterials' => $watchStrapMaterials,
            'watchCategories' => $watchCategories,
            'watchBarnds' => $watchBarnds,
            'watchDialColors' => $watchDialColors,
            'watchGlassTypes' => $watchGlassTypes,
            'watchMadeins' => $watchMadeins,
            'watchType' => $watchType,
            'watchSuppliers' => $watchSuppliers,
        ]);
    }

    public $watchDetails;
    public function viewWatch($id)
    {
        // Find the watch with its related data
        $this->watchDetails = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select('watch_details.*', 'watch_suppliers.*', 'watch_prices.*', 'watch_stocks.*','watch_suppliers.name as supplier_name')
            ->where('watch_details.id', $id)
            ->first();
        // dd($this->watchDetails);
        $this->js("$('#viewWatchModal').modal('show')");
    }

    public function createWatch()
    {
        $this->reset();
        $this->js("$('#createWatchModal').modal('show')");

    }

    public function saveWatch()
    {
        $this->validateCretaeWatch();

        // Generate code first
        $this->code = $this->generateCode();
        // dd($this->code);

        // Use database transaction to ensure all records are created together
        DB::beginTransaction();

        try {

            // Handle image upload if file exists
            $imagePath = null;
            if ($this->image) {
                $imageName = time() . '-' . $this->code . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('public/images/WatchImages', $imageName);
                $imagePath = 'images/WatchImages/' . $imageName;
            }


            // 1. Create main watch record
            $watch = WatchDetail::create([
                'code' => $this->code,
                'name' => $this->name,
                'model' => $this->model,
                'color' => $this->color,
                'made_by' => $this->madeBy,
                'gender' => $this->gender,
                'type' => $this->type,
                'movement' => $this->movement,
                'dial_color' => $this->dialColor,
                'strap_color' => $this->strapColor,
                'strap_material' => $this->strapMaterial,
                'case_diameter_mm' => $this->caseDiameter,
                'case_thickness_mm' => $this->caseThickness,
                'glass_type' => $this->glassType,
                'water_resistance' => $this->waterResistance,
                'features' => $this->features,
                'image' => $imagePath,
                'warranty' => $this->warranty,
                'description' => $this->description,
                'barcode' => $this->barcode,
                'status' => $this->status,
                'location' => $this->location,
                'brand' => $this->brand,
                'category' => $this->category,
                'supplier_id' => $this->supplier
            ]);

            // 2. Create price record
            WatchPrice::create([
                'supplier_price' => $this->supplierPrice,
                'selling_price' => $this->sellingPrice,
                'discount_price' => $this->discountPrice,
                'watch_id' => $watch->id
            ]);

            // 3. Create stock record
            $shopStock = (int) $this->shopStock;
            $storeStock = (int) $this->storeStock;
            $damageStock = (int) $this->damageStock;

            WatchStock::create([
                'shop_stock' => $shopStock,
                'store_stock' => $storeStock,
                'damage_stock' => $damageStock,
                'total_stock' => $shopStock + $storeStock + $damageStock,
                'available_stock' => $shopStock + $storeStock,
                'watch_id' => $watch->id
            ]);

            // Commit the transaction - everything was successful
            DB::commit();

            // Show success message and clean up
            $this->js("Swal.fire('Success!', 'Watch created successfully', 'success')");
            $this->reset();
            $this->js('$("#createWatchModal").modal("hide")');

        } catch (Exception $e) {
            // Roll back the transaction if anything failed
            DB::rollBack();

            // Log the detailed error for developers
            logger('Error creating watch: ' . $e->getMessage());
            dd($e->getMessage());
            // Show a user-friendly error message
            $this->js("Swal.fire({
                icon: 'error',
                title: 'Watch Creation Failed',
                text: '" . $e->getMessage() . "',
            })");
        }
    }

    public $editId;
    public $editCode;
    public $editName;
    public $editModel;
    public $editBrand;    
    public $editColor;
    public $editMadeBy;
    public $editCategory;
    public $editType;
    public $editGender;
    public $editMovement;
    public $editDialColor;
    public $editStrapColor;
    public $editStrapMaterial;
    public $editCaseDiameter;
    public $editCaseThickness;
    public $editGlassType;
    public $editWaterResistance;
    public $editFeatures;
    public $editWarranty;
    public $editDescription;
    public $editStatus;
    public $editLocation;
    public $editSupplier;
    public $editSupplierPrice;
    public $editSellingPrice;
    public $editDiscountPrice;
    public $editShopStock;
    public $editStoreStock;
    public $editDamageStock;
    public $editBarcode;
    public $editImage;
    public $existingImage;
    public $isLoading = false;
    public function editWatch($id)
    {
        // $this->isLoading = true;
        // Find the watch with its related data
        $watch = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select('watch_details.*', 'watch_suppliers.*', 'watch_prices.*', 'watch_stocks.*','watch_suppliers.name as supplier_name', 'watch_details.name as watch_name')
            ->where('watch_details.id', $id)
            ->first();

        // dd($watch);
        // Basic information
        $this->editId = $id;
        $this->editCode = $watch->code;
        $this->editName = $watch->watch_name;
        $this->editModel = $watch->model;
        $this->editBrand = $watch->brand;
        $this->editColor = $watch->color;
        $this->editMadeBy = $watch->made_by;

        // Classification
        $this->editCategory = $watch->category;
        $this->editGender = $watch->gender;
        $this->editType = $watch->type;

        // Technical Specifications
        $this->editMovement = $watch->movement;
        $this->editDialColor = $watch->dial_color;
        $this->editStrapColor = $watch->strap_color;
        $this->editStrapMaterial = $watch->strap_material;
        $this->editCaseDiameter = $watch->case_diameter_mm;
        $this->editCaseThickness = $watch->case_thickness_mm;
        $this->editGlassType = $watch->glass_type;
        $this->editWaterResistance = $watch->water_resistance;
        $this->editFeatures = $watch->features;

        // Product Information
        $this->existingImage = $watch->image;
        $this->editWarranty = $watch->warranty;
        $this->editBarcode = $watch->barcode;
        $this->editDescription = $watch->description;

        // Supplier Information
        $this->editSupplier = $watch->supplier_name;
        $this->editSupplierPrice = $watch->supplier_price;

        // Pricing and Inventory
        $this->editSellingPrice = $watch->selling_price;
        $this->editDiscountPrice = $watch->discount_price;
        $this->editShopStock = $watch->shop_stock;
        $this->editStoreStock = $watch->store_stock;
        $this->editDamageStock = $watch->damage_stock;
        $this->editStatus = $watch->status;
        $this->editLocation = $watch->location;

        // dd($this->editId,$this->editCode,$this->editName,$this->editModel,$this->editBrand,$this->editColor,$this->editMadeBy,$this->editCategory,$this->editType,$this->editGender,$this->editMovement,$this->editDialColor,$this->editStrapColor,$this->editStrapMaterial,$this->editCaseDiameter,$this->editCaseThickness,$this->editGlassType,$this->editWaterResistance,$this->editFeatures,$this->existingImage,$this->editWarranty,$this->editBarcode,$this->editDescription,$this->editSupplierPrice,$this->editSellingPrice,$this->editDiscountPrice);
        // Show the modal
        // $this->isLoading = false;
        // $this->js("$('#editWatchModal').modal('show')");
        $this->dispatch('open-edit-modal');
    }

    public function updateWatch($id)
    {
        // dd('update');
        $this->validateEditWatch();

        // Use database transaction to ensure all records are updated together
        DB::beginTransaction();

        try {
            // dd($this->editId);
            // Handle image upload if file exists
            $imagePath = $this->existingImage;
            if ($this->editImage) {
                $imageName = time() . '-' . $this->editCode . '.' . $this->editImage->getClientOriginalExtension();
                $this->editImage->storeAs('public/images/WatchImages', $imageName);
                $imagePath = 'images/WatchImages/' . $imageName;
            }

            $code = $this->editCode();
            // dd($code);
            // Update the main watch record
            WatchDetail::where('id', $id)->update([
                'code' => $code,
                'name' => $this->editName,
                'model' => $this->editModel,
                'color' => $this->editColor,
                'made_by' => $this->editMadeBy,
                'brand' => $this->editBrand,
                'category' => $this->editCategory,
                'gender' => $this->editGender,
                'type' => $this->editType,
                'movement' => $this->editMovement,
                'dial_color' => $this->editDialColor,
                'strap_color' => $this->editStrapColor,
                'strap_material' => $this->editStrapMaterial,
                'case_diameter_mm' => $this->editCaseDiameter,
                'case_thickness_mm' => $this->editCaseThickness,
                'glass_type' => $this->editGlassType,
                'water_resistance' => $this->editWaterResistance,
                'features' => $this->editFeatures,
                'warranty' => $this->editWarranty,
                'barcode' => $this->editBarcode,
                'description' => $this->editDescription,
                'image' => $imagePath,
                'status' => $this->editStatus,
                'location' => $this->editLocation,
            ]);

            // Update the price record
            WatchPrice::where('watch_id', $this->editId)->update([
                'supplier_price' => $this->editSupplierPrice,
                'selling_price' => $this->editSellingPrice,
                'discount_price' => $this->editDiscountPrice,
            ]);

            // 3. Create stock record
            $shopStock = (int) $this->editShopStock;
            $storeStock = (int) $this->editStoreStock;
            $damageStock = (int) $this->editDamageStock;
            // Update the stock record
            WatchStock::where('watch_id', $this->editId)->update([
                'shop_stock' => $shopStock,
                'store_stock' => $storeStock,
                'damage_stock' => $damageStock,
                'total_stock' => $shopStock + $storeStock + $damageStock,
                'available_stock' => $shopStock + $storeStock
            ]);

            // Update the supplier record
            WatchSupplier::where('id', $this->editSupplier)->update([
                'name' => $this->editSupplier,
            ]);

            DB::commit();

            // Close the modal
            $this->js("$('#editWatchModal').modal('hide')");
            $this->js("Swal.fire('Success!', 'Watch updated successfully!', 'success')");
        } catch (Exception $e) {
            DB::rollBack();
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }
    }   

    public $deleteId;
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch("confirm-delete");
    }
    #[On('confirmDelete')]
    public function deleteWatch()
    {
        try {
            WatchDetail::where('id', $this->deleteId)->delete();
        } catch (Exception $e) {
            // log($e->getMessage());
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }
    }

    public function exportToCsv()
    {
        return redirect()->route('watches.export');
    }

    private function validateCretaeWatch()
    {
        // Organize validation rules by categories for better readability
        $this->validate([
            // Basic information
            'name' => 'required',
            'model' => 'required',
            'barcode' => 'required',
            'description' => 'required',
            'image' => 'required|image|max:2048',

            // Classifications
            'brand' => 'required',
            'category' => 'required',
            'gender' => 'required',
            'type' => 'required',

            // Technical specifications
            'color' => 'required',
            'madeBy' => 'required',
            'movement' => 'required',
            'dialColor' => 'required',
            'strapColor' => 'required',
            'strapMaterial' => 'required',
            'caseDiameter' => 'required|numeric',
            'caseThickness' => 'required|numeric',
            'glassType' => 'required',
            'waterResistance' => 'required',
            'features' => 'required',
            'warranty' => 'required',

            // Inventory information
            'supplier' => 'required',
            'status' => 'required',
            'location' => 'required',

            // Price information
            'supplierPrice' => 'required|numeric|min:0',
            'sellingPrice' => 'required|numeric|min:0',
            'discountPrice' => 'required|numeric|min:0',

            // Stock information
            'shopStock' => 'required|numeric|min:0',
            'storeStock' => 'required|numeric|min:0',
            'damageStock' => 'required|numeric|min:0',
        ]);
    }

    private function validateEditWatch()
    {
        // Organize validation rules by categories for better readability
        $this->validate([
            // Basic information
            'editName' => 'required',
            'editModel' => 'required',
            'editBarcode' => 'required',
            'editDescription' => 'required',
            'editImage' => $this->existingImage ? 'nullable|image|max:2048' : 'required|image|max:2048',

            // Classifications
            'editBrand' => 'required',
            'editCategory' => 'required',
            'editGender' => 'required',
            'editType' => 'required',

            // Technical specifications
            'editColor' => 'required',
            'editMadeBy' => 'required',
            'editMovement' => 'required',
            'editDialColor' => 'required',
            'editStrapColor' => 'required',
            'editStrapMaterial' => 'required',
            'editCaseDiameter' => 'required|numeric',
            'editCaseThickness' => 'required|numeric',
            'editGlassType' => 'required',
            'editWaterResistance' => 'required',
            'editFeatures' => 'required',
            'editWarranty' => 'required',

            // Inventory information
            'editSupplier' => 'required',
            'editStatus' => 'required',
            'editLocation' => 'required',

            // Price information
            'editSupplierPrice' => 'required|numeric|min:0',
            'editSellingPrice' => 'required|numeric|min:0',
            'editDiscountPrice' => 'required|numeric|min:0',

            // Stock information
            'editShopStock' => 'required|numeric|min:0',
            'editStoreStock' => 'required|numeric|min:0',
            'editDamageStock' => 'required|numeric|min:0',
        ]);
    }

    /**
     * Generate a unique watch code consisting of brand, color, strap, gender prefixes + numeric ID
     * 
     * @return string Formatted watch code (e.g. "ROGSM1")
     */
    private function generateCode()
    {
        // Get the next numeric ID (last ID + 1)
        $lastWatch = WatchDetail::latest('id')->first();
        $numericId = $lastWatch ? $lastWatch->id + 1 : 1;

        // Extract code components from properties
        $components = [
            'brand' => strtoupper(substr($this->brand ?? '', 0, 3)),
            'color' => strtoupper(substr($this->color ?? '', 0, 1)),
            'strap' => strtoupper(substr($this->strapMaterial ?? '', 0, 1)),
            'gender' => strtoupper(substr($this->gender ?? '', 0, 1)),
        ];

        // Combine components into alphabetic prefix
        $prefix = implode('', $components);

        // Return the complete code
        return $prefix . $numericId;
    }

    private function editCode()
    {
        // Get the next numeric ID (last ID + 1)
       
        $numericId = $this->editId;

        // Extract code components from properties
        $components = [
            'brand' => strtoupper(substr($this->editBrand ?? '', 0, 3)),
            'color' => strtoupper(substr($this->editColor ?? '', 0, 1)),
            'strap' => strtoupper(substr($this->editStrapMaterial ?? '', 0, 1)),
            'gender' => strtoupper(substr($this->editGender ?? '', 0, 1)),
        ];

        // Combine components into alphabetic prefix
        $prefix = implode('', $components);
        // dd($prefix . $numericId);
        // Return the complete code
        return $prefix . $numericId;
    }
}
