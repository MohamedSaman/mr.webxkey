<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Supplier List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createSupplier">
                        <i class="bi bi-plus-circle me-1"></i> Create Supplier
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Supplier Name</th>
                            <th class="text-center">Contact Number</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($suppliers->count() > 0)
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $supplier->name ?? '-' }}</td>
                                    <td class="text-center">{{ $supplier->contact ?? '-' }}</td>
                                    <td class="text-center">{{ $supplier->email ?? '-' }}</td>
                                    <td class="text-center">{{ $supplier->address ?? '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editSupplier({{ $supplier->id }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $supplier->id }})">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No suppliers found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
        {{-- Create Suplier Modal --}}
        <div wire:ignore.self class="modal fade" id="createSupplierModal" tabindex="-1"
            aria-labelledby="createSupplierModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createSupplierModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplierName" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" id="supplierName" wire:model="name"
                                    placeholder="Enter supplier name">
                                @error('name')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    wire:model="contactNumber" placeholder="Enter contact number">
                                @error('contactNumber')
                                    <span class="text-danger">* {{ $message }}</span>    
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                    placeholder="Enter email">  
                                @error('email')     
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror  
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" wire:model="address"
                                    placeholder="Enter address">
                                @error('address')                       
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>          
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveSupplier">Add Supplier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Supplier Modal --}}
    <div wire:ignore.self class="modal fade" id="editSupplierModal" tabindex="-1"
        aria-labelledby="editSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSupplierModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editName" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="editName"
                                wire:model="editName" placeholder="Enter supplier name">
                            @error('editName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editContactNumber"
                                wire:model="editContactNumber" placeholder="Enter contact number">
                            @error('editContactNumber')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>   
                    <div class="row">   
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail"
                                wire:model="editEmail" placeholder="Enter email">  
                            @error('editEmail')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror  
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="editAddress"
                                wire:model="editAddress" placeholder="Enter address">
                            @error('editAddress')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>          
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateSupplier({{$editSupplierId}})">Update Supplier</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
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
                    text: "Supplier has been deleted.",
                    icon: "success"
                });
            }
        });
    });
</script>
@endpush
