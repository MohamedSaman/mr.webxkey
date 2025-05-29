<div>
    <div class="container-fluid py-4">
        <!-- Stats Bar -->
        <div class="row mb-4">
            <!-- Pending Approvals Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card border-0 overflow-hidden shadow-sm hover-lift">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="p-4 flex-grow-1">
                                <h6 class="text-sm text-uppercase fw-bold text-muted mb-1">Pending Approvals</h6>
                                <div class="d-flex align-items-center">
                                    <h2 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Payment::where('status', 'pending')->count() }}
                                    </h2>
                                    <span class="badge bg-primary-subtle text-primary ms-2 py-1 px-2">Awaiting Review
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gradient-primary p-4 d-flex align-items-center">
                                <div class="icon-shape icon-lg bg-white bg-opacity-25 shadow-sm rounded-3 text-center">
                                    <i class="bi bi-hourglass-split text-white fs-4" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Progress bar indicator -->
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card border-0 overflow-hidden shadow-sm hover-lift">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="p-4 flex-grow-1">
                                <h6 class="text-sm text-uppercase fw-bold text-muted mb-1">Total Approved</h6>
                                <div class="d-flex align-items-center">
                                    <h2 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Payment::where('status', 'approved')->count() }}
                                    </h2>
                                    <span class="badge bg-success-subtle text-success ms-2 py-1 px-2">Completed</span>
                                </div>
                            </div>
                            <div class="bg-gradient-success p-4 d-flex align-items-center">
                                <div class="icon-shape icon-lg bg-white bg-opacity-25 shadow-sm rounded-3 text-center">
                                    <i class="bi bi-check-circle text-white fs-4" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Progress bar indicator -->
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 80%"></div>
                    </div>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card border-0 overflow-hidden shadow-sm hover-lift">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="p-4 flex-grow-1">
                                <h6 class="text-sm text-uppercase fw-bold text-muted mb-1">Rejected</h6>
                                <div class="d-flex align-items-center">
                                    <h2 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Payment::where('status', 'rejected')->count() }}
                                    </h2>
                                    <span class="badge bg-danger-subtle text-danger ms-2 py-1 px-2">Declined</span>
                                </div>
                            </div>
                            <div class="bg-gradient-danger p-4 d-flex align-items-center">
                                <div class="icon-shape icon-lg bg-white bg-opacity-25 shadow-sm rounded-3 text-center">
                                    <i class="bi bi-x-circle text-white fs-4" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Progress bar indicator -->
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-danger" style="width: 35%"></div>
                    </div>
                </div>
            </div>

            <!-- Today's Approvals Card -->
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 overflow-hidden shadow-sm hover-lift">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="p-4 flex-grow-1">
                                <h6 class="text-sm text-uppercase fw-bold text-muted mb-1">Today's Approvals</h6>
                                <div class="d-flex align-items-center">
                                    <h2 class="font-weight-bolder mb-0">
                                        {{ \App\Models\Payment::where('status', 'approved')->whereDate('updated_at', today())->count() }}
                                    </h2>
                                    <span class="badge bg-info-subtle text-info ms-2 py-1 px-2">Today</span>
                                </div>
                            </div>
                            <div class="bg-gradient-info p-4 d-flex align-items-center">
                                <div class="icon-shape icon-lg bg-white bg-opacity-25 shadow-sm rounded-3 text-center">
                                    <i class="bi bi-calendar-check text-white fs-4" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Progress bar indicator -->
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 50%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 p-3">
                    <div class="card-header pb-2 bg-light">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h5 class="mb-0">Payment Approvals</h5>
                                {{-- <p class="text-sm mb-0 text-muted">Manage customer payment requests</p> --}}
                            </div>
                            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto">
                                <!-- Search bar - responsive width -->
                                <div class="input-group input-group-merge border rounded-pill bg-white shadow-sm flex-grow-1 flex-md-grow-0"
                                    style="min-width: 200px; max-width: 100%;">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="bi bi-search text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 py-2"
                                        placeholder="Search invoices or customers..."
                                        wire:model.live.debounce.300ms="search">
                                </div>

                                <!-- Filter dropdown - takes full width on xs screens -->
                                <div class="dropdown w-100 w-sm-auto">
                                    <button class="btn btn-outline-primary dropdown-toggle shadow-sm rounded-pill w-100"
                                        type="button" id="filterDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-funnel me-1"></i> Filters
                                        @if ($filters['status'] || $filters['dateRange'])
                                            <span class="badge bg-primary ms-1">!</span>
                                        @endif
                                    </button>
                                    <div class="dropdown-menu p-3 shadow-lg dropdown-menu-end" style="width: 300px;"
                                        aria-labelledby="filterDropdown">
                                        <h6 class="dropdown-header bg-light rounded-3 py-2 mb-3 text-center">Filter
                                            Options</h6>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Payment Status</label>
                                            <select class="form-select form-select-sm rounded-pill"
                                                wire:model.live="filters.status">
                                                <option value="pending">Pending Approval</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="">All Statuses</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Date Range</label>
                                            <input type="text" class="form-control form-control-sm rounded-pill"
                                                placeholder="Select date range" wire:model.live="filters.dateRange">
                                        </div>

                                        <div class="d-grid">
                                            <button class="btn btn-sm btn-secondary rounded-pill"
                                                wire:click="resetFilters">
                                                <i class="bi bi-arrow-repeat me-1"></i> Reset Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Invoice</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Customer</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Staff</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Amount</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Payment Method</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div
                                                        class="icon-shape bg-gradient-primary text-white icon-sm me-3 shadow rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-receipt"></i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $payment->sale->invoice_number }}
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $payment->created_at->format('d M Y H:i') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar avatar-sm rounded-circle bg-gradient-dark text-white me-2 d-flex align-items-center justify-content-center">
                                                        <span>{{ substr($payment->sale->customer->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $payment->sale->customer->name }}</p>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $payment->sale->customer->phone }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar avatar-sm rounded-circle bg-light text-dark me-2 d-flex align-items-center justify-content-center">
                                                        <span>{{ substr($payment->sale->user->name, 0, 1) }}</span>
                                                    </div>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $payment->sale->user->name }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="icon-shape icon-xs bg-success-subtle text-success rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                       <i class="bi bi text-success">Rs.</i>
                                                    </div>
                                                    <span class="text-sm font-weight-bold">
                                                        {{ number_format($payment->amount, 2) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->due_payment_method ?: $payment->payment_method)) }}
                                                </span>
                                            </td>
                                            <td>
                                                {!! $payment->status_badge !!}
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary rounded-pill shadow-sm"
                                                    wire:click="getPaymentDetails({{ $payment->id }})">
                                                    <i class="bi bi-eye me-1"></i> Review
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-secondary">
                                                <div
                                                    class="icon-shape icon-xl bg-light text-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-inbox-fill fs-1"></i>
                                                </div>
                                                <h5>No payments found</h5>
                                                <p class="text-muted">No payments matching your criteria were found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="px-4 pt-4">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Approval Modal -->
    <div wire:ignore.self class="modal fade" id="payment-approval-modal" tabindex="-1" role="dialog"
        aria-labelledby="payment-approval-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="payment-approval-modal-label">
                        <i class="bi bi-shield-check me-2"></i> Payment Review
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @if ($selectedPayment)
                        <div class="row g-0">
                            <!-- Left column - Details -->
                            <div class="col-md-8 p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                            <div class="card-header bg-light py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-primary text-white rounded-circle me-2">
                                                        <i class="bi bi-file-text"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-bold">Invoice Details</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-muted">Invoice Number:</span>
                                                    <span
                                                        class="badge bg-primary-subtle text-primary">{{ $selectedPayment->sale->invoice_number }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-muted">Sale Date:</span>
                                                    <span>{{ $selectedPayment->sale->created_at->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-muted">Due Date:</span>
                                                    <span
                                                        class="{{ now()->gt($selectedPayment->due_date) ? 'text-danger fw-bold' : '' }}">
                                                        {{ $selectedPayment->due_date ? $selectedPayment->due_date->format('d/m/Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-0">
                                                    <span class="text-muted">Due Amount:</span>
                                                    <span
                                                        class="fs-5 fw-bold text-primary">Rs.{{ number_format($selectedPayment->amount, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Customer & Staff -->
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-light py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-info text-white rounded-circle me-2">
                                                        <i class="bi bi-people"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-bold">Customer & Staff</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex mb-3">
                                                    <div
                                                        class="avatar avatar-md rounded-circle bg-gradient-dark text-white me-3 d-flex align-items-center justify-content-center">
                                                        <span
                                                            class="fs-5">{{ substr($selectedPayment->sale->customer->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">
                                                            {{ $selectedPayment->sale->customer->name }}</h6>
                                                        <p class="text-muted mb-0">
                                                            {{ $selectedPayment->sale->customer->phone }}</p>
                                                        <span
                                                            class="badge bg-light text-dark mt-1">{{ ucfirst($selectedPayment->sale->customer_type) }}
                                                            Customer</span>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="d-flex">
                                                    <div
                                                        class="avatar avatar-md rounded-circle bg-light text-dark me-3 d-flex align-items-center justify-content-center">
                                                        <span
                                                            class="fs-5">{{ substr($selectedPayment->sale->user->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $selectedPayment->sale->user->name }}
                                                        </h6>
                                                        <p class="text-muted mb-0">Sales Staff</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Payment Method -->
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-light py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-success text-white rounded-circle me-2">
                                                        <i class="bi bi-credit-card"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-bold">Payment Method</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-muted">Method:</span>
                                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                                        {{ ucfirst(str_replace('_', ' ', $selectedPayment->due_payment_method ?: $selectedPayment->payment_method)) }}
                                                    </span>
                                                </div>

                                                @if ($selectedPayment->bank_name)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-3">
                                                        <span class="text-muted">Bank:</span>
                                                        <span>{{ $selectedPayment->bank_name }}</span>
                                                    </div>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-muted">Status:</span>
                                                    <div>{!! $selectedPayment->status_badge !!}</div>
                                                </div>

                                                @if ($selectedPayment->payment_date)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-0">
                                                        <span class="text-muted">Payment Date:</span>
                                                        <span>{{ $selectedPayment->payment_date->format('d M Y, h:i A') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        @if ($selectedPayment->sale->notes)
                                            <div class="card border-0 shadow-sm mb-4">
                                                <div class="card-header bg-light py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="icon-shape bg-warning text-white rounded-circle me-2">
                                                            <i class="bi bi-sticky"></i>
                                                        </div>
                                                        <h6 class="mb-0 fw-bold">Notes</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="alert alert-light mb-0 p-3">
                                                        {{ $selectedPayment->sale->notes }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action buttons for pending payments -->
                                @if ($selectedPayment->status === 'pending')
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Payment Review Actions</h6>

                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-success px-4"
                                                    wire:click="approvePayment">
                                                    <i class="bi bi-check-circle-fill me-2"></i> Approve Payment
                                                </button>

                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="collapse" href="#rejectPaymentForm"
                                                    role="button" aria-expanded="false">
                                                    <i class="bi bi-x-circle me-2"></i> Reject Payment
                                                </button>
                                            </div>

                                            <div class="collapse mt-3" id="rejectPaymentForm">
                                                <div class="card border border-danger">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-danger d-flex align-items-center">
                                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                            Reject Payment
                                                        </h6>

                                                        <div class="alert alert-info mb-3">
                                                            <i class="bi bi-info-circle-fill me-2"></i>
                                                            Rejecting this payment will automatically extend the due date by 3 days from
                                                            <strong>{{ $selectedPayment->due_date ? $selectedPayment->due_date->format('d M Y') : 'N/A' }}</strong> to
                                                            <strong>{{ $selectedPayment->due_date ? $selectedPayment->due_date->copy()->addDays(3)->format('d M Y') : 'N/A' }}</strong>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="rejectionReason" class="form-label">Rejection Reason</label>
                                                            <textarea class="form-control @error('rejectionReason') is-invalid @enderror" id="rejectionReason" rows="3"
                                                                wire:model="rejectionReason" placeholder="Please provide a detailed reason for rejecting this payment"></textarea>
                                                            @error('rejectionReason')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <button type="button" class="btn btn-danger"
                                                            wire:click="rejectPayment">Confirm Rejection & Extend Due Date</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="alert alert-{{ $selectedPayment->status === 'approved' ? 'success' : 'warning' }} d-flex align-items-center">
                                        <i
                                            class="bi bi-{{ $selectedPayment->status === 'approved' ? 'check-circle' : 'info-circle' }}-fill me-2 fs-4"></i>
                                        <div>
                                            @if ($selectedPayment->status === 'approved')
                                                This payment was approved on
                                                {{ $selectedPayment->payment_date->format('d M Y, h:i A') }}.
                                            @elseif($selectedPayment->status === 'rejected')
                                                This payment was rejected. The staff member has been notified.
                                            @else
                                                This payment is awaiting staff action.
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Right column - Document -->
                           <div class="col-md-4 bg-light p-4 border-start">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-dark text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-white">Payment Document</h6>
                @if ($selectedPayment->due_payment_attachment)
                    @php
                        $extension = pathinfo($selectedPayment->due_payment_attachment, PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                        $fileUrl = asset('public/storage/' . str_replace('public/', '', $selectedPayment->due_payment_attachment));
                    @endphp
                    @if (!$isPdf)
                        <button type="button" class="btn btn-sm btn-light" onclick="openFullImage('{{ $fileUrl }}')">
                            <i class="bi bi-fullscreen"></i>
                        </button>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if ($selectedPayment->due_payment_attachment)
                @if ($isPdf)
                    <div class="text-center py-5 bg-light">
                        <div class="icon-shape icon-xl bg-danger bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                        </div>
                        <h5 class="mb-3">PDF Document</h5>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i> View PDF
                            </a>
                            <a href="{{ $fileUrl }}" download class="btn btn-outline-secondary">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                        <div class="alert alert-light mb-0">
                            <i class="bi bi-info-circle me-2"></i> PDF will open in a new tab
                        </div>
                    </div>
                @else
                    <div class="document-preview">
                        <img src="{{ $fileUrl }}" class="img-fluid w-100" style="max-height: 500px; object-fit: contain;">
                    </div>
                    <div class="bg-white p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">Image Document</span>
                            <div>
                                <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="icon-shape icon-xl bg-secondary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-file-earmark-x fs-1 text-secondary"></i>
                    </div>
                    <h5 class="text-muted">No Document Attached</h5>
                    <p class="text-muted mb-0">No payment proof was provided</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Purchase Information -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-gradient-info text-white py-3">
            <h6 class="mb-0 fw-bold text-white">Purchase Details</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-xs text-uppercase text-muted fw-normal ps-3">Item</th>
                            <th class="text-xs text-uppercase text-muted fw-normal">Qty</th>
                            <th class="text-xs text-uppercase text-muted fw-normal text-end pe-3">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedPayment->sale->items as $item)
                            <tr>
                                <td class="ps-3">
                                    @if ($item->watch)
                                        <p class="text-sm mb-0">
                                            {{ $item->watch->brand ?? 'Unknown Brand' }}
                                            {{ $item->watch->model ?? 'Unknown Model' }}
                                        </p>
                                        <span class="text-xs text-muted">{{ $item->watch->code ?? 'N/A' }}</span>
                                    @else
                                        <p class="text-sm mb-0">
                                            {{ $item->watch_name ?? 'Watch Details Unavailable' }}
                                        </p>
                                        <span class="text-xs text-danger">Item record may have been deleted</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $item->quantity }}</td>
                                <td class="text-sm text-end pe-3">Rs.{{ number_format($item->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold text-sm">Total:</td>
                            <td class="text-end pe-3 fw-bold text-primary text-sm">
                                Rs.{{ number_format($selectedPayment->sale->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading payment details...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Add these styles if not already present */
            .hover-lift {
                transition: all 0.3s ease;
            }

            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1)!important;
            }

            .icon-shape {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bg-opacity-25 {
                opacity: 0.25;
            }

            .bg-primary-subtle {
                background-color: rgba(var(--bs-primary-rgb), 0.1);
            }

            .bg-success-subtle {
                background-color: rgba(var(--bs-success-rgb), 0.1);
            }

            .bg-danger-subtle {
                background-color: rgba(var(--bs-danger-rgb), 0.1);
            }

            .bg-info-subtle {
                background-color: rgba(var(--bs-info-rgb), 0.1);
            }

            /* Gradient backgrounds */
            .bg-gradient-primary {
                background: linear-gradient(45deg, #4e73df, #224abe);
            }

            .bg-gradient-success {
                background: linear-gradient(45deg, #1cc88a, #13855c);
            }

            .bg-gradient-danger {
                background: linear-gradient(45deg, #e74a3b, #a62c1f);
            }

            .bg-gradient-info {
                background: linear-gradient(45deg, #36b9cc, #258391);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Helper function to handle modal operations safely
                const handleModal = (modalId, action) => {
                    const modalElement = document.getElementById(modalId);
                    
                    // First, check if a modal instance already exists and dispose it
                    const existingModal = bootstrap.Modal.getInstance(modalElement);
                    if (existingModal) {
                        existingModal.dispose();
                    }
                    
                    if (action === 'show') {
                        // Create a fresh modal instance and show it
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        
                        // Add event listener for when modal is hidden (manually closed)
                        modalElement.addEventListener('hidden.bs.modal', function() {
                            // Clean up the modal instance when it's closed
                            const modalToDispose = bootstrap.Modal.getInstance(modalElement);
                            if (modalToDispose) {
                                modalToDispose.dispose();
                            }
                        }, { once: true }); // Use once:true so we don't accumulate listeners
                    }
                };

                @this.on('openModal', (modalId) => {
                    handleModal(modalId, 'show');
                });

                @this.on('closeModal', (modalId) => {
                    const modalElement = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                });

                @this.on('showToast', ({
                    type,
                    message
                }) => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: type,
                        title: message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            });

            function openFullImage(imageUrl) {
                console.log('Opening image:', imageUrl); 
                Swal.fire({
                    imageUrl: imageUrl,
                    imageAlt: 'Payment Receipt',
                    width: '90%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            }
        </script>
    @endpush
</div>
