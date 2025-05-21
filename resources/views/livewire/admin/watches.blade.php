<div>
    @push('styles')
        <style>
            /* Tab navigation styles */
            .content-tabs {
                display: flex;
                border-bottom: 1px solid #dee2e6;
                margin-bottom: 20px;
            }

            .content-tab {
                padding: 10px 20px;
                cursor: pointer;
                font-weight: 500;
                color: #495057;
                border-bottom: 3px solid transparent;
                transition: all 0.2s;
            }

            .content-tab.active {
                color: #0d6efd;
                border-bottom-color: #0d6efd;
            }

            .content-tab:hover:not(.active) {
                color: #0d6efd;
                border-bottom-color: #dee2e6;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            /* Inventory header styles */
            .inventory-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .search-bar {
                width: 300px;
            }

            /* Add product button */
            .add-product-btn {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            /* Improved action buttons */
            .action-btns {
                display: flex;
                gap: 8px;
                justify-content: center;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                background: transparent;
                transition: all 0.2s ease;
            }

            .action-btn:hover {
                transform: scale(1.1);
            }

            .action-btn.edit {
                color: #0d6efd;
                background-color: rgba(13, 110, 253, 0.1);
            }

            .action-btn.delete {
                color: #dc3545;
                background-color: rgba(220, 53, 69, 0.1);
            }

            .action-btn.qr {
                color: #6c757d;
                background-color: rgba(108, 117, 125, 0.1);
            }

            .action-btn:hover.edit {
                background-color: rgba(13, 110, 253, 0.2);
            }

            .action-btn:hover.delete {
                background-color: rgba(220, 53, 69, 0.2);
            }

            .action-btn:hover.qr {
                background-color: rgba(108, 117, 125, 0.2);
            }
        </style>
    @endpush
    <div class="container-fluid p-3">
        <!-- Navigation Tabs -->
        <div class="content-tabs">
            <div class="content-tab active" data-tab="products">Products</div>
            <div class="content-tab" data-tab="barcode">Barcode Scanner</div>
            <div class="content-tab" data-tab="reports">Reports</div>
        </div>

        <!-- Products Content -->
        <div id="products" class="tab-content active">
            <div class="inventory-header">
                <h2>Product Inventory</h2>
                <div class="d-flex gap-3">

                    <div class="search-bar">
                        <div class="input-group">
                            <input type="text" class="form-control" id="product-search" wire:model.live ="search"
                                placeholder="Search products...">
                            {{-- <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                <i class="bi bi-search"></i>
                            </button> --}}
                        </div>
                    </div>
                    <button class="btn btn-primary add-product-btn" id="add-product-btn" wire:click="createWatch">
                        <i class="bi bi-plus-lg"></i> Add Product
                    </button>
                    <button wire:click="exportToCsv" class="btn btn-success">
                        <i class="fas fa-file-csv"></i> Export to CSV
                    </button>
                </div>
            </div>

            <p class="text-muted mb-4">Manage your product catalog and inventory levels</p>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">WatchName</th>
                        <th scope="col" class="text-center">Code</th>
                        <th scope="col" class="text-center">Barcode</th>
                        <th scope="col" class="text-center">Brand</th>
                        <th scope="col" class="text-center">Model</th>
                        <th scope="col" class="text-center">Stock</th>
                        <th scope="col" class="text-center">Selling Price</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </thead>
                    <tbody>
                        @if ($watches->count() > 0)
                            @foreach ($watches as $watch)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $watch->watch_name }}</td>
                                    <td class="text-center">{{ $watch->code }}</td>
                                    <td class="text-center">{{ $watch->barcode }}</td>
                                    <td class="text-center">{{ $watch->brand }}</td>
                                    <td class="text-center">{{ $watch->model }}</td>
                                    <td class="text-center">
                                        @if ($watch->available_stock > 0)
                                            <span class="badge bg-success rounded-pill">In Stock</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $watch->selling_price }}</td>
                                    <td class="text-center">
                                        @if ($watch->status == 'active')
                                            <span class="badge bg-success rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Inactive</span>
                                        @endif
                                    <td class="text-center">
                                        <div class="action-btns" style="justify-content: center;">
                                            <button class="btn action-btn edit" title="Edit"
                                                wire:click="editWatch({{ $watch->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-pencil" wire:loading.class="d-none"
                                                    wire:target="editWatch({{ $watch->id }})"></i>
                                                <span wire:loading wire:target="editWatch({{ $watch->id }})">
                                                    <i class="spinner-border spinner-border-sm"></i>
                                                </span>
                                            </button>
                                            <button class="action-btn delete" title="Delete"
                                                wire:click="confirmDelete({{ $watch->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <button class="action-btn qr" title="View"
                                                wire:click="viewWatch({{ $watch->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No watches found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $watches->links() }}
                </div>
            </div>
            {{-- <!-- Create Watch Modal --> --}}
            <div wire:ignore.self class="modal fade" id="createWatchModal" tabindex="-1"
                aria-labelledby="createWatchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h1 class="modal-title fs-5 text-white" id="createWatchModalLabel">Create New Watch</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-light p-4">
                            <!-- Basic Information Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Basic Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label fw-bold">Name:</label>
                                                <input type="text" class="form-control" id="name"
                                                    wire:model="name">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="model" class="form-label fw-bold">Model:</label>
                                                <input type="text" class="form-control" id="model"
                                                    wire:model="model">
                                                @error('model')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="brand" class="form-label fw-bold">Brand:</label>
                                                <select class="form-select" id="brand" wire:model="brand">
                                                    <option value="">Select Brand</option>
                                                    @foreach ($watchBarnds as $watchBrand)
                                                        <option value="{{ $watchBrand->brand_name }}">
                                                            {{ $watchBrand->brand_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('brand')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="color" class="form-label fw-bold">Color:</label>
                                                <select class="form-select" id="color" wire:model="color">
                                                    <option value="">Select Color</option>
                                                    @foreach ($watchColors as $watchColor)
                                                        <option value="{{ $watchColor->name }}">
                                                            {{ $watchColor->name }} ({{ $watchColor->hex_code }})

                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('color')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="madeBy" class="form-label fw-bold">Made By:</label>
                                                <select class="form-select" id="madeBy" wire:model="madeBy">
                                                    <option value="">Select Country</option>
                                                    @foreach ($watchMadeins as $madein)
                                                        <option value="{{ $madein->country_name }}">
                                                            {{ $madein->country_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('madeBy')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Classification Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Classification</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="category" class="form-label fw-bold">Category:</label>
                                                <select class="form-select" id="category" wire:model="category">
                                                    <option value="">Select Category</option>
                                                    @foreach ($watchCategories as $watchCategory)
                                                        <option value="{{ $watchCategory->category_name }}">
                                                            {{ $watchCategory->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label fw-bold">Gender:</label>
                                                <select class="form-select" id="gender" wire:model="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Men</option>
                                                    <option value="female">Women</option>
                                                    <option value="unisex">Unisex</option>
                                                </select>
                                                @error('gender')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="type" class="form-label fw-bold">Type:</label>
                                                <select class="form-select" id="type" wire:model="type">
                                                    <option value="">Select Type</option>
                                                    @foreach ($watchType as $type)
                                                        <option value="{{ $type->type_name }}">
                                                            {{ $type->type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Specifications Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Technical Specifications</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="movement" class="form-label fw-bold">Movement:</label>
                                                <select wire:model="movement" id="movement" class="form-select">
                                                    <option value="">Select Movement</option>
                                                    <option value="Mechanical">Mechanical</option>
                                                    <option value="Quartz">Quartz</option>
                                                    <option value="Automatic">Automatic</option>
                                                </select>
                                                @error('movement')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="dialColor" class="form-label fw-bold">Dial Color:</label>
                                                <select class="form-select" id="dialColor" wire:model="dialColor">
                                                    <option value="">Select Dial Color</option>
                                                    @foreach ($watchDialColors as $dialColor)
                                                        <option value="{{ $dialColor->dial_color_name }}">
                                                            {{ $dialColor->dial_color_name }}
                                                            @if (isset($dialColor->dial_color_code))
                                                                ({{ $dialColor->dial_color_code }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('dialColor')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="strapColor" class="form-label fw-bold">Strap
                                                    Color:</label>
                                                <select class="form-select" id="strapColor" wire:model="strapColor">
                                                    <option value="">Select Strap Color</option>
                                                    @foreach ($watchStrapColors as $strapColor)
                                                        <option value="{{ $strapColor->strap_color_name }}">
                                                            {{ $strapColor->strap_color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('strapColor')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="strapMaterial" class="form-label fw-bold">Strap
                                                    Material:</label>
                                                <select class="form-select" id="strapMaterial"
                                                    wire:model="strapMaterial">
                                                    <option value="">Select Strap Material</option>
                                                    @foreach ($watchStrapMaterials as $material)
                                                        <option value="{{ $material->strap_material_name }}">
                                                            {{ $material->strap_material_name }}
                                                            @if (isset($material->material_quality))
                                                                ({{ $material->material_quality }} Quality)
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('strapMaterial')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="caseDiameter" class="form-label fw-bold">Case Diameter
                                                    (mm):</label>
                                                <input type="number" step="0.1" class="form-control"
                                                    id="caseDiameter" wire:model="caseDiameter">
                                                @error('caseDiameter')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="caseThickness" class="form-label fw-bold">Case Thickness
                                                    (mm):</label>
                                                <input type="number" step="0.1" class="form-control"
                                                    id="caseThickness" wire:model="caseThickness">
                                                @error('caseThickness')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="glassType" class="form-label fw-bold">Glass Type:</label>
                                                <select class="form-select" id="glassType" wire:model="glassType">
                                                    <option value="">Select Glass Type</option>
                                                    @foreach ($watchGlassTypes as $glass)
                                                        <option value="{{ $glass->glass_type_name }}">
                                                            {{ $glass->glass_type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('glassType')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="waterResistance" class="form-label fw-bold">Water
                                                    Resistance:</label>
                                                <input type="text" class="form-control" id="waterResistance"
                                                    wire:model="waterResistance">
                                                @error('waterResistance')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="features" class="form-label fw-bold">Features:</label>
                                                <input type="text" class="form-control" id="features"
                                                    wire:model="features">
                                                @error('features')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Information Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Product Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="image" class="form-label fw-bold">Watch Image:</label>
                                                <input type="file" class="form-control" id="image"
                                                    wire:model="image" accept="image/*">
                                                <div wire:loading wire:target="image">Uploading...</div>
                                                @if ($image && is_object($image))
                                                    <img src="{{ $image->temporaryUrl() }}"
                                                        class="mt-2 img-thumbnail" style="height: 100px">
                                                @endif
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="warranty" class="form-label fw-bold">Warranty:</label>
                                                <input type="text" class="form-control" id="warranty"
                                                    wire:model="warranty">
                                                @error('warranty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="barcode" class="form-label fw-bold">Barcode:</label>
                                                <input type="text" class="form-control" id="barcode"
                                                    wire:model="barcode">
                                                @error('barcode')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="description"
                                                    class="form-label fw-bold">Description:</label>
                                                <textarea class="form-control" id="description" rows="3" wire:model="description"></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier Information Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Supplier Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="supplier" class="form-label fw-bold">Supplier:</label>
                                                <select class="form-select" id="supplier" wire:model="supplier">
                                                    <option value="">Select Supplier</option>
                                                    @foreach ($watchSuppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">
                                                            {{ $supplier->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('supplier')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="supplierPrice" class="form-label fw-bold">Supplier
                                                    Price:</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    id="supplierPrice" wire:model="supplierPrice">
                                                @error('supplierPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing and Inventory Card -->
                            <div class="card mb-4 shadow border border-primary">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h5 class="card-title mb-0 text-primary">Pricing and Inventory</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="sellingPrice" class="form-label fw-bold">Selling
                                                    Price:</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    id="sellingPrice" wire:model="sellingPrice">
                                                @error('sellingPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="discountPrice" class="form-label fw-bold">Discount
                                                    Price:</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    id="discountPrice" wire:model="discountPrice">
                                                @error('discountPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="shopStock" class="form-label fw-bold">Shop Stock:</label>
                                                <input type="number" class="form-control" id="shopStock"
                                                    wire:model="shopStock">
                                                @error('shopStock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="storeStock" class="form-label fw-bold">Store
                                                    Stock:</label>
                                                <input type="number" class="form-control" id="storeStock"
                                                    wire:model="storeStock">
                                                @error('storeStock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="damageStock" class="form-label fw-bold">Damage
                                                    Stock:</label>
                                                <input type="number" class="form-control" id="damageStock"
                                                    wire:model="damageStock">
                                                @error('damageStock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="status" class="form-label fw-bold">Status:</label>
                                                <select class="form-select" id="status" wire:model="status">
                                                    <option value="">Select Status</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                    <option value="Discontinued">Discontinued</option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="location" class="form-label fw-bold">Store
                                                    Location:</label>
                                                <input type="text" class="form-control" id="location"
                                                    wire:model="location">
                                                @error('location')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" wire:click="saveWatch">Save Watch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barcode Scanner Content -->
        <div id="barcode" class="tab-content">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Point your camera at a barcode to scan it. The product information
                will be automatically filled if it exists in your inventory.
            </div>

            <div id="scanner-container">
                <div id="scanner">
                    <div id="scanner-placeholder">
                        <i class="bi bi-upc-scan" style="font-size: 3rem;"></i>
                        <p>Scanner inactive</p>
                    </div>
                </div>
                <div id="scanner-controls">
                    <button id="start-scanner-btn" class="btn btn-primary">
                        <i class="bi bi-camera"></i> Start Scanner
                    </button>
                    <button id="stop-scanner-btn" class="btn btn-secondary" disabled>
                        <i class="bi bi-camera-off"></i> Stop Scanner
                    </button>
                </div>
                <div class="mb-3">
                    <label for="scanned-barcode" class="form-label">Scanned Barcode</label>
                    <input type="text" class="form-control" id="scanned-barcode" readonly>
                </div>
                <button id="use-barcode-btn" class="btn btn-success" disabled>
                    <i class="bi bi-check-circle"></i> Use This Barcode
                </button>
            </div>
        </div>

        <!-- Reports Content -->
        <div id="reports" class="tab-content">
            <div class="alert alert-info">
                This is the Reports content. Here you can generate inventory reports.
            </div>
        </div>

        <!-- View Watch Modal -->
        <div wire:ignore.self class="modal fade" id="viewWatchModal" tabindex="-1"
            aria-labelledby="viewWatchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h1 class="modal-title fs-5 text-white" id="viewWatchModalLabel">Watch Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    @if ($watchDetails)
                        <div class="modal-body p-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <!-- Image Column -->
                                        <div class="col-md-4 border-end">
                                            <div class="position-relative h-100">
                                                @if ($watchDetails->image)
                                                    <img src="{{ asset('public/storage/' . $watchDetails->image) }}"
                                                        alt="{{ $watchDetails->name }}"
                                                        class="img-fluid rounded-start h-100 w-100 object-fit-cover">
                                                @else
                                                    <div
                                                        class="bg-light d-flex align-items-center justify-content-center h-100">
                                                        <i class="bi bi-watch text-muted"
                                                            style="font-size: 5rem;"></i>
                                                        <p class="text-muted">No image available</p>
                                                    </div>
                                                @endif

                                                <!-- Status badges in corner -->
                                                <div
                                                    class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-2">
                                                    <span
                                                        class="badge bg-{{ $watchDetails->status == 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($watchDetails->status) }}
                                                    </span>

                                                    <!-- Stock Status Badge -->
                                                    @if ($watchDetails->available_stock > 0)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle-fill"></i> In Stock
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle-fill"></i> Out of Stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Main Details Column -->
                                        <div class="col-md-8">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h3 class="fw-bold mb-0 text-primary">{{ $watchDetails->name }}
                                                    </h3>
                                                </div>

                                                <!-- Display code prominently -->
                                                <div class="mb-3">
                                                    <span class="badge bg-dark p-2 fs-6">Code:
                                                        {{ $watchDetails->code }}</span>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-1">Brand</p>
                                                        <h5 class="fw-bold text-primary">{{ $watchDetails->brand }}
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-1">Model</p>
                                                        <h5 class="text-primary">{{ $watchDetails->model }}</h5>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-1">Category</p>
                                                        <h5 class="text-primary">{{ $watchDetails->category }}</h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-1">Gender</p>
                                                        <h5 class="text-primary">{{ ucfirst($watchDetails->gender) }}
                                                        </h5>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="text-muted mb-1">Description</p>
                                                    <p>{{ $watchDetails->description }}</p>
                                                </div>

                                                <!-- Pricing with attractive discount display -->
                                                <div class="card bg-light p-3 mb-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5 class="text-danger fw-bold mb-0">
                                                                Rs.
                                                                {{ number_format($watchDetails->selling_price, 2) }}
                                                            </h5>
                                                            @if ($watchDetails->available_stock > 0)
                                                                <small class="text-success">
                                                                    <i class="bi bi-check-circle-fill"></i>
                                                                    {{ $watchDetails->available_stock }} units
                                                                    available
                                                                </small>
                                                            @else
                                                                <small class="text-danger fw-bold">
                                                                    <i class="bi bi-exclamation-triangle-fill"></i> OUT
                                                                    OF STOCK
                                                                </small>
                                                            @endif
                                                        </div>
                                                        @if ($watchDetails->discount_price > 0)
                                                            <div class="position-relative">
                                                                <div
                                                                    class="position-absolute top-0 start-50 translate-middle">
                                                                    <span
                                                                        class="badge bg-danger p-2 rounded-pill">SPECIAL
                                                                        OFFER</span>
                                                                </div>
                                                                <div class="border border-success rounded-3 p-2 text-center mt-3"
                                                                    style="background-color: rgba(25, 135, 84, 0.1);">
                                                                    <span class="text-success fw-bold fs-5">
                                                                        SAVE Rs.
                                                                        {{ number_format($watchDetails->discount_price, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Specifications using Accordion instead of Tabs -->
                            <div class="accordion mt-4" id="watchDetailsAccordion">
                                <!-- Specifications Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#specs-collapse" aria-expanded="true"
                                            aria-controls="specs-collapse">
                                            <i class="bi bi-info-circle me-2"></i> Specifications
                                        </button>
                                    </h2>
                                    <div id="specs-collapse" class="accordion-collapse collapse show"
                                        data-bs-parent="#watchDetailsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-muted" width="40%">Color</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->color }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Made By</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->made_by }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Movement</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->movement }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Type</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Dial Color</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->dial_color }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Water Resistance</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->water_resistance }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-muted" width="40%">Case Diameter
                                                                </th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->case_diameter_mm }} mm</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Case Thickness</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->case_thickness_mm }} mm</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Glass Type</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->glass_type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Strap Material</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->strap_material }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Strap Color</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->strap_color }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Features</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->features }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#inventory-collapse"
                                            aria-expanded="false" aria-controls="inventory-collapse">
                                            <i class="bi bi-box-seam me-2"></i> Inventory
                                        </button>
                                    </h2>
                                    <div id="inventory-collapse" class="accordion-collapse collapse"
                                        data-bs-parent="#watchDetailsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-primary">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Shop Stock</p>
                                                                <h4 class="card-title text-primary">
                                                                    {{ $watchDetails->shop_stock }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-primary">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Store Stock</p>
                                                                <h4 class="card-title text-primary">
                                                                    {{ $watchDetails->store_stock }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-danger">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Damage Stock</p>
                                                                <h4 class="card-title text-danger">
                                                                    {{ $watchDetails->damage_stock }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        class="card mb-3 {{ $watchDetails->available_stock > 0 ? 'border-success' : 'border-danger' }}">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Available Stock</p>
                                                                <h4
                                                                    class="card-title {{ $watchDetails->available_stock > 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $watchDetails->available_stock }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-dark">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Total Stock</p>
                                                                <h4 class="card-title">
                                                                    {{ $watchDetails->total_stock }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card mb-3 border-info">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Store Location</p>
                                                                <h5 class="card-title text-info">
                                                                    {{ $watchDetails->location }}</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplier Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#supplier-collapse"
                                            aria-expanded="false" aria-controls="supplier-collapse">
                                            <i class="bi bi-truck me-2"></i> Supplier Details
                                        </button>
                                    </h2>
                                    <div id="supplier-collapse" class="accordion-collapse collapse"
                                        data-bs-parent="#watchDetailsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="fw-bold mb-3">Supplier Information</h5>
                                                    <table class="table table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-muted" width="40%">Supplier</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->supplier_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Email</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->email ?: 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Phone</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->contact ?: 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Barcode</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->barcode }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Warranty</th>
                                                                <td class="text-primary fw-medium">
                                                                    {{ $watchDetails->warranty }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-md-6">
                                                    <h5 class="fw-bold mb-3">Pricing Information</h5>
                                                    <div class="card mb-3 border-secondary">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Supplier Price</p>
                                                                <h5 class="card-title">Rs.
                                                                    {{ number_format($watchDetails->supplier_price, 2) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card mb-3 border-primary">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="card-text fw-bold">Selling Price</p>
                                                                <h5 class="card-title text-primary">Rs.
                                                                    {{ number_format($watchDetails->selling_price, 2) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($watchDetails->discount_price > 0)
                                                        <div class="card border-success bg-success bg-opacity-10">
                                                            <div
                                                                class="card-header bg-success bg-opacity-25 border-success">
                                                                <h6 class="text-success mb-0 fw-bold">SPECIAL DISCOUNT
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <p class="card-text fw-bold text-success">Discount
                                                                        Amount</p>
                                                                    <h5 class="card-title text-success">
                                                                        Rs.
                                                                        {{ number_format($watchDetails->discount_price, 2) }}
                                                                    </h5>
                                                                </div>
                                                                <div class="progress mt-2" style="height: 10px;">
                                                                    <div class="progress-bar bg-success"
                                                                        role="progressbar"
                                                                        style="width: {{ min(($watchDetails->discount_price / $watchDetails->selling_price) * 100, 100) }}%"
                                                                        aria-valuenow="{{ ($watchDetails->discount_price / $watchDetails->selling_price) * 100 }}"
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <div class="d-flex justify-content-between mt-1">
                                                                    <small class="text-muted">0%</small>
                                                                    <small class="text-muted">Save
                                                                        {{ number_format(($watchDetails->discount_price / $watchDetails->selling_price) * 100, 0) }}%</small>
                                                                    <small class="text-muted">100%</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{--                         <button type="button" class="btn btn-primary" wire:click="editWatch({{ $watchDetails->id }})">
                            <i class="bi bi-pencil-square"></i> Edit Watch
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Edit Watch Modal --> --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editId ?? 'new' }}" class="modal fade hidden"
        id="editWatchModal" tabindex="-1" aria-labelledby="editWatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-white" id="editWatchModalLabel">Edit Watch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light p-4">
                    {{-- Basic Information --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Basic Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editCode" class="form-label fw-bold">Code:</label>
                                        <input type="text" class="form-control" id="editCode"
                                            wire:model="editCode" readonly>
                                        @error('editCode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editName" class="form-label fw-bold">Name:</label>
                                        <input type="text" class="form-control" id="editName"
                                            wire:model="editName">
                                        @error('editName')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editBrand" class="form-label fw-bold">Brand:</label>
                                        <select class="form-select" id="editBrand" wire:model="editBrand">
                                            @foreach ($watchBarnds as $watchBrand)
                                                <option value="{{ $watchBrand->brand_name }}">
                                                    {{ $watchBrand->brand_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editBrand')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editModel" class="form-label fw-bold">Model:</label>
                                        <input type="text" class="form-control" id="editModel"
                                            wire:model="editModel">
                                        @error('editModel')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editColor" class="form-label fw-bold">Color:</label>
                                        <select class="form-select" id="editColor" wire:model="editColor">
                                            <option value="">Select Color</option>
                                            @foreach ($watchColors as $watchColor)
                                                <option value="{{ $watchColor->name }}">
                                                    {{ $watchColor->name }} ({{ $watchColor->hex_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editMadeBy" class="form-label fw-bold">Made By:</label>
                                        <select class="form-select" id="editMadeBy" wire:model="editMadeBy">
                                            <option value="">Select Country</option>
                                            @foreach ($watchMadeins as $madein)
                                                <option value="{{ $madein->country_name }}">
                                                    {{ $madein->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editMadeBy')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Classification --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Classification</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editCategory" class="form-label fw-bold">Category:</label>
                                        <select class="form-select" id="editCategory" wire:model="editCategory">
                                            <option value="">Select Category</option>
                                            @foreach ($watchCategories as $watchCategory)
                                                <option value="{{ $watchCategory->category_name }}">
                                                    {{ $watchCategory->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editCategory')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editGender" class="form-label fw-bold">Gender:</label>
                                        <select class="form-select" id="editGender" wire:model="editGender">
                                            <option value="male">Men</option>
                                            <option value="female">Women</option>
                                            <option value="unisex">Unisex</option>
                                        </select>
                                        @error('editGender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editType" class="form-label fw-bold">Type:</label>
                                        <select class="form-select" id="editType" wire:model="editType">
                                            <option value="">Select Type</option>
                                            @foreach ($watchType as $type)
                                                <option value="{{ $type->type_name }}">
                                                    {{ $type->type_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editType')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical Specifications --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Technical Specifications</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editMovement" class="form-label fw-bold">Movement:</label>
                                        <select wire:model="editMovement" id="editMovement" class="form-select">
                                            <option value="">Select Movement</option>
                                            <option value="Mechanical">Mechanical</option>
                                            <option value="Quartz">Quartz</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                        @error('editMovement')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editDialColor" class="form-label fw-bold">Dial Color:</label>
                                        <select class="form-select" id="editDialColor" wire:model="editDialColor">
                                            <option value="">Select Dial Color</option>
                                            @foreach ($watchDialColors as $dialColor)
                                                <option value="{{ $dialColor->dial_color_name }}">
                                                    {{ $dialColor->dial_color_name }}
                                                    @if (isset($dialColor->dial_color_code))
                                                        ({{ $dialColor->dial_color_code }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editDialColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStrapColor" class="form-label fw-bold">Strap
                                            Color:</label>
                                        <select class="form-select" id="editStrapColor" wire:model="editStrapColor">
                                            <option value="">Select Strap Color</option>
                                            @foreach ($watchStrapColors as $strapColor)
                                                <option value="{{ $strapColor->strap_color_name }}">
                                                    {{ $strapColor->strap_color_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editStrapColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStrapMaterial" class="form-label fw-bold">Strap
                                            Material:</label>
                                        <select class="form-select" id="editStrapMaterial"
                                            wire:model="editStrapMaterial">
                                            <option value="">Select Strap Material</option>
                                            @foreach ($watchStrapMaterials as $material)
                                                <option value="{{ $material->strap_material_name }}">
                                                    {{ $material->strap_material_name }}
                                                    @if (isset($material->material_quality))
                                                        ({{ $material->material_quality }} Quality)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editStrapMaterial')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editCaseDiameter" class="form-label fw-bold">Case Diameter
                                            (mm):</label>
                                        <input type="number" step="0.1" class="form-control"
                                            id="editCaseDiameter" wire:model="editCaseDiameter">
                                        @error('editCaseDiameter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editCaseThickness" class="form-label fw-bold">Case Thickness
                                            (mm):</label>
                                        <input type="number" step="0.1" class="form-control"
                                            id="editCaseThickness" wire:model="editCaseThickness">
                                        @error('editCaseThickness')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editGlassType" class="form-label fw-bold">Glass Type:</label>
                                        <select class="form-select" id="editGlassType" wire:model="editGlassType">
                                            <option value="">Select Glass Type</option>
                                            @foreach ($watchGlassTypes as $glass)
                                                <option value="{{ $glass->glass_type_name }}">
                                                    {{ $glass->glass_type_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editGlassType')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editWaterResistance" class="form-label fw-bold">Water
                                            Resistance:</label>
                                        <input type="text" class="form-control" id="editWaterResistance"
                                            wire:model="editWaterResistance">
                                        @error('editWaterResistance')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editFeatures" class="form-label fw-bold">Features:</label>
                                        <input type="text" class="form-control" id="editFeatures"
                                            wire:model="editFeatures">
                                        @error('editFeatures')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Information --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Product Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editImage" class="form-label fw-bold">Image:</label>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="editImage" 
                                                wire:model="editImage" accept="image/*">
                                        </div>
                                        <div wire:loading wire:target="editImage" class="text-primary">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Uploading...
                                        </div>
                                        <div class="mt-2">
                                            @if ($editImage && is_object($editImage))
                                                <div class="mb-2">New image preview:</div>
                                                <img src="{{ $editImage->temporaryUrl() }}" class="img-thumbnail"
                                                    style="height: 100px">
                                            @elseif($existingImage)
                                                <div class="mb-2">Current image:</div>
                                                <img src="{{ asset('storage/' . $existingImage) }}"
                                                    class="img-thumbnail" style="height: 100px">
                                            @endif
                                        </div>
                                        <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max
                                            size:
                                            2MB</small>
                                        @error('editImage')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editWarranty" class="form-label fw-bold">Warranty:</label>
                                        <input type="text" class="form-control" id="editWarranty"
                                            wire:model="editWarranty">
                                        @error('editWarranty')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editBarcode" class="form-label fw-bold">Barcode:</label>
                                        <input type="text" class="form-control" id="editBarcode"
                                            wire:model="editBarcode">
                                        @error('editBarcode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="editDescription" class="form-label fw-bold">Description:</label>
                                        <textarea class="form-control" id="editDescription" rows="3" wire:model="editDescription"></textarea>
                                        @error('editDescription')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Supplier Information --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Supplier Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editSupplier" class="form-label fw-bold">Supplier:</label>
                                        <select class="form-select" id="editSupplier" wire:model="editSupplier">
                                            @foreach ($watchSuppliers as $supplier)
                                                <option value="{{ $supplier->id }}">
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editSupplier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editSupplierPrice" class="form-label fw-bold">Supplier
                                            Price:</label>
                                        <input type="number" step="0.01" class="form-control"
                                            id="editSupplierPrice" wire:model="editSupplierPrice">
                                        @error('editSupplierPrice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing and Inventory --}}
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Pricing and Inventory</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editSellingPrice" class="form-label fw-bold">Selling
                                            Price:</label>
                                        <input type="number" step="0.01" class="form-control"
                                            id="editSellingPrice" wire:model="editSellingPrice">
                                        @error('editSellingPrice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editDiscountPrice" class="form-label fw-bold">Discount
                                            Price:</label>
                                        <input type="number" step="0.01" class="form-control"
                                            id="editDiscountPrice" wire:model="editDiscountPrice">
                                        @error('editDiscountPrice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editShopStock" class="form-label fw-bold">Shop
                                            Stock:</label>
                                        <input type="number" class="form-control" id="editShopStock"
                                            wire:model="editShopStock">
                                        @error('editShopStock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStoreStock" class="form-label fw-bold">Store
                                            Stock:</label>
                                        <input type="number" class="form-control" id="editStoreStock"
                                            wire:model="editStoreStock">
                                        @error('editStoreStock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editDamageStock" class="form-label fw-bold">Damage
                                            Stock:</label>
                                        <input type="number" class="form-control" id="editDamageStock"
                                            wire:model="editDamageStock">
                                        @error('editDamageStock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStatus" class="form-label fw-bold">Status:</label>
                                        <select class="form-select" id="editStatus" wire:model="editStatus">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('editStatus')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="editLocation" class="form-label fw-bold">Store
                                            Location:</label>
                                        <input type="text" class="form-control" id="editLocation"
                                            wire:model="editLocation">
                                        @error('editLocation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateWatch({{$editId}})">Update Watch</button>
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.content-tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show the selected tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');

                    // Stop scanner when switching away from barcode tab
                    if (tabId !== 'barcode' && typeof scannerActive !== 'undefined' &&
                        scannerActive) {
                        stopScanner();
                    }
                });
            });

            // Clean up scanner when leaving page
            window.addEventListener('beforeunload', function() {
                if (typeof scannerActive !== 'undefined' && scannerActive) {
                    Quagga.stop();
                }
            });
        });
    </script>
    <script>
        window.addEventListener('confirm-delete', event => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // call component's function deleteOffer
                    Livewire.dispatch('confirmDelete');
                    Swal.fire({
                        title: "Deleted!",
                        text: "Watch has been deleted successfully.",
                        icon: "success"
                    });
                }
            });
        });
    </script>

    <script>
        window.addEventListener('open-edit-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editWatchModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush
