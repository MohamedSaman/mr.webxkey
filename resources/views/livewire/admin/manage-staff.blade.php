<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <h4 class="card-title mb-2 mb-sm-0">Staff List</h4>
                    <div class="card-tools">
                        <button class="btn btn-primary w-100 w-sm-auto" wire:click="createStaff">
                            <i class="bi bi-plus-circle me-1"></i> Create Staff
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive"> <!-- Wrap table in div with table-responsive class -->
                    <table class="table table-bordered table-hover"> <!-- Remove table-responsive from table -->
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Staff Name</th>
                                <th class="text-center">Contact Number</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($staffs->count() > 0)
                                @foreach ($staffs as $staff)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $staff->name ?? '-' }}</td>
                                        <td class="text-center">{{ $staff->contact ?? '-' }}</td>
                                        <td class="text-center">{{ $staff->email ?? '-' }}</td>
                                        <td class="text-center">{{ $staff->role ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click="editStaff({{ $staff->id }})" wire:loading.attr="disabled">
                                                    <i class="bi bi-pencil" wire:loading.class="d-none"
                                                        wire:target="editStaff({{ $staff->id }})"></i>
                                                    <span wire:loading wire:target="editStaff({{ $staff->id }})">
                                                        <i class="spinner-border spinner-border-sm"></i>
                                                    </span>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="confirmDelete({{ $staff->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="alert alert-primary bg-opacity-10 my-2">
                                            <i class="bi bi-info-circle me-2"></i> No staffs found.
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- <div class="d-flex justify-content-center">
                    {{ $staffs->links('livewire.custom-pagination') }}
                </div> --}}
            </div>
        </div>
        {{-- Create Suplier Modal --}}
        <div wire:ignore.self class="modal fade" id="createStaffModal" tabindex="-1"
            aria-labelledby="createStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createStaffModalLabel">Create Staff</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3"> <!-- Switch to g-3 for better spacing -->
                            <div class="col-12 col-md-6"> <!-- Full width on mobile -->
                                <label for="staffName" class="form-label">Staff Name</label>
                                <input type="text" class="form-control" id="staffName" wire:model="name"
                                    placeholder="Enter staff name">
                                @error('name')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6"> <!-- Full width on mobile -->
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    wire:model="contactNumber" placeholder="Enter contact number">
                                @error('contactNumber')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-1"> <!-- Add some margin top -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                    placeholder="Enter email">
                                @error('email')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="Password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="Password" wire:model="password"
                                        placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePasswordVisibility('Password')">
                                        <i class="bi bi-eye" id="PasswordToggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="ConfirmPassword"
                                        wire:model="confirmPassword" placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePasswordVisibility('ConfirmPassword')">
                                        <i class="bi bi-eye" id="ConfirmPasswordToggleIcon"></i>
                                    </button>
                                </div>
                                @error('confirmPassword')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-column flex-sm-row">
                        <button type="button" class="btn btn-secondary w-100 w-sm-auto mb-2 mb-sm-0"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary w-100 w-sm-auto" wire:click="saveStaff">Add Staff
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Staff Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editStaffId ?? 'new' }}" class="modal fade hidden"
        id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editStaffModalLabel">Edit Staff</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editName" class="form-label">Staff Name</label>
                            <input type="text" class="form-control" id="editName" wire:model="editName">
                            @error('editName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editContactNumber"
                                wire:model="editContactNumber">
                            @error('editContactNumber')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" wire:model="editEmail">
                            @error('editEmail')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editPassword" class="form-label">Password (leave blank to keep
                                current)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editPassword"
                                    wire:model="editPassword">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePasswordVisibility('editPassword')">
                                    <i class="bi bi-eye" id="editPasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('editPassword')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editConfirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="editConfirmPassword"
                                    wire:model="editConfirmPassword">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePasswordVisibility('editConfirmPassword')">
                                    <i class="bi bi-eye" id="editConfirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('editConfirmPassword')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- @dump($editStaffId, $editName, $editContactNumber, $editEmail, $editBussinessName, $editStaffType, $editAddress) --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="updateStaff({{ $editStaffId }})">Update Staff</button>
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
                        text: "Staff has been deleted.",
                        icon: "success"
                    });
                }
            });
        });

        window.addEventListener('edit-staff-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('editStaffModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });

        // Password toggle visibility function
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + 'ToggleIcon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye");
                toggleIcon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye-slash");
                toggleIcon.classList.add("bi-eye");
            }
        }
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
        }
        
        .btn {
            padding: 0.375rem 0.5rem;
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
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    }
    
    /* Focus on most important content for very small screens */
    @media (max-width: 400px) {
        /* Option to hide less important columns if needed */
        .table td:nth-child(4), .table th:nth-child(4) {
            display: none; /* Hides email column on very small screens */
        }
    }
    
    /* Ensure password toggle button is properly sized */
    .input-group .btn-outline-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
