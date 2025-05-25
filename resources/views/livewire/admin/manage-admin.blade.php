<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <h4 class="card-title mb-0">Admin List</h4>
                    <div class="card-tools">
                        <button class="btn btn-primary w-100 w-sm-auto" wire:click="createAdmin">
                            <i class="bi bi-plus-circle me-1"></i> Create Admin
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Admin Name</th>
                                <th class="text-center">Contact Number</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($admins->count() > 0)
                                @foreach ($admins as $admin)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $admin->name ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $admin->contact ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $admin->email ?? '-' }}</td>
                                        <td class="text-center align-middle">{{ $admin->role ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-primary"
                                                    wire:click="editAdmin({{ $admin->id }})" wire:loading.attr="disabled">
                                                    <i class="bi bi-pencil" wire:loading.class="d-none"
                                                        wire:target="editAdmin({{ $admin->id }})"></i>
                                                    <span wire:loading wire:target="editAdmin({{ $admin->id }})">
                                                        <i class="spinner-border spinner-border-sm"></i>
                                                    </span>
                                                </button>
                                                <button class="btn btn-danger"
                                                    wire:click="confirmDelete({{ $admin->id }})">
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
                                            <i class="bi bi-info-circle me-2"></i> No admins found.
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Create Suplier Modal --}}
        <div wire:ignore.self class="modal fade" id="createAdminModal" tabindex="-1"
            aria-labelledby="createAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createAdminModalLabel">Create Admin</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="adminName" class="form-label">Admin Name</label>
                                <input type="text" class="form-control" id="adminName" wire:model="name"
                                    placeholder="Enter admin name">
                                @error('name')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    wire:model="contactNumber" placeholder="Enter contact number">
                                @error('contactNumber')
                                    <span class="text-danger">* {{ $message }}</span>    
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
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
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('Password')">
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
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('ConfirmPassword')">
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
                        <button type="button" class="btn btn-secondary w-100 w-sm-auto mb-2 mb-sm-0" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary w-100 w-sm-auto" wire:click="saveAdmin">Add Admin</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Admin Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editAdminId ?? 'new' }}" class="modal fade hidden" id="editAdminModal" tabindex="-1"
        aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAdminModalLabel">Edit Admin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="adminName" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="adminName"
                                wire:model="editName" >
                            @error('editName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber"
                                wire:model="editContactNumber" >
                            @error('editContactNumber')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>   
                    <div class="row g-3 mt-1">   
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"
                                wire:model="editEmail" >  
                            @error('editEmail')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror  
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="Password" class="form-label">Password (leave blank to keep current)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="Password"
                                    wire:model="editPassword">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('Password')">
                                    <i class="bi bi-eye" id="PasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('editPassword')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6">
                            <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="ConfirmPassword"
                                    wire:model="editConfirmPassword">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('ConfirmPassword')">
                                    <i class="bi bi-eye" id="ConfirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('editConfirmPassword')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- @dump($editAdminId, $editName, $editContactNumber, $editEmail, $editBussinessName, $editAdminType, $editAddress) --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateAdmin({{$editAdminId}})">Update Admin</button>
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
                    text: "Admin has been deleted.",
                    icon: "success"
                });
            }
        });
    });

    window.addEventListener('edit-admin-modal', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('editAdminModal'));
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
    /* Make sure modal content doesn't overflow on small screens */
    .modal-content {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Improve table readability on small screens */
    @media (max-width: 767.98px) {
        .table {
            font-size: 0.875rem;
        }
        
        .btn-group-sm > .btn, .btn-sm {
            padding: 0.25rem 0.4rem;
        }
        
        .btn-group {
            display: flex;
            gap: 0.25rem;
        }
    }
    
    /* Ensure modal footer buttons stack properly on mobile */
    @media (max-width: 575.98px) {
        .modal-footer {
            justify-content: center;
        }
    }
</style>
@endpush
