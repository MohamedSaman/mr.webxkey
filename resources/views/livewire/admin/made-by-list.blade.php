<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Country List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createCountry">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Country
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Country Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:key="countries-table-{{now()}}">
                        @if ($countries->count() > 0)
                            @foreach ($countries as $country)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $country->country_name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editCountry({{ $country->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $country->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Countrys found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Create Country Model --}}
        <div wire:ignore.self wire:key="create-modal" class="modal fade" id="createCountryModal" tabindex="-1" aria-labelledby="createCountryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="createCountryModalLabel">Add Country</h1>
                        <button country="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row">

                            <div class="mb-3">
                                <label for="countryName" class="form-label">Country Name</label>
                                <input country="text" class="form-control" id="countryName" wire:model="countryName">
                                @error('countryName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button country="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button country="button" class="btn btn-primary" wire:click="saveCountry">Add Country</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Create Country Model --}}
    </div>
    {{-- Edit Country Model --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editCountryId ?? 'new' }}"  class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="editCountryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="editCountryModalLabel">Edit Country</h1>
                    <button country="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">

                        <div class="mb-3">
                            <label for="editCountryName" class="form-label">Country Name</label>
                            <input country="text" class="form-control" id="editCountryName" wire:model="editCountryName">
                            @error('editCountryName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button country="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button country="button" class="btn btn-primary" wire:click="updateCountry({{$editCountryId}})">Update Country</button>
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
                        text: "Country has been deleted.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
    <script>
        window.addEventListener('edit-country-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editCountryModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
    <script>
        window.addEventListener('create-country-modal', event => {
            @this.resetForm();
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('createCountryModal'));
                modal.show();
            }, 300); // 500ms delay before showing the modal
        });
    </script>
@endpush