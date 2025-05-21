<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Type List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createType">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Type
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Type Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:key="type-list-{{now()}}">
                        @if ($types->count() > 0)
                            @foreach ($types as $type)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $type->type_name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editType({{ $type->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $type->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Types found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Type Model --}}
        <div wire:ignore.self wire:key="create-modal" class="modal fade" id="createTypeModal" tabindex="-1"
            aria-labelledby="createTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createTypeModalLabel">Add Type</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="typeName" class="form-label">Type Name</label>
                                <input type="text" class="form-control" id="typeName" wire:model="typeName">
                                @error('typeName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveType">Add Type</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Type Model --}}
    </div>
    {{-- Edit Type Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editTypeId ?? 'new' }}" class="modal fade" id="editTypeModal"
        tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editTypeModalLabel">Edit Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editTypeName" class="form-label">Type Name</label>
                            <input type="text" class="form-control" id="editTypeName" wire:model="editTypeName">
                            @error('editTypeName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateType({{ $editTypeId }})">Update
                        Type</button>
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
                        text: "Type has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('edit-type-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editTypeModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
    <script>
        window.addEventListener('create-type-modal', event => {
            @this.resetForm();
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createTypeModal'));
                modal.show();
            }, 300); // 500ms delay before showing the modal
        });
    </script>
@endpush
