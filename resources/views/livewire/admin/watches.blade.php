<div>
    <div class="container-fluid p-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watches</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createWatch">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($watches->count() > 0)
                            @foreach ($watches as $watch)
                                <tr>
                                    <td>{{ $watch->id }}</td>
                                    <td>{{ $watch->name }}</td>
                                    <td>{{ $watch->price }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewWatch({{ $watch->id }})">
                                                <i class="bi bi-eye d-md-none"></i>
                                                <span class="d-none d-md-inline">View</span>
                                            </button>
                                            <button class="btn btn-sm btn-warning"
                                                wire:click="editWatch({{ $watch->id }})">
                                                <i class="bi bi-pencil d-md-none"></i>
                                                <span class="d-none d-md-inline">Edit</span>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="deleteWatch({{ $watch->id }})">
                                                <i class="bi bi-trash d-md-none"></i>
                                                <span class="d-none d-md-inline">Delete</span>
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
            </div>
        </div>
    </div>
    <!-- Create Watch Modal -->
    <div wire:ignore.self class="modal fade" id="createWatchModal" tabindex="-1"
        aria-labelledby="createWatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-white" id="createWatchModalLabel">Create New Watch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <input type="text" class="form-control" id="name" wire:model="name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="model" class="form-label fw-bold">Model:</label>
                                        <input type="text" class="form-control" id="model" wire:model="model">
                                        @error('model')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="brand" class="form-label fw-bold">Brand:</label>
                                        <input type="text" class="form-control" id="brand" wire:model="brand">
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
                                                <option value="{{ $watchColor->id }}">
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
                                        <label for="made_by" class="form-label fw-bold">Made By:</label>
                                        <input type="text" class="form-control" id="made_by"
                                            wire:model="made_by">
                                        @error('made_by')
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
                                        <input type="text" class="form-control" id="category"
                                            wire:model="category">
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
                                            <option value="Men">Men</option>
                                            <option value="Women">Women</option>
                                            <option value="Unisex">Unisex</option>
                                        </select>
                                        @error('gender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="type" class="form-label fw-bold">Type:</label>
                                        <input type="text" class="form-control" id="type" wire:model="type">
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
                                        <input type="text" class="form-control" id="movement"
                                            wire:model="movement">
                                        @error('movement')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="dialColor" class="form-label fw-bold">Dial Color:</label>
                                        <input type="text" class="form-control" id="dialColor"
                                            wire:model="dialColor">
                                        @error('dialColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="strapColor" class="form-label fw-bold">Strap Color:</label>
                                        <input type="text" class="form-control" id="strapColor"
                                            wire:model="strapColor">
                                        @error('strapColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="strapMaterial" class="form-label fw-bold">Strap Material:</label>
                                        <input type="text" class="form-control" id="strapMaterial"
                                            wire:model="strapMaterial">
                                        @error('strapMaterial')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="caseDiameter" class="form-label fw-bold">Case Diameter
                                            (mm):</label>
                                        <input type="number" step="0.1" class="form-control" id="caseDiameter"
                                            wire:model="caseDiameter">
                                        @error('caseDiameter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="caseThickness" class="form-label fw-bold">Case Thickness
                                            (mm):</label>
                                        <input type="number" step="0.1" class="form-control" id="caseThickness"
                                            wire:model="caseThickness">
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
                                        <input type="text" class="form-control" id="glassType"
                                            wire:model="glassType">
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
                                        <label for="image" class="form-label fw-bold">Image:</label>
                                        <input type="file" class="form-control" id="image"
                                            wire:model="image">
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
                                        <label for="description" class="form-label fw-bold">Description:</label>
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
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="supplier" class="form-label fw-bold">Supplier:</label>
                                        <input type="text" class="form-control" id="supplier"
                                            wire:model="supplier">
                                        @error('supplier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="supplierContact" class="form-label fw-bold">Supplier
                                            Contact:</label>
                                        <input type="text" class="form-control" id="supplierContact"
                                            wire:model="supplierContact">
                                        @error('supplierContact')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="supplierPrice" class="form-label fw-bold">Supplier Price:</label>
                                        <input type="number" step="0.01" class="form-control" id="supplierPrice"
                                            wire:model="supplierPrice">
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
                                        <label for="sellingPrice" class="form-label fw-bold">Selling Price:</label>
                                        <input type="number" step="0.01" class="form-control" id="sellingPrice"
                                            wire:model="sellingPrice">
                                        @error('sellingPrice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discountPrice" class="form-label fw-bold">Discount Price:</label>
                                        <input type="number" step="0.01" class="form-control" id="discountPrice"
                                            wire:model="discountPrice">
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
                                        <label for="storeStock" class="form-label fw-bold">Store Stock:</label>
                                        <input type="number" class="form-control" id="storeStock"
                                            wire:model="storeStock">
                                        @error('storeStock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="damageStock" class="form-label fw-bold">Damage Stock:</label>
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
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
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
                                        <label for="location" class="form-label fw-bold">Location:</label>
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
    <!-- Edit Watch Modal -->
    <div wire:ignore.self class="modal fade" id="editWatchModal" tabindex="-1"
        aria-labelledby="editWatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5 text-white" id="editWatchModalLabel">Create New Watch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <label for="editCode" class="form-label fw-bold">Code:</label>
                                        <input type="text" class="form-control" id="editCode"
                                            wire:model="editCode">
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
                                        <input type="text" class="form-control" id="editBrand"
                                            wire:model="editBrand">
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
                                        <input type="text" class="form-control" id="editColor"
                                            wire:model="editColor">
                                        @error('editColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editMadeBy" class="form-label fw-bold">Made By:</label>
                                        <input type="text" class="form-control" id="editMadeBy"
                                            wire:model="editMadeBy">
                                        @error('editMadeBy')
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
                                        <label for="editCategory" class="form-label fw-bold">Category:</label>
                                        <input type="text" class="form-control" id="editCategory"
                                            wire:model="editCategory">
                                        @error('editCategory')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editGender" class="form-label fw-bold">Gender:</label>
                                        <select class="form-select" id="editGender" wire:model="editGender">
                                            <option value="Men">Men</option>
                                            <option value="Women">Women</option>
                                            <option value="Unisex">Unisex</option>
                                        </select>
                                        @error('editGender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editType" class="form-label fw-bold">Type:</label>
                                        <input type="text" class="form-control" id="editType"
                                            wire:model="editType">
                                        @error('editType')
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
                                        <label for="editMovement" class="form-label fw-bold">Movement:</label>
                                        <input type="text" class="form-control" id="editMovement"
                                            wire:model="editMovement">
                                        @error('editMovement')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editDialColor" class="form-label fw-bold">Dial Color:</label>
                                        <input type="text" class="form-control" id="editDialColor"
                                            wire:model="editDialColor">
                                        @error('editDialColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editStrapColor" class="form-label fw-bold">Strap Color:</label>
                                        <input type="text" class="form-control" id="editStrapColor"
                                            wire:model="editStrapColor">
                                        @error('editStrapColor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="strap_material" class="form-label fw-bold">Strap Material:</label>
                                        <input type="text" class="form-control" id="strap_material"
                                            wire:model="strap_material">
                                        @error('strap_material')
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
                                        <input type="text" class="form-control" id="editGlassType"
                                            wire:model="editGlassType">
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

                    <!-- Product Information Card -->
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Product Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editImage" class="form-label fw-bold">Image:</label>
                                        <input type="file" class="form-control" id="editImage"
                                            wire:model="editImage">
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

                    <!-- Supplier Information Card -->
                    <div class="card mb-4 shadow border border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="card-title mb-0 text-primary">Supplier Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editSupplier" class="form-label fw-bold">Supplier:</label>
                                        <input type="text" class="form-control" id="editSupplier"
                                            wire:model="editSupplier">
                                        @error('editSupplier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editSupplierContact" class="form-label fw-bold">Supplier
                                            Contact:</label>
                                        <input type="text" class="form-control" id="editSupplierContact"
                                            wire:model="editSupplierContact">
                                        @error('editSupplierContact')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
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

                    <!-- Pricing and Inventory Card -->
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
                                        <label for="editShopStock" class="form-label fw-bold">Shop Stock:</label>
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
                                        <label for="editStoreStock" class="form-label fw-bold">Store Stock:</label>
                                        <input type="number" class="form-control" id="editStoreStock"
                                            wire:model="editStoreStock">
                                        @error('editStoreStock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editDamageStock" class="form-label fw-bold">Damage Stock:</label>
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
                                        <label for="editLocation" class="form-label fw-bold">Location:</label>
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
                    <button type="button" class="btn btn-primary" wire:click="saveWatch">Save Watch</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="editWatchModal" tabindex="-1" aria-labelledby="editWatchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editWatchModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
