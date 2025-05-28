<div>
    @push('styles')
        <style>
            /* Enhanced Card Styling */
            .stat-card {
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                height: 100%;
                transition: all 0.3s ease;
                border: none;
                position: relative;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 6px;
                background: linear-gradient(to right, #4f46e5, #7c3aed);
                opacity: 0.8;
            }

            .stat-value {
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 5px;
                letter-spacing: -0.5px;
            }

            .stat-label {
                color: #6c757d;
                font-size: 14px;
                font-weight: 500;
                margin-bottom: 10px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .stat-icon {
                width: 56px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                transition: all 0.3s ease;
            }

            /* Enhanced Table Styling */
            .custom-table {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            }

            .custom-table thead th {
                background-color: #f8fafc;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 1px;
                color: #64748b;
                padding: 16px 20px;
                border-bottom: 2px solid #e2e8f0;
            }

            .custom-table tbody td {
                padding: 16px 20px;
                vertical-align: middle;
                border-bottom: 1px solid #f1f5f9;
                color: #334155;
                font-size: 14px;
            }

            .custom-table tbody tr {
                transition: all 0.2s ease;
            }

            .custom-table tbody tr:hover {
                background-color: #f8fafc;
            }

            .custom-table tbody tr:last-child td {
                border-bottom: none;
            }

            /* Invoice number style */
            .invoice-number {
                font-family: 'Courier New', monospace;
                font-weight: 600;
                letter-spacing: -0.5px;
                color: #334155;
            }

            /* Avatar styling */
            .avatar-circle {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 16px;
                letter-spacing: -0.5px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            }

            /* Enhanced status badges */
            .status-badge {
                padding: 6px 10px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            .badge-paid {
                background-color: #ecfdf5;
                color: #10b981;
            }

            .badge-partial {
                background-color: #fffbeb;
                color: #f59e0b;
            }

            .badge-unpaid {
                background-color: #fee2e2;
                color: #ef4444;
            }

            /* Enhanced buttons */
            .btn-action {
                border-radius: 8px;
                padding: 8px 12px;
                transition: all 0.2s;
                border: none;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            }

            .btn-action:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            }

            /* Modal enhancements */
            .modal-content {
                border: none;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }

            .modal-header {
                border-bottom: 1px solid #f1f5f9;
                background-color: #f8fafc;
                padding: 18px 24px;
            }

            .modal-title {
                font-weight: 600;
                font-size: 18px;
                color: #334155;
            }

            .modal-body {
                padding: 24px;
            }

            .modal-footer {
                border-top: 1px solid #f1f5f9;
                padding: 18px 24px;
            }

            /* Info cards in modal */
            .info-card {
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                height: 100%;
                border: none;
                transition: all 0.2s;
            }

            .info-card:hover {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            }

            .info-card h6 {
                font-weight: 600;
                color: #64748b;
                margin-bottom: 16px;
                font-size: 16px;
            }

            .field-label {
                font-size: 12px;
                font-weight: 600;
                color: #94a3b8;
                margin-bottom: 4px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            /* Product image styling */
            .product-image-container {
                width: 60px;
                height: 60px;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.2s;
            }

            .product-image-container:hover {
                transform: scale(1.08);
            }

            /* Enhanced empty state */
            .empty-state {
                padding: 60px 20px;
                text-align: center;
            }

            .empty-state-icon {
                height: 140px;
                margin-bottom: 20px;
                opacity: 0.6;
            }

            .empty-state-text {
                font-size: 16px;
                color: #94a3b8;
                font-weight: 500;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <!-- Summary Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Total Sales</div>
                            <div class="stat-value text-primary">Rs.{{ number_format($totals->total_sales ?? 0, 2) }}
                            </div>
                            <div class="text-muted small">From {{ $sales->total() }} transactions</div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10">
                            <i class="bi bi-currency-dollar text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card bg-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Total Due</div>
                            <div class="stat-value text-danger">Rs.{{ number_format($totals->total_due ?? 0, 2) }}</div>
                            <div class="text-muted small">
                                @if ($totals->total_sales != 0)
                                    {{ round(($totals->total_due / $totals->total_sales) * 100, 1) }}% of total sales
                                @else
                                    0% of total sales
                                @endif
                            </div>

                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10">
                            <i class="bi bi-cash-coin text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card bg-white">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Customers</div>
                            <div class="stat-value text-success">{{ $totals->customer_count ?? 0 }}</div>
                            <div class="text-muted small">
                                @if ($totals->customer_count != 0)
                                    {{ round($sales->total() / $totals->customer_count, 1) }} sales per customer
                                @else
                                    0 sales per customer
                                @endif
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10">
                            <i class="bi bi-people text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search Row -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0"
                                placeholder="Search by invoice or customer..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select shadow-none border" wire:model.live="filterStatus">
                            <option value="">All Payment Status</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partially Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select shadow-none border" wire:model.live="filterCustomerType">
                            <option value="">All Customer Types</option>
                            <option value="retail">Retail</option>
                            <option value="wholesale">Wholesale</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                            <i class="bi bi-x-circle me-1"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="custom-table mb-4">
            <div class="p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Customer Sales</h5>
                <div class="text-muted small">
                    Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of
                    {{ $sales->total() ?? 0 }} entries
                </div>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>INVOICE</th>
                            <th>CUSTOMER</th>
                            <th>DATE</th>
                            <th>TOTAL</th>
                            <th>PAID</th>
                            <th>DUE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <span class="invoice-number">{{ $sale->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="avatar-circle bg-{{ $sale->customer_type == 'wholesale' ? 'primary' : 'info' }} text-white me-2">
                                            {{ strtoupper(substr($sale->customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $sale->customer->name }}</div>
                                            <div class="text-muted small text-capitalize">{{ $sale->customer_type }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y') }}</div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($sale->created_at)->format('h:i A') }}</div>
                                </td>
                                <td class="fw-semibold">Rs.{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="fw-semibold text-success">
                                    Rs.{{ number_format($sale->total_amount - $sale->due_amount, 2) }}</td>
                                <td class="fw-semibold {{ $sale->due_amount > 0 ? 'text-danger' : 'text-muted' }}">
                                    Rs.{{ number_format($sale->due_amount, 2) }}
                                </td>
                                <td>
                                    <span class="status-badge badge-{{ $sale->payment_status }}">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-action btn-primary"
                                        wire:click="viewSaleDetails({{ $sale->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <img src="{{ asset('images/no-data.svg') }}" alt="No Data"
                                            class="empty-state-icon">
                                        <p class="empty-state-text">No sales data found</p>
                                        <button class="btn btn-outline-primary mt-3">Create New Sale</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $sales->links('livewire.custom-pagination') }}
            </div>
        </div>
    </div>

    <!-- Sale Details Modal -->
    <div class="modal fade" id="saleDetailsModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-receipt me-2 text-primary"></i>
                        Sale Details - <span
                            class="ms-2 invoice-number">{{ $selectedSale->invoice_number ?? '' }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedSale)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6><i class="bi bi-person me-2"></i>Customer Information</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-circle bg-{{ $selectedSale->customer_type == 'wholesale' ? 'primary' : 'info' }} text-white me-3"
                                            style="width: 50px; height: 50px; font-size: 18px;">
                                            {{ strtoupper(substr($selectedSale->customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">{{ $selectedSale->customer->name }}</h5>
                                            <p class="text-muted mb-0 text-capitalize">
                                                {{ $selectedSale->customer_type }} Customer</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="field-label">Email</div>
                                                <div>{{ $selectedSale->customer->email }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="field-label">Phone</div>
                                                <div>{{ $selectedSale->customer->phone }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="field-label">Invoice Number</div>
                                                <div class="fw-semibold">{{ $selectedSale->invoice_number }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="field-label">Sale Date</div>
                                                <div>
                                                    {{ \Carbon\Carbon::parse($selectedSale->created_at)->format('d M Y, h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6><i class="bi bi-credit-card me-2"></i>Payment Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="field-label">Payment Status</div>
                                                <div>
                                                    <span
                                                        class="status-badge badge-{{ $selectedSale->payment_status }}">
                                                        {{ ucfirst($selectedSale->payment_status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="field-label">Payment Type</div>
                                                <div class="text-capitalize">{{ $selectedSale->payment_type }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <div class="field-label">Subtotal</div>
                                                <div>Rs.{{ number_format($selectedSale->subtotal, 2) }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="field-label">Discount</div>
                                                <div>Rs.{{ number_format($selectedSale->discount_amount, 2) }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="field-label">Total Amount</div>
                                                <div class="fw-bold">
                                                    Rs.{{ number_format($selectedSale->total_amount, 2) }}</div>
                                            </div>
                                            <div class="mb-0">
                                                <div class="field-label">Due Amount</div>
                                                <div
                                                    class="fw-bold {{ $selectedSale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                    Rs.{{ number_format($selectedSale->due_amount, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment progress bar -->
                                    @if ($selectedSale->payment_status != 'unpaid')
                                        <div class="mt-3">
                                            @php
                                                $paymentPercentage = round(
                                                    (($selectedSale->total_amount - $selectedSale->due_amount) /
                                                        $selectedSale->total_amount) *
                                                        100,
                                                );
                                            @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="text-muted small">Payment Progress</span>
                                                <span
                                                    class="badge bg-{{ $paymentPercentage == 100 ? 'success' : 'warning' }}">{{ $paymentPercentage }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $paymentPercentage == 100 ? 'success' : 'warning' }}"
                                                    role="progressbar" style="width: {{ $paymentPercentage }}%;"
                                                    aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Product Items Card -->
                        <div class="info-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Products Purchased</h6>
                                <span class="badge bg-primary rounded-pill">{{ count($saleItems) }} items</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%">PRODUCT</th>
                                            <th>CODE</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-end">UNIT PRICE</th>
                                            <th class="text-end">DISCOUNT</th>
                                            <th class="text-end">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saleItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-image-container me-3">
                                                            @if (isset($item['image']) && $item['image'])
                                                                <img src="{{ asset('public/storage/' . $item['image']) }}"
                                                                    alt="{{ $item['watch_name'] }}" class="img-fluid"
                                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="width: 100%; height: 100%;">
                                                                    <i class="bi bi-watch text-muted fs-4"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $item['brand'] ?? '' }}
                                                                {{ $item['watch_name'] }}</div>
                                                            <div class="text-muted small">{{ $item['model'] ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $item->watch_code }}</span>
                                                </td>
                                                <td class="text-center fw-semibold">{{ $item->quantity }}</td>
                                                <td class="text-end">Rs.{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-end">Rs.{{ number_format($item->discount, 2) }}</td>
                                                <td class="text-end fw-bold">Rs.{{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Order Summary -->
                            <div class="row mt-4">
                                <div class="col-md-6 col-lg-7"></div>
                                <div class="col-md-6 col-lg-5">
                                    <div class="bg-light rounded p-4">
                                        <h6 class="mb-3">Order Summary</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Subtotal</span>
                                            <span>Rs.{{ number_format($selectedSale->subtotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Discount</span>
                                            <span>Rs.{{ number_format($selectedSale->discount_amount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted">Total</span>
                                            <span
                                                class="fw-semibold">Rs.{{ number_format($selectedSale->total_amount, 2) }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-success">Paid Amount</span>
                                            <span
                                                class="fw-semibold text-success">Rs.{{ number_format($selectedSale->total_amount - $selectedSale->due_amount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-danger">Due Amount</span>
                                            <span
                                                class="fw-semibold {{ $selectedSale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                Rs.{{ number_format($selectedSale->due_amount, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                    <a href="javascript:void(0)" class="btn btn-primary btn-action" onclick="printInvoice()">
                        <i class="bi bi-printer me-1"></i> Print Invoice
                    </a>
                    <a href="{{ route('receipts.download', ['id' => $selectedSale->id ?? 0]) }}"
                        class="btn btn-success btn-action" target="_blank">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener(addEventListenerLoaded, function() {
            window.addEventListener('open-sale-modal', event => {
                const modalElement = document.getElementById('saleDetailsModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            });

            // Print invoice function
            window.printInvoice = function() {
                const printContent = document.querySelector('#saleDetailsModal .modal-content').cloneNode(true);

                // Remove buttons and elements not needed for printing
                const buttons = printContent.querySelectorAll(
                    '.modal-footer button, .modal-footer a, .btn-close');
                buttons.forEach(button => button.remove());

                // Create a new window for printing
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Invoice ${document.querySelector('.invoice-number').textContent}</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
                        <style>
                            body { padding: 20px; }
                            @media print {
                                .modal-footer, .btn-close { display: none !important; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            ${printContent.outerHTML}
                        </div>
                        <script>
                            window.onload = function() { 
                                window.print();
                                setTimeout(function() { window.close(); }, 500);
                            }
                        <\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            };
        });
    </script>
@endpush
