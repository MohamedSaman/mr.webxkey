<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Stock Details</h4>
                <div class="card-tools">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th class="text-center">Total Qty</th>
                            <th class="text-center">Sold Qty</th>
                            <th class="text-center">Available Qty</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($staffStocks->count() > 0)
                            @foreach ($staffStocks as $staffStock)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $staffStock->name ?? 'Unknown' }}</td>
                                    <td>{{ $staffStock->email ?? '-' }}</td>
                                    <td>{{ $staffStock->contact ?? '-' }}</td>
                                    <td class="text-center">{{ $staffStock->total_quantity }}</td>
                                    <td class="text-center">{{ $staffStock->sold_quantity }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $staffStock->available_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $staffStock->available_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="viewStockDetails({{ $staffStock->user_id }})"
                                            class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="alert alert-primary bg-opacity-10 my-2">
                                        <i class="bi bi-info-circle me-2"></i> No staff stock data found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold" id="stockDetailsModalLabel">
                            @if ($stockDetails && count($stockDetails) > 0)
                                {{ $stockDetails[0]->staff_name }}'s Inventory
                            @else
                                Staff Inventory
                            @endif
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @if ($stockDetails && count($stockDetails) > 0)
                        <!-- Summary Stats -->
                        <div class="px-4 py-3 bg-light border-bottom">
                            <div class="row g-3 text-center">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-white shadow-sm">
                                        <div class="text-primary fs-4 fw-bold">
                                            {{ $stockDetails->sum('quantity') }}
                                        </div>
                                        <div class="text-muted">Total Assigned</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-white shadow-sm">
                                        <div class="text-success fs-4 fw-bold">
                                            {{ $stockDetails->sum('sold_quantity') }}</div>
                                        <div class="text-muted">Total Sold</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-white shadow-sm">
                                        <div class="text-danger fs-4 fw-bold">
                                            {{ $stockDetails->sum('quantity') - $stockDetails->sum('sold_quantity') }}
                                        </div>
                                        <div class="text-muted">Total Available</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Item Grid -->
                        <div class="p-4">
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Search watches..." id="watchSearchInput">
                            </div>
                            <div class="row g-3 watch-items">
                                @foreach ($stockDetails as $item)
                                    <div class="col-md-6 col-lg-4 watch-item">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="row g-0">
                                                <div class="col-4">
                                                    <div
                                                        class="p-3 h-100 d-flex align-items-center justify-content-center bg-light rounded-start">
                                                        @if ($item->watch_image)
                                                            <img src="{{ asset('storage/' . $item->watch_image) }}"
                                                                alt="{{ $item->watch_name }}" class="img-fluid"
                                                                style="max-height: 100px; object-fit: contain;">
                                                        @else
                                                            <div class="text-center text-muted">
                                                                <i class="bi bi-watch fs-1"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title mb-1 fw-bold watch-brand">
                                                            {{ $item->watch_brand }}</h6>
                                                        <p class="card-text small mb-0 watch-name">
                                                            {{ $item->watch_name }} {{ $item->watch_model }}
                                                        </p>
                                                        <p class="card-text small text-muted mb-2">
                                                            Code: {{ $item->watch_code }}
                                                        </p>

                                                        <!-- Stock Status -->
                                                        <div
                                                            class="d-flex align-items-center justify-content-between mt-auto">
                                                            <div class="small">
                                                                <span class="text-muted">Status:</span>
                                                                @php
                                                                    $available = $item->quantity - $item->sold_quantity;
                                                                    $percentSold =
                                                                        $item->quantity > 0
                                                                            ? ($item->sold_quantity / $item->quantity) *
                                                                                100
                                                                            : 0;

                                                                    if ($available == 0) {
                                                                        $badgeClass = 'bg-danger';
                                                                        $statusText = 'Sold Out';
                                                                    } elseif ($available < 3) {
                                                                        $badgeClass = 'bg-warning';
                                                                        $statusText = 'Low Stock';
                                                                    } else {
                                                                        $badgeClass = 'bg-success';
                                                                        $statusText = 'In Stock';
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="badge {{ $badgeClass }} rounded-pill">{{ $statusText }}</span>
                                                            </div>
                                                            <div class="small">
                                                                <span
                                                                    class="fw-bold">{{ $available ?? 0 }}/{{ $item->quantity ?? 0 }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- Progress bar -->
                                                        <div class="progress mt-2" style="height: 5px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $percentSold }}%;"
                                                                aria-valuenow="{{ $percentSold }}" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <div class="py-5">
                                <i class="bi bi-search display-4 text-muted"></i>
                                <p class="mt-3">No watch inventory data found for this staff member.</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.addEventListener('open-stock-details-modal', event => {
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('stockDetailsModal'));
                modal.show();
            }, 500); // 500ms delay before showing the modal
        });

        // Search functionality for watches in modal
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('input', function(e) {
                if (e.target && e.target.id === 'watchSearchInput') {
                    const searchValue = e.target.value.toLowerCase();
                    document.querySelectorAll('.watch-item').forEach(item => {
                        const brand = item.querySelector('.watch-brand').textContent.toLowerCase();
                        const name = item.querySelector('.watch-name').textContent.toLowerCase();

                        if (brand.includes(searchValue) || name.includes(searchValue)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            });
        });
    </script>
@endpush
