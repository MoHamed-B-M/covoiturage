<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rydo - Connect the dots. Share the ride.</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        midnight: '#0F172A',
                        mint: '#2DD4BF',
                        cyber: '#A855F7',
                    },
                    boxShadow: {
                        'glow-mint': '0 0 20px rgba(45, 212, 191, 0.3)',
                        'glow-purple': '0 0 20px rgba(168, 85, 247, 0.3)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: #FFFFFF;
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #2DD4BF;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(45, 212, 191, 0.2);
        }
        .text-gradient-mint {
            background: linear-gradient(135deg, #2DD4BF 0%, #0D9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-cyber-gradient {
            background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%);
        }
        
        /* Premium Input Styles */
        .premium-input-group {
            position: relative;
            transition: all 0.3s ease;
        }
        .premium-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .premium-input:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: #4338CA;
        }
        .premium-input:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: #4338CA;
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.1), inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }
        .floating-label {
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .premium-input:focus ~ .floating-label,
        .premium-input:not(:placeholder-shown) ~ .floating-label {
            transform: translateY(-1.2rem) scale(0.75);
            color: #4338CA;
            font-weight: 800;
        }
        
        /* Tutorial Modal Animations */
        #tutorial-modal {
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #tutorial-content {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
        }
        .modal-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .modal-hidden #tutorial-content {
            transform: scale(0.9) translateY(20px);
            opacity: 0;
        }

        /* Intro Overlay */
        #intro-overlay {
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }
        #intro-overlay.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        #intro-start-btn {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #intro-start-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 0 40px rgba(45, 212, 191, 0.4);
        }
        #intro-start-btn.hidden {
            opacity: 0;
            transform: scale(0.8);
            pointer-events: none;
        }
        #intro-loader {
            transition: opacity 0.5s ease;
        }
        #intro-loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Truck Loader (from Uiverse.io by vinodjangid07) */
        .loader {
            width: fit-content;
            height: fit-content;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .truckWrapper {
            width: 200px;
            height: 100px;
            display: flex;
            flex-direction: column;
            position: relative;
            align-items: center;
            justify-content: flex-end;
            overflow-x: hidden;
        }
        .truckBody {
            width: 130px;
            height: fit-content;
            margin-bottom: 6px;
            animation: motion 1s linear infinite;
        }
        @keyframes motion {
            0% { transform: translateY(0px); }
            50% { transform: translateY(3px); }
            100% { transform: translateY(0px); }
        }
        .truckTires {
            width: 130px;
            height: fit-content;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 10px 0px 15px;
            position: absolute;
            bottom: 0;
        }
        .truckTires svg { width: 24px; }
        .road {
            width: 100%;
            height: 1.5px;
            background-color: #282828;
            position: relative;
            bottom: 0;
            align-self: flex-end;
            border-radius: 3px;
        }
        .road::before {
            content: "";
            position: absolute;
            width: 20px;
            height: 100%;
            background-color: #282828;
            right: -50%;
            border-radius: 3px;
            animation: roadAnimation 1.4s linear infinite;
            border-left: 10px solid white;
        }
        .road::after {
            content: "";
            position: absolute;
            width: 10px;
            height: 100%;
            background-color: #282828;
            right: -65%;
            border-radius: 3px;
            animation: roadAnimation 1.4s linear infinite;
            border-left: 4px solid white;
        }
        .lampPost {
            position: absolute;
            bottom: 0;
            right: -90%;
            height: 90px;
            animation: roadAnimation 1.4s linear infinite;
        }
        @keyframes roadAnimation {
            0% { transform: translateX(0px); }
            100% { transform: translateX(-350px); }
        }
    </style>
</head>
<body class="antialiased selection:bg-mint/30">

    <!-- Intro Overlay -->
    <script>if(localStorage.getItem('rydo_intro_seen')){var _s=document.createElement('style');_s.textContent='#intro-overlay{display:none!important}';document.head.appendChild(_s)}</script>
    <div id="intro-overlay" class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-midnight">
        <div class="text-center" id="intro-start">
            <svg class="h-24 md:h-32 w-auto mx-auto mb-8" viewBox="0 0 180 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="logoGradIntro" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#2DD4BF"/>
                        <stop offset="100%" stop-color="#0D9488"/>
                    </linearGradient>
                </defs>
                <rect x="2" y="6" width="12" height="36" rx="3" fill="url(#logoGradIntro)" opacity="0.4"/>
                <rect x="8" y="2" width="12" height="44" rx="3" fill="url(#logoGradIntro)" opacity="0.7"/>
                <rect x="14" y="6" width="12" height="36" rx="3" fill="url(#logoGradIntro)"/>
                <path d="M38 14 L45 34 L52 14" stroke="url(#logoGradIntro)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M42 24 L48 24" stroke="url(#logoGradIntro)" stroke-width="3" stroke-linecap="round"/>
                <path d="M62 14 L62 34" stroke="url(#logoGradIntro)" stroke-width="4" stroke-linecap="round"/>
                <path d="M62 14 C62 14 70 18 72 24 C74 30 62 34 62 34" stroke="url(#logoGradIntro)" stroke-width="3.5" stroke-linecap="round" fill="none"/>
                <text x="86" y="32" font-family="'Plus Jakarta Sans', sans-serif" font-weight="800" font-size="28" fill="white" letter-spacing="1">Rydo</text>
            </svg>
            <p class="text-slate-500 text-sm uppercase tracking-[0.3em] font-bold mb-12">Connect the dots. Share the ride.</p>
            <button id="intro-start-btn" class="bg-gradient-to-r from-mint to-teal-600 text-midnight px-12 py-5 rounded-2xl font-black uppercase text-sm shadow-glow-mint cursor-pointer">
                <i class="ph ph-play-circle me-2"></i> Press to Start
            </button>
        </div>
        <div id="intro-loader" class="hidden absolute inset-0 flex flex-col items-center justify-center">
            <div class="loader">
                <div class="truckWrapper">
                    <div class="truckBody">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 198 93" class="trucksvg">
                            <path stroke-width="3" stroke="#282828" fill="#F83D3D" d="M135 22.5H177.264C178.295 22.5 179.22 23.133 179.594 24.0939L192.33 56.8443C192.442 57.1332 192.5 57.4404 192.5 57.7504V89C192.5 90.3807 191.381 91.5 190 91.5H135C133.619 91.5 132.5 90.3807 132.5 89V25C132.5 23.6193 133.619 22.5 135 22.5Z"></path>
                            <path stroke-width="3" stroke="#282828" fill="#7D7C7C" d="M146 33.5H181.741C182.779 33.5 183.709 34.1415 184.078 35.112L190.538 52.112C191.16 53.748 189.951 55.5 188.201 55.5H146C144.619 55.5 143.5 54.3807 143.5 53V36C143.5 34.6193 144.619 33.5 146 33.5Z"></path>
                            <path stroke-width="2" stroke="#282828" fill="#282828" d="M150 65C150 65.39 149.763 65.8656 149.127 66.2893C148.499 66.7083 147.573 67 146.5 67C145.427 67 144.501 66.7083 143.873 66.2893C143.237 65.8656 143 65.39 143 65C143 64.61 143.237 64.1344 143.873 63.7107C144.501 63.2917 145.427 63 146.5 63C147.573 63 148.499 63.2917 149.127 63.7107C149.763 64.1344 150 64.61 150 65Z"></path>
                            <rect stroke-width="2" stroke="#282828" fill="#FFFCAB" rx="1" height="7" width="5" y="63" x="187"></rect>
                            <rect stroke-width="2" stroke="#282828" fill="#282828" rx="1" height="11" width="4" y="81" x="193"></rect>
                            <rect stroke-width="3" stroke="#282828" fill="#DFDFDF" rx="2.5" height="90" width="121" y="1.5" x="6.5"></rect>
                            <rect stroke-width="2" stroke="#282828" fill="#DFDFDF" rx="2" height="4" width="6" y="84" x="1"></rect>
                        </svg>
                    </div>
                    <div class="truckTires">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 30 30" class="tiresvg">
                            <circle stroke-width="3" stroke="#282828" fill="#282828" r="13.5" cy="15" cx="15"></circle>
                            <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 30 30" class="tiresvg">
                            <circle stroke-width="3" stroke="#282828" fill="#282828" r="13.5" cy="15" cx="15"></circle>
                            <circle fill="#DFDFDF" r="7" cy="15" cx="15"></circle>
                        </svg>
                    </div>
                    <div class="road"></div>
                    <svg xml:space="preserve" viewBox="0 0 453.459 453.459" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" id="Capa_1" version="1.1" fill="#000000" class="lampPost">
                        <path d="M252.882,0c-37.781,0-68.686,29.953-70.245,67.358h-6.917v8.954c-26.109,2.163-45.463,10.011-45.463,19.366h9.993c-1.65,5.146-2.507,10.54-2.507,16.017c0,28.956,23.558,52.514,52.514,52.514c28.956,0,52.514-23.558,52.514-52.514c0-5.478-0.856-10.872-2.506-16.017h9.992c0-9.354-19.352-17.204-45.463-19.366v-8.954h-6.149C200.189,38.779,223.924,16,252.882,16c29.952,0,54.32,24.368,54.32,54.32c0,28.774-11.078,37.009-25.105,47.437c-17.444,12.968-37.216,27.667-37.216,78.884v113.914h-0.797c-5.068,0-9.174,4.108-9.174,9.177c0,2.844,1.293,5.383,3.321,7.066c-3.432,27.933-26.851,95.744-8.226,115.459v11.202h45.75v-11.202c18.625-19.715-4.794-87.527-8.227-115.459c2.029-1.683,3.322-4.223,3.322-7.066c0-5.068-4.107-9.177-9.176-9.177h-0.795V196.641c0-43.174,14.942-54.283,30.762-66.043c14.793-10.997,31.559-23.461,31.559-60.277C323.202,31.545,291.656,0,252.882,0z M232.77,111.694c0,23.442-19.071,42.514-42.514,42.514c-23.442,0-42.514-19.072-42.514-42.514c0-5.531,1.078-10.957,3.141-16.017h78.747C231.693,100.736,232.77,106.162,232.77,111.694z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-slate-500 text-xs uppercase tracking-[0.3em] font-bold mt-8 animate-pulse">Chargement...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('intro-overlay');
            const startBtn = document.getElementById('intro-start-btn');
            const startSection = document.getElementById('intro-start');
            const loader = document.getElementById('intro-loader');

            if (localStorage.getItem('rydo_intro_seen')) {
                return;
            }

            startBtn.addEventListener('click', function() {
                localStorage.setItem('rydo_intro_seen', '1');
                startSection.style.display = 'none';
                loader.classList.remove('hidden');

                const startTime = Date.now();
                const minDuration = 3000;

                function finishIntro() {
                    const elapsed = Date.now() - startTime;
                    const remaining = Math.max(0, minDuration - elapsed);

                    setTimeout(function() {
                        overlay.classList.add('fade-out');
                        document.body.style.overflow = '';
                    }, remaining);
                }

                window.addEventListener('load', finishIntro);
                if (document.readyState === 'complete') {
                    window.removeEventListener('load', finishIntro);
                    finishIntro();
                }
            });
        });
    </script>

    <!-- Tutorial Modal Overlay -->
    <div id="tutorial-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 modal-hidden">
        <div class="absolute inset-0 bg-midnight/60 backdrop-blur-xl" onclick="toggleTutorial()"></div>
        <div id="tutorial-content" class="glass max-w-2xl w-full rounded-[40px] p-8 md:p-12 relative overflow-hidden shadow-2xl border border-white/10">
            <button onclick="toggleTutorial()" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
            
            <div class="text-center space-y-6">
                <div class="w-16 h-16 bg-mint/10 rounded-2xl flex items-center justify-center text-mint mx-auto mb-4">
                    <i class="bi bi-rocket-takeoff-fill text-3xl"></i>
                </div>
                <h2 class="text-4xl font-black tracking-tighter">How Rydo Works</h2>
                <p class="text-slate-400 text-lg">Your step-by-step guide to the future of carpooling.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 text-left">
                    <div class="space-y-3">
                        <div class="text-mint font-black text-xl">01</div>
                        <h4 class="font-bold text-white">Search Mission</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Enter your origin, destiny and launch date to find active travel missions.</p>
                    </div>
                    <div class="space-y-3">
                        <div class="text-cyber font-black text-xl">02</div>
                        <h4 class="font-bold text-white">Book Slot</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Review verified agent profiles and secure your seat with one click.</p>
                    </div>
                    <div class="space-y-3">
                        <div class="text-indigo-500 font-black text-xl">03</div>
                        <h4 class="font-bold text-white">Ride Cyber</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Meet your driver, connect the dots, and share the cost of the future.</p>
                    </div>
                </div>
                
                <div class="pt-8">
                    <button onclick="toggleTutorial()" class="w-full bg-mint text-midnight py-4 rounded-2xl font-black uppercase text-sm shadow-glow-mint hover:scale-[1.02] transition-transform">
                        Understood
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTutorial() {
            const modal = document.getElementById('tutorial-modal');
            modal.classList.toggle('modal-hidden');
            if(!modal.classList.contains('modal-hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    </script>

    <!-- Navbar -->
    <nav class="glass sticky top-0 z-50 px-8 py-3 flex items-center justify-between mx-4 my-4 rounded-2xl">
        <a href="index.php" class="flex items-center group">
            <svg class="h-10 md:h-12 w-auto transition-transform duration-500 group-hover:scale-105" viewBox="0 0 180 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="logoGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#2DD4BF"/>
                        <stop offset="100%" stop-color="#0D9488"/>
                    </linearGradient>
                </defs>
                <rect x="2" y="6" width="12" height="36" rx="3" fill="url(#logoGrad)" opacity="0.4"/>
                <rect x="8" y="2" width="12" height="44" rx="3" fill="url(#logoGrad)" opacity="0.7"/>
                <rect x="14" y="6" width="12" height="36" rx="3" fill="url(#logoGrad)"/>
                <path d="M38 14 L45 34 L52 14" stroke="url(#logoGrad)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M42 24 L48 24" stroke="url(#logoGrad)" stroke-width="3" stroke-linecap="round"/>
                <path d="M62 14 L62 34" stroke="url(#logoGrad)" stroke-width="4" stroke-linecap="round"/>
                <path d="M62 14 C62 14 70 18 72 24 C74 30 62 34 62 34" stroke="url(#logoGrad)" stroke-width="3.5" stroke-linecap="round" fill="none"/>
                <text x="86" y="32" font-family="'Plus Jakarta Sans', sans-serif" font-weight="800" font-size="28" fill="white" letter-spacing="1">Rydo</text>
            </svg>
        </a>

        <div class="hidden md:flex items-center gap-10 font-bold text-sm uppercase tracking-widest text-slate-400">
            <?php 
                $current_page = basename($_SERVER['PHP_SELF']); 
            ?>
            <a href="index.php" class="hover:text-mint transition-colors <?= $current_page == 'index.php' ? 'text-mint' : '' ?>">Home</a>
            <a href="search.php" class="hover:text-mint transition-colors <?= $current_page == 'search.php' ? 'text-mint' : '' ?>">Explorer</a>
            
            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <a href="admin_dashboard.php" class="relative group flex items-center gap-2 transition-all <?= $current_page == 'admin_dashboard.php' ? 'text-cyber' : 'hover:text-cyber' ?>">
                    <i class="ph ph-shield-check text-lg"></i>
                    <span>Admin</span>
                    <?php if ($current_page == 'admin_dashboard.php'): ?>
                        <span class="absolute -bottom-2 left-0 w-full h-0.5 bg-cyber shadow-[0_0_10px_rgba(168,85,247,0.5)]"></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="my_bookings.php" class="hover:text-mint transition-colors <?= $current_page == 'my_bookings.php' ? 'text-mint' : '' ?>">Mes Réservations</a>
                <a href="add_trip.php" class="hover:text-mint transition-colors <?= $current_page == 'add_trip.php' ? 'text-mint' : '' ?>">Publier</a>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-5">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="logout.php" class="text-slate-400 hover:text-red-400">
                    <i class="bi bi-power text-xl"></i>
                </a>
            <?php else: ?>
                <a href="login.php" class="text-sm font-bold uppercase text-slate-400 hover:text-white">Sign In</a>
                <a href="register.php" class="bg-mint text-midnight px-6 py-2.5 rounded-xl font-extrabold text-xs uppercase shadow-glow-mint hover:scale-105 transition-transform">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pb-20">
