<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <h4 class="card-title mb-2 mb-sm-0">Supplier List</h4>
                    <div class="card-tools">
                        <button class="btn btn-primary w-100 w-sm-auto" wire:click="createSupplier">
                            <i class="bi bi-plus-circle me-1"></i> Create Supplier
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive"> <!-- Move table-responsive to a wrapper div -->
                    <table class="table table-bordered table-hover">
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
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click="editSupplier({{ $supplier->id }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="confirmDelete({{ $supplier->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
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
                </div>
                <div class="d-flex justify-content-center">
                    {{ $suppliers->links('livewire.custom-pagination') }}
                </div>
            </div>
        </div>
        {{-- Create Supplier Modal --}}
        <div wire:ignore.self wire:key="create-supplier-modal" class="modal fade" id="createSupplierModal" tabindex="-1"
            aria-labelledby="createSupplierModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createSupplierModalLabel">Add New Supplier</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3"> <!-- Use g-3 for better spacing -->
                            <div class="col-12 col-md-6"> <!-- Full width on mobile -->
                                <label for="supplierName" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" id="supplierName" wire:model.defer="name"
                                    placeholder="Enter supplier name">
                                @error('name')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6"> <!-- Full width on mobile -->
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    wire:model.defer="contactNumber" placeholder="Enter contact number">
                                @error('contactNumber')
                                    <span class="text-danger">* {{ $message }}</span>    
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model.defer="email"
                                    placeholder="Enter email">  
                                @error('email')     
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror  
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" wire:model.defer="address"
                                    placeholder="Enter address">
                                @error('address')                       
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>          
                        </div>
                    </div>
                    <div class="modal-footer flex-column flex-sm-row">
                        <button type="button" class="btn btn-secondary w-100 w-sm-auto mb-2 mb-sm-0" 
                            wire:click="resetForm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary w-100 w-sm-auto" 
                            wire:click="saveSupplier">Add Supplier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Supplier Modal --}}
    <div wire:ignore.self wire:key="edit-supplier-modal-{{ $editSupplierId ?? 'new' }}" class="modal fade" id="editSupplierModal" tabindex="-1"
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let createModal = document.getElementById('createSupplierModal');
        if (createModal) {
            createModal.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('resetForm');
            });
        }
    });

    window.addEventListener('create-supplier', event => {
        @this.resetForm();
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('createSupplierModal'));
            modal.show();
        }, 300);
    });
</script>
<script>

        window.addEventListener('edit-supplier', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
                modal.show();
            }, 300); // 500ms delay before showing the modal
        });
 
</script>
@endpush
@push('styles')
<style>
    /* Make modals more mobile-friendly */
    @media (max-width: 575.98px) {
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
        
        .modal-header {
            padding: 0.75rem 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-footer {
            padding: 0.75rem 1rem;
            justify-content: center;
        }
    }
    
    /* Improve table display on smaller screens */
    @media (max-width: 767.98px) {
        .table {
            font-size: 0.85rem;
        }
        
        .table td, .table th {
            padding: 0.5rem 0.25rem;
        }
        
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    }
    
    /* Focus on most important content for very small screens */
    @media (max-width: 400px) {
        /* Hide less important columns on tiny screens */
        .table td:nth-child(4), .table th:nth-child(4), /* Email column */
        .table td:nth-child(5), .table th:nth-child(5) { /* Address column */
            display: none;
        }
    }
</style>
@endpush
