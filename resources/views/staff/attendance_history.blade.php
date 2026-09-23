<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Attendance Ledger - LaundryStaff Pro</title>
    
    <!-- Bootstrap 5 & FontAwesome Icons -->
    <link class="sub-link" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link class="sub-link" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .brand-gradient { background: linear-gradient(135deg, #4A154B 0%, #E91E63 100%) !important; color: white !important; }
        .text-brand { color: #4A154B !important; }
        .card-stats { border: none; border-radius: 12px; }
        .table-container { border-radius: 12px; overflow: hidden; background: white; }
    </style>
</head>
<body>

    <!-- Admin Only Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark brand-gradient shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                <i class="fa-solid fa-shield-halved me-2"></i>LaundryStaff Pro 
                <span class="fs-6 fw-normal text-white-50">| Administrative Terminal</span>
            </span>
            <div class="d-flex">
                <a href="/attendance" class="btn btn-sm btn-outline-light rounded-pill px-3 me-2">
                    <i class="fa-solid fa-calculator me-1"></i> Kiosk View
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light rounded-pill px-3 text-brand fw-semibold">
                    <i class="fa-solid fa-chart-pie me-1"></i> System Panel
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <!-- Status Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
                <i class="fa-solid fa-circle-check me-2 text-success"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-4">
            <h1 class="fw-bold text-dark mb-1">Master Attendance History</h1>
            <p class="text-muted">Review authenticated punch-ins, query tracking keywords, and delete record anomalies.</p>
        </div>

        <!-- Metric Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card card-stats shadow-sm p-3 bg-white border">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary-subtle text-primary rounded-3 me-3"><i class="fa-solid fa-list-check fa-xl"></i></div>
                        <div>
                            <h6 class="text-muted small mb-1">Total System Logs (Filtered)</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $logs->count() }} entries</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stats shadow-sm p-3 bg-white border">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success-subtle text-success rounded-3 me-3"><i class="fa-solid fa-clock-rotate-left fa-xl"></i></div>
                        <div>
                            <h6 class="text-muted small mb-1">Admin Access Verified</h6>
                            <h3 class="fw-bold mb-0 text-success" style="font-size: 1.25rem;">{{ auth()->user()->email }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Search Control -->
        <div class="card shadow-sm border-0 p-4 mb-4 bg-white rounded-3">
            <form action="{{ route('attendance.history') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light text-secondary border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search logs by Staff Name, Badge ID, or Email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn w-100 btn-dark fw-semibold shadow-sm" style="background-color: #4A154B; border: none;">Search</button>
                    @if(request()->has('search'))
                        <a href="{{ route('attendance.history') }}" class="btn btn-light border shadow-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Admin Master Data Table -->
        <div class="table-container shadow-sm border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Log ID</th>
                            <th>Staff Member</th>
                            <th>Identity Email</th>
                            <th>Timestamp</th>
                            <th class="text-end pe-4">System Actions</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#{{ $log->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-light rounded-circle text-center me-2 text-brand" style="width:32px; height:32px; line-height:16px;"><i class="fa-solid fa-user-tie"></i></div>
                                    <div class="fw-semibold text-dark">{{ $log->user->name ?? 'System Staff' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal">{{ $log->user->email ?? 'N/A' }}</span>
                            </td>
                           <td>
            <!-- Status Badge -->
            @if($log->status === 'Completed' || $log->clock_out !== null)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
            @else
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 animate-pulse">Active (Working)</span>
            @endif
        </td>
        <td>
            <!-- Clock In / Clock Out Waktu -->
            <div class="row g-0">
                <div class="col-6">
                    <span class="text-muted d-block small" style="font-size:10px;">CLOCKED IN</span>
                    <strong class="text-dark">
                        <i class="fa-solid fa-arrow-right text-success me-1"></i> 
                        {{ \Carbon\Carbon::parse($log->clock_in)->format('h:i A') }}
                    </strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block small" style="font-size:10px;">CLOCKED OUT</span>
                    @if($log->clock_out)
                        <strong class="text-dark">
                            <i class="fa-solid fa-arrow-left text-danger me-1"></i> 
                            {{ \Carbon\Carbon::parse($log->clock_out)->format('h:i A') }}
                        </strong>
                    @else
                        <span class="text-muted italic">--:--</span>
                    @endif
                </div>
            </div>
            <div class="text-muted mt-1" style="font-size: 11px;">
                <i class="fa-regular fa-calendar me-1"></i> 
                {{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}
            </div>
        </td>
        <td>
            <!-- Total Hours Worked -->
            @if($log->hours_worked !== null)
                <span class="fw-bold text-brand">{{ $log->hours_worked }} hours</span>
            @else
                <span class="text-muted small">Calculating...</span>
            @endif
        </td>
        <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
                
                <!-- Force Clock Out Button (Only appears if not clocked out yet) -->
                @if(!$log->clock_out)
                <form action="{{ route('attendance.clockout', $log->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to clock out this staff member now?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning fw-semibold px-3">
                        <i class="fa-solid fa-stopwatch me-1"></i> Force Clock Out
                    </button>
                </form>
                @endif
                            <td class="text-end pe-4">
                                <!-- Destructive Delete Option -->
                              <!-- Pastikan ia memanggil route('attendance.delete', $log->id) -->
<!-- GANTIKAN DENGAN KOD FORM STANDAR INI -->
<form action="{{ url('admin/attendance/purge/' . $log->id) }}" method="POST" onsubmit="return confirm('⚠️ WARNING: Are you sure you want to permanently delete this attendance record?');" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
        <i class="fa-solid fa-trash-can me-1"></i> Purge Record
    </button>
</form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                                No attendance records found matching that search sequence.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>