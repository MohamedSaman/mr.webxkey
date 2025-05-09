<div>
    @push('styles')
    <style>
        .hover-card {
            transition: all 0.3s ease;
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            cursor: pointer;
        }
        
        .transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
    @endpush

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm mb-4 border-0 hover-card transition">
                    <div class="card-body text-white rounded p-3" style="background-color: #3498db;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-3 me-3">
                                    <i class="bi bi-people-fill fs-1" style="color: #3498db;"></i>
                                </div>
                                <div>
                                    <h6 class="text-uppercase text-white fw-bold mb-1">Total Users</h6>
                                    <h2 class="mb-0 fw-bolder">{{ $usersCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-4 border-0 hover-card transition">
                    <div class="card-body text-white rounded p-3" style="background-color: #3498db;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-3 me-3">
                                    <i class="bi bi-people-fill fs-1" style="color: #3498db;"></i>
                                </div>
                                <div>
                                    <h6 class="text-uppercase text-white fw-bold mb-1">Total Users</h6>
                                    <h2 class="mb-0 fw-bolder">{{ $usersCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-4 border-0 hover-card transition">
                    <div class="card-body text-white rounded p-3" style="background-color: #3498db;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-3 me-3">
                                    <i class="bi bi-people-fill fs-1" style="color: #3498db;"></i>
                                </div>
                                <div>
                                    <h6 class="text-uppercase text-white fw-bold mb-1">Total Users</h6>
                                    <h2 class="mb-0 fw-bolder">{{ $usersCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm mb-4 border-0 hover-card transition">
                    <div class="card-body text-white rounded p-3" style="background-color: #3498db;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-3 me-3">
                                    <i class="bi bi-people-fill fs-1" style="color: #3498db;"></i>
                                </div>
                                <div>
                                    <h6 class="text-uppercase text-white fw-bold mb-1">Total Users</h6>
                                    <h2 class="mb-0 fw-bolder">{{ $usersCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
