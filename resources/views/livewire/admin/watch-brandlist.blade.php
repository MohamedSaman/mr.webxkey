<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Brand List</h4>
                <div class="card-tools">
                    <button wire:click="createBrand" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Add New Brand
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:key="brand-list-{{ now() }}">
                        @if ($brands->count() > 0)
                            @foreach ($brands as $brand)
                                <tr wire:key="brand-{{ $brand->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->brand_name }}</td>
                                    <td>
                                        <button wire:click="editBrand({{ $brand->id }})" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $brand->id }})" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">No brands found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Create Brand Modal -->
        <div wire:ignore.self wire:key="create-brand-modal" class="modal fade" id="createBrandModal" tabindex="-1" 
            aria-labelledby="createBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createBrandModalLabel">Add New Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="form-group mb-3">
                            <label for="brandName">Brand Name</label>
                            <input type="text" wire:model.lazy="brandName" class="form-control" placeholder="Enter brand name">
                            @error('brandName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click="saveBrand" class="btn btn-primary">Save Brand</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Create Brand Modal -->
    </div>
    
    <!-- Edit Brand Modal -->
    <div wire:ignore.self wire:key="edit-modal-{{ $editBrandId ?? 'new' }}" class="modal fade" id="editBrandModal" 
        tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="form-group mb-3">
                        <label for="editBrandName">Brand Name</label>
                        <input type="text" wire:model.lazy="editBrandName" class="form-control" placeholder="Enter brand name">
                        @error('editBrandName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button wire:click="updateBrand({{ $editBrandId }})" class="btn btn-primary">Update Brand</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Brand Modal -->
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
                    @this.dispatch('confirmDelete');
                }
            });
        });

        window.addEventListener('edit-brand', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editBrandModal'));
                modal.show();
            }, 300);
        });

        window.addEventListener('create-brand-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createBrandModal'));
                modal.show();
            }, 200);
        });

        document.addEventListener('livewire:initialized', function() {
            // Re-initialize event handlers after Livewire updates
        });

        window.addEventListener('brands-updated', function() {
            // This will be triggered when brands are updated
            // You might need additional code here if needed
        });
    </script>
@endpush
