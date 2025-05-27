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
    public $supplierPrice = 0;
    public $sellingPrice;
    public $discountPrice;
    public $shopStock = 0;
    public $storeStock = 0;
    public $damageStock = 0;
    public $status;
    public $location;
    public $search = '';

    public $imagePreview = null;
    public $editImagePreview = null;

    private function getDefaultSupplier()
    {
        // Cache the supplier ID to avoid repeated database queries
        static $supplierId = null;
        
        if ($supplierId === null) {
            $defaultSupplier = WatchSupplier::latest('id')->first();
            
            if (!$defaultSupplier) {
                $defaultSupplier = WatchSupplier::create([
                    'name' => 'Default Supplier',
                    'email' => null,
                    'contact' => null,
                    'address' => null,
                ]);
            }
            
            $supplierId = $defaultSupplier->id;
        }
        
        return $supplierId;
    }
    public function render()
    {
        $watches = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select(
                'watch_details.id',
                'watch_details.code',
                'watch_details.name as watch_name',
                'watch_details.model',
                'watch_details.color',
                'watch_details.made_by',
                'watch_details.gender',
                'watch_details.type',
                'watch_details.movement',
                'watch_details.dial_color',
                'watch_details.strap_color',
                'watch_details.strap_material',
                'watch_details.case_diameter_mm',
                'watch_details.case_thickness_mm',
                'watch_details.glass_type',
                'watch_details.water_resistance',
                'watch_details.features',
                'watch_details.image',
                'watch_details.warranty',
                'watch_details.description',
                'watch_details.barcode',
                'watch_details.status',
                'watch_details.location',
                'watch_details.brand',
                'watch_details.category',
                'watch_details.supplier_id',
                'watch_suppliers.id as supplier_id',
                'watch_suppliers.name as supplier_name',
                'watch_prices.supplier_price',
                'watch_prices.selling_price',
                'watch_prices.discount_price',
                'watch_stocks.shop_stock',
                'watch_stocks.store_stock',
                'watch_stocks.damage_stock',
                'watch_stocks.total_stock',
                'watch_stocks.available_stock'
            )
            ->where('watch_details.name', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.code', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.model', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.brand', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.status', 'like', '%' . $this->search . '%')
            ->orWhere('watch_details.barcode', 'like', '%' . $this->search . '%')
            ->orderBy('watch_details.created_at', 'desc')
            ->paginate(10);

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
            ->select(
                'watch_details.id',
                'watch_details.code',
                'watch_details.name as watch_name',
                'watch_details.model',
                'watch_details.color',
                'watch_details.made_by',
                'watch_details.gender',
                'watch_details.type',
                'watch_details.movement',
                'watch_details.dial_color',
                'watch_details.strap_color',
                'watch_details.strap_material',
                'watch_details.case_diameter_mm',
                'watch_details.case_thickness_mm',
                'watch_details.glass_type',
                'watch_details.water_resistance',
                'watch_details.features',
                'watch_details.image',
                'watch_details.warranty',
                'watch_details.description',
                'watch_details.barcode',
                'watch_details.status',
                'watch_details.location',
                'watch_details.brand',
                'watch_details.category',
                'watch_details.supplier_id',
                'watch_suppliers.name as supplier_name',
                'watch_prices.supplier_price',
                'watch_prices.selling_price',
                'watch_prices.discount_price',
                'watch_stocks.shop_stock',
                'watch_stocks.store_stock',
                'watch_stocks.damage_stock',
                'watch_stocks.total_stock',
                'watch_stocks.available_stock'
            )
            ->where('watch_details.id', $id)
            ->first();
// dd($this->watchDetails);        
        $this->js("$('#viewWatchModal').modal('show')");
    }

    public function createWatch()
    {
        $this->resetForm();
        
        // Set default supplier
        $this->supplier = $this->getDefaultSupplier();
        $this->supplierPrice = 0;
        
        $this->js("
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createWatchModal'));
                modal.show();
            }, 500);
        ");
    }

    public function saveWatch()
    {
        $this->validateCretaeWatch();

        $this->code = $this->generateCode();
        $this->supplier = $this->getDefaultSupplier();

        DB::beginTransaction();

        try {
            $imagePath = null;
            if ($this->image) {
                $imageName = time() . '-' . $this->code . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('public/images/WatchImages', $imageName);
                $imagePath = 'images/WatchImages/' . $imageName;
            }

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

            WatchPrice::create([
                'supplier_price' => $this->supplierPrice ?? 0,
                'selling_price' => $this->sellingPrice,
                'discount_price' => $this->discountPrice,
                'watch_id' => $watch->id
            ]);

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

            DB::commit();

            $this->js('$("#createWatchModal").modal("hide")');
            $this->resetForm();
            $this->dispatch('watch-created');
            $this->js("Swal.fire('Success!', 'Watch created successfully', 'success')");
            return redirect()->route('admin.watches');

        } catch (Exception $e) {
            DB::rollBack();
            logger('Error creating watch: ' . $e->getMessage());
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
    public $editSupplierName; // Add this to store the supplier name for display
    public $editSupplierPrice = 0;
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
        $this->resetEditImage();
        // Find the watch with its related data
        $watch = WatchDetail::join('watch_suppliers', 'watch_details.supplier_id', '=', 'watch_suppliers.id')
            ->join('watch_prices', 'watch_details.id', '=', 'watch_prices.watch_id')
            ->join('watch_stocks', 'watch_details.id', '=', 'watch_stocks.watch_id')
            ->select(
                'watch_details.id',
                'watch_details.code',
                'watch_details.name as watch_name',
                'watch_details.model',
                'watch_details.color',
                'watch_details.made_by',
                'watch_details.gender',
                'watch_details.type',
                'watch_details.movement',
                'watch_details.dial_color',
                'watch_details.strap_color',
                'watch_details.strap_material',
                'watch_details.case_diameter_mm',
                'watch_details.case_thickness_mm',
                'watch_details.glass_type',
                'watch_details.water_resistance',
                'watch_details.features',
                'watch_details.image',
                'watch_details.warranty',
                'watch_details.description',
                'watch_details.barcode',
                'watch_details.status',
                'watch_details.location',
                'watch_details.brand',
                'watch_details.category',
                'watch_details.supplier_id',
                'watch_suppliers.name as supplier_name',
                'watch_prices.supplier_price',
                'watch_prices.selling_price',
                'watch_prices.discount_price',
                'watch_stocks.shop_stock',
                'watch_stocks.store_stock',
                'watch_stocks.damage_stock'
            )
            ->where('watch_details.id', $id)
            ->first();

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

        // Supplier Information - Store both ID and name
        $this->editSupplier = $watch->supplier_id; // Store the ID for the update
        $this->editSupplierName = $watch->supplier_name ?? $this->getDefaultSupplier(); // Store name for display
        $this->editSupplierPrice = $watch->supplier_price ?? 0;

        // Pricing and Inventory
        $this->editSellingPrice = $watch->selling_price;
        $this->editDiscountPrice = $watch->discount_price;
        $this->editShopStock = $watch->shop_stock;
        $this->editStoreStock = $watch->store_stock;
        $this->editDamageStock = $watch->damage_stock;
        $this->editStatus = $watch->status;
        $this->editLocation = $watch->location;

        $this->dispatch('open-edit-modal');
    }

    public function updateWatch($id)
    {
        $this->validateEditWatch();

        // Use database transaction to ensure all records are updated together
        DB::beginTransaction();

        try {
            // Handle image upload if file exists
            $imagePath = $this->existingImage;
            if ($this->editImage) {
                $imageName = time() . '-' . $this->editCode . '.' . $this->editImage->getClientOriginalExtension();
                $this->editImage->storeAs('public/images/WatchImages', $imageName);
                $imagePath = 'images/WatchImages/' . $imageName;
            }

            $code = $this->editCode();
            
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
                'supplier_id' => $this->editSupplier ?? $this->getDefaultSupplier(), // Use the correct supplier ID
            ]);

            // Update the price record
            WatchPrice::where('watch_id', $this->editId)->update([
                'supplier_price' => $this->editSupplierPrice ?? 0,
                'selling_price' => $this->editSellingPrice,
                'discount_price' => $this->editDiscountPrice,
            ]);

            // Update stock record
            $shopStock = (int) $this->editShopStock;
            $storeStock = (int) $this->editStoreStock;
            $damageStock = (int) $this->editDamageStock;
            
            WatchStock::where('watch_id', $this->editId)->update([
                'shop_stock' => $shopStock,
                'store_stock' => $storeStock,
                'damage_stock' => $damageStock,
                'total_stock' => $shopStock + $storeStock + $damageStock,
                'available_stock' => $shopStock + $storeStock
            ]);

            DB::commit();
            $this->resetEditImage();

            // Close the modal
            $this->js("$('#editWatchModal').modal('hide')");
            $this->js("Swal.fire('Success!', 'Watch updated successfully!', 'success')");
        } catch (Exception $e) {
            DB::rollBack();
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
        }
    }   

    public function duplicateWatch()
    {
        $this->validateEditWatch();

        // Generate a new code for the duplicated watch
        $this->code = $this->generateDuplicateCode();
        $this->supplier = $this->editSupplier ?? $this->getDefaultSupplier();

        DB::beginTransaction();

        try {
            // Handle image - use the existing image or the newly uploaded one
            $imagePath = $this->existingImage;
            if ($this->editImage) {
                $imageName = time() . '-' . $this->code . '.' . $this->editImage->getClientOriginalExtension();
                $this->editImage->storeAs('public/images/WatchImages', $imageName);
                $imagePath = 'images/WatchImages/' . $imageName;
            }

            // Create a new watch record with the edited values
            $watch = WatchDetail::create([
                'code' => $this->code,
                'name' => $this->editName,  // Add (Copy) to indicate it's a duplicate
                'model' => $this->editModel,
                'color' => $this->editColor,
                'made_by' => $this->editMadeBy,
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
                'image' => $imagePath,
                'warranty' => $this->editWarranty,
                'description' => $this->editDescription,
                'barcode' => $this->editBarcode,  // Modify barcode to avoid duplicates
                'status' => $this->editStatus,
                'location' => $this->editLocation,
                'brand' => $this->editBrand,
                'category' => $this->editCategory,
                'supplier_id' => $this->supplier
            ]);

            // Create pricing record
            WatchPrice::create([
                'supplier_price' => $this->editSupplierPrice ?? 0,
                'selling_price' => $this->editSellingPrice,
                'discount_price' => $this->editDiscountPrice,
                'watch_id' => $watch->id
            ]);

            // Create stock record
            $shopStock = (int) $this->editShopStock;
            $storeStock = (int) $this->editStoreStock;
            $damageStock = (int) $this->editDamageStock;

            WatchStock::create([
                'shop_stock' => $shopStock,
                'store_stock' => $storeStock,
                'damage_stock' => $damageStock,
                'total_stock' => $shopStock + $storeStock + $damageStock,
                'available_stock' => $shopStock + $storeStock,
                'watch_id' => $watch->id
            ]);

            DB::commit();
            $this->resetEditImage();

            // Close the modal and show success message
            $this->js('$("#editWatchModal").modal("hide")');
            $this->resetForm();
            $this->js("Swal.fire('Success!', 'Watch duplicated successfully', 'success')");

        } catch (Exception $e) {
            DB::rollBack();
            logger('Error duplicating watch: ' . $e->getMessage());
            $this->js("Swal.fire({
                icon: 'error',
                title: 'Watch Duplication Failed',
                text: '" . $e->getMessage() . "',
            })");
        }
    }

    private function generateDuplicateCode()
    {
        $lastWatch = WatchDetail::latest('id')->first();
        $numericId = $lastWatch ? $lastWatch->id + 1 : 1;

        $components = [
            'brand' => strtoupper(substr($this->editBrand ?? '', 0, 3)),
            'color' => strtoupper(substr($this->editColor ?? '', 0, 1)),
            'strap' => strtoupper(substr($this->editStrapMaterial ?? '', 0, 1)),
            'gender' => strtoupper(substr($this->editGender ?? '', 0, 1)),
        ];

        $prefix = implode('', $components);

        return $prefix . $numericId;
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
            DB::beginTransaction();
            
            // First delete any related sale_items
            DB::table('sale_items')->where('watch_id', $this->deleteId)->delete();
            
            // Then delete the watch and its related data
            WatchStock::where('watch_id', $this->deleteId)->delete();
            WatchPrice::where('watch_id', $this->deleteId)->delete();
            WatchDetail::where('id', $this->deleteId)->delete();
            
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            $this->js("Swal.fire('Error!', '" . $e->getMessage() . "', 'error')");
            return false;
        }
    }

    public function exportToCsv()
    {
        return redirect()->route('watches.export');
    }

    private function validateCretaeWatch()
    {
        $this->validate([
            'name' => 'required',
            'model' => 'required',
            'barcode' => 'required',
            'description' => 'required',
            'image' => 'required|image|max:2048',
            'brand' => 'required',
            'category' => 'required',
            'gender' => 'required',
            'type' => 'required',
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
            // 'supplier' => 'required',
            'status' => 'required',
            'location' => 'required',
            // 'supplierPrice' => 'required|numeric|min:0',
            'sellingPrice' => 'required|numeric|min:0',
            'discountPrice' => 'required|numeric|min:0',
            'shopStock' => 'required|numeric|min:0',
            'storeStock' => 'required|numeric|min:0',
            'damageStock' => 'required|numeric|min:0',
        ]);
    }

    private function validateEditWatch()
    {
        $this->validate([
            'editName' => 'required',
            'editModel' => 'required',
            'editBarcode' => 'required',
            'editDescription' => 'required',
            'editImage' => $this->existingImage ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'editBrand' => 'required',
            'editCategory' => 'required',
            'editGender' => 'required',
            'editType' => 'required',
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
            // 'editSupplier' => 'required',
            'editStatus' => 'required',
            'editLocation' => 'required',
            // 'editSupplierPrice' => 'required|numeric|min:0',
            'editSellingPrice' => 'required|numeric|min:0',
            'editDiscountPrice' => 'required|numeric|min:0',
            'editShopStock' => 'required|numeric|min:0',
            'editStoreStock' => 'required|numeric|min:0',
            'editDamageStock' => 'required|numeric|min:0',
        ]);
    }

    private function generateCode()
    {
        $lastWatch = WatchDetail::latest('id')->first();
        $numericId = $lastWatch ? $lastWatch->id + 1 : 1;

        $components = [
            'brand' => strtoupper(substr($this->brand ?? '', 0, 3)),
            'color' => strtoupper(substr($this->color ?? '', 0, 1)),
            'strap' => strtoupper(substr($this->strapMaterial ?? '', 0, 1)),
            'gender' => strtoupper(substr($this->gender ?? '', 0, 1)),
        ];

        $prefix = implode('', $components);

        return $prefix . $numericId;
    }

    private function editCode()
    {
        $numericId = $this->editId;

        $components = [
            'brand' => strtoupper(substr($this->editBrand ?? '', 0, 3)),
            'color' => strtoupper(substr($this->editColor ?? '', 0, 1)),
            'strap' => strtoupper(substr($this->editStrapMaterial ?? '', 0, 1)),
            'gender' => strtoupper(substr($this->editGender ?? '', 0, 1)),
        ];

        $prefix = implode('', $components);

        return $prefix . $numericId;
    }

    #[On('reset-create-form')]
    public function resetCreateForm()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        // Enhanced reset that clears all relevant properties
        $this->reset([
            'code', 'name', 'model', 'color', 'madeBy', 'category', 'gender',
            'type', 'movement', 'dialColor', 'strapColor', 'strapMaterial',
            'caseDiameter', 'caseThickness', 'glassType', 'waterResistance',
            'features', 'warranty', 'description', 'barcode', 'status',
            'location', 'sellingPrice', 'discountPrice', 'shopStock',
            'storeStock', 'damageStock', 'image', 'supplier', 'supplierPrice'
        ]);
        
        // Reset validation errors
        $this->resetValidation();
        $this->resetErrorBag();
    }

    /**
     * Get file preview information with fallbacks for invalid temporary URLs
     * 
     * @param mixed $file The uploaded file object
     * @return array File information including type, preview URL, and icon
     */
    private function getFilePreview($file)
    {
        if (!$file || !is_object($file)) {
            return [
                'type' => null,
                'url' => null,
                'icon' => null
            ];
        }
        
        $extension = strtolower($file->getClientOriginalExtension());
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
        $isPdf = $extension === 'pdf';
        
        $result = [
            'type' => $isImage ? 'image' : ($isPdf ? 'pdf' : 'other'),
            'name' => $file->getClientOriginalName(),
            'url' => null,
            'icon' => $isImage ? 'bi-file-image' : ($isPdf ? 'bi-file-earmark-pdf' : 'bi-file'),
            'icon_color' => $isImage ? 'text-primary' : ($isPdf ? 'text-danger' : 'text-secondary')
        ];
        
        // Only try to get temporary URL for images
        if ($isImage) {
            try {
                $result['url'] = $file->temporaryUrl();
            } catch (\Exception $e) {
                // Temporary URL failed, we'll use the icon instead
                $result['url'] = null;
            }
        }
        
        return $result;
    }

    public function updatedImage()
    {
        if ($this->image) {
            $this->imagePreview = $this->getFilePreview($this->image);
        }
    }

    public function updatedEditImage()
    {
        if ($this->editImage) {
            $this->editImagePreview = $this->getFilePreview($this->editImage);
        }
    }

    public function resetEditImage()
    {
        $this->editImage = null;
        $this->editImagePreview = null;
    }
}
