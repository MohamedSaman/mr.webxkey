<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Dial Color List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createDialColor">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Color
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Dial Color Name</th>
                            <th class="text-center">Hex Code</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($dialColors->count() > 0)
                            @foreach ($dialColors as $dialColor)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $dialColor->dial_color_name }}</td>
                                    <td class="text-center">{{ $dialColor->dial_color_code }}</td>
                                    <td class="text-center">
                                        <div class="color-circle"
                                            style="
                                            width: 30px; 
                                            height: 30px; 
                                            border-radius: 50%; 
                                            background-color: {{ $dialColor->dial_color_code }}; 
                                            border: 1px solid #dee2e6;
                                            display: inline-block;
                                            ">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editDialColor({{ $dialColor->id }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $dialColor->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="5" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Dial Colors found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Dial Color Model --}}
        <div wire:ignore.self  class="modal fade" id="createDialColorModal" tabindex="-1" aria-labelledby="createDialColorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createDialColorModalLabel">Add Color</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="dialColorName" class="form-label">Dial Color Name</label>
                                <input type="text" class="form-control" id="dialColorName" wire:model="dialColorName">
                                @error('dialColorName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="dialColorCode" class="form-label">Dial Color Code</label>
                                <input type="text" class="form-control" id="dialColorCode" wire:model="dialColorCode">
                                @error('dialColorCode')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
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
    </div>
    {{-- edit Dial Color Model --}}
    {{-- Create Dial Color Model --}}
    <div wire:ignore.self  class="modal fade" id="editDialColorModal" tabindex="-1" aria-labelledby="editDialColorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editDialColorModalLabel">Edit Dial Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editDialColorName" class="form-label">Dial Color Name</label>
                            <input type="text" class="form-control" id="editDialColorName" wire:model="editDialColorName">
                            @error('editDialColorName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="editDialColorCode" class="form-label">Dial Color Code</label>
                            <input type="text" class="form-control" id="editDialColorCode" wire:model="editDialColorCode">
                            @error('editDialColorCode')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateDialColor({{$editDialColorId}})">Update Color</button>
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
                        text: "Dial Color has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
@endpush
