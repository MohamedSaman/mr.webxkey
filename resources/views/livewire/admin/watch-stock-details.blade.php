<div>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light">
                <h4 class="card-title mb-2 mb-md-0">Watch Stock Details</h4>
                <div class="card-tools">
                    {{-- <button class="btn btn-primary" wire:click="createBrand">
                        <i class="bi bi-plus-circle me-1"></i> Create Watch Brand
                    </button> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Image</th>
                            <th class="text-center"> Name</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Sold Qty</th>
                            <th class="text-center">Available Qty</th>
                            <th class="text-center">Damage Qty</th>
                            <th class="text-center">Total Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($watchStocks->count() > 0)
                            @foreach ($watchStocks as $watchStock)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('public/storage/' . $watchStock->watch_image) }}" alt="Watch Image"
                                            class="img-fluid" style="width: 50px; height: 50px;">
                                    </td>
                                    <td class="text-center">{{ $watchStock->watch_name ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->watch_code ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->watch_brand ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->watch_model ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->sold_count ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->available_stock ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->damage_stock ?? '-' }}</td>
                                    <td class="text-center">{{ $watchStock->total_stock ?? '-' }}</td>
                                    {{-- <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editBrand({{ $brand->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $brand->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    {{-- <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-2"
                                            wire:click="editBrand({{ $brand->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $brand->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class="text-center">
                                <div class="alert alert-primary bg-opacity-10 my-2">
                                    <i class="bi bi-info-circle me-2"></i> No watches Brands found.
                                </div>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div>

