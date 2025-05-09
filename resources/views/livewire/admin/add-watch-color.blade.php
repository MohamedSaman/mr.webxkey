<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Color List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createColor">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Color
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Color Name</th>
                            <th class="text-center">Hex Code</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editColor({{ $color->id }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $color->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
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
        {{-- Create Color Model --}}
        <div wire:ignore.self  class="modal fade" id="createColorModal" tabindex="-1" aria-labelledby="createColorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createColorModalLabel">Add Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="colorName" class="form-label">Color Name</label>
                                <input type="text" class="form-control" id="colorName" wire:model="colorName">
                                @error('colorName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="colorCode" class="form-label">Color Code</label>
                                <input type="text" class="form-control" id="colorCode" wire:model="colorCode">
                                @error('colorCode')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveColor">Add Color</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Color Model --}}
    </div>
    {{-- edit Color Model --}}
    {{-- Create Color Model --}}
    <div wire:ignore.self  class="modal fade" id="editColorModal" tabindex="-1" aria-labelledby="editColorModalLabel"
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
                            <input type="text" class="form-control" id="editColorCode" wire:model="editColorCode">
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
@endpush
