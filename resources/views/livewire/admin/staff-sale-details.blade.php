<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 p-2 p-md-3">
            <h4 class="card-title fs-5 fs-md-4 mb-2 mb-md-0">Staff Sales Details</h4>
            <div class="btn-group btn-group-sm">
                <button onclick="printStaffSales()" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> <span class="d-none d-sm-inline">Print</span>
                </button>
                <button wire:click="exportToCsv" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> <span class="d-none d-sm-inline">Export</span> CSV
                </button>
            </div>
        </div>
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Staff</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th class="d-none d-sm-table-cell">Contact</th>
                            <th>Qty</th>
                            <th class="d-none d-lg-table-cell">Value</th>
                            <th>Sold</th>
                            <th class="d-none d-lg-table-cell">Sold Value</th>
                            <th>Avail</th>
                            <th class="d-none d-lg-table-cell">Avail Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffSales as $sale)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">
                                    {{ $sale->name ?? 'N/A' }}
                                    <div class="d-sm-none small text-muted">
                                        {{ $sale->contact ?? 'N/A' }}
                                    </div>
                                    <div class="d-md-none small text-muted">
                                        {{ $sale->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $sale->email ?? 'N/A' }}</td>
                                <td class="d-none d-sm-table-cell">{{ $sale->contact ?? 'N/A' }}</td>
                                <td>{{ $sale->total_quantity }}</td>
                                <td class="d-none d-lg-table-cell">{{ number_format($sale->total_value, 2) }}</td>
                                <td>{{ $sale->sold_quantity }}</td>
                                <td class="d-none d-lg-table-cell">{{ number_format($sale->sold_value, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $sale->available_quantity > 0 ? 'primary' : 'danger' }}">
                                        {{ $sale->available_quantity }}
                                    </span>
                                </td>
                                <td class="d-none d-lg-table-cell">{{ number_format($sale->total_value - $sale->sold_value, 2) }}</td>
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
                {{-- {{ $staffSales->links('livewire.custom-pagination') }} --}}
            </div>
        </div>
    </div>


    <div wire:ignore.self wire:key="edit-modal-{{ $staffId ?? 'new' }}" class="modal fade" id="salesDetails"
        tabindex="-1" aria-labelledby="salesDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="salesDetailsModalLabel">
                        <i class="bi bi-person-badge me-2"></i>
                        {{ isset($staffName) ? $staffName : 'Staff' }}'s Product Sales Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 p-md-3">
                    @if ($productDetails)
                        <!-- Staff Information -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-primary fw-bold">Staff Information</h5>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Name:</strong> {{ $staffDetails->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Email:</strong> {{ $staffDetails->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Contact:</strong> {{ $staffDetails->contact ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                           $summaryStats = $this->getSummaryStats($staffId);
                        @endphp
                        <!-- Summary Stats Cards -->
                        <div class="row g-3 mb-4">
                            <!-- Quantity Stats -->
                            <div class="col-12 col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h6 class="mb-0">Inventory Quantity Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center g-2">
                                            <div class="col-4">
                                                <div class="border-end h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5">
                                                        {{ data_get($summaryStats, 'total_quantity', 0) }}</h5>
                                                    <p class="text-muted small mb-0">Total Qty</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5">
                                                        {{ data_get($summaryStats, 'sold_quantity', 0) }}
                                                    </h5>
                                                    <p class="text-muted small mb-0">Sold Qty</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5 text-{{ data_get($summaryStats, 'available_quantity', 0) > 0 ? 'success' : 'danger' }}">
                                                        {{ data_get($summaryStats, 'available_quantity', 0) }}
                                                    </h5>
                                                    <p class="text-muted small mb-0">Available</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Value Stats -->
                            <div class="col-12 col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-success text-white py-2">
                                        <h6 class="mb-0">Inventory Value Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center g-2">
                                            <div class="col-4">
                                                <div class="border-end h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5">
                                                        Rs.{{ number_format(data_get($summaryStats, 'total_value', 0), 0) }}
                                                    </h5>
                                                    <p class="text-muted small mb-0">Total Value</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5">
                                                        Rs.{{ number_format(data_get($summaryStats, 'sold_value', 0), 0) }}
                                                    </h5>
                                                    <p class="text-muted small mb-0">Sold Value</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="h-100">
                                                    <h5 class="fw-bold fs-6 fs-md-5 text-{{ data_get($summaryStats, 'available_value', 0) > 0 ? 'success' : 'danger' }}">
                                                        Rs.{{ number_format(data_get($summaryStats, 'available_value', 0) ?? 0, 0) }}
                                                    </h5>
                                                    <p class="text-muted small mb-0">Available</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Table -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white py-2">
                                <h6 class="mb-0">Product-wise Sales Details</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th class="text-center d-none d-md-table-cell">Unit Price</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Sold</th>
                                                <th class="text-center">Avail</th>
                                                <th class="text-center d-none d-lg-table-cell">Total</th>
                                                <th class="text-center d-none d-lg-table-cell">Sold Value</th>
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
                                                                    <img src="{{ asset('public/storage/' . $product->watch_image) }}"
                                                                        class="img-fluid rounded"
                                                                        alt="{{ $product->watch_name }}"
                                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0 fs-6">{{ $product->watch_name }}</h6>
                                                                <small class="text-muted">
                                                                    {{ $product->watch_brand }} |
                                                                    {{ $product->watch_model }}
                                                                </small>
                                                                <div class="d-md-none small mt-1">
                                                                    <span class="badge bg-light text-dark">
                                                                        Rs.{{ number_format($product->unit_price, 0) }}
                                                                    </span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <small class="badge bg-light text-dark">
                                                                        {{ $product->watch_code }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center d-none d-md-table-cell">
                                                        Rs.{{ number_format($product->unit_price, 0) }}
                                                    </td>
                                                    <td class="text-center align-middle">{{ $product->quantity }}</td>
                                                    <td class="text-center align-middle">{{ $product->sold_quantity }}</td>
                                                    <td class="text-center align-middle">
                                                        <span class="badge bg-{{ $product->quantity - $product->sold_quantity > 0 ? 'success' : 'danger' }}">
                                                            {{ $product->quantity - $product->sold_quantity }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center d-none d-lg-table-cell">
                                                        Rs.{{ number_format($product->total_value, 0) }}
                                                    </td>
                                                    <td class="text-center d-none d-lg-table-cell">
                                                        Rs.{{ number_format($product->sold_value, 0) }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if ($product->status == 'completed')
                                                            <span class="badge bg-success">Completed</span>
                                                        @elseif($product->status == 'partial')
                                                            <span class="badge bg-warning text-dark">Partial</span>
                                                        @else
                                                            <span class="badge bg-info">{{ ucfirst($product->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-3">No product details found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer flex-wrap gap-2">
                    <button type="button" onclick="printStaffDetails()" class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i> Print Details
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            }, 500);
        });

        // Improved print functions remain unchanged
        function printStaffSales() {
            // Store a reference to the original window styles
            const originalStyles = document.head.innerHTML;
            
            // Create a new window for printing that's fully isolated
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            // Get the table content
            const tableContent = document.querySelector('.table-responsive').innerHTML;
            
            // Create print-friendly HTML with complete document structure
            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Staff Sales Report</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                        th { background-color: #f2f2f2; }
                        .text-center { text-align: center; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h2 { margin-bottom: 5px; }
                        .header p { margin-top: 0; color: #666; }
                        .badge { padding: 3px 8px; border-radius: 4px; font-size: smaller; }
                        .bg-primary { background-color: #0d6efd; color: white; }
                        .bg-danger { background-color: #dc3545; color: white; }
                        .bg-success { background-color: #198754; color: white; }
                        .bg-warning { background-color: #ffc107; color: black; }
                        .bg-info { background-color: #0dcaf0; color: white; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Staff Sales Report</h2>
                        <p>Generated on ${new Date().toLocaleString()}</p>
                    </div>
                    ${tableContent}
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="window.print();" style="padding: 8px 16px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Report</button>
                        <button onclick="window.close();" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Close</button>
                    </div>
                </body>
                </html>
            `;
            
            // Write to the new window
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            
            // Wait for content to load before focusing
            printWindow.onload = function() {
                printWindow.focus();
            };
        }
        
        function printStaffDetails() {
            // Create a new window for printing with complete isolation
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            // Get staff info and product details
            const staffInfo = document.querySelector('.modal-body .card-body').innerHTML;
            const summaryStats = document.querySelectorAll('.modal-body .row.g-3.mb-4 .card');
            const productsTable = document.querySelector('.modal-body .table-responsive').innerHTML;
            const staffName = document.querySelector('#salesDetailsModalLabel').textContent.trim();
            
            // Create HTML for summary stats cards
            let summaryHtml = '<div style="display: flex; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap;">';
            summaryStats.forEach(card => {
                summaryHtml += '<div style="width: 48%; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px;">';
                summaryHtml += '<div style="background-color: #f8f9fa; padding: 10px; border-bottom: 1px solid #ddd;">';
                summaryHtml += card.querySelector('.card-header').textContent;
                summaryHtml += '</div>';
                summaryHtml += '<div style="padding: 15px;">';
                summaryHtml += card.querySelector('.card-body').innerHTML;
                summaryHtml += '</div>';
                summaryHtml += '</div>';
            });
            summaryHtml += '</div>';
            
            // Create print-friendly HTML with self-contained CSS
            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${staffName} - Sales Details</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; margin: 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
                        .staff-info { margin-bottom: 20px; }
                        .row { display: flex; }
                        .col-4 { width: 33.33%; }
                        .text-center { text-align: center; }
                        .border-end { border-right: 1px solid #eee; }
                        .fw-bold { font-weight: bold; }
                        .text-success { color: green; }
                        .text-danger { color: red; }
                        .text-muted { color: #6c757d; }
                        .badge { padding: 3px 8px; border-radius: 4px; font-size: smaller; }
                        .bg-success { background-color: #198754; color: white; }
                        .bg-danger { background-color: #dc3545; color: white; }
                        .bg-warning { background-color: #ffc107; color: black; }
                        .bg-info { background-color: #0dcaf0; color: white; }
                        .bg-light { background-color: #f8f9fa; color: black; }
                        .bg-primary { background-color: #0d6efd; color: white; }
                        img { max-width: 100%; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>${staffName}</h1>
                        <p>Sales Report - Generated on ${new Date().toLocaleString()}</p>
                    </div>
                    
                    <div class="staff-info">
                        <h3>Staff Information</h3>
                        ${staffInfo}
                    </div>
                    
                    <div class="summary-stats">
                        <h3>Sales Summary</h3>
                        ${summaryHtml}
                    </div>
                    
                    <div class="products">
                        <h3>Product Details</h3>
                        ${productsTable}
                    </div>
                    
                    <div class="no-print" style="text-align: center; margin-top: 30px;">
                        <button onclick="window.print();" style="padding: 8px 16px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Report</button>
                        <button onclick="window.close();" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Close</button>
                    </div>
                </body>
                </html>
            `;
            
            // Write to the new window
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            
            // Wait for images to load
            printWindow.onload = function() {
                printWindow.focus();
            };
        }
    </script>
@endpush
