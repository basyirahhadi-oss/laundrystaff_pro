<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Hub - LaundryStaff Pro</title>
    
    <!-- Bootstrap 5 & FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .kiosk-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .kiosk-card:hover { transform: translateY(-3px); }
        .table-container { border-radius: 12px; overflow: hidden; }
        .brand-color { background-color: #4A154B !important; color: white !important; }
    </style>
</head>
<body>

    <!-- Top Navigation Bar (Link back to Admin Dashboard) -->
    <nav class="navbar navbar-expand-lg navbar-dark brand-color shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fa-solid fa-shirt me-2"></i>LaundryStaff Pro</span>
            <div class="d-flex">
                <!-- 🎯 Butang untuk kembali ke Dashboard Utama Admin -->
                 @auth
                    @if(auth()->user()->email === 'admin@zaujati.com')
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa-solid fa-chart-pie me-1"></i> Control Panel
                        </a>
                    @else
                        <a href="/staff" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa-solid fa-house me-1"></i> Staff Hub
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <!-- Header Section with Real-time Context -->
        <div class="row align-items-center mb-5 text-start">
            <div class="col-md-8">
                <h1 class="fw-bold text-dark mb-1">Attendance Gateway</h1>
                <p class="text-muted mb-0">Authorized personnel terminal for daily biometric and check-in logs.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                     <div class="p-3 bg-white shadow-sm rounded-3 inline-block border">
                    <i class="fa-regular fa-clock text-secondary me-2"></i>
                    <span class="fw-semibold text-dark" id="live-clock">{{ now()->format('d M Y | h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Side: Dynamic Kiosk Entry -->
            <div class="col-lg-4">
                <div class="card kiosk-card shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-camera text-secondary me-2"></i>Terminal Kiosk</h5>
                    <p class="small text-muted">Enter Staff ID or scan barcode to initialize the Face Verification camera system.</p>
                    
                    <!-- Dynamic ID Scanner Input Form -->
                    <form action="{{ route('staff.attendance.index') }}" method="GET" class="mt-3">
                        <div class="mb-3">
                            <label for="staff_id" class="form-label small fw-semibold text-secondary">Staff Identification ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-id-card text-muted"></i></span>
                                <input type="text" name="staff_id" id="staff_id" class="form-control" placeholder="e.g., STF02" required autocomplete="off">
                            </div>
                        </div>
                        <button type="submit" class="btn brand-color w-100 py-2.5 rounded-3 fw-semibold">
                            <i class="fa-solid fa-expand me-2"></i>Launch Face Scan
                        </button>
                    </form>

                    <div class="hr-text text-center my-4 text-muted small position-relative">
                        <hr><span class="bg-white px-2 position-absolute top-50 start-50 translate-middle">OR</span>
                    </div>

                    <!-- Quick General Access Button (Fallback) -->
                    <a href="{{ route('staff.attendance.index') }}" class="btn btn-outline-secondary w-100 py-2.5 rounded-3 btn-sm">
                        <i class="fa-solid fa-display me-2"></i>Open General Kiosk View
                    </a>
                </div>
            </div>

            <!-- Right Side: Co-workers Directory Section -->
            <div class="col-lg-8">
                <div class="card kiosk-card shadow-sm p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users text-secondary me-2"></i>Active Directory</h5>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">{{ $staff->count() }} Registered Staff</span>
                    </div>
                    
                    <div class="table-responsive table-container border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small uppercase">
                                <tr>
                                    <th class="ps-4">Staff ID</th>
                                    <th>Full Name</th>
                                    <th>Department / Position</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach($staff as $s)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $s->staff_id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $s->full_name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1.5 fw-normal">{{ $s->position }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('staff.attendance.index', ['staff_id' => $s->staff_id]) }}" class="btn btn-sm btn-link text-decoration-none p-0 text-primary fw-semibold">
                                            Select <i class="fa-solid fa-chevron-right ms-1 fs-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Optional Real-time Clock Script -->
    <script>
        setInterval(() => {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            document.getElementById('live-clock').innerText = new Date().toLocaleString('en-US', options).replace(/,/g, ' |');
        }, 1000);
    </script>
</body>
</html>