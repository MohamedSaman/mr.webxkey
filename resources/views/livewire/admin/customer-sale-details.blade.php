<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Customer Sales Details</h4>
            <div class="d-flex gap-2">
                <button wire:click="printData" class="btn btn-secondary">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button wire:click="exportToCSV" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export CSV
                </button>
            </div>
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
                    <button type="button" class="btn btn-primary" onclick="printModalContent()">
                        <i class="bi bi-printer"></i> Print Details
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Existing modal opening script
    window.addEventListener('open-customer-sale-details-modal', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('customerSalesModal'));
            modal.show();
        }, 500);
    });

    // Main table print function
    document.addEventListener('livewire:initialized', function() {
        Livewire.on('print-customer-table', function() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            
            // Get the table content
            const tableElement = document.querySelector('.table.table-striped').cloneNode(true);
            
            // Remove the action column for printing
            const actionColumnIndex = 8; // 0-based index of "Action" column
            const headerRow = tableElement.querySelector('thead tr');
            const headerCells = headerRow.querySelectorAll('th');
            headerCells[actionColumnIndex].remove();
            
            const rows = tableElement.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > actionColumnIndex) {
                    cells[actionColumnIndex].remove();
                }
            });
            
            // Create print-friendly HTML
            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Customer Sales Details</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        @page {
                            size: landscape;
                            margin: 1cm;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            margin: 0;
                        }
                        h2, h3, p {
                            margin: 0 0 10px 0;
                        }
                        .container {
                            max-width: 100%;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        thead {
                            background-color: #f2f2f2;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 10px 8px;
                            text-align: center;
                        }
                        th {
                            font-weight: bold;
                        }
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        .badge {
                            padding: 5px 10px;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: bold;
                            color: white;
                            display: inline-block;
                        }
                        .bg-primary, .badge-primary {
                            background-color: #0d6efd;
                            color: white;
                        }
                        .bg-info, .badge-info {
                            background-color: #0dcaf0;
                            color: black;
                        }
                        .bg-success, .badge-success {
                            background-color: #198754;
                            color: white;
                        }
                        .bg-danger, .badge-danger {
                            background-color: #dc3545;
                            color: white;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 25px;
                            padding-bottom: 15px;
                            border-bottom: 2px solid #ddd;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 30px;
                            border-top: 1px solid #ddd;
                            padding-top: 10px;
                            font-size: 12px;
                            color: #666;
                        }
                        .buttons {
                            text-align: center;
                            margin-top: 25px;
                        }
                        .btn {
                            padding: 8px 16px;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            margin-right: 10px;
                        }
                        .btn-primary {
                            background-color: #0d6efd;
                            color: white;
                        }
                        .btn-secondary {
                            background-color: #6c757d;
                            color: white;
                        }
                        @media print {
                            .buttons {
                                display: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Customer Sales Details</h2>
                            <p>Generated on: ${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div class="table-responsive">
                            ${tableElement.outerHTML}
                        </div>
                        
                        <div class="footer">
                            <p>Mr. WebxKey - Customer Sales Report</p>
                        </div>
                        
                        <div class="buttons">
                            <button onclick="window.print();" class="btn btn-primary">
                                <span>Print Report</span>
                            </button>
                            <button onclick="window.close();" class="btn btn-secondary">
                                <span>Close</span>
                            </button>
                        </div>
                    </div>
                </body>
                </html>
            `;
            
            // Write to the new window
            printWindow.document.open();
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            
            // Wait for content to load before focusing
            printWindow.onload = function() {
                printWindow.focus();
            };
        });
    });

    // Modal print function
    function printModalContent() {
        // Get the customer data
        const customerName = document.querySelector('#customerSalesModalLabel').innerText.trim();
        const modalBody = document.querySelector('.modal-body');
        
        // Extract key data
        const customerInfoSection = modalBody.querySelector('.card.mb-4');
        const customerDetails = {};
        customerInfoSection.querySelectorAll('p').forEach(p => {
            const text = p.innerText;
            if (text.includes(':')) {
                const [key, value] = text.split(':');
                customerDetails[key.trim()] = value.trim();
            }
        });
        
        // Extract summary data
        const summaryCards = modalBody.querySelectorAll('.row.mb-4 .card-body');
        const summaryData = {
            totalSales: summaryCards[0]?.querySelector('h3')?.innerText || '0',
            totalPaid: summaryCards[1]?.querySelector('h3')?.innerText || '0',
            totalDue: summaryCards[2]?.querySelector('h3')?.innerText || '0',
        };
        
        // Extract payment percentage
        const progressBar = modalBody.querySelector('.progress-bar');
        const paymentPercentage = progressBar?.getAttribute('aria-valuenow') || '0';
        
        // Get product sales data
        const productTable = modalBody.querySelector('.table');
        
        // Create a new window
        const printWindow = window.open('', '_blank', 'width=1000,height=800');
        
        // Prepare HTML content with inline styles for better print reliability
        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>${customerName}</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    @page { size: landscape; margin: 1cm; }
                    body { font-family: Arial, sans-serif; padding: 20px; margin: 0; }
                    h2, h3, h5 { margin: 0 0 10px 0; }
                    p { margin: 0 0 8px 0; }
                    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
                    .customer-info { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; background: #f9f9f9; }
                    .summary-row { display: flex; justify-content: space-between; margin-bottom: 20px; }
                    .summary-card { width: 31%; border: 1px solid #ddd; border-radius: 5px; padding: 15px; text-align: center; }
                    .total-sales { background-color: #f8f9fa; }
                    .amount-paid { background-color: #d1e7dd; }
                    .amount-due { background-color: #f8d7da; }
                    .progress-container { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
                    .progress-bar-container { height: 10px; background-color: #e9ecef; border-radius: 5px; margin: 10px 0; }
                    .progress-bar-fill { height: 100%; background-color: #0d6efd; border-radius: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .font-bold { font-weight: bold; }
                    .footer { text-align: center; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; color: #666; }
                    .print-btn { display: block; margin: 20px auto; padding: 10px 20px; background: #0d6efd; color: white; 
                                 border: none; border-radius: 5px; cursor: pointer; }
                    @media print {
                        .print-btn { display: none; }
                        body { -webkit-print-color-adjust: exact; color-adjust: exact; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${customerName}</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                
                <div class="customer-info">
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="width: 50%;">
                            <p><strong>Name:</strong> ${customerDetails['Name'] || 'N/A'}</p>
                            <p><strong>Email:</strong> ${customerDetails['Email'] || 'N/A'}</p>
                            <p><strong>Phone:</strong> ${customerDetails['Phone'] || 'N/A'}</p>
                            <p><strong>Type:</strong> ${customerDetails['Type'] || 'N/A'}</p>
                        </div>
                        <div style="width: 50%;">
                            <p><strong>Business Name:</strong> ${customerDetails['Business Name'] || 'N/A'}</p>
                            <p><strong>Address:</strong> ${customerDetails['Address'] || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="summary-row">
                    <div class="summary-card total-sales">
                        <h5>Total Sales Amount</h5>
                        <h3>${summaryData.totalSales}</h3>
                    </div>
                    <div class="summary-card amount-paid">
                        <h5>Amount Paid</h5>
                        <h3>${summaryData.totalPaid}</h3>
                    </div>
                    <div class="summary-card amount-due">
                        <h5>Amount Due</h5>
                        <h3>${summaryData.totalDue}</h3>
                    </div>
                </div>
                
                <div class="progress-container">
                    <p class="font-bold">Payment Progress</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: ${paymentPercentage}%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>0%</span>
                        <span>${paymentPercentage}%</span>
                        <span>100%</span>
                    </div>
                </div>
                
                <h5>Product-wise Sales</h5>
                ${productTable ? productTable.outerHTML : '<p>No product sales data available</p>'}
                
                <div class="footer">
                    <p>Mr. WebxKey - Customer Sales Report</p>
                </div>
                
                <button class="print-btn" onclick="window.print(); setTimeout(() => window.close(), 500);">
                    Print Report
                </button>
            </body>
            </html>
        `;
        
        // Write to the new window
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();
        
        // Wait for content to load before focusing
        printWindow.onload = function() {
            printWindow.focus();
        };
    }
</script>
@endpush
