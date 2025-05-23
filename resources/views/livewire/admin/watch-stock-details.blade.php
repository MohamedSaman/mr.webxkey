<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Stock Details</h4>
                <div class="card-tools d-flex gap-2">
                    <button wire:click="exportToCSV" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <button id="printButton" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Image</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th class="text-center">Sold</th>
                                <th class="text-center">Available</th>
                                <th class="text-center">Damage</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($watchStocks->count() > 0)
                                @foreach ($watchStocks as $watchStock)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            @if($watchStock->watch_image)
                                                <img src="{{ asset('public/storage/' . $watchStock->watch_image) }}" 
                                                    alt="{{ $watchStock->watch_name }}"
                                                    class="img-thumbnail" 
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                    style="width: 40px; height: 40px; margin: 0 auto;">
                                                    <i class="bi bi-watch text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $watchStock->watch_name ?? '-' }}</td>
                                        <td>{{ $watchStock->watch_code ?? '-' }}</td>
                                        <td>{{ $watchStock->watch_brand ?? '-' }}</td>
                                        <td>{{ $watchStock->watch_model ?? '-' }}</td>
                                        <td class="text-center">{{ $watchStock->sold_count ?? '0' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $watchStock->available_stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $watchStock->available_stock ?? '0' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $watchStock->damage_stock ?? '0' }}</td>
                                        <td class="text-center fw-bold">{{ $watchStock->total_stock ?? '0' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-primary bg-opacity-10 my-2">
                                            <i class="bi bi-info-circle me-2"></i> No watch stock data found.
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print functionality
        document.getElementById('printButton').addEventListener('click', function() {
            printWatchStockDetails();
        });
    });

    function printWatchStockDetails() {
        // Clone the table content
        const tableContent = document.querySelector('.table-responsive').cloneNode(true);
        
        // Create print window
        const printWindow = window.open('', '_blank', 'height=600,width=800');
        
        // Create HTML content for print
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Watch Stock Details - Print Report</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { 
                        padding: 20px; 
                        font-size: 14px;
                    }
                    
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 8px; }
                    
                    @media print {
                        .no-print { display: none; }
                        thead { display: table-header-group; }
                        tr { page-break-inside: avoid; }
                    }
                    
                    @media (max-width: 768px) {
                        body { padding: 10px; }
                        th, td { padding: 4px; font-size: 12px; }
                    }
                    
                    .print-header {
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                        border-bottom: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class="container-fluid">
                    <div class="print-header d-flex justify-content-between align-items-center flex-wrap">
                        <h2>Watch Stock Details</h2>
                        <div class="text-end no-print">
                            <button class="btn btn-primary" onclick="window.print();">Print</button>
                            <button class="btn btn-secondary ms-2" onclick="window.close();">Close</button>
                        </div>
                    </div>
                    
                    ${tableContent.outerHTML}
                    
                    <div class="mt-4 text-center text-muted">
                        <small>Generated on ${new Date().toLocaleString()}</small>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
    }
</script>
@endpush

