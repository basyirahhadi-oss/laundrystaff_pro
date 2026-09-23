<x-app-layout>
    <!-- Bootstrap 5 & FontAwesome Icons (Diperlukan untuk reka bentuk anda) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .kiosk-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .kiosk-card:hover { transform: translateY(-3px); }
        .table-container { border-radius: 12px; overflow: hidden; }
        .brand-color { background-color: #4A154B !important; color: white !important; }
    </style>

    <div class="container py-5">
        
        <!-- Notifikasi Mesej Sukses / Ralat -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- Header Section -->
        <div class="row align-items-center mb-5 text-start">
            <div class="col-md-8">
                <h1 class="fw-bold text-dark mb-1">Attendance Gateway</h1>
                <p class="text-muted mb-0">Authorized personnel terminal for daily biometric and check-in logs.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="p-3 bg-white shadow-sm rounded-3 d-inline-block border">
                    <i class="fa-regular fa-clock text-secondary me-2"></i>
                    <span class="fw-semibold text-dark" id="live-clock">{{ now()->format('d M Y | h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Side: Terminal Kiosk Form -->
            <div class="col-lg-4">
                <div class="card kiosk-card shadow-sm p-4 bg-white h-100 border">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-camera text-secondary me-2"></i>Terminal Kiosk</h5>
                    <p class="small text-muted">Enter Staff ID or scan barcode to initialize the Face Verification camera system.</p>
                    
                    <!-- Form menghantar Staff ID ke halaman scan -->
                    <form action="{{ route('staff.dashboard') }}" method="GET" class="mt-3">
                        <div class="mb-3">
                            <label for="staff_id" class="form-label small fw-semibold text-secondary">Staff Identification ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-id-card text-muted"></i></span>
                                <input type="text" name="staff_id" id="staff_id" class="form-control" placeholder="e.g., STF02" required autocomplete="off" autofocus>
                            </div>
                        </div>
                        <button type="submit" class="btn brand-color w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="fa-solid fa-expand me-2"></i>Launch Face Scan
                        </button>
                    </form>

                    <div class="text-center my-4 text-muted small position-relative">
                        <hr><span class="bg-white px-2 position-absolute top-50 start-50 translate-middle">OR</span>
                    </div>

                    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary w-100 py-2.5 rounded-3 btn-sm">
                        <i class="fa-solid fa-display me-2"></i>Open General Kiosk View
                    </a>
                </div>
            </div>

            <!-- Right Side: Active Directory Section -->
            <div class="col-lg-8">
                <div class="card kiosk-card shadow-sm p-4 bg-white h-100 border">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users text-secondary me-2"></i>Active Directory</h5>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                            {{ $staff->count() ?? 0 }} Registered Staff
                        </span>
                    </div>
                    
                    <div class="table-responsive table-container border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4">Staff ID</th>
                                    <th>Full Name</th>
                                    <th>Department / Position</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($staff as $s)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $s->staff_id }}</td>
                                    <td><div class="fw-semibold">{{ $s->full_name }}</div></td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1.5 fw-normal">{{ $s->position }}</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('staff.dashboard', ['staff_id' => $s->staff_id]) }}" class="btn btn-sm btn-link text-decoration-none p-0 text-primary fw-semibold">
                                            Select <i class="fa-solid fa-chevron-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No record registered.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        setInterval(() => {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            document.getElementById('live-clock').innerText = new Date().toLocaleString('en-US', options).replace(/,/g, ' |');
        }, 1000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>