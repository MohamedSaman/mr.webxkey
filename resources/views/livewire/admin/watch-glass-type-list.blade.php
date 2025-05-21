<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Glass Type List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createGlassType">
                        <i class="bi bi-plus-circle me-1"></i> Create Glass Type
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Glass Type Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:key="glass-types-{{now()}}">
                        @if ($glassTypes->count() > 0)
                            @foreach ($glassTypes as $glassType)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $glassType->glass_type_name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editGlassType({{ $glassType->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $glassType->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No Glass Types found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Glass Type Model --}}
        <div wire:ignore.self wire:key="create-modal" class="modal fade" id="createGlassTypeModal" tabindex="-1"
            aria-labelledby="createGlassTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createGlassTypeModalLabel">Add glassType</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="glassTypeName" class="form-label">Glass Type Name</label>
                                <input type="text" class="form-control" id="glassTypeName"
                                    wire:model="glassTypeName">
                                @error('glassTypeName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveGlassType">Add Glass
                            Type</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Glass Type Model --}}
    </div>
    {{-- Edit Glass Type Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editGlassTypeId ?? 'new' }}" class="modal fade"
        id="editGlassTypeModal" tabindex="-1" aria-labelledby="editGlassTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editGlassTypeModalLabel">Add Glass Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editGlassTypeName" class="form-label">Glass Type Name</label>
                            <input type="text" class="form-control" id="editGlassTypeName"
                                wire:model="editGlassTypeName">
                            @error('editGlassTypeName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="updateGlassType({{ $editGlassTypeId }})">Update glassType</button>
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
                        text: "Glass Type has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('edit-glass-type', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editGlassTypeModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
     <script>
        window.addEventListener('create-glass-type', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createGlassTypeModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush
