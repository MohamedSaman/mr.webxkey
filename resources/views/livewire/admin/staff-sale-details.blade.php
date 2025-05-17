<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Staff Sales Details</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table  table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Assigned Qty</th>
                            <th>Assigned Value</th>
                            <th>Sold Qty</th>
                            <th>Sold Value</th>
                            <th>Available Qty</th>
                            <th>Available Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffSales as $sale)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sale->name ?? 'N/A' }}</td>
                                <td>{{ $sale->email ?? 'N/A' }}</td>
                                <td>{{ $sale->contact ?? 'N/A' }}</td>
                                <td>{{ $sale->total_quantity }}</td>
                                <td>{{ number_format($sale->total_value, 2) }}</td>
                                <td>{{ $sale->sold_quantity }}</td>
                                <td>{{ number_format($sale->sold_value, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $sale->available_quantity > 0 ? 'primary' : 'danger' }}">
                                        {{ $sale->available_quantity }}
                                    </span>
                                </td>
                                <td>{{ number_format($sale->total_value - $sale->sold_value, 2) }}</td>
                                <td>
                                    <button wire:click="viewSaleDetails({{ $sale->user_id }})"
                                        class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $staffSales->links() }}
            </div>
        </div>
    </div>


    <div wire:ignore.self wire:key="edit-modal-{{ $staffId ?? 'new' }}" class="modal fade" id="salesDetails"
        tabindex="-1" aria-labelledby="salesDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="salesDetailsModalLabel">
                        <i class="bi bi-person-badge me-2"></i>
                        {{ isset($staffName) ? $staffName : 'Staff' }}'s Product Sales Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($productDetails)
                        <!-- Staff Information -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-primary fw-bold">Staff Information</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Name:</strong> {{ $staffDetails->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Email:</strong> {{ $staffDetails->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Contact:</strong> {{ $staffDetails->contact ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Stats Cards -->
                        <div class="row mb-4">
                            <!-- Quantity Stats -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Inventory Quantity Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h5 class="fw-bold">
                                                        {{ $staffSales[$staffId]->total_quantity ?? 0 }}</h5>
                                                    <p class="text-muted small">Total Qty</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h5 class="fw-bold">{{ $staffSales[$staffId]->sold_quantity ?? 0 }}
                                                    </h5>
                                                    <p class="text-muted small">Sold Qty</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div>
                                                    <h5
                                                        class="fw-bold text-{{ $staffSales[$staffId]->available_quantity > 0 ? 'success' : 'danger' }}">
                                                        {{ $staffSales[$staffId]->available_quantity ?? 0 }}
                                                    </h5>
                                                    <p class="text-muted small">Available Qty</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Value Stats -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">Inventory Value Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h5 class="fw-bold">
                                                        ₹{{ number_format($staffSales[$staffId]->total_value ?? 0, 2) }}
                                                    </h5>
                                                    <p class="text-muted small">Total Value</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h5 class="fw-bold">
                                                        ₹{{ number_format($staffSales[$staffId]->sold_value ?? 0, 2) }}
                                                    </h5>
                                                    <p class="text-muted small">Sold Value</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div>
                                                    @php
                                                        $availableValue = $staffSales[$staffId]->total_value - $staffSales[$staffId]->sold_value;
                                                    @endphp
                                                    <h5
                                                        class="fw-bold 
                                                        text-{{ $availableValue > 0 ? 'success' : 'danger' }}
                                                        ">
                                                        ₹{{ number_format($availableValue ?? 0, 2) }}
                                                    </h5>
                                                    <p class="text-muted small">Available Value</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Table -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">Product-wise Sales Details</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Assigned Qty</th>
                                                <th class="text-center">Sold Qty</th>
                                                <th class="text-center">Available Qty</th>
                                                <th class="text-center">Total Value</th>
                                                <th class="text-center">Sold Value</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($productDetails as $index => $product)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($product->watch_image)
                                                                <div class="me-2" style="width: 40px; height: 40px;">
                                                                    <img src="{{ asset('storage/' . $product->watch_image) }}"
                                                                        class="img-fluid rounded"
                                                                        alt="{{ $product->watch_name }}"
                                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0">{{ $product->watch_name }}</h6>
                                                                <small class="text-muted">
                                                                    {{ $product->watch_brand }} |
                                                                    {{ $product->watch_model }}
                                                                </small>
                                                                <br>
                                                                <small class="badge bg-light text-dark">
                                                                    {{ $product->watch_code }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        ₹{{ number_format($product->unit_price, 2) }}
                                                    </td>
                                                    <td class="text-center">{{ $product->quantity }}</td>
                                                    <td class="text-center">{{ $product->sold_quantity }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-{{ $product->quantity - $product->sold_quantity > 0 ? 'success' : 'danger' }}">
                                                            {{ $product->quantity - $product->sold_quantity }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        ₹{{ number_format($product->total_value, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        ₹{{ number_format($product->sold_value, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($product->status == 'completed')
                                                            <span class="badge bg-success">Completed</span>
                                                        @elseif($product->status == 'partial')
                                                            <span class="badge bg-warning text-dark">Partial</span>
                                                        @else
                                                            <span
                                                                class="badge bg-info">{{ ucfirst($product->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-3">No product details
                                                        found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.addEventListener('open-sales-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('salesDetails'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });
    </script>
@endpush
