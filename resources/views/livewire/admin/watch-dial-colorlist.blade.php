<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light p-2 p-md-3">
                <h4 class="card-title fs-5 fs-md-4 mb-2 mb-md-0">Watch Dial Color List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm btn-md-md" wire:click="createDialColor">
                        <i class="bi bi-plus-circle me-1"></i> <span class="d-none d-sm-inline">Create</span> Watch Color
                    </button>
                </div>
            </div>
            <div class="card-body p-2 p-md-3">
                <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-md-lg">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Color Name</th>
                            <th class="text-center d-none d-md-table-cell">Hex Code</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:key ="dial-color-list-{{ now() }}">
                        @if ($dialColors->count() > 0)
                            @foreach ($dialColors as $dialColor)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-center align-middle">{{ $dialColor->dial_color_name }}</td>
                                    <td class="text-center align-middle d-none d-md-table-cell">{{ $dialColor->dial_color_code }}</td>
                                    <td class="text-center align-middle">
                                        <div class="color-circle"
                                            style="
                                            width: 25px; 
                                            height: 25px; 
                                            border-radius: 50%; 
                                            background-color: {{ $dialColor->dial_color_code }}; 
                                            border: 1px solid #dee2e6;
                                            display: inline-block;
                                            "
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $dialColor->dial_color_code }}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="editDialColor({{ $dialColor->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $dialColor->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No watches Dial Colors found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        
        {{-- Create Dial Color Model --}}
        <div wire:ignore.self wire:key="create-modal" class="modal fade" id="createDialColorModal" tabindex="-1"
            aria-labelledby="createDialColorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createDialColorModalLabel">Add Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="dialColorName" class="form-label">Dial Color Name</label>
                                    <input type="text" class="form-control" id="dialColorName" wire:model="dialColorName"
                                        placeholder="Enter Dial Color Name">
                                    @error('dialColorName')
                                        <span class="text-danger">* {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="dialColorCode" class="form-label">Dial Color Code</label>
                                    <input type="color" class="form-control form-control-color w-100" id="dialColorCode" wire:model="dialColorCode"
                                        placeholder="Select Dial Color">
                                    @error('dialColorCode')
                                        <span class="text-danger">* {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveDialColor">Add Color</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Dial Color Model --}}
        
        {{-- edit Dial Color Model --}}
        <div wire:ignore.self wire:key="edit-modal-{{ $editDialColorId ?? 'new' }}" class="modal fade"
            id="editDialColorModal" tabindex="-1" aria-labelledby="editDialColorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="editDialColorModalLabel">Edit Dial Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="editDialColorName" class="form-label">Dial Color Name</label>
                                    <input type="text" class="form-control" id="editDialColorName"
                                        wire:model="editDialColorName">
                                    @error('editDialColorName')
                                        <span class="text-danger">* {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="editDialColorCode" class="form-label">Dial Color Code</label>
                                    <input type="color" class="form-control form-control-color w-100" id="editDialColorCode"
                                        wire:model="editDialColorCode">
                                    @error('editDialColorCode')
                                        <span class="text-danger">* {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            wire:click="updateDialColor({{ $editDialColorId }})">Update Color</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
        
        // Reinitialize tooltips after Livewire updates
        document.addEventListener('livewire:initialized', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

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
                    Livewire.dispatch('confirmDelete');
                    Swal.fire({
                        title: "Deleted!",
                        text: "Dial Color has been deleted.",
                        icon: "success"
                    });
                }
            });
        });

        window.addEventListener('edit-dial-color', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editDialColorModal'));
                modal.show();
            }, 500);
        });

        window.addEventListener('create-dial-color', event => {
            @this.resetForm();
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createDialColorModal'));
                modal.show();
            }, 200);
        });
    </script>
@endpush
