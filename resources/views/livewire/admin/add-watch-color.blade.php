<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Color List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary w-100 w-md-auto" wire:click="createColor">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Color
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Color Name</th>
                                <th class="text-center">Hex Code</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody wire:key="color-list-{{ now() }}">
                            @if ($colors->count() > 0)
                                @foreach ($colors as $color)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $color->name ?? '-' }}</td>
                                        <td class="text-center">{{ $color->hex_code ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($color->hex_code)
                                            <div class="color-circle"
                                                style="
                                                width: 30px; 
                                                height: 30px; 
                                                border-radius: 50%; 
                                                background-color: {{ $color->hex_code }}; 
                                                border: 1px solid #dee2e6;
                                                display: inline-block;
                                                ">
                                            </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click="editColor({{ $color->id }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="confirmDelete({{ $color->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="4" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No watches colors found.
                                    </div>
                                </td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Create Color Model --}}
        <div wire:ignore.self wire:key="create-color-modal"  class="modal fade" id="createColorModal" tabindex="-1" aria-labelledby="createColorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createColorModalLabel">Add Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-3">
                            <label for="colorName" class="form-label">Color Name</label>
                            <input type="text" class="form-control" id="colorName" wire:model="colorName" placeholder="Enter Color Name">
                            @error('colorName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="colorCode" class="form-label">Color Code</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color" id="colorCode" wire:model="colorCode" placeholder="Select Color Code">
                                <span class="text-muted">{{ $colorCode }}</span>
                            </div>
                            @error('colorCode')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer flex-column flex-sm-row">
                        <button type="button" class="btn btn-secondary w-100 w-sm-auto mb-2 mb-sm-0" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary w-100 w-sm-auto" wire:click="saveColor">Add Color</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Color Model --}}
    </div>
    {{-- edit Color Model --}}
    {{-- Create Color Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editColorId ?? 'new' }}" class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editColorModalLabel">Edit Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editColorName" class="form-label">Color Name</label>
                            <input type="text" class="form-control" id="editColorName" wire:model="editColorName">
                            @error('editColorName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="editColorCode" class="form-label">Color Code</label>
                            <input type="color" class="form-control" id="editColorCode" wire:model="editColorCode">
                            @error('editColorCode')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateColor({{$editColorId}})">Update Color</button>
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
                        text: "Color has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('open-edit-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editColorModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
    <script>
        window.addEventListener('create-color-modal', event => {
            @this.resetForm();
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createColorModal'));
                modal.show();
            }, 200); // 500ms delay before showing the modal
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
        
        /* Better color picker for mobile */
        input[type="color"] {
            height: 40px;
            min-width: 60px;
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
            padding: 0.25rem 0.5rem;
        }
        
        .color-circle {
            width: 25px !important;
            height: 25px !important;
        }
    }
</style>
@endpush
