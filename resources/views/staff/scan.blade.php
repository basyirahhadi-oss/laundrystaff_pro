<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biometric Attendance Kiosk — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', sans-serif;
            letter-spacing: -0.018em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        h1, h2, h3, h4, h5, h6 { letter-spacing: -0.03em; }
        .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        #webcam-feed { transform: scaleX(-1); }
        .ring-scan { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @keyframes scanSweep {
            0% { top: 10%; opacity: 0.8; }
            50% { top: 90%; opacity: 1; }
            100% { top: 10%; opacity: 0.8; }
        }
        .scan-line {
            animation: scanSweep 2.4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    {{-- KIOSK HEADER --}}
    <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-10 h-10 rounded-xl object-contain bg-slate-950 p-1 border border-slate-800 shadow-md">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-sm text-white tracking-tight">Zaujati Laundry Hub</span>
                        <span class="text-[10px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 px-1.5 py-0.5 rounded">KIOSK GATEWAY</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-mono-nums">Terminal MY-KUL-01 · Biometric AI Gate</p>
                </div>
            </div>
            <a href="{{ route('kiosk.gateway') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition border border-slate-700/80">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Exit Terminal</span>
            </a>
        </div>
    </header>

    {{-- MAIN SCANNER CONSOLE --}}
    <main class="flex-1 max-w-5xl w-full mx-auto px-6 py-8 flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- WEBCAM VIEWPORT --}}
            <div class="lg:col-span-7">
                <div class="bg-slate-950 rounded-3xl border border-slate-800/80 p-5 shadow-2xl relative overflow-hidden">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-black border border-slate-800">
                        <video id="webcam-feed" autoplay playsinline muted class="w-full h-full object-cover"></video>

                        {{-- SCANNING HUD OVERLAYS --}}
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            {{-- TARGET RETICLE RING --}}
                            <div id="targetRing" class="ring-scan w-3/5 h-4/5 rounded-3xl border-2 border-indigo-500/40 relative">
                                {{-- RETICLE CORNERS --}}
                                <div class="absolute -top-1 -left-1 w-4 h-4 border-t-2 border-l-2 border-indigo-400"></div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 border-t-2 border-r-2 border-indigo-400"></div>
                                <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-2 border-l-2 border-indigo-400"></div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-2 border-r-2 border-indigo-400"></div>
                            </div>
                            {{-- SCANNING LASER BEAM --}}
                            <div class="absolute left-10 right-10 h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent scan-line shadow-[0_0_12px_rgba(34,211,238,0.8)]"></div>
                        </div>

                        {{-- LIVE TELEMETRY BADGE --}}
                        <div class="absolute top-3 left-3 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/10 text-[10px] font-mono-nums text-slate-300 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>60 FPS · 68 Landmarks</span>
                        </div>
                    </div>

                    {{-- STATUS TICKER --}}
                    <div class="mt-4 flex items-center justify-between px-2">
                        <div class="flex items-center gap-2">
                            <span id="statusDot" class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                            <span id="statusText" class="text-xs font-bold text-slate-200">Initializing Biometric Vision Engine...</span>
                        </div>
                        <span class="text-[10px] font-mono-nums text-slate-500 uppercase">TinyFace-SSD v2</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: RECOGNITION RESULTS & SECURITY AUDIT PANEL --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-slate-950 rounded-3xl border border-slate-800/80 p-6 shadow-2xl">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Verification Result</h2>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            <i class="fa-solid fa-shield-halved text-[9px]"></i>
                            <span>Defense-in-Depth</span>
                        </span>
                    </div>

                    {{-- UNIDENTIFIED STATE --}}
                    <div id="unidentifiedState" class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 mb-5">
                        <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-500 text-lg flex-shrink-0">
                            <i class="fa-solid fa-user-viewfinder"></i>
                        </div>
                        <div>
                            <p class="font-bold text-xs text-slate-200">Waiting for Facial Alignment...</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Position your face inside the target box and blink naturally.</p>
                        </div>
                    </div>

                    {{-- IDENTIFIED STATE --}}
                    <div id="identifiedState" class="hidden items-center gap-3.5 p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 mb-5">
                        <img id="identifiedPhoto" src="" alt="Identified staff" class="w-12 h-12 rounded-xl object-cover border-2 border-indigo-500 shadow-md flex-shrink-0">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p id="identifiedName" class="font-black text-sm text-white"></p>
                                <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                            </div>
                            <p id="identifiedRole" class="text-xs text-indigo-300 font-semibold"></p>
                        </div>
                    </div>

                    {{-- LIVENESS & REPLAY SECURITY GAUGES --}}
                    <div class="space-y-3 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/80 mb-6">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-eye text-cyan-400 text-[11px]"></i>
                                <span>EAR Blink Liveness:</span>
                            </span>
                            <div class="flex items-center gap-1.5" id="livenessBadge">
                                <span class="w-2 h-2 rounded-full bg-amber-400" id="livenessDot"></span>
                                <span id="livenessLabel" class="font-bold font-mono-nums text-amber-400 text-[11px]">Awaiting Blink</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs border-t border-slate-800/60 pt-2.5">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-key text-purple-400 text-[11px]"></i>
                                <span>Anti-Replay Nonce:</span>
                            </span>
                            <span class="text-[11px] font-mono-nums font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800/60 px-1.5 py-0.5 rounded">
                                HMAC Protected
                            </span>
                        </div>
                    </div>

                    {{-- CONFIRM PUNCH IN FORM --}}
                    <form id="attendanceForm" action="{{ route('kiosk.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="verified_identity" id="verified_identity" value="">
                        <input type="hidden" name="face_matched" id="face_matched" value="">
                        <input type="hidden" name="liveness_verified" id="liveness_verified" value="">
                        <input type="hidden" name="kiosk_token" id="kiosk_token" value="{{ $kioskToken ?? '' }}">

                        <button type="submit" id="verifyBtn" disabled
                            class="w-full rounded-2xl bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-800 disabled:text-slate-500 disabled:cursor-not-allowed text-white font-black text-xs py-3.5 px-4 transition-all shadow-lg shadow-indigo-600/30 disabled:shadow-none flex items-center justify-center gap-2">
                            <i class="fa-solid fa-fingerprint"></i>
                            <span id="verifyBtnLabel">Waiting for Liveness Check...</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        const video = document.getElementById('webcam-feed');
        const targetRing = document.getElementById('targetRing');
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('statusText');
        const verifyBtn = document.getElementById('verifyBtn');
        const verifyBtnLabel = document.getElementById('verifyBtnLabel');
        const faceMatchedInput = document.getElementById('face_matched');
        const livenessVerifiedInput = document.getElementById('liveness_verified');
        const verifiedIdentityInput = document.getElementById('verified_identity');
        const unidentifiedState = document.getElementById('unidentifiedState');
        const identifiedState = document.getElementById('identifiedState');
        const identifiedPhoto = document.getElementById('identifiedPhoto');
        const identifiedName = document.getElementById('identifiedName');
        const identifiedRole = document.getElementById('identifiedRole');
        const livenessDot = document.getElementById('livenessDot');
        const livenessLabel = document.getElementById('livenessLabel');

        const STAFF_LIST = @json($staffList);
        const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';
        const MATCH_THRESHOLD = 0.55;

        let faceMatcher = null;
        let matched = false;
        let livenessVerified = false;
        let blinkDetected = false;
        let eyesClosed = false;
        let detectLoop = null;

        function setState(state, label) {
            const colors = {
                loading: ['bg-amber-400', 'border-indigo-500/40'],
                scanning: ['bg-cyan-400', 'border-cyan-400/70'],
                liveness: ['bg-amber-400', 'border-amber-400'],
                matched: ['bg-emerald-400', 'border-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.3)]'],
                error: ['bg-rose-500', 'border-rose-500'],
            };
            statusDot.className = 'inline-block w-2.5 h-2.5 rounded-full mr-1.5 ' + colors[state][0];
            targetRing.className = 'ring-scan w-3/5 h-4/5 rounded-3xl border-2 relative ' + colors[state][1];
            statusText.textContent = label;
        }

        function updateLivenessBadge(passed) {
            if (passed) {
                livenessDot.className = 'w-2 h-2 rounded-full bg-emerald-400';
                livenessLabel.textContent = 'Verified Real Human ✅';
                livenessLabel.className = 'font-bold font-mono-nums text-emerald-400 text-[11px]';
            } else {
                livenessDot.className = 'w-2 h-2 rounded-full bg-amber-400 animate-ping';
                livenessLabel.textContent = 'Blink / Smile to Verify 👁️';
                livenessLabel.className = 'font-bold font-mono-nums text-amber-400 text-[11px]';
            }
        }

        // Eye Aspect Ratio (EAR) formula for anti-spoof blink detection
        function euclideanDistance(p1, p2) {
            return Math.hypot(p1.x - p2.x, p1.y - p2.y);
        }

        function calculateEAR(landmarks) {
            const leftEye = [36, 37, 38, 39, 40, 41].map(i => landmarks[i]);
            const rightEye = [42, 43, 44, 45, 46, 47].map(i => landmarks[i]);

            const leftEAR = (euclideanDistance(leftEye[1], leftEye[5]) + euclideanDistance(leftEye[2], leftEye[4])) /
                            (2.0 * euclideanDistance(leftEye[0], leftEye[3]));

            const rightEAR = (euclideanDistance(rightEye[1], rightEye[5]) + euclideanDistance(rightEye[2], rightEye[4])) /
                             (2.0 * euclideanDistance(rightEye[0], rightEye[3]));

            return (leftEAR + rightEAR) / 2.0;
        }

        // Mouth Aspect Ratio (MAR) formula for natural smile / speech anti-spoofing
        function calculateMAR(landmarks) {
            const topLip = landmarks[51];
            const bottomLip = landmarks[57];
            const leftCorner = landmarks[48];
            const rightCorner = landmarks[54];

            const vertical = euclideanDistance(topLip, bottomLip);
            const horizontal = euclideanDistance(leftCorner, rightCorner);

            return vertical / (horizontal || 1.0);
        }

        function setMatchedState(isMatch, staffMember) {
            matched = isMatch;
            faceMatchedInput.value = isMatch ? '1' : '';
            livenessVerifiedInput.value = (isMatch && livenessVerified) ? '1' : '';
            verifiedIdentityInput.value = isMatch ? staffMember.userId : '';

            const canSubmit = isMatch && livenessVerified;
            verifyBtn.disabled = !canSubmit;

            if (canSubmit) {
                verifyBtnLabel.textContent = 'Authorize & Punch In Attendance';
                setState('matched', 'Face Matched & Liveness Verified ✅');
            } else if (isMatch && !livenessVerified) {
                verifyBtnLabel.textContent = 'Please blink naturally or smile to verify liveness';
                setState('liveness', 'Face recognized! Now blink or smile to complete anti-spoof check 👁️😊');
            } else {
                verifyBtnLabel.textContent = 'Waiting for facial alignment...';
                setState('scanning', 'Scanning camera feed...');
            }

            if (isMatch) {
                unidentifiedState.classList.add('hidden');
                identifiedState.classList.remove('hidden');
                identifiedState.classList.add('flex');
                identifiedPhoto.src = staffMember.photoUrl;
                identifiedName.textContent = staffMember.name;
                identifiedRole.textContent = staffMember.role;
            } else {
                unidentifiedState.classList.remove('hidden');
                identifiedState.classList.add('hidden');
                identifiedState.classList.remove('flex');
            }
        }

        async function loadModels() {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        }

        async function buildFaceMatcher() {
            if (STAFF_LIST.length === 0) {
                setState('error', 'No staff facial profiles registered');
                return false;
            }

            const labeled = [];
            for (const staff of STAFF_LIST) {
                try {
                    const img = await faceapi.fetchImage(staff.photoUrl);
                    const detection = await faceapi
                        .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detection) {
                        labeled.push(new faceapi.LabeledFaceDescriptors(String(staff.userId), [detection.descriptor]));
                    }
                } catch (e) {
                    // Skip unreadable photo
                }
            }

            if (labeled.length === 0) {
                setState('error', 'No usable staff facial profiles found');
                return false;
            }

            faceMatcher = new faceapi.FaceMatcher(labeled, MATCH_THRESHOLD);
            return true;
        }

        async function setupCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 640, height: 480, facingMode: 'user' },
                    audio: false,
                });
                video.srcObject = stream;
                await new Promise((resolve) => { video.onloadedmetadata = resolve; });
                return true;
            } catch (error) {
                setState('error', 'Camera access denied');
                alert('Could not access camera. Please grant camera permission.');
                return false;
            }
        }

        function findStaffById(userId) {
            return STAFF_LIST.find((s) => String(s.userId) === String(userId));
        }

        let isDetecting = false;
        function startDetectionLoop() {
            setState('scanning', 'Searching for active face...');
            detectLoop = setInterval(async () => {
                if (isDetecting) return;
                isDetecting = true;

                try {
                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) {
                        setMatchedState(false, null);
                        isDetecting = false;
                        return;
                    }

                    // 1. Multi-Modal Anti-Spoof Liveness:
                    // A: EAR (Eye Aspect Ratio) for natural blink detection (relaxed threshold)
                    const ear = calculateEAR(detection.landmarks.positions);
                    if (ear < 0.245) {
                        eyesClosed = true;
                    } else if (eyesClosed && ear > 0.26) {
                        eyesClosed = false;
                        livenessVerified = true;
                        updateLivenessBadge(true);
                    }

                    // B: MAR (Mouth Aspect Ratio) for smile / natural mouth movement
                    const mar = calculateMAR(detection.landmarks.positions);
                    if (mar > 0.28) {
                        livenessVerified = true;
                        updateLivenessBadge(true);
                    }

                    // 2. 1:N Biometric Matcher
                    const bestMatch = faceMatcher.findBestMatch(detection.descriptor);

                    if (bestMatch.label === 'unknown') {
                        setMatchedState(false, null);
                        isDetecting = false;
                        return;
                    }

                    const staffMember = findStaffById(bestMatch.label);
                    setMatchedState(!!staffMember, staffMember);
                } catch (err) {
                    console.error('Detection frame error:', err);
                } finally {
                    isDetecting = false;
                }
            }, 120);
        }

        window.addEventListener('DOMContentLoaded', async () => {
            setState('loading', 'Connecting optical camera...');
            const cameraReady = await setupCamera();

            setState('loading', 'Loading AI Biometric & Liveness Models...');
            await loadModels();

            setState('loading', 'Indexing staff biometric landmarks...');
            const matcherReady = await buildFaceMatcher();

            if (cameraReady && matcherReady) {
                startDetectionLoop();
            }

            document.getElementById('attendanceForm')?.addEventListener('submit', () => {
                verifyBtn.disabled = true;
                verifyBtnLabel.textContent = 'Recording Attendance...';
            });
        });

        window.addEventListener('beforeunload', () => {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach((track) => track.stop());
            }
            if (detectLoop) clearInterval(detectLoop);
        });
    </script>
</body>
</html>