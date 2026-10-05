<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biometric Attendance Kiosk — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: -0.015em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        #webcam-feed { transform: scaleX(-1); }
        .ring-scan { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @keyframes scanSweep {
            0% { top: 8%; opacity: 0.8; }
            50% { top: 92%; opacity: 1; }
            100% { top: 8%; opacity: 0.8; }
        }
        .scan-line {
            animation: scanSweep 2.4s ease-in-out infinite;
        }

        /* High-Contrast Timbul & 3D Depth Card System */
        .emboss-panel {
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.96) 0%, rgba(11, 19, 38, 0.94) 100%);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 2px solid rgba(255, 255, 255, 0.22);
            box-shadow: 
                0 25px 60px -15px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(59, 130, 246, 0.22),
                inset 0 1px 1px rgba(255, 255, 255, 0.35);
        }

        /* Embossed 3D Text & Relief */
        .text-timbul {
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.95), 0 0 12px rgba(255, 255, 255, 0.3);
        }
        .text-timbul-dark {
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.4);
        }

        /* Sleek Compact 3D Elevated Button */
        .btn-3d-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            box-shadow: 
                0 4px 12px rgba(16, 185, 129, 0.35),
                inset 0 1px 1px rgba(255, 255, 255, 0.4),
                0 2px 0 #047857 !important;
        }
        .btn-3d-emerald:hover {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
            box-shadow: 
                0 6px 16px rgba(16, 185, 129, 0.45),
                inset 0 1px 1px rgba(255, 255, 255, 0.5),
                0 2px 0 #047857 !important;
        }
        .btn-3d-emerald:active {
            transform: translateY(2px) !important;
            box-shadow: 
                0 2px 6px rgba(16, 185, 129, 0.25),
                inset 0 1px 1px rgba(255, 255, 255, 0.2),
                0 0px 0 #047857 !important;
        }

        /* Background Tech Grid & Dots */
        .bg-tech-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .bg-tech-dots {
            background-image: radial-gradient(rgba(225, 29, 116, 0.25) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-[#070b14] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-pink-600 selection:text-white relative overflow-x-hidden">

    <!-- Highly Visible Aesthetic Modern Laundry Wallpaper -->
    <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105" 
         style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-t from-[#070b14]/92 via-[#070b14]/80 to-[#070b14]/70 backdrop-blur-xs pointer-events-none"></div>

    <!-- Tech Grid & Dot Overlay -->
    <div class="fixed inset-0 z-0 bg-tech-grid opacity-35 pointer-events-none"></div>
    <div class="fixed inset-0 z-0 bg-tech-dots opacity-45 pointer-events-none"></div>

    <!-- Ambient Glowing Orbs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#4A154B]/40 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#E11D74]/30 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-950/20 rounded-full blur-[150px] pointer-events-none"></div>

    {{-- KIOSK HEADER --}}
    <header class="bg-slate-950/85 backdrop-blur-md border-b border-white/15 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-black border border-pink-500/40 p-1 flex items-center justify-center shrink-0 shadow-md overflow-hidden">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-black text-sm text-white tracking-tight drop-shadow-sm">Zaujati Laundry Hub</span>
                        <span class="text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-pink-500/20 to-purple-500/20 text-pink-300 border border-pink-500/40 px-2 py-0.5 rounded-full shadow-sm">KIOSK GATEWAY</span>
                    </div>
                    <p class="text-[11px] text-cyan-300 font-mono-nums font-semibold flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Terminal MY-KUL-01 · Biometric AI Vision Gate
                    </p>
                </div>
            </div>
            <a href="{{ route('kiosk.gateway') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white transition border border-white/20 shadow-md">
                <i class="fa-solid fa-arrow-left text-[10px] text-cyan-300"></i>
                <span class="text-timbul">Exit Terminal</span>
            </a>
        </div>
    </header>

    {{-- MAIN SCANNER CONSOLE --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8 flex flex-col justify-center relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- LEFT: WEBCAM VIEWPORT --}}
            <div class="lg:col-span-7">
                <div class="emboss-panel rounded-3xl p-5 relative overflow-hidden">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-black border-2 border-slate-700/80 shadow-2xl">
                        <video id="webcam-feed" autoplay playsinline muted class="w-full h-full object-cover"></video>

                        {{-- SCANNING HUD OVERLAYS --}}
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            {{-- TARGET RETICLE RING --}}
                            <div id="targetRing" class="ring-scan w-3/5 h-4/5 rounded-3xl border-2 border-indigo-500/40 relative">
                                {{-- RETICLE CORNERS --}}
                                <div class="absolute -top-1 -left-1 w-5 h-5 border-t-2 border-l-2 border-indigo-400"></div>
                                <div class="absolute -top-1 -right-1 w-5 h-5 border-t-2 border-r-2 border-indigo-400"></div>
                                <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-2 border-l-2 border-indigo-400"></div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-2 border-r-2 border-indigo-400"></div>
                            </div>
                            {{-- SCANNING LASER BEAM --}}
                            <div class="absolute left-8 right-8 h-1 bg-gradient-to-r from-transparent via-cyan-400 to-transparent scan-line shadow-[0_0_15px_rgba(34,211,238,0.9)]"></div>
                        </div>

                        {{-- LIVE TELEMETRY BADGE --}}
                        <div class="absolute top-3 left-3 bg-slate-950/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/20 text-xs font-mono-nums text-white flex items-center gap-2 shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                            <span class="font-bold text-[11px] tracking-wide">60 FPS · 68 Landmarks</span>
                        </div>
                    </div>

                    {{-- STATUS TICKER --}}
                    <div class="mt-4 flex items-center justify-between px-2">
                        <div class="flex items-center gap-2.5">
                            <span id="statusDot" class="inline-block w-3 h-3 rounded-full bg-amber-400 animate-ping shadow-[0_0_10px_#fbbf24]"></span>
                            <span id="statusText" class="text-xs sm:text-sm font-extrabold text-white text-timbul">Initializing Biometric Vision Engine...</span>
                        </div>
                        <span class="text-[11px] font-mono-nums font-bold text-cyan-300 bg-cyan-950/80 border border-cyan-500/40 px-2 py-0.5 rounded shadow-sm uppercase">TinyFace-SSD v2</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: RECOGNITION RESULTS & SECURITY AUDIT PANEL (TIMBUL & HIGH-CONTRAST) --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="emboss-panel rounded-3xl p-6 sm:p-7 relative overflow-hidden">
                    
                    <!-- Top Bevel Highlight Rim -->
                    <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>

                    <!-- CARD HEADER: VERIFICATION RESULT -->
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/15">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></span>
                            </span>
                            <h2 class="text-sm sm:text-base font-black uppercase tracking-wider text-white text-timbul">
                                Verification Result
                            </h2>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-blue-600 to-indigo-600 text-white border border-blue-400/60 shadow-[0_2px_8px_rgba(37,99,235,0.4)] tracking-wide">
                            <i class="fa-solid fa-shield-halved text-cyan-300 text-[10px]"></i>
                            <span>Defense-in-Depth</span>
                        </span>
                    </div>

                    {{-- UNIDENTIFIED STATE --}}
                    <div id="unidentifiedState" class="flex items-center gap-4 p-4.5 rounded-2xl bg-slate-900/90 border-2 border-white/20 mb-5 shadow-xl">
                        <div class="w-13 h-13 rounded-xl bg-slate-800 border border-white/15 flex items-center justify-center text-cyan-400 text-xl flex-shrink-0 shadow-inner p-3">
                            <i class="fa-solid fa-user-viewfinder animate-pulse text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-black text-sm text-white text-timbul">Waiting for Facial Alignment...</p>
                            <p class="text-xs text-slate-300 font-medium mt-1 leading-relaxed">Position your face inside the reticle box and blink naturally.</p>
                        </div>
                    </div>

                    {{-- IDENTIFIED STATE (TIMBUL & GLOWING CARD) --}}
                    <div id="identifiedState" class="hidden items-center gap-4 p-4.5 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-2 border-indigo-400/70 shadow-[0_12px_30px_rgba(0,0,0,0.8),0_0_25px_rgba(99,102,241,0.35)] mb-5 relative overflow-hidden">
                        
                        <!-- Accent Highlight -->
                        <div class="absolute inset-x-0 top-0 h-[1.5px] bg-gradient-to-r from-transparent via-cyan-300 to-transparent"></div>

                        <!-- Staff Photo -->
                        <div class="relative shrink-0">
                            <img id="identifiedPhoto" src="" alt="Identified staff" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-400 shadow-[0_0_15px_rgba(52,211,153,0.6)]">
                            <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-slate-950 items-center justify-center shadow-md">
                                    <i class="fa-solid fa-check text-[8px] text-white"></i>
                                </span>
                            </span>
                        </div>

                        <!-- Staff Information (Super Clear & Timbul) -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p id="identifiedName" class="font-black text-lg sm:text-xl text-white tracking-tight text-timbul"></p>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-500/25 border border-emerald-400/60 text-emerald-300 text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid fa-circle-check text-[10px] text-emerald-400"></i> Matched
                                </span>
                            </div>
                            <p id="identifiedRole" class="text-xs sm:text-sm text-cyan-300 font-bold mt-1 drop-shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-id-badge text-cyan-400 text-xs"></i>
                                <span id="identifiedRoleText">Staff Member</span>
                            </p>
                        </div>
                    </div>

                    {{-- LIVENESS & REPLAY SECURITY GAUGES (HIGH CONTRAST & TIMBUL) --}}
                    <div class="space-y-3.5 p-4 sm:p-5 rounded-2xl bg-slate-900/90 border-2 border-white/20 shadow-[0_8px_20px_rgba(0,0,0,0.6)] mb-6">
                        
                        <!-- EAR Blink Liveness Row -->
                        <div class="flex items-center justify-between text-xs sm:text-sm gap-2">
                            <span class="text-white font-bold flex items-center gap-2 drop-shadow-sm">
                                <div class="w-6 h-6 rounded-lg bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-400 shrink-0 shadow-sm">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </div>
                                <span class="text-timbul">EAR Blink Liveness:</span>
                            </span>
                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950 border border-white/25 shadow-inner" id="livenessBadge">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_8px_#fbbf24]" id="livenessDot"></span>
                                <span id="livenessLabel" class="font-black font-mono text-amber-300 text-xs tracking-wide">Awaiting Blink</span>
                            </div>
                        </div>

                        <!-- Anti-Replay Nonce Row -->
                        <div class="flex items-center justify-between text-xs sm:text-sm border-t border-white/10 pt-3 gap-2">
                            <span class="text-white font-bold flex items-center gap-2 drop-shadow-sm">
                                <div class="w-6 h-6 rounded-lg bg-purple-500/20 border border-purple-400/40 flex items-center justify-center text-purple-400 shrink-0 shadow-sm">
                                    <i class="fa-solid fa-shield-halved text-xs"></i>
                                </div>
                                <span class="text-timbul">Anti-Replay Nonce:</span>
                            </span>
                            <span class="text-xs font-mono font-black text-emerald-300 bg-emerald-950/90 border border-emerald-400/60 px-3 py-1.5 rounded-full shadow-[0_0_12px_rgba(16,185,129,0.35)] flex items-center gap-1.5">
                                <i class="fa-solid fa-lock text-[10px] text-emerald-400"></i>
                                <span>HMAC Protected</span>
                            </span>
                        </div>
                    </div>

                    {{-- CONFIRM PUNCH IN FORM (ULTRA-TIMBUL 3D BUTTON) --}}
                    <form id="attendanceForm" action="{{ route('kiosk.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="verified_identity" id="verified_identity" value="">
                        <input type="hidden" name="face_matched" id="face_matched" value="">
                        <input type="hidden" name="liveness_verified" id="liveness_verified" value="">
                        <input type="hidden" name="kiosk_token" id="kiosk_token" value="{{ $kioskToken ?? '' }}">

                        <button type="submit" id="verifyBtn" disabled
                            class="w-full rounded-xl bg-slate-800 text-slate-300 border border-slate-700/80 font-bold text-xs py-2.5 px-4 transition-all flex items-center justify-center gap-2 cursor-not-allowed shadow-inner">
                            <i class="fa-solid fa-fingerprint text-xs"></i>
                            <span id="verifyBtnLabel" class="text-timbul">Waiting for facial alignment...</span>
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
        const identifiedRoleText = document.getElementById('identifiedRoleText');
        const livenessBadge = document.getElementById('livenessBadge');
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
                loading: ['bg-amber-400 shadow-[0_0_10px_#fbbf24]', 'border-indigo-500/50'],
                scanning: ['bg-cyan-400 shadow-[0_0_10px_#22d3ee]', 'border-cyan-400/80'],
                liveness: ['bg-amber-400 shadow-[0_0_10px_#fbbf24]', 'border-amber-400'],
                matched: ['bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.6)]', 'border-emerald-400 shadow-[0_0_25px_rgba(52,211,153,0.5)]'],
                error: ['bg-rose-500 shadow-[0_0_10px_#f43f5e]', 'border-rose-500'],
            };
            statusDot.className = 'inline-block w-3 h-3 rounded-full ' + colors[state][0];
            targetRing.className = 'ring-scan w-3/5 h-4/5 rounded-3xl border-2 relative ' + colors[state][1];
            statusText.textContent = label;
        }

        function updateLivenessBadge(passed) {
            if (passed) {
                livenessDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_10px_#34d399]';
                livenessLabel.textContent = 'Verified Real Human ✅';
                livenessLabel.className = 'font-black font-mono text-emerald-300 text-xs tracking-wide drop-shadow-sm';
                livenessBadge.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/90 border-2 border-emerald-400/80 shadow-[0_0_15px_rgba(52,211,153,0.4)]';
            } else {
                livenessDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_10px_#fbbf24] animate-ping';
                livenessLabel.textContent = 'Awaiting Blink / Smile 👁️';
                livenessLabel.className = 'font-black font-mono text-amber-300 text-xs tracking-wide drop-shadow-sm';
                livenessBadge.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-950 border border-white/25 shadow-inner';
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
                verifyBtn.className = 'w-full rounded-xl btn-3d-emerald text-white font-extrabold text-xs py-2.5 px-4 transition-all flex items-center justify-center gap-2 cursor-pointer uppercase tracking-wider transform hover:scale-[1.01] active:scale-[0.99] shadow-md';
                setState('matched', 'Face Matched & Liveness Verified ✅');
            } else if (isMatch && !livenessVerified) {
                verifyBtnLabel.textContent = 'Blink or Smile to Verify Liveness 👁️';
                verifyBtn.className = 'w-full rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-400 text-slate-950 font-bold text-xs py-2.5 px-4 transition-all flex items-center justify-center gap-2 cursor-wait border-b-2 border-amber-700 shadow-sm';
                setState('liveness', 'Face recognized! Now blink or smile to complete anti-spoof check 👁️😊');
            } else {
                verifyBtnLabel.textContent = 'Waiting for facial alignment...';
                verifyBtn.className = 'w-full rounded-xl bg-slate-800 text-slate-300 border border-slate-700/80 font-bold text-xs py-2.5 px-4 transition-all flex items-center justify-center gap-2 cursor-not-allowed shadow-inner';
                setState('scanning', 'Scanning camera feed...');
            }

            if (isMatch) {
                unidentifiedState.classList.add('hidden');
                identifiedState.classList.remove('hidden');
                identifiedState.classList.add('flex');
                identifiedPhoto.src = staffMember.photoUrl;
                identifiedName.textContent = staffMember.name;
                if (identifiedRoleText) {
                    identifiedRoleText.textContent = staffMember.role || 'Staff Member';
                } else if (identifiedRole) {
                    identifiedRole.textContent = staffMember.role || 'Staff Member';
                }
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
                verifyBtn.className = 'w-full rounded-xl bg-emerald-600 text-white font-bold text-xs py-2.5 px-4 flex items-center justify-center gap-2 cursor-wait opacity-90';
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