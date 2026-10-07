<x-app-layout>
    <!-- FACE-API.JS CDN SCRIPT -->
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

    <!-- MAIN HEADER -->
<x-slot name="header">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                Attendance Terminal
            </span>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Facial Biometric Attendance
            </h1>
            <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                Face recognition verification with real-time liveness detection.
            </p>
        </div>
        
        @auth
            @if(auth()->user()->email === 'admin@zaujati.com')
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 bg-white text-slate-700 font-semibold py-2 px-4 rounded-xl shadow-xs hover:bg-slate-50 border border-slate-200 text-xs transition">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i>
                    <span>Back to Dashboard</span>
                </a>
            @else
                <a href="/staff" class="inline-flex items-center justify-center gap-2 bg-white text-slate-700 font-semibold py-2 px-4 rounded-xl shadow-xs hover:bg-slate-50 border border-slate-200 text-xs transition">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i>
                    <span>Back to Dashboard</span>
                </a>
            @endif
        @endauth
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left & Center: Main Camera Stage -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2" style="color: #4A154B;">
                        📷 Staff Attendance Camera
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Please select your name, look at the camera, and wait for facial verification.</p>
                    
                    <!-- Staff Name Selection -->
<div class="mb-6 max-w-xs mx-auto">
    @php 
        // Retrieve active staff profile based on selectedStaffId
        $currentStaff = isset($selectedStaffId) ? $staffList->firstWhere('staff_id', $selectedStaffId) : null;
        
        // Prepare profile photo URL (supports Base64 Data URI & local assets)
        $staffImageUrl = '';
        if ($currentStaff && !empty($currentStaff->profile_picture)) {
            if (str_starts_with($currentStaff->profile_picture, 'data:image') || str_starts_with($currentStaff->profile_picture, 'http')) {
                $staffImageUrl = $currentStaff->profile_picture;
            } elseif (file_exists(public_path('uploads/staff/' . $currentStaff->profile_picture))) {
                $staffImageUrl = asset('uploads/staff/' . $currentStaff->profile_picture);
            }
        }
    @endphp

    <!-- Hidden input for reference image matching -->
    <input type="hidden" id="staff_id" value="{{ $selectedStaffId ?? '' }}" data-image="{{ $staffImageUrl }}">

    <!-- Clean Staff Identity Display Card -->
    <div class="max-w-xs mx-auto p-2 bg-purple-100 border border-purple-100 rounded-xl text-center shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-purple-600">👤 Staff Verification</p>
        <p class="text-lg font-bold text-gray-700 mt-0.5">
            @if($currentStaff)
                {{ $currentStaff->full_name }}
            @else
                <span class="text-red-500 text-sm">⚠️ No Staff Selected From Directory</span>
            @endif
        </p>
    </div>
</div>

                    <!-- Live Webcam Viewport Window -->
                    <div class="relative flex justify-center bg-black rounded-lg overflow-hidden border-4 border-purple-900" style="height: 350px; max-width: 450px; margin: 0 auto;">
                        <video id="webcam" autoplay muted playsinline class="w-full h-full object-cover"></video>
                        <!-- Canvas overlay for face bounding box -->
                        <canvas id="overlay" class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none"></canvas>
                    </div>

                    <!-- Action Punch Buttons (Locked until face matched) -->
                    <div class="mt-4 flex justify-center gap-4">
                        <button id="btn-clockin" onclick="processAttendance('clock-in')" disabled class="bg-gray-400 text-white font-bold py-2 px-6 rounded-lg shadow cursor-not-allowed opacity-50 transition">
                            🔒 Clock In
                        </button>
                        <button id="btn-clockout" onclick="processAttendance('clock-out')" disabled class="bg-gray-400 text-white font-bold py-2 px-6 rounded-lg shadow cursor-not-allowed opacity-50 transition">
                            🔒 Clock Out
                        </button>
                    </div>

                    <!-- Real-time Status Alert -->
                    <div id="scan-status" class="mt-4 p-2 text-sm font-semibold text-purple-700 bg-purple-50 rounded-lg animate-pulse">
                        ⏳ Loading AI Face Recognition Models... Please wait.
                    </div>
                </div>

                <!-- Right: Today's Attendance Logs -->
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-medium text-gray-900 mb-4" style="color: #4A154B;">
        📋 Today's Attendance Log
    </h3>
    <div class="space-y-3 overflow-y-auto" style="max-height: 450px;">
        @forelse($todayAttendance as $log)
            <div class="p-3 border rounded-lg flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition">
                <div>
                    <p class="font-semibold text-gray-800 text-sm uppercase">{{ $log->full_name }}</p>
                    <p class="text-xs text-gray-500">ID: {{ $log->staff_id }}</p>
                    <div class="mt-1 text-xs">
                        <span class="text-green-600 font-bold">In: {{ date('h:i A', strtotime($log->clock_in)) }}</span>
                        @if($log->clock_out)
                            <span class="text-red-600 font-bold ml-2">Out: {{ date('h:i A', strtotime($log->clock_out)) }}</span>
                        @else
                            <span class="text-gray-400 italic ml-2">Not Clocked Out</span>
                        @endif
                    </div>
                </div>
                
                <!-- Delete Button (Admin Only) -->
                {{-- Restriction: Delete action only available when in admin mode --}}
               @if(auth()->user()->email == 'admin@zaujati.com')
                    <div>
                        <button onclick="deleteAttendance('{{ $log->id }}')" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-full transition" title="Delete Log">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-2 text-gray-400" title="Locked">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center italic py-8">No staff clocked in today yet.</p>
        @endforelse
    </div>
</div>

            </div>
        </div>
    </div>

    <!-- JAVASCRIPT SCANNER LOGIC -->
    <script>
        const video = document.getElementById('webcam');
        const statusBox = document.getElementById('scan-status');
        const btnClockIn = document.getElementById('btn-clockin');
        const btnClockOut = document.getElementById('btn-clockout');
        
        let faceMatcher = null;
        let faceDetectionInterval = null;

        // 1. Initialize camera & reference face matching on load
        window.addEventListener('DOMContentLoaded', async () => {
            try {
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                
                statusBox.innerHTML = "✅ AI Models Loaded.";
                statusBox.className = "mt-4 p-2 text-sm font-semibold text-green-700 bg-green-50 rounded-lg";
                
                // Initialize Webcam
                await startCamera();

                // Retrieve ID & reference image from hidden input
                const staffInput = document.getElementById('staff_id');
                if (staffInput && staffInput.value !== "") {
                    const imageUrl = staffInput.getAttribute('data-image');
                    initiateFaceScanning(imageUrl);
                } else {
                    statusBox.innerHTML = "❌ No staff data provided from dashboard.";
                }

            } catch (error) {
                statusBox.innerHTML = "❌ Failed to load AI models. Check internet connection.";
                console.error("Model Load Error:", error);
            }
        });

        // 2. Start Webcam Media Stream
        function startCamera() {
            return new Promise((resolve, reject) => {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 485 } })
                        .then(function(stream) {
                            video.srcObject = stream;
                            resolve();
                        })
                        .catch(function(error) {
                            statusBox.innerHTML = "❌ Camera access denied. Please allow permissions.";
                            console.error("Camera Error: ", error);
                            reject(error);
                        });
                } else {
                    reject("Media devices not supported");
                }
            });
        }

        // 3. Process Reference Photo & Start Camera Loop
        async function initiateFaceScanning(imageUrl) {
            if (!imageUrl) {
                statusBox.innerHTML = "⚠️ No reference picture uploaded for this staff. Please contact Admin.";
                statusBox.className = "mt-4 p-2 text-sm font-semibold text-red-700 bg-red-50 rounded-lg";
                return;
            }

            statusBox.innerHTML = "⏳ Fetching and processing your reference photo...";
            statusBox.className = "mt-4 p-2 text-sm font-semibold text-yellow-700 bg-yellow-50 rounded-lg";

            try {
                const referenceImage = await faceapi.fetchImage(imageUrl);
                const referenceDetection = await faceapi.detectSingleFace(referenceImage).withFaceLandmarks().withFaceDescriptor();

                if (!referenceDetection) {
                    statusBox.innerHTML = "❌ Face could not be recognized on the reference photo! Inform admin.";
                    return;
                }

                faceMatcher = new faceapi.FaceMatcher(referenceDetection, 0.55);
                statusBox.innerHTML = "🔍 Looking for your face... Please look directly at the camera.";
                statusBox.className = "mt-4 p-2 text-sm font-semibold text-blue-700 bg-blue-50 rounded-lg";

                // Start live webcam matching loop
                startLiveFaceMatching();

            } catch (err) {
                statusBox.innerHTML = "❌ Error processing face reference.";
                console.error(err);
            }
        }

        // 4. Live Webcam Facial Comparison Loop
        function startLiveFaceMatching() {
            faceDetectionInterval = setInterval(async () => {
                if (!faceMatcher) return;

                const singleDetection = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();

                if (singleDetection) {
                    const match = faceMatcher.findBestMatch(singleDetection.descriptor);
                    
                    if (match.label !== 'unknown') {
                        statusBox.innerHTML = "🎉 Face Verified Successfully! You may Clock In / Out now.";
                        statusBox.className = "mt-4 p-2 text-sm font-semibold text-green-700 bg-green-100 rounded-lg";
                        
                        enableAttendanceButtons();
                        clearInterval(faceDetectionInterval); 
                    } else {
                        statusBox.innerHTML = "❌ Face Mismatch! Please ensure bright lighting and that your face is facing straight ahead.";
                        statusBox.className = "mt-4 p-2 text-sm font-semibold text-red-600 bg-red-50 rounded-lg";
                    }
                } else {
                    statusBox.innerHTML = "👤 No face detected in video frame. Position yourself properly.";
                }
            }, 1000);
        }

        // 5. Update Attendance Button States
        function enableAttendanceButtons() {
            btnClockIn.disabled = false;
            btnClockIn.className = "bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:scale-105";
            btnClockIn.innerHTML = "🔒 Clock In";

            btnClockOut.disabled = false;
            btnClockOut.className = "bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:scale-105";
            btnClockOut.innerHTML = "🔒 Clock Out";
        }

        function disableAttendanceButtons() {
            btnClockIn.disabled = true;
            btnClockIn.className = "bg-gray-400 text-white font-bold py-2 px-6 rounded-lg shadow cursor-not-allowed opacity-50";
            btnClockIn.innerHTML = "🔒 Clock In";

            btnClockOut.disabled = true;
            btnClockOut.className = "bg-gray-400 text-white font-bold py-2 px-6 rounded-lg shadow cursor-not-allowed opacity-50";
            btnClockOut.innerHTML = "🔒 Clock Out";
        }

        // 6. Handle AJAX Punch Submission to Laravel Controller
        async function processAttendance(type) {
            const staffId = document.getElementById('staff_id').value;
            if (!staffId) return;

            statusBox.innerHTML = "⏳ Securing and storing your attendance log to database...";
            const url = type === 'clock-in' ? "{{ route('staff.attendance.clockin') }}" : "{{ route('staff.attendance.clockout') }}";
            
            try {
                let response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ staff_id: staffId })
                });
                
                let result = await response.json();
                
                if(response.ok && result.status === 'success') {
                    alert(result.message);
                    window.location.reload(); 
                } else {
                    statusBox.innerHTML = "⚠️ " + (result.message || "Failed to process.");
                    alert(result.message || "Something went wrong.");
                }
            } catch (error) {
                statusBox.innerHTML = "❌ Network error. Check your server routes.";
                console.error(error);
            }
        }

        // 7. Delete Attendance Record
        async function deleteAttendance(id) {
            if (!confirm("Are you sure you want to delete this attendance record?")) {
                return;
            }

            try {
                let response = await fetch(`/attendance/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                let result = await response.json();

                if (response.ok && result.status === 'success') {
                    alert(result.message);
                    window.location.reload(); 
                } else {
                    alert(result.message || "Failed to delete record.");
                }
            } catch (error) {
                alert("Network error. Could not delete record.");
                console.error(error);
            }
        }
    </script>
</x-app-layout>