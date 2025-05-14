<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Strap Material List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createStrapMaterial">
                        <i class="bi bi-plus-circle me-1"></i> Create Strap Material
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Strap Material Name</th>
                            <th class="text-center">Strap Material Quality</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($strapMaterials->count() > 0)
                            @foreach ($strapMaterials as $strapMaterial)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $strapMaterial->strap_material_name ?? '-' }}</td>
                                    <td class="text-center">{{ $strapMaterial->material_quality  ?? '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editStrapMaterial({{ $strapMaterial->id }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $strapMaterial->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Strap Materials found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Strap Material Model --}}
        <div wire:ignore.self class="modal fade" id="createStrapMaterialModal" tabindex="-1" aria-labelledby="createStrapMaterialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createStrapMaterialModalLabel">Add Strap Material</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">
                            <div class="mb-3">
                                <label for="strapMaterialName" class="form-label">Strap Material Name</label>
                                <input type="text" class="form-control" id="strapMaterialName" wire:model="strapMaterialName">
                                @error('strapMaterialName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="materialQuality" class="form-label">Strap Material Quality</label>
                                <select class="form-select" id="materialQuality" wire:model="materialQuality">
                                    <option value="">Select Quality</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                    <option value="Branded">Branded</option>
                                    <option value="Original">Original</option>
                                    <option value="Local">Local</option>
                                    <option value="Premium">Premium</option>
                                </select>
                                @error('materialQuality')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveStrapMaterial">Add Strap Material</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Strap Material Model --}}
    </div>
    {{-- Edit Strap Material Model --}}
    <div wire:ignore.self  wire:key="edit-modal-{{ $editStrapMaterialId ?? 'new' }}" class="modal fade" id="editStrapMaterialModal" tabindex="-1" aria-labelledby="editStrapMaterialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editStrapMaterialModalLabel">Edit Strap Material</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="mb-3">
                            <label for="editStrapMaterialName" class="form-label">Strap Material Name</label>
                            <input type="text" class="form-control" id="editStrapMaterialName" wire:model="editStrapMaterialName">
                            @error('editStrapMaterialName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="editMaterialQuality" class="form-label">Strap Material Quality</label>
                            <select class="form-select" id="editMaterialQuality" wire:model="editMaterialQuality">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                                <option value="Branded">Branded</option>
                                <option value="Original">Original</option>
                                <option value="Local">Local</option>
                                <option value="Premium">Premium</option>
                            </select>
                            @error('editMaterialQuality')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateStrapMaterial({{$editStrapMaterialId}})">Update Strap Material</button>
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
        window.addEventListener('edit-strap-material', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editStrapMaterialModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush