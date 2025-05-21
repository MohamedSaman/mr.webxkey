<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Customer List</h4>
                <div class="card-tools">
                    <button class="btn btn-primary" wire:click="createCustomer">
                        <i class="bi bi-plus-circle me-1"></i> Create Customer
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Bussiness Name</th>
                            <th class="text-center">Contact Number</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($customers->count() > 0)
                            @foreach ($customers as $customer)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $customer->name ?? '-' }}</td>
                                    <td class="text-center">{{ $customer->bussiness_name ?? '-' }}</td>
                                    <td class="text-center">{{ $customer->phone ?? '-' }}</td>
                                    <td class="text-center">{{ $customer->email ?? '-' }}</td>
                                    <td class="text-center">{{ $customer->type ?? '-' }}</td>
                                    <td class="text-center">{{ $customer->address ?? '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editCustomer({{ $customer->id }})" wire:loading.attr="disabled">
                                            <i class="bi bi-pencil" wire:loading.class="d-none"
                                                wire:target="editWatch({{ $customer->id }})"></i>
                                            <span wire:loading wire:target="editCustomer({{ $customer->id }})">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </span>

                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $customer->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No customers found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- <div class="d-flex justify-content-center">
                    {{ $customers->links('livewire.custom-pagination') }}
                </div> --}}
            </div>
        </div>
        {{-- Create Suplier Modal --}}
        <div wire:ignore.self class="modal fade" id="createCustomerModal" tabindex="-1"
            aria-labelledby="createCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createCustomerModalLabel">Create Customer</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" wire:model="name"
                                    placeholder="Enter customer name">
                                @error('name')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    wire:model="contactNumber" placeholder="Enter contact number">
                                @error('contactNumber')
                                    <span class="text-danger">* {{ $message }}</span>    
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                    placeholder="Enter email">  
                                @error('email')     
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror  
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bussinessName" class="form-label">Bussiness Name</label>
                                <input type="text" class="form-control" id="bussinessName" wire:model="bussinessName"
                                    placeholder="Enter Bussiness Name">
                                @error('bussinessName')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerType" class="form-label">Customer Type</label>
                                <select class="form-select" id="customerType" wire:model="customerType">
                                    <option value="">Select customer type</option>
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                </select>
                                @error('customerType')
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" wire:model="address"
                                    placeholder="Enter address">
                                @error('address')                       
                                    <span class="text-danger">* {{ $message }}</span>
                                @enderror
                            </div>         
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="saveCustomer">Add Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Customer Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editCustomerId ?? 'new' }}" class="modal fade hidden" id="editCustomerModal" tabindex="-1"
        aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCustomerModalLabel">Edit Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="editName"
                                wire:model="editName" >
                            @error('editName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editContactNumber"
                                wire:model="editContactNumber" >
                            @error('editContactNumber')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                    </div>   
                    <div class="row">   
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail"
                                wire:model="editEmail" >  
                            @error('editEmail')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror  
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editBussinessName" class="form-label">Bussiness Name</label>
                            <input type="text" class="form-control" id="editBussinessName"
                                wire:model="editBussinessName" >
                            @error('editBussinessName')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCustomerType" class="form-label">Customer Type</label>
                            <select class="form-select" id="editCustomerType" wire:model="editCustomerType">
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                            </select>
                            @error('editCustomerType')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="editAddress"
                                wire:model="editAddress">
                            @error('editAddress')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>       
                    </div>
                </div>
                {{-- @dump($editCustomerId, $editName, $editContactNumber, $editEmail, $editBussinessName, $editCustomerType, $editAddress) --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="updateCustomer({{$editCustomerId}})">Update Customer</button>
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
                    text: "Customer has been deleted.",
                    icon: "success"
                });
            }
        });
    });
</script>
<script>
    window.addEventListener('open-edit-modal', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
            modal.show();
        }, 500); // 500ms delay before showing the modal
    });
</script>
@endpush
