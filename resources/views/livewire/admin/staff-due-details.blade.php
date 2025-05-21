<div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Staff Payment Collection Details</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Total Sales</th>
                                <th>Collected Amount</th>
                                <th>Due Amount</th>
                                <th>Collection Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffDues as $index => $staff)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->contact }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            ₹{{ number_format($staff->total_amount, 2) }}</td>
                                        </span>
                                       
                                    <td>
                                        <span class="badge bg-success">
                                            ₹{{ number_format($staff->collected_amount, 2) }}</td>
                                        </span>
                                        
                                    <td>
                                        <span class="badge bg-{{ $staff->due_amount > 0 ? 'danger' : 'success' }}">
                                            ₹{{ number_format($staff->due_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $percentage =
                                                $staff->total_amount > 0
                                                    ? round(($staff->collected_amount / $staff->total_amount) * 100)
                                                    : 100;
                                            $progressClass = match (true) {
                                                $percentage >= 90 => 'success',
                                                $percentage >= 70 => 'info',
                                                $percentage >= 50 => 'primary',
                                                $percentage >= 30 => 'warning',
                                                default => 'danger',
                                            };
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $progressClass }}" role="progressbar"
                                                    style="width: {{ $percentage }}%;"
                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ms-2">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No staff due records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $staffDues->links('livewire.custom-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
