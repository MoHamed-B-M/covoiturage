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
    </style>
</head>
<body class="antialiased selection:bg-mint/30">

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
    <nav class="glass sticky top-0 z-50 px-8 py-5 flex items-center justify-between mx-4 my-4 rounded-2xl">
        <a href="index.php" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-cyber-gradient rounded-xl flex items-center justify-center text-white shadow-glow-purple">
                <i class="bi bi-lightning-charge-fill text-xl"></i>
            </div>
            <span class="text-2xl font-extrabold tracking-tighter uppercase">Rydo</span>
        </a>

        <div class="hidden md:flex items-center gap-10 font-bold text-sm uppercase tracking-widest text-slate-400">
            <a href="index.php" class="hover:text-mint transition-colors">Home</a>
            <a href="search.php" class="hover:text-mint transition-colors">Explorer</a>
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="add_trip.php" class="hover:text-mint transition-colors">Publier</a>
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
