<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Strap Color List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createStrapColor">
                        <i class="bi bi-plus-circle me-1"></i> Create Strap Color
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Strap Color Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:key="strap-color-list-{{now()}}">
                        @if ($strapColors->count() > 0)
                            @foreach ($strapColors as $strapColor)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $strapColor->strap_color_name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editStrapColor({{ $strapColor->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $strapColor->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Strap Colors found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Strap Color Model --}}
        <div wire:ignore.self wire:key="create-modal" class="modal fade" id="createStrapColorModal" tabindex="-1"
            aria-labelledby="createStrapColorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createStrapColorModalLabel">Add Strap Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="strapColorName" class="form-label">Strap Color Name</label>
                                <input type="text" class="form-control" id="strapColorName"
                                    wire:model="strapColorName">
                                @error('strapColorName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveStrapColor">Add Strap
                            Color</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Strap Color Model --}}
    </div>
    {{-- Edit Strap Color Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editStrapColorId ?? 'new' }}" class="modal fade"
        id="editStrapColorModal" tabindex="-1" aria-labelledby="editStrapColorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editStrapColorModalLabel">Edit Strap Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="mb-3">
                            <label for="editStrapColorName" class="form-label">Strap Color Name</label>
                            <input type="text" class="form-control" id="editStrapColorName"
                                wire:model="editStrapColorName">
                            @error('editStrapColorName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="updateStrapColor({{ $editStrapColorId }})">Update StrapColor</button>
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
                        text: "Strap Color has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('edit-strap-color', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editStrapColorModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
    <script>
        window.addEventListener('create-strap-color', event => {
            @this.resetForm();
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createStrapColorModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush
