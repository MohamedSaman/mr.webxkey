<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Brand List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createBrand">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Brand
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Brand Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($brands->count() > 0)
                            @foreach ($brands as $brand)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $brand->brand_name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editBrand({{ $brand->id }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $brand->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Brands found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Brand Model --}}
        <div wire:ignore.self class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createBrandModalLabel">Add Brand</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="brandName" class="form-label">Brand Name</label>
                                <input type="text" class="form-control" id="brandName" wire:model="brandName">
                                @error('brandName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveBrand">Add Brand</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Brand Model --}}
    </div>
    {{-- Edit Brand Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editBrandId ?? 'new' }}"  class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editBrandModalLabel">Edit Brand</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editBrandName" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="editBrandName" wire:model="editBrandName">
                            @error('editBrandName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateBrand({{$editBrandId}})">Update Brand</button>
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
                        text: "Brand has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('edit-brand', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editBrandModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush