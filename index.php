<?php
include "header.php";
include "db.php";

$trips = $pdo
    ->query(
        "SELECT T.*, U.id as user_id, U.name, U.phone, U.profile_pic FROM Trips T JOIN Users U ON T.user_id = U.id WHERE T.seats > 0 ORDER BY T.date_trip ASC LIMIT 6",
    )
    ->fetchAll();
?>

<!-- Three.js Liquid Shader Background Container -->
<div id="container" class="fixed inset-0 z-[-1] pointer-events-none"></div>

<!-- Shader Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/88/three.min.js"></script>
<script id="vertexShader" type="x-shader/x-vertex">
    void main() {
        gl_Position = vec4( position, 1.0 );
    }
</script>
<script id="fragmentShader" type="x-shader/x-fragment">
    uniform vec2 u_resolution;
    uniform vec2 u_mouse;
    uniform float u_time;
    uniform sampler2D u_noise;
    uniform sampler2D u_buffer;
    uniform bool u_renderpass;

    const float blurMultiplier = 0.95;
    const float circleSize = .25;
    const float blurStrength = .98;
    const float threshold = .5;
    const float scale = 4.;

    #define _fract true
    #define PI 3.141592653589793
    #define TAU 6.283185307179586

    vec2 hash2(vec2 p) {
        vec2 o = texture2D( u_noise, (p+0.5)/256.0, -100.0 ).xy;
        return o;
    }

    vec3 hsb2rgb( in vec3 c ){
        vec3 rgb = clamp(abs(mod(c.x*6.0+vec3(0.0,4.0,2.0), 6.0)-3.0)-1.0, 0.0, 1.0 );
        rgb = rgb*rgb*(3.0-2.0*rgb);
        return c.z * mix( vec3(1.0), rgb, c.y);
    }

    vec3 domain(vec2 z){
        return vec3(hsb2rgb(vec3(atan(z.y,z.x)/TAU,1.,1.)));
    }

    vec3 colour(vec2 z) {
        return domain(z);
    }

    #define pow2(x) (x * x)
    const int samples = 5;
    const float sigma = float(samples) * 0.25;

    float gaussian(vec2 i) {
        return 1.0 / (2.0 * PI * pow2(sigma)) * exp(-((pow2(i.x) + pow2(i.y)) / (2.0 * pow2(sigma))));
    }

    vec3 hash33(vec3 p){ 
        float n = sin(dot(p, vec3(7, 157, 113))); 
        return fract(vec3(2097152, 262144, 32768)*n); 
    }

    vec3 blur(sampler2D sp, vec2 uv, vec2 scale) {
        vec3 col = vec3(0.0);
        float accum = 0.0;
        float weight;
        vec2 offset;
        for (int x = -samples / 2; x < samples / 2; ++x) {
            for (int y = -samples / 2; y < samples / 2; ++y) {
                offset = vec2(x, y);
                weight = gaussian(offset);
                col += texture2D(sp, uv + scale * offset).rgb * weight;
                accum += weight;
            }
        }
        return col / accum;
    }

    void main() {
        vec2 uv = (gl_FragCoord.xy - 0.5 * u_resolution.xy) / u_resolution.y;
        uv *= scale;
        vec2 mouse = u_mouse * scale;
        vec2 ps = vec2(1.0) / u_resolution.xy;
        vec2 sample = gl_FragCoord.xy / u_resolution.xy;
        vec2 o = mouse*.2+vec2(.65, .5);
        float d = .98;
        sample = d * (sample - o);
        sample += o;
        sample += vec2(sin((u_time+uv.y * .5)*10.)*.001, -.00);

        vec3 fragcolour;
        vec4 tex;

        if(u_renderpass) {
            tex = vec4(blur(u_buffer, sample, ps*blurStrength) * blurMultiplier, 1.);
            float df = length(mouse - uv);
            fragcolour = vec3( smoothstep( circleSize, 0., df ) );
        } else {
            tex = texture2D(u_buffer, sample, 2.) * .98;
            tex = vec4(
                smoothstep(0.0, threshold - fwidth(tex.x), tex.x),
                smoothstep(0.2, threshold - fwidth(tex.y) + .2, tex.y),
                smoothstep(-0.05, threshold - fwidth(tex.z) - .2, tex.z),
                1.
            );
            vec3 n = hash33(vec3(uv, u_time*.1));
            tex.rgb += n * .1 - .05;
        }

        gl_FragColor = vec4(fragcolour,1.0);
        gl_FragColor += tex;
    }
</script>

<script>
    /* Fully Autonomous & Optimized JavaScript Implementation */
    let container;
    let camera, scene, renderer;
    let uniforms;
    let divisor = 1 / 15; // Slightly slower, heavier feel for drift
    let newmouse = { x: 0, y: 0 };
    let mouseActive = false;
    let mouseTimer;
    
    let loader = new THREE.TextureLoader();
    let texture, rtTexture, rtTexture2;
    
    // Performance Control
    let lastTime = 0;
    const fpsInterval = 1000 / 60;

    loader.setCrossOrigin("anonymous");
    loader.load(
        'https://s3-us-west-2.amazonaws.com/s.cdpn.io/982762/noise.png',
        function (tex) {
            texture = tex;
            texture.wrapS = THREE.RepeatWrapping;
            texture.wrapT = THREE.RepeatWrapping;
            texture.minFilter = THREE.LinearFilter;
            init();
            animate(0);
        }
    );

    function init() {
        container = document.getElementById('container');
        camera = new THREE.Camera();
        camera.position.z = 1;
        scene = new THREE.Scene();
        var geometry = new THREE.PlaneBufferGeometry(2, 2);
        
        rtTexture = new THREE.WebGLRenderTarget(window.innerWidth * .2, window.innerHeight * .2);
        rtTexture2 = new THREE.WebGLRenderTarget(window.innerWidth * .2, window.innerHeight * .2);
        
        uniforms = {
            u_time: { type: "f", value: 1.0 },
            u_resolution: { type: "v2", value: new THREE.Vector2() },
            u_noise: { type: "t", value: texture },
            u_buffer: { type: "t", value: rtTexture.texture },
            u_mouse: { type: "v2", value: new THREE.Vector2() },
            u_renderpass: { type: 'b', value: false }
        };

        var material = new THREE.ShaderMaterial({
            uniforms: uniforms,
            vertexShader: document.getElementById('vertexShader').textContent,
            fragmentShader: document.getElementById('fragmentShader').textContent
        });
        material.extensions.derivatives = true;
        var mesh = new THREE.Mesh(geometry, material);
        scene.add(mesh);
        
        renderer = new THREE.WebGLRenderer({ alpha: true, powerPreference: "high-performance" });
        renderer.setPixelRatio(1);
        container.appendChild(renderer.domElement);
        
        onWindowResize();
        window.addEventListener('resize', onWindowResize, false);
        
        // Hybrid Mouse Control
        document.addEventListener('pointermove', (e) => {
            mouseActive = true;
            let ratio = window.innerHeight / window.innerWidth;
            newmouse.x = (e.pageX - window.innerWidth / 2) / window.innerWidth / ratio;
            newmouse.y = (e.pageY - window.innerHeight / 2) / window.innerHeight * -1;
            
            clearTimeout(mouseTimer);
            mouseTimer = setTimeout(() => { mouseActive = false; }, 2000);
        }, { passive: true });
    }

    function onWindowResize() {
        renderer.setSize(window.innerWidth, window.innerHeight);
        uniforms.u_resolution.value.x = renderer.domElement.width;
        uniforms.u_resolution.value.y = renderer.domElement.height;
        rtTexture.setSize(window.innerWidth * .2, window.innerHeight * .2);
        rtTexture2.setSize(window.innerWidth * .2, window.innerHeight * .2);
    }

    function animate(time) {
        requestAnimationFrame(animate);
        const delta = time - lastTime;
        if (delta < fpsInterval) return;
        lastTime = time - (delta % fpsInterval);
        render(time);
    }

    function renderTexture() {
        let odims = uniforms.u_resolution.value.clone();
        uniforms.u_resolution.value.x = window.innerWidth * .2;
        uniforms.u_resolution.value.y = window.innerHeight * .2;
        uniforms.u_buffer.value = rtTexture2.texture;
        uniforms.u_renderpass.value = true;
        renderer.setRenderTarget(rtTexture);
        renderer.render(scene, camera, rtTexture, true);
        let buffer = rtTexture;
        rtTexture = rtTexture2;
        rtTexture2 = buffer;
        uniforms.u_buffer.value = rtTexture.texture;
        uniforms.u_resolution.value = odims;
        uniforms.u_renderpass.value = false;
    }

    function render(time) {
        let targetX = newmouse.x;
        let targetY = newmouse.y;

        // Autonomous Drifting Logic (Low Overhead)
        if (!mouseActive) {
            const t = time * 0.0005;
            targetX = Math.sin(t * 0.7) * 0.5 + Math.cos(t * 0.3) * 0.2;
            targetY = Math.cos(t * 0.5) * 0.3 + Math.sin(t * 0.2) * 0.1;
        }

        // Smooth Interpolation (Heavy Drift feel)
        uniforms.u_mouse.value.x += (targetX - uniforms.u_mouse.value.x) * divisor;
        uniforms.u_mouse.value.y += (targetY - uniforms.u_mouse.value.y) * divisor;
        
        uniforms.u_time.value = time * 0.0005;
        renderer.setRenderTarget(null);
        renderer.render(scene, camera);
        renderTexture();
    }
</script>

<style>
    body { background-color: #0F172A !important; }
    #container { background-color: #0F172A; }
    #container canvas { display: block; }
</style>

<!-- Hero Section -->
<div class="relative min-h-[70vh] flex flex-col items-center justify-center pt-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full">
        
        <!-- Left Content -->
        <div class="space-y-8 text-center lg:text-left z-10">
            <div class="inline-block px-4 py-1.5 rounded-full bg-mint/10 border border-mint/20 text-mint text-[10px] font-black uppercase tracking-[0.2em] animate-pulse">
                Next-Gen Carpooling
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter leading-[0.9]">
                DRIVE THE <br>
                <span class="text-gradient-mint italic">FUTURE.</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl font-medium max-w-lg mx-auto lg:mx-0 leading-relaxed opacity-80">
                Connect with reliable drivers in Sidi Bouzid. Secure, sustainable, and purely cybernetic travel experiences.
            </p>
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                <a href="#explore" class="bg-cyber-gradient text-white px-8 py-4 rounded-2xl font-black text-sm uppercase shadow-glow-purple hover:scale-105 transition-transform">Get Started</a>
                <button onclick="toggleTutorial()" class="px-8 py-4 rounded-2xl border border-slate-700 font-black text-sm uppercase hover:bg-slate-800 transition-colors">How it works</button>
            </div>
        </div>

        <!-- Right Visual: Isometric City Map Stylized -->
        <div class="relative hidden lg:block">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-cyber/20 rounded-full filter blur-[100px]"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-mint/10 rounded-full filter blur-[100px]"></div>
            
            <svg viewBox="0 0 800 600" class="w-full h-auto drop-shadow-2xl">
                <!-- Isometric Grid Lines -->
                <path d="M100 300 L400 150 L700 300 L400 450 Z" fill="none" stroke="#2DD4BF" stroke-width="0.5" opacity="0.2" />
                <path d="M100 310 L400 160 L700 310 L400 460 Z" fill="none" stroke="#A855F7" stroke-width="0.5" opacity="0.1" />
                
                <!-- Glowing Route Lines -->
                <path d="M200 350 Q 400 200, 600 350" fill="none" stroke="url(#mintGradient)" stroke-width="3" class="route-line shadow-glow-mint" />
                <path d="M300 250 Q 400 400, 500 200" fill="none" stroke="url(#purpleGradient)" stroke-width="3" class="route-line shadow-glow-purple" />
                
                <!-- Connection Nodes -->
                <circle cx="200" cy="350" r="6" fill="#2DD4BF" class="animate-ping" />
                <circle cx="200" cy="350" r="4" fill="#2DD4BF" />
                
                <circle cx="600" cy="350" r="6" fill="#A855F7" class="animate-ping" />
                <circle cx="600" cy="350" r="4" fill="#A855F7" />

                <!-- Gradients for SVG -->
                <defs>
                    <linearGradient id="mintGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#2DD4BF" />
                        <stop offset="100%" stop-color="transparent" />
                    </linearGradient>
                    <linearGradient id="purpleGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#A855F7" />
                        <stop offset="100%" stop-color="transparent" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Centered Call-to-Action -->
    <div class="w-full max-w-xl mt-16 z-20 flex justify-center animate-scale-in">
        <div class="glass p-3 rounded-[40px] border border-white/10 shadow-2xl backdrop-blur-3xl group">
            <a href="search.php" class="inline-flex items-center justify-center gap-4 px-12 py-6 rounded-full bg-gradient-to-r from-mint via-cyber to-indigo-600 text-midnight font-black uppercase tracking-[0.2em] text-sm hover:scale-[1.05] active:scale-95 transition-all duration-500 shadow-glow-mint group-hover:shadow-glow-purple relative overflow-hidden btn-glow">
                <span>Go to Explorer</span>
                <i class="ph ph-rocket-launch text-2xl"></i>
                
                <!-- Inner Glow Overlay -->
                <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
        </div>
    </div>
</div>

<style>
    /* Continuous Pulse/Glow Animation */
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 20px rgba(45, 212, 191, 0.3); }
        50% { box-shadow: 0 0 40px rgba(168, 85, 247, 0.5); }
        100% { box-shadow: 0 0 20px rgba(45, 212, 191, 0.3); }
    }
    .btn-glow {
        animation: pulse-glow 3s infinite ease-in-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<!-- Active Missions (Trips) Section -->
<div class="mt-32" id="explore">
    <div class="flex items-end justify-between mb-16 px-4">
        <div>
            <span class="text-mint font-black text-[10px] uppercase tracking-[0.3em]">Live Feed</span>
            <h2 class="text-5xl font-black tracking-tighter mt-2 text-white">ACTIVE MISSIONS</h2>
        </div>
        <a href="search.php" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-mint flex items-center gap-3 group transition-colors">
            Access All <i class="bi bi-arrow-right-short text-xl transition-transform group-hover:translate-x-2"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php foreach ($trips as $t): ?>
        <div class="glass-card rounded-[32px] p-8 group relative overflow-hidden">
            <!-- Background Accent -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-mint/5 rounded-full group-hover:bg-mint/10 transition-colors"></div>
            
            <div class="flex justify-between items-start mb-8">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="<?= $t["profile_pic"] ?: 'https://ui-avatars.com/api/?name='.urlencode($t['name']).'&background=0F172A&color=2DD4BF' ?>" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white/10 group-hover:ring-mint/30 transition-all shadow-xl">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-midnight"></div>
                    </div>
                    <div>
                        <h4 class="font-black text-white text-lg tracking-tight"><?= htmlspecialchars($t["name"]) ?></h4>
                        <span class="text-[9px] font-black text-mint uppercase tracking-[0.2em]">Verified Agent</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-tighter">Budget</span>
                    <span class="text-2xl font-black text-white"><?= $t["price"] ?> <small class="text-xs text-mint uppercase">TND</small></span>
                </div>
            </div>

            <div class="space-y-6 relative mb-8">
                <div class="flex gap-4">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-mint shadow-glow-mint"></div>
                        <div class="w-0.5 h-10 bg-gradient-to-b from-mint/20 to-cyber/20"></div>
                        <div class="w-2 h-2 rounded-full bg-cyber shadow-glow-purple"></div>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">Departure</span>
                            <p class="font-bold text-white text-sm"><?= htmlspecialchars($t["departure"]) ?></p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-1">Arrival</span>
                            <p class="font-bold text-white text-sm"><?= htmlspecialchars($t["destination"]) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5">
                        <i class="bi bi-clock-history mr-2"></i><?= date("H:i", strtotime($t["date_trip"])) ?>
                    </div>
                    <div class="px-3 py-1 rounded-lg bg-white/5 text-[10px] font-bold text-slate-400 border border-white/5">
                        <i class="bi bi-people-fill mr-2"></i><?= $t["seats"] ?> slots
                    </div>
                </div>
                <button onclick="openPreview(<?= htmlspecialchars(json_encode($t)) ?>)" class="bg-white/5 hover:bg-mint hover:text-midnight text-white w-10 h-10 rounded-xl flex items-center justify-center transition-all border border-white/10 hover:border-mint shadow-xl">
                    <i class="ph ph-eye font-black"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Quick Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-[150] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-500">
    <!-- Heavy Backdrop Blur Overlay -->
    <div class="absolute inset-0 bg-midnight/80 backdrop-blur-[24px]" onclick="closePreview()"></div>
    
    <!-- Modal Content -->
    <div id="preview-content" class="relative w-full max-w-2xl glass rounded-[48px] overflow-hidden border border-mint/20 shadow-[0_0_80px_rgba(45,212,191,0.15)] scale-90 opacity-0 transition-all duration-500">
        <!-- Close Icon -->
        <button onclick="closePreview()" class="absolute top-8 right-8 w-12 h-12 flex items-center justify-center rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors z-10">
            <i class="ph ph-x text-2xl font-bold"></i>
        </button>

        <!-- Profile Header Section -->
        <div class="relative h-48 bg-gradient-to-br from-mint/20 via-cyber/10 to-transparent">
            <div class="absolute -bottom-12 left-12 flex items-end gap-6">
                <div class="relative">
                    <img id="p-avatar" src="" class="w-32 h-32 rounded-[32px] object-cover border-4 border-midnight shadow-2xl">
                    <div class="absolute -bottom-2 -right-2 bg-mint text-midnight px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 shadow-lg ring-4 ring-midnight">
                        <i class="ph ph-seal-check-fill text-sm"></i> Verified
                    </div>
                </div>
                <div class="pb-2">
                    <h3 id="p-name" class="text-3xl font-black text-white tracking-tighter"></h3>
                    <div class="flex items-center gap-3 mt-1">
                        <div class="flex text-amber-400">
                            <i class="ph ph-star-fill"></i><i class="ph ph-star-fill"></i><i class="ph ph-star-fill"></i><i class="ph ph-star-fill"></i><i class="ph ph-star-half-fill"></i>
                        </div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">4.8 • Top Agent</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="pt-20 p-12 space-y-8">
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Véhicule</span>
                    <p id="p-car" class="text-white font-bold text-lg"></p>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Plaque d'immatriculation</span>
                    <p class="text-white font-bold text-lg">TN 216 • 4829</p>
                </div>
            </div>

            <div class="glass p-8 rounded-[32px] border border-white/5 space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-mint/10 flex items-center justify-center text-mint shrink-0">
                        <i class="ph ph-map-pin-line text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Point de départ</span>
                        <p id="p-dep" class="text-white font-bold"></p>
                        <p class="text-slate-500 text-xs mt-1">Prise en charge : Station service Agil</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-cyber/10 flex items-center justify-center text-cyber shrink-0">
                        <i class="ph ph-map-pin text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Destination</span>
                        <p id="p-dest" class="text-white font-bold"></p>
                        <p class="text-slate-500 text-xs mt-1">Dépose : Centre Ville (Passage)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-2 gap-4 pt-4">
                <button class="py-4 rounded-2xl border border-white/10 text-white font-black uppercase text-xs tracking-widest hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-chat-circle-dots text-xl"></i>
                    Contacter
                </button>
                <a id="p-book" href="" class="py-4 rounded-2xl bg-mint text-midnight font-black uppercase text-xs tracking-widest shadow-glow-mint hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                    <i class="ph ph-ticket text-xl"></i>
                    Réserver
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openPreview(data) {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');
        
        // Populate Data
        document.getElementById('p-avatar').src = data.profile_pic || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name) + '&background=0F172A&color=2DD4BF';
        document.getElementById('p-name').textContent = data.name;
        document.getElementById('p-car').textContent = data.car_brand;
        document.getElementById('p-dep').textContent = data.departure;
        document.getElementById('p-dest').textContent = data.destination;
        document.getElementById('p-book').href = 'book.php?id=' + data.id;

        // Animate In
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            content.classList.remove('opacity-0', 'scale-90');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-90', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        }, 300);
    }
</script>

<style>
    /* Springy Animation for Modal */
    #preview-content {
        transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<?php include "footer.php"; ?>
