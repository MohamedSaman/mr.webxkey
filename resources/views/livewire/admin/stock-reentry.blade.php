<div class="container">
    <h4 class="mb-4">Stock Re-entry for {{ $staff->name }}</h4>

    <!-- Search Bar -->
   <div class="mb-3">
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Search watches..." id="watchSearchInput">
                            </div>

    <!-- Item Grid -->

    <div class="row">
        <!-- Left: Product List -->
        <div class="col-lg-7" style="max-height: 75vh; overflow-y: auto;">
            @if ($products->count())
                <div class="row g-2 g-md-3 staff-products">
                    @foreach ($products as $product)
                        @php
                            $watch = $product->watchDetail;
                            $available = $product->quantity - $product->sold_quantity;
                            $percentSold = $product->quantity > 0 ? ($product->sold_quantity / $product->quantity) * 100 : 0;

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

                        <div class="col-12 col-sm-6 staff-product-item">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <div class="p-2 p-md-3 h-100 d-flex align-items-center justify-content-center bg-light rounded-start">
                                            @if ($watch && $watch->image)
                                                <img src="{{ asset('public/storage/' . $watch->image) }}" alt="{{ $watch->name }}" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                                
                                            @else
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-watch fs-3 fs-md-1"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-2 p-md-3 d-flex flex-column">
                                            <h6 class="card-title mb-1 fw-bold fs-6">{{ $watch->brand ?? 'Brand' }}</h6>
                                            <p class="card-text small mb-0">{{ $watch->name ?? 'N/A' }} {{ $watch->model ?? '' }}</p>
                                            <p class="card-text small text-muted mb-2">Code: {{ $watch->code ?? 'N/A' }}</p>

                                            <div class="d-flex align-items-center justify-content-between mt-auto flex-wrap gap-1">
                                                <div class="small">
                                                    <span class="text-muted">Status:</span>
                                                    <span class="badge {{ $badgeClass }} rounded-pill">{{ $statusText }}</span>
                                                </div>
                                                <div class="small">
                                                    <span class="fw-bold">{{ $available }}/{{ $product->quantity }}</span>
                                                </div>
                                            </div>

                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentSold }}%;" aria-valuenow="{{ $percentSold }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>

                                            <div class="mt-2 text-end">
                                                <button class="btn btn-sm btn-outline-primary" wire:click="selectProduct({{ $product->id }})">
                                                    Re-entry
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    No matching stock found.
                </div>
            @endif
        </div>

        <!-- Right: Selected Product Form -->
        <div class="col-lg-5">
            @if ($selectedProduct)
                <div class="card mt-3 mt-lg-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <strong>Edit Stock: </strong> {{ $selectedProduct->watchDetail->name ?? 'Selected Product' }}
                            <p class="card-text small text-muted mb-0">Code: {{ $selectedProduct->watchDetail->code ?? 'N/A' }}</p>
                        </div>
                        @if ($selectedProduct->watchDetail && $selectedProduct->watchDetail->image)
                            <img src="{{ asset('public/storage/' . $selectedProduct->watchDetail->image) }}" alt="{{ $selectedProduct->watchDetail->name }}" class="img-fluid ms-3" style="max-height: 60px; object-fit: contain;">
                        @endif
                    </div>
                    <div class="card-body">
                        <p>Available Quantity: {{ $selectedProduct->quantity - $selectedProduct->sold_quantity }}</p>

                        <div class="mb-3">
                            <label>Damaged Quantity</label>
                            <input type="number" class="form-control" wire:model.defer="damagedQuantity">
                        </div>

                        <div class="mb-3">
                            <label>Restock Quantity</label>
                            <input type="number" class="form-control" wire:model.defer="restockQuantity">
                        </div>

                        <button wire:click="submitReentry" class="btn btn-success">Submit</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    function filterProducts() {
        const searchInput = document.getElementById('watchSearchInput');
        if (!searchInput) return;
        const searchValue = searchInput.value.toLowerCase();
        document.querySelectorAll('.staff-product-item').forEach(item => {
            const brand = item.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const name = item.querySelector('.card-text.small.mb-0')?.textContent.toLowerCase() || '';
            const code = item.querySelector('.card-text.small.text-muted')?.textContent.toLowerCase() || '';
            if (
                brand.includes(searchValue) ||
                name.includes(searchValue) ||
                code.includes(searchValue)
            ) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('watchSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterProducts);
        }
    });

    // Re-apply filter after Livewire DOM updates
    document.addEventListener('livewire:message.processed', function() {
        filterProducts();
    });
    document.addEventListener('livewire:message.processed', function() {
    const searchInput = document.getElementById('watchSearchInput');
    if (searchInput) {
        searchInput.value = localStorage.getItem('watchSearchValue') || '';
        filterProducts();
    }
});
    
</script>
@endpush
