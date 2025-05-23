<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Customer Sales Details</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Invoices</th>
                            <th>Total Sales</th>
                            <th>Total Paid</th>
                            <th>Total Due</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customerSales as $index => $customer)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $customer->type == 'wholesale' ? 'primary' : 'info' }}">
                                        {{ ucfirst($customer->type) }}
                                    </span>
                                </td>
                                <td>{{ $customer->invoice_count }}</td>
                                <td>Rs.{{ number_format($customer->total_sales, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $customer->total_sales-$customer->total_due > 0 ? 'success' : 'danger' }} px-2 py-1">
                                        Rs.{{ number_format($customer->total_sales-$customer->total_due, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->total_due > 0 ? 'danger' : 'success' }} px-2 py-1">
                                        Rs.{{ number_format($customer->total_due, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click="viewSaleDetails({{ $customer->customer_id }})" 
                                            class="btn btn-sm btn-outline-primary btn-hover-primary">
                                        <i class="bi bi-eye"></i> 
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">No customer sales records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $customerSales->links('livewire.custom-pagination') }}
            </div>
        </div>
    </div>

    <!-- Customer Sale Details Modal -->
    <div wire:ignore.self class="modal fade" id="customerSalesModal" tabindex="-1" aria-labelledby="customerSalesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="customerSalesModalLabel">
                        <i class="bi bi-person me-2"></i>
                        {{ $modalData ? $modalData['customer']->name . '\'s Sales History' : 'Sales History' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($modalData)
                        <!-- Customer Information Section - Simplified to match design -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Name:</strong> {{ $modalData['customer']->name }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $modalData['customer']->email }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> {{ $modalData['customer']->phone }}</p>
                                        <p class="mb-1"><strong>Type:</strong> 
                                            <span class="badge bg-primary px-2">
                                                {{ ucfirst($modalData['customer']->type) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Business Name:</strong> {{ $modalData['customer']->business_name ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Address:</strong> {{ $modalData['customer']->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Summary Cards - Updated to match design -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <h6 class="text-primary mb-2">Total Sales Amount</h6>
                                        <h3 class="fw-bold">Rs.{{ number_format($modalData['salesSummary']->total_amount, 2) }}</h3>
                                        <p class="text-muted mb-0">Across {{ count($modalData['invoices']) }} invoices</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <h6 class="text-success mb-2">Amount Paid</h6>
                                        <h3 class="fw-bold text-success">Rs.{{ number_format($modalData['salesSummary']->total_paid, 2) }}</h3>
                                        <p class="text-muted mb-0">
                                            {{ round(($modalData['salesSummary']->total_paid / $modalData['salesSummary']->total_amount) * 100) }}% of total
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <h6 class="text-danger mb-2">Amount Due</h6>
                                        <h3 class="fw-bold text-danger">Rs.{{ number_format($modalData['salesSummary']->total_due, 2) }}</h3>
                                        <p class="text-muted mb-0">
                                            {{ round(($modalData['salesSummary']->total_due / $modalData['salesSummary']->total_amount) * 100) }}% outstanding
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Progress Bar - Simplified to match design -->
                        @php
                            $paymentPercentage = $modalData['salesSummary']->total_amount > 0 
                                ? round(($modalData['salesSummary']->total_paid / $modalData['salesSummary']->total_amount) * 100) 
                                : 0;
                        @endphp
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="fw-bold mb-2">Payment Progress</p>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        <div class="progress-bar bg-primary" 
                                             role="progressbar" 
                                             style="width: {{ $paymentPercentage }}%;" 
                                             aria-valuenow="{{ $paymentPercentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="ms-3 fw-bold">{{ $paymentPercentage }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Product-wise Sales Table - With scrolling for more than 5 items -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Product-wise Sales</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                    <table class="table table-hover mb-0">
                                        <thead class="position-sticky top-0 bg-white" style="z-index: 1;">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Discount</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($modalData['productSales'] as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($item->watch_image)
                                                                <div class="me-3" style="width: 50px; height: 50px;">
                                                                    <img src="{{ asset('public/storage/' . $item->watch_image) }}" 
                                                                         class="img-fluid rounded" 
                                                                         alt="{{ $item->watch_name }}" 
                                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="fw-bold mb-1">{{ $item->watch_name }}</h6>
                                                                <small class="text-muted d-block">
                                                                    {{ $item->watch_brand ?? '' }} {{ $item->watch_model ?? '' }}
                                                                </small>
                                                                <span class="badge bg-light text-dark">
                                                                    {{ $item->watch_code }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->invoice_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->sale_date)->format('d M Y') }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">Rs.{{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end">Rs.{{ number_format($item->discount, 2) }}</td>
                                                    <td class="text-end fw-bold">Rs.{{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-3">No product sales found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading customer sales data...</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('open-customer-sale-details-modal', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('customerSalesModal'));
            modal.show();
        }, 500); // 500ms delay before showing the modal
    });
</script>
@endpush
