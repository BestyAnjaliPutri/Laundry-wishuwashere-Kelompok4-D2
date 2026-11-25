<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0 px-md-2">

    <style>
        /* Animasi khusus elemen laundry */
        @keyframes washSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes floatCute {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-7px) rotate(3deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(14, 165, 233, 0.2); }
            50% { box-shadow: 0 0 20px rgba(14, 165, 233, 0.45); }
        }
        .anim-wash {
            display: inline-block;
            animation: washSpin 8s linear infinite;
        }
        .anim-float {
            display: inline-block;
            animation: floatCute 3.5s ease-in-out infinite;
        }
        .stat-card-cute {
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
            border: 2px solid #bae6fd !important;
        }
        .stat-card-cute:hover {
            transform: translateY(-4px);
            border-color: #0ea5e9 !important;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.15);
        }
        .hero-laundry-card {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 55%, #e0f2fe 100%);
            border: 2px solid #7dd3fc !important;
            position: relative;
            overflow: hidden;
        }
        .hero-laundry-card::after {
            content: "🫧";
            position: absolute;
            right: 15px;
            bottom: 10px;
            font-size: 5rem;
            opacity: 0.12;
            pointer-events: none;
        }
    </style>

    <!-- 1. TOP HEADER STATUS BAR (PASTEL BLUE & CUTE LAUNDRY) -->
    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-white border border-2" style="border-color: #bae6fd !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4 anim-float">🧼</span>
            <div>
                <strong class="fs-5 text-dark">Overview Dashboard</strong>
                <span class="text-primary ms-1">✨</span>
                <span class="badge bg-info-subtle text-primary-emphasis border border-info ms-2">Laundry Mode</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 text-white" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                <i class="fa-solid fa-droplet anim-float me-1"></i> STATUS: AKTIF 🧺
            </span>
        </div>
    </div>

    <!-- 2. HERO CARD BANNER (TEMA BIRU PASTEL & ELEMEN LAUNDRY) -->
    <div class="card p-4 mb-4 hero-laundry-card">
        <div class="row align-items-center g-4">
            <!-- Kolom Kiri Hero -->
            <div class="col-12 col-xl-8">
                <!-- Badges -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge px-3 py-2 text-white" style="background: #0284c7;">
                        <i class="fa-solid fa-sparkles me-1"></i> SYSTEM ONLINE 🧼
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                        <i class="fa-solid fa-check-double me-1"></i> VER: 2.3.5
                    </span>
                    <span class="badge bg-white text-info-emphasis border border-info px-3 py-2">
                        <i class="fa-solid fa-shirt me-1 text-info"></i> WISHUWASHERE_LAUNDRY 🧺
                    </span>
                </div>

                <!-- Judul & Deskripsi -->
                <h3 class="fw-bold text-dark mb-2">
                    Accounting Information System — wishuwashere. 🧺
                </h3>
                <div class="fw-bold small mb-2" style="color: #0284c7;">
                    ✨ Fresh, Clean &amp; Perfectly Balanced Laundry Accounting ✨
                </div>
                <p class="text-secondary mb-4" style="max-width: 680px; font-size: 0.95rem; line-height: 1.6;">
                    Sistem Informasi Akuntansi berbasis web terintegrasi khusus usaha laundry — pencatatan transaksi kasir instan, jurnal umum otomatis, buku besar, worksheet, serta 4 laporan keuangan pokok yang balance dan presisi.
                </p>

                <!-- Tombol Aksi Cepat Sesuai Playlist SIA -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('kasir') ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-bold shadow-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">
                        <i class="fa-solid fa-cash-register anim-float"></i>
                        <span>Buka Transaksi Kasir</span>
                    </a>
                    <a href="<?= base_url('neraca-lajur') ?>" class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 fw-bold bg-white" style="border-color: #0ea5e9; color: #0284c7;">
                        <i class="fa-solid fa-table-columns"></i>
                        <span>Neraca Lajur</span>
                    </a>
                    <a href="<?= base_url('laporan') ?>" class="btn btn-outline-info d-flex align-items-center gap-2 px-3 py-2 fw-bold bg-white" style="border-color: #38bdf8; color: #0369a1;">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan Hero: 3D Cute Bunny AI Assistant & Navigator -->
            <div class="col-12 col-xl-4">
                <style>
                    /* ===== 3D CUTE BUNNY AI NAVIGATOR STYLES ===== */
                    .bunny-card-container {
                        perspective: 1000px;
                    }
                    .bunny-ai-card {
                        background: linear-gradient(145deg, #ffffff 0%, #f0f9ff 60%, #e0f2fe 100%);
                        border: 2px solid #bae6fd !important;
                        border-radius: 20px !important;
                        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.12), 0 8px 10px -6px rgba(2, 132, 199, 0.08);
                        position: relative;
                        overflow: hidden;
                        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                        transform-style: preserve-3d;
                    }
                    .bunny-ai-card:hover {
                        transform: translateY(-4px) rotateX(2deg) rotateY(-2deg);
                        box-shadow: 0 16px 32px -6px rgba(2, 132, 199, 0.2), 0 10px 14px -6px rgba(2, 132, 199, 0.12);
                        border-color: #7dd3fc !important;
                    }
                    /* Glow Ambient Background */
                    .bunny-glow-bg {
                        position: absolute;
                        top: -30px;
                        right: -30px;
                        width: 140px;
                        height: 140px;
                        background: radial-gradient(circle, rgba(56, 189, 248, 0.35) 0%, rgba(244, 114, 182, 0.15) 50%, transparent 70%);
                        border-radius: 50%;
                        filter: blur(20px);
                        pointer-events: none;
                        z-index: 1;
                    }
                    /* 3D Bunny Character Floating Animation */
                    .bunny-3d-wrapper {
                        position: relative;
                        display: inline-block;
                        cursor: pointer;
                        user-select: none;
                        transition: transform 0.25s ease;
                    }
                    .bunny-3d-wrapper:hover {
                        transform: scale(1.08);
                    }
                    .bunny-3d-wrapper:active {
                        transform: scale(0.95);
                    }
                    .anim-bunny-float {
                        animation: bunnyFloat 3.6s ease-in-out infinite;
                        transform-origin: center bottom;
                    }
                    .anim-bunny-ear-left {
                        transform-origin: 40px 65px;
                        animation: earWiggleLeft 4.2s ease-in-out infinite;
                    }
                    .anim-bunny-ear-right {
                        transform-origin: 80px 65px;
                        animation: earWiggleRight 4.8s ease-in-out infinite;
                    }
                    .anim-bunny-eyes {
                        animation: bunnyBlink 4.5s infinite;
                        transform-origin: center;
                    }
                    .anim-bunny-cheeks {
                        animation: cheekBlush 2.5s ease-in-out infinite alternate;
                    }
                    /* Floating Bubbles */
                    .bubble-particle {
                        position: absolute;
                        border-radius: 50%;
                        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.95), rgba(56, 189, 248, 0.4) 60%, rgba(14, 165, 233, 0.8) 100%);
                        box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25), inset -2px -2px 4px rgba(255, 255, 255, 0.6);
                        pointer-events: none;
                    }
                    .bubble-1 {
                        width: 16px;
                        height: 16px;
                        top: 25px;
                        left: 15px;
                        animation: floatBubble 3.2s ease-in-out infinite;
                    }
                    .bubble-2 {
                        width: 22px;
                        height: 22px;
                        top: 15px;
                        right: 20px;
                        animation: floatBubble 4s ease-in-out 1s infinite;
                    }
                    .bubble-3 {
                        width: 12px;
                        height: 12px;
                        bottom: 40px;
                        right: 15px;
                        animation: floatBubble 2.8s ease-in-out 0.5s infinite;
                    }
                    /* Keyframes */
                    @keyframes bunnyFloat {
                        0%, 100% { transform: translateY(0px) rotate(0deg); }
                        50% { transform: translateY(-7px) rotate(1.5deg); }
                    }
                    @keyframes earWiggleLeft {
                        0%, 85%, 100% { transform: rotate(0deg); }
                        90% { transform: rotate(-8deg); }
                        95% { transform: rotate(4deg); }
                    }
                    @keyframes earWiggleRight {
                        0%, 82%, 100% { transform: rotate(0deg); }
                        88% { transform: rotate(9deg); }
                        94% { transform: rotate(-3deg); }
                    }
                    @keyframes bunnyBlink {
                        0%, 94%, 98%, 100% { transform: scaleY(1); }
                        96% { transform: scaleY(0.08); }
                    }
                    @keyframes cheekBlush {
                        0% { opacity: 0.75; transform: scale(0.95); }
                        100% { opacity: 1; transform: scale(1.05); }
                    }
                    @keyframes floatBubble {
                        0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.8; }
                        50% { transform: translateY(-10px) translateX(4px); opacity: 1; }
                    }
                    /* Speech Bubble */
                    .bunny-speech-bubble {
                        background: #ffffff;
                        border: 1.5px solid #bae6fd;
                        border-radius: 14px;
                        padding: 8px 12px;
                        position: relative;
                        font-size: 0.82rem;
                        color: #0369a1;
                        font-weight: 600;
                        line-height: 1.35;
                        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.08);
                        transition: all 0.3s ease;
                    }
                    .bunny-speech-bubble::after {
                        content: '';
                        position: absolute;
                        bottom: -7px;
                        left: 50%;
                        transform: translateX(-50%);
                        border-width: 7px 7px 0;
                        border-style: solid;
                        border-color: #ffffff transparent;
                        display: block;
                        width: 0;
                    }
                    .bunny-speech-bubble::before {
                        content: '';
                        position: absolute;
                        bottom: -9px;
                        left: 50%;
                        transform: translateX(-50%);
                        border-width: 8px 8px 0;
                        border-style: solid;
                        border-color: #bae6fd transparent;
                        display: block;
                        width: 0;
                    }
                    /* Mini Navigator Pills */
                    .bunny-nav-pill {
                        background: #ffffff;
                        border: 1px solid #bae6fd;
                        color: #0284c7;
                        border-radius: 20px;
                        padding: 4px 10px;
                        font-size: 0.74rem;
                        font-weight: 700;
                        text-decoration: none;
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                        transition: all 0.2s ease;
                        box-shadow: 0 2px 4px rgba(2, 132, 199, 0.04);
                    }
                    .bunny-nav-pill:hover {
                        background: #0284c7;
                        color: #ffffff !important;
                        border-color: #0284c7;
                        transform: translateY(-2px);
                        box-shadow: 0 4px 8px rgba(2, 132, 199, 0.2);
                    }
                </style>

                <div class="bunny-card-container">
                    <div class="card p-3 border-2 bunny-ai-card text-center">
                        <div class="bunny-glow-bg"></div>

                        <!-- Header Badge: AI Navigator Online -->
                        <div class="d-flex justify-content-between align-items-center mb-2 position-relative" style="z-index: 2;">
                            <span class="badge border bg-white d-flex align-items-center gap-1 shadow-sm px-2 py-1" style="border-color: #bae6fd !important; color: #0284c7; font-size: 0.72rem; font-weight: 800;">
                                <span class="spinner-grow spinner-grow-sm text-info" style="width: 8px; height: 8px;" role="status"></span>
                                <span>AI NAVIGATOR // 3D</span>
                            </span>
                            <span class="badge bg-warning text-dark shadow-sm px-2 py-1" style="font-size: 0.7rem; font-weight: 800; border-radius: 8px;">
                                <i class="fa-solid fa-sparkles text-danger me-1"></i> FLUFFY 🐰
                            </span>
                        </div>

                        <!-- Dynamic Speech Bubble -->
                        <div class="mb-2 position-relative" style="z-index: 2;">
                            <div class="bunny-speech-bubble" id="bunnyDialogText">
                                Halo Kelompok 4! 🫧 Aku Fluffy, asisten AI laundry. Mau aku antar ke mana hari ini?
                            </div>
                        </div>

                        <!-- 3D Vector Bunny Character -->
                        <div class="py-1 position-relative" style="z-index: 2;">
                            <!-- Floating Bubbles around Bunny -->
                            <div class="bubble-particle bubble-1"></div>
                            <div class="bubble-particle bubble-2"></div>
                            <div class="bubble-particle bubble-3"></div>

                            <div class="bunny-3d-wrapper anim-bunny-float" id="bunnyTrigger" onclick="talkToBunny()" title="Klik aku untuk tips akuntansi &amp; sapaan lucu! 🐾">
                                <svg width="124" height="120" viewBox="0 0 120 115" fill="none" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 10px 16px rgba(2, 132, 199, 0.18));">
                                    <defs>
                                        <!-- 3D Gradients -->
                                        <radialGradient id="bunnyBodyGrad" cx="50%" cy="35%" r="65%">
                                            <stop offset="0%" stop-color="#ffffff" />
                                            <stop offset="70%" stop-color="#f8fafc" />
                                            <stop offset="100%" stop-color="#e2e8f0" />
                                        </radialGradient>
                                        <radialGradient id="bunnyEarInner" cx="45%" cy="35%" r="65%">
                                            <stop offset="0%" stop-color="#fed7aa" />
                                            <stop offset="60%" stop-color="#fbcfe8" />
                                            <stop offset="100%" stop-color="#f472b6" />
                                        </radialGradient>
                                        <radialGradient id="bunnyCheekGrad" cx="50%" cy="50%" r="50%">
                                            <stop offset="0%" stop-color="#fda4af" stop-opacity="0.9" />
                                            <stop offset="100%" stop-color="#fda4af" stop-opacity="0" />
                                        </radialGradient>
                                        <radialGradient id="bunnyEye3D" cx="35%" cy="30%" r="60%">
                                            <stop offset="0%" stop-color="#334155" />
                                            <stop offset="60%" stop-color="#0f172a" />
                                            <stop offset="100%" stop-color="#020617" />
                                        </radialGradient>
                                        <linearGradient id="bunnyCrownGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#38bdf8" />
                                            <stop offset="100%" stop-color="#0284c7" />
                                        </linearGradient>
                                    </defs>

                                    <!-- Left Ear (3D Floppy) -->
                                    <g class="anim-bunny-ear-left">
                                        <!-- Outer Ear Shadow & Base -->
                                        <path d="M 38 65 C 24 45 22 18 33 6 C 42 -2 52 14 47 48 Z" fill="url(#bunnyBodyGrad)" stroke="#cbd5e1" stroke-width="1.5" />
                                        <!-- Inner Ear Soft Pink -->
                                        <path d="M 37 54 C 28 40 28 20 34 12 C 40 6 46 18 42 43 Z" fill="url(#bunnyEarInner)" />
                                    </g>

                                    <!-- Right Ear (3D Floppy) -->
                                    <g class="anim-bunny-ear-right">
                                        <!-- Outer Ear Shadow & Base -->
                                        <path d="M 82 65 C 96 45 98 18 87 6 C 78 -2 68 14 73 48 Z" fill="url(#bunnyBodyGrad)" stroke="#cbd5e1" stroke-width="1.5" />
                                        <!-- Inner Ear Soft Pink -->
                                        <path d="M 83 54 C 92 40 92 20 86 12 C 80 6 74 18 78 43 Z" fill="url(#bunnyEarInner)" />
                                    </g>

                                    <!-- Cute Laundry Soap Crown / Bubbles on Head -->
                                    <circle cx="60" cy="40" r="8" fill="url(#bunnyCrownGrad)" opacity="0.9" />
                                    <circle cx="50" cy="44" r="6" fill="#7dd3fc" opacity="0.85" />
                                    <circle cx="70" cy="44" r="6" fill="#38bdf8" opacity="0.85" />
                                    <circle cx="59" cy="38" r="2.5" fill="#ffffff" opacity="0.9" />

                                    <!-- Bunny 3D Head Spherical -->
                                    <ellipse cx="60" cy="74" rx="36" ry="32" fill="url(#bunnyBodyGrad)" stroke="#e2e8f0" stroke-width="1.5" />
                                    <!-- Head Highlight Specular 3D -->
                                    <ellipse cx="52" cy="56" rx="14" ry="7" fill="#ffffff" opacity="0.65" transform="rotate(-15 52 56)" />

                                    <!-- Cute Blushing Cheeks -->
                                    <ellipse class="anim-bunny-cheeks" cx="37" cy="80" rx="8" ry="5" fill="url(#bunnyCheekGrad)" />
                                    <ellipse class="anim-bunny-cheeks" cx="83" cy="80" rx="8" ry="5" fill="url(#bunnyCheekGrad)" />

                                    <!-- Anime Eyes with Dual Specular Sparkles -->
                                    <g class="anim-bunny-eyes">
                                        <!-- Left Eye -->
                                        <ellipse cx="46" cy="71" rx="5.5" ry="7" fill="url(#bunnyEye3D)" />
                                        <circle cx="44.5" cy="68.5" r="2.2" fill="#ffffff" />
                                        <circle cx="48" cy="73" r="1.1" fill="#ffffff" />

                                        <!-- Right Eye -->
                                        <ellipse cx="74" cy="71" rx="5.5" ry="7" fill="url(#bunnyEye3D)" />
                                        <circle cx="72.5" cy="68.5" r="2.2" fill="#ffffff" />
                                        <circle cx="76" cy="73" r="1.1" fill="#ffffff" />
                                    </g>

                                    <!-- Kawaii Button Nose & Mouth (:3) -->
                                    <path d="M 58 77 C 59 75 61 75 62 77 C 62 78 58 78 58 77 Z" fill="#f43f5e" />
                                    <path d="M 55 81 C 57 84 60 84 60 81 C 60 84 63 84 65 81" stroke="#475569" stroke-width="1.6" stroke-linecap="round" fill="none" />

                                    <!-- Tiny Cute 3D Paws Holding a Soap Sponge -->
                                    <ellipse cx="44" cy="98" rx="8" ry="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.2" />
                                    <ellipse cx="76" cy="98" rx="8" ry="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.2" />
                                    <!-- Laundry Soap bar held by paws -->
                                    <rect x="50" y="93" width="20" height="9" rx="4" fill="url(#bunnyCrownGrad)" stroke="#38bdf8" stroke-width="1" />
                                    <circle cx="56" cy="97" r="1.5" fill="#ffffff" opacity="0.8" />
                                </svg>
                            </div>
                        </div>

                        <!-- Mascot Title -->
                        <div class="mb-2 position-relative" style="z-index: 2;">
                            <h6 class="fw-extrabold text-dark mb-0" style="letter-spacing: -0.3px;">
                                wishuwashere. AI Assistant
                            </h6>
                            <small class="text-secondary fw-semibold" style="font-size: 0.75rem;">
                                Siap memandu kasir &amp; akuntansi Anda 🧺✨
                            </small>
                        </div>

                        <!-- Mini Navigator Action Pills -->
                        <div class="d-flex flex-wrap justify-content-center gap-1 mt-1 position-relative" style="z-index: 2;">
                            <a href="<?= base_url('kasir') ?>" class="bunny-nav-pill" title="Buka Transaksi Kasir">
                                <span>🧺 Kasir</span>
                            </a>
                            <a href="<?= base_url('neraca-lajur') ?>" class="bunny-nav-pill" title="Lihat Neraca Lajur 10 Kolom">
                                <span>📊 Neraca Lajur</span>
                            </a>
                            <a href="<?= base_url('penyesuaian') ?>" class="bunny-nav-pill" title="Input Jurnal Penyesuaian">
                                <span>📑 Penyesuaian</span>
                            </a>
                            <a href="<?= base_url('laporan') ?>" class="bunny-nav-pill" title="Cetak Laporan Keuangan">
                                <span>📈 Laporan</span>
                            </a>
                        </div>

                        <!-- Interactive Clickable Tip Button -->
                        <button type="button" class="btn btn-sm mt-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-1 shadow-xs" onclick="talkToBunny()" style="background: #e0f2fe; border: 1px solid #bae6fd; color: #0284c7; border-radius: 10px; font-size: 0.75rem; padding: 5px 8px;">
                            <i class="fa-solid fa-wand-magic-sparkles text-info"></i>
                            <span>Klik Aku untuk Tips AI! 💬</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- 3. 4 KARTU STATISTIK (BIRU PASTEL & ELEMEN LAUNDRY LUCU) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Account Codes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-tags me-1"></i> Akun
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-primary-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-wallet anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">TOTAL ACCOUNT CODES</small>
                <h2 class="fw-bold my-1" style="color: #0369a1;">14</h2>
                <small class="text-secondary">Akun Bagan Standard 🧼</small>
            </div>
        </div>

        <!-- Card 2: Journal Entries -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-receipt me-1"></i> Jurnal
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-info-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-shirt anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">JOURNAL ENTRIES</small>
                <h2 class="fw-bold my-1" style="color: #0284c7;"><?= $total_order ?></h2>
                <small class="text-secondary">Transaksi Terinput 🧺</small>
            </div>
        </div>

        <!-- Card 3: Sedang Diproses (Laundry Washing) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-water me-1"></i> Proses
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-warning-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #fef9c3; color: #ca8a04;">
                        <i class="fa-solid fa-arrows-rotate anim-wash"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">DALAM PENGERJAAN</small>
                <h2 class="fw-bold text-warning my-1"><?= $sedang_diproses ?></h2>
                <small class="text-secondary">Cuci &amp; Setrika Aktif 👕</small>
            </div>
        </div>

        <!-- Card 4: Balance Status -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 h-100 text-center position-relative stat-card-cute">
                <span class="badge bg-success-subtle text-success border border-success-subtle position-absolute top-0 end-0 m-3">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Balance!
                </span>
                <div class="mt-2 mb-2 d-flex justify-content-center">
                    <div class="stat-card-icon border border-success-subtle" style="width: 58px; height: 58px; font-size: 1.6rem; background: #dcfce7; color: #16a34a;">
                        <i class="fa-solid fa-scale-balanced anim-float"></i>
                    </div>
                </div>
                <small class="text-secondary fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">BALANCE STATUS</small>
                <h2 class="fw-bold text-success my-1">BALANCED <i class="fa-solid fa-circle-check fs-5"></i></h2>
                <small class="text-secondary">Debit = Kredit Seimbang ✨</small>
            </div>
        </div>
    </div>

    <!-- 4. TABEL TRANSAKSI TERBARU & WIDGET SAMPING -->
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-2" style="border-color: #bae6fd !important;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="color: #0369a1;">
                        <i class="fa-solid fa-basket-shopping me-1 anim-float"></i> Transaksi Cucian wishuwashere.
                    </span>
                    <a href="<?= base_url('kasir') ?>" class="btn btn-sm btn-primary fw-bold" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); border: none;">
                        <i class="fa-solid fa-plus me-1"></i> Terima Cucian (Kasir)
                    </a>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tableTransaksi" style="border-color: #bae6fd;">
                            <thead>
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Pelanggan</th>
                                    <th>Paket Layanan</th>
                                    <th>Total Biaya</th>
                                    <th>Status Cucian</th>
                                    <th>Status Bayar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transaksi)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-secondary" style="background: #f8fdff;">
                                            <div class="anim-float mb-2" style="font-size: 2.5rem;">🧺</div>
                                            <strong class="d-block text-dark mb-1">Belum ada transaksi cucian yang diinput</strong>
                                            <small class="text-muted d-block mb-3">Silakan input data pelanggan dan order kasir pertama Anda!</small>
                                            <a href="<?= base_url('kasir') ?>" class="btn btn-sm btn-outline-primary fw-bold" style="border-color: #0284c7; color: #0284c7;">
                                                <i class="fa-solid fa-plus me-1"></i> Buat Order Baru
                                            </a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transaksi as $t): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#<?= esc($t['kode_transaksi']) ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= esc($t['nama_pelanggan']) ?></div>
                                                <?php if (!empty($t['no_hp'])): ?>
                                                    <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $t['no_hp'])) ?>" target="_blank" class="small text-decoration-none text-success">
                                                        <i class="fa-brands fa-whatsapp me-1"></i><?= esc($t['no_hp']) ?>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><?= esc($t['jenis_paket']) ?></span>
                                                <div class="small fw-bold mt-1 text-dark"><?= esc($t['nama_paket']) ?> (<?= esc($t['berat_qty']) ?> <?= $t['jenis_paket'] === 'kiloan' ? 'Kg' : 'Pcs' ?>)</div>
                                            </td>
                                            <td><strong class="text-primary">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></strong></td>
                                            <td>
                                                <?php
                                                    $badgeClass = 'bg-secondary';
                                                    if ($t['status_cucian'] === 'Sedang Dicuci') $badgeClass = 'bg-warning text-dark border border-warning';
                                                    elseif ($t['status_cucian'] === 'Proses Setrika') $badgeClass = 'bg-info text-dark border border-info';
                                                    elseif ($t['status_cucian'] === 'Siap Diambil') $badgeClass = 'bg-success text-white';
                                                    elseif ($t['status_cucian'] === 'Sudah Diambil') $badgeClass = 'bg-dark text-white';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= esc($t['status_cucian']) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($t['status_bayar'] === 'Lunas'): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success">Lunas (<?= esc($t['metode_bayar']) ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger">Belum Lunas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('kasir/cetak/' . $t['id']) ?>" target="_blank" class="btn btn-outline-primary" title="Cetak Nota"><i class="fa-solid fa-print"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Samping: Pencarian & Daftar Paket -->
        <div class="col-12 col-lg-4 d-flex flex-column gap-3">
            <div class="card p-3 border-2" style="border-color: #bae6fd !important;">
                <div class="card-header bg-transparent p-0 pb-2 mb-2 border-bottom" style="border-color: #bae6fd !important;">
                    <strong style="color: #0369a1;"><i class="fa-solid fa-magnifying-glass me-1"></i> Pencarian Cepat Nota</strong>
                </div>
                <input type="text" id="quickSearchInput" class="form-control" placeholder="Cari No Nota / Pelanggan...">
                <small class="text-muted mt-2" style="font-size: 0.75rem;"><i class="fa-solid fa-info-circle me-1 text-info"></i> Ketik untuk memfilter baris tabel di samping secara instan.</small>
            </div>

            <div class="card border-2" style="border-color: #bae6fd !important;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="color: #0369a1;"><i class="fa-solid fa-tags me-1 text-primary"></i> Paket Layanan Aktif</span>
                    <a href="<?= base_url('paket') ?>" class="small text-decoration-none fw-bold" style="color: #0284c7;">Kelola &rarr;</a>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($paket as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="background: #ffffff; border-color: #e0f2fe;">
                            <div>
                                <div class="fw-bold text-dark"><i class="fa-solid fa-shirt me-1 text-info small"></i> <?= esc($p['nama_paket']) ?></div>
                                <small class="text-muted">Estimasi: <?= esc($p['estimasi_hari']) ?> Hari</small>
                            </div>
                            <span class="badge bg-info-subtle text-primary-emphasis border border-info-subtle">
                                Rp <?= number_format($p['harga'], 0, ',', '.') ?> / <?= $p['jenis'] === 'kiloan' ? 'Kg' : 'Pcs' ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    // Live Search Filter Tabel
    const searchInput = document.getElementById('quickSearchInput');
    const topSearchInput = document.getElementById('topLiveSearch');
    
    function filterTable(keyword) {
        keyword = keyword.toLowerCase();
        const rows = document.querySelectorAll('#tableTransaksi tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', (e) => filterTable(e.target.value));
    if (topSearchInput) topSearchInput.addEventListener('input', (e) => filterTable(e.target.value));

    // ===== 3D BUNNY AI ASSISTANT INTERACTION =====
    const bunnyTips = [
        "Hai Kelompok 4! 🫧 Aku Fluffy, asisten AI laundry kamu. Mau aku antar ke menu mana sekarang?",
        "💡 Tip Kasir: Input transaksi kasir baru, jurnal umum & saldo kas terupdate otomatis lho! 🧺",
        "⚖️ Tip Akuntansi: Pastikan debit dan kredit di Neraca Lajur 10 Kolom selalu seimbang (balance)! ✨",
        "📑 Tip Penyesuaian: Jangan lupa cek Jurnal Penyesuaian beban sewa & perlengkapan akhir periode! 🧼",
        "📊 Tip Laporan: 4 Laporan pokok (Laba Rugi, Modal, Neraca, Arus Kas) siap diekspor / dicetak rapi! 💰",
        "🐰 Status Cloud: Database TiDB Cloud & Vercel online 100% aktif & siap dipakai demo! 🚀",
        "💖 Semangat menyelesaikan tugas Sistem Informasi Akuntansi Kelompok 4! Kalian luar biasa! 🌸"
    ];
    let bunnyTipIndex = 0;

    function talkToBunny() {
        const dialog = document.getElementById('bunnyDialogText');
        const trigger = document.getElementById('bunnyTrigger');
        if (!dialog) return;

        // Play gentle kawaii sparkle sound using Web Audio API
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.25);
        } catch (e) {
            // Audio context not allowed or blocked
        }

        // Cute bounce animation
        if (trigger) {
            trigger.style.transform = 'scale(1.22) rotate(4deg)';
            setTimeout(() => {
                trigger.style.transform = '';
            }, 300);
        }

        // Cycle tip
        bunnyTipIndex = (bunnyTipIndex + 1) % bunnyTips.length;
        dialog.style.opacity = '0';
        dialog.style.transform = 'translateY(5px)';
        setTimeout(() => {
            dialog.textContent = bunnyTips[bunnyTipIndex];
            dialog.style.opacity = '1';
            dialog.style.transform = 'translateY(0)';
        }, 180);
    }
</script>

<?= $this->endSection() ?>
