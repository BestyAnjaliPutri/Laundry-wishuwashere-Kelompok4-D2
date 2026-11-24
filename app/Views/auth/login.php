<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - wishuwashere. 🫧 SIA Laundry</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-blue: #0284c7;
            --primary-light: #38bdf8;
            --primary-dark: #0369a1;
            --pastel-sky: #f0f9ff;
            --pastel-bubble: #e0f2fe;
            --border-soft: #bae6fd;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, #e0f2fe 0%, #f0f9ff 45%, #eff6ff 80%, #fdf4ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Ambient floating soap bubbles */
        .bubble-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .ambient-bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255,255,255,0.95), rgba(186,230,253,0.5) 50%, rgba(56,189,248,0.2) 100%);
            box-shadow: inset 0 0 10px rgba(255,255,255,0.9), 0 4px 12px rgba(14,165,233,0.15);
            animation: floatSlow linear infinite;
        }

        .b1 { width: 45px; height: 45px; left: 6%; animation-duration: 14s; animation-delay: 0s; }
        .b2 { width: 70px; height: 70px; left: 16%; animation-duration: 19s; animation-delay: 2s; }
        .b3 { width: 35px; height: 35px; left: 32%; animation-duration: 12s; animation-delay: 4s; }
        .b4 { width: 55px; height: 55px; left: 52%; animation-duration: 16s; animation-delay: 1s; }
        .b5 { width: 40px; height: 40px; left: 74%; animation-duration: 13s; animation-delay: 3s; }
        .b6 { width: 85px; height: 85px; left: 88%; animation-duration: 21s; animation-delay: 5s; }

        @keyframes floatSlow {
            0% {
                transform: translateY(105vh) scale(0.7) rotate(0deg);
                opacity: 0;
            }
            15% {
                opacity: 0.85;
            }
            85% {
                opacity: 0.85;
            }
            100% {
                transform: translateY(-120px) scale(1.05) rotate(360deg);
                opacity: 0;
            }
        }

        /* Card Container */
        .login-wrapper {
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 2;
            margin: auto;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1.5px solid rgba(186, 230, 253, 0.9);
            border-radius: 28px;
            box-shadow: 0 25px 70px -15px rgba(2, 132, 199, 0.2), 0 8px 24px -6px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        /* Top Header */
        .login-header-banner {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 60%, #e0f2fe 100%);
            border-bottom: 1.5px solid var(--border-soft);
            padding: 32px 28px 24px;
            text-align: center;
            position: relative;
        }

        /* Laundry Washing Machine / Shirt Emblem */
        .brand-emblem-wrap {
            position: relative;
            width: 76px;
            height: 76px;
            margin: 0 auto 14px;
        }

        .brand-emblem-pulse {
            position: absolute;
            inset: -4px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.4), rgba(2, 132, 199, 0.2));
            filter: blur(6px);
            animation: pulseGlow 3s ease-in-out infinite alternate;
        }

        @keyframes pulseGlow {
            from { transform: scale(0.95); opacity: 0.6; }
            to   { transform: scale(1.08); opacity: 1; }
        }

        .brand-emblem {
            position: relative;
            width: 76px;
            height: 76px;
            border-radius: 22px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 2.1rem;
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .brand-emblem:hover {
            transform: scale(1.08) rotate(4deg);
        }

        .brand-title {
            font-weight: 900;
            font-size: 1.85rem;
            letter-spacing: -0.7px;
            color: #0369a1;
            margin: 0 0 4px;
        }

        .brand-subtitle-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            color: #0284c7;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.3px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
        }

        /* TIM KELOMPOK 4 SHOWCASE */
        .kelompok-section {
            background: linear-gradient(135deg, #f8fdff 0%, #f0f9ff 100%);
            border: 1.5px solid #bae6fd;
            border-radius: 20px;
            padding: 16px 18px;
            margin: 20px 24px 0;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.06);
        }

        .kelompok-header-tag {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1.5px dashed #bae6fd;
        }

        .kelompok-title {
            font-weight: 800;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #0369a1;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .kelompok-badge-d2 {
            background: #dbeafe;
            color: #1e40af;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 12px;
        }

        /* Member Grid 2 Columns */
        .members-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 12px;
        }

        @media (max-width: 480px) {
            .members-grid {
                grid-template-columns: 1fr;
            }
        }

        .member-card-item {
            background: #ffffff;
            border: 1px solid #e0f2fe;
            border-radius: 12px;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .member-card-item:hover {
            border-color: #7dd3fc;
            background: #f0f9ff;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(2, 132, 199, 0.1);
        }

        .member-avatar {
            width: 28px;
            height: 28px;
            border-radius: 9px;
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.72rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .member-info {
            overflow: hidden;
            line-height: 1.2;
        }

        .member-name {
            font-weight: 700;
            font-size: 0.75rem;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .member-nim-tag {
            font-family: monospace;
            font-size: 0.65rem;
            color: #0284c7;
            font-weight: 600;
        }

        /* FORM BODY */
        .login-body-content {
            padding: 24px 28px 28px;
        }

        .form-section-title {
            font-weight: 800;
            font-size: 0.95rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .form-section-title i {
            color: var(--primary-blue);
        }

        .input-group-modern {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-modern label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        .input-box-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-box-icon {
            position: absolute;
            left: 14px;
            color: #0284c7;
            font-size: 0.95rem;
            pointer-events: none;
            z-index: 3;
            transition: color 0.2s;
        }

        .form-control-modern {
            width: 100%;
            background: #f8fdff;
            border: 1.5px solid var(--border-soft);
            border-radius: 14px;
            padding: 12px 42px 12px 42px;
            font-size: 0.92rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .form-control-modern:focus {
            background: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.16);
            outline: none;
        }

        .form-control-modern::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #94a3b8;
            padding: 6px 8px;
            cursor: pointer;
            z-index: 3;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .btn-toggle-eye:hover {
            color: var(--primary-blue);
        }

        /* SUBMIT BUTTON */
        .btn-submit-login {
            width: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.98rem;
            letter-spacing: 0.3px;
            padding: 13px 20px;
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-submit-login:hover {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            box-shadow: 0 8px 26px rgba(2, 132, 199, 0.45);
            transform: translateY(-2px);
            color: #ffffff;
        }

        .btn-submit-login:active {
            transform: scale(0.98);
        }

        /* QUICK AUTOFILL BAR */
        .quick-fill-card {
            background: #f0fdf4;
            border: 1.5px dashed #86efac;
            border-radius: 14px;
            padding: 10px 14px;
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.78rem;
        }

        .quick-fill-btn {
            background: #16a34a;
            color: #ffffff;
            border: none;
            font-weight: 700;
            font-size: 0.72rem;
            padding: 5px 11px;
            border-radius: 8px;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .quick-fill-btn:hover {
            background: #15803d;
            transform: scale(1.04);
        }

        /* FOOTER BRANDING */
        .login-footer-text {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Bubble Burst on Click */
        .click-bubble {
            position: fixed;
            pointer-events: none;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.98), rgba(186,230,253,.8) 45%, rgba(56,189,248,.5) 75%, rgba(14,165,233,.35) 100%);
            border: 1px solid rgba(255,255,255,0.9);
            z-index: 99999;
            transform: translate(-50%, -50%);
            animation: bubbleBurst 0.75s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        @keyframes bubbleBurst {
            0%   { transform: translate(-50%, -50%) scale(0.4); opacity: 1; }
            60%  { opacity: 0.9; }
            100% { transform: translate(-50%, -50%) translate(var(--tx), var(--ty)) scale(var(--scale)); opacity: 0; }
        }
    </style>
</head>
<body>

    <!-- Ambient floating bubbles -->
    <div class="bubble-bg">
        <div class="ambient-bubble b1"></div>
        <div class="ambient-bubble b2"></div>
        <div class="ambient-bubble b3"></div>
        <div class="ambient-bubble b4"></div>
        <div class="ambient-bubble b5"></div>
        <div class="ambient-bubble b6"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- HEADER BANNER -->
            <div class="login-header-banner">
                <div class="brand-emblem-wrap">
                    <div class="brand-emblem-pulse"></div>
                    <div class="brand-emblem">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                </div>
                <h2 class="brand-title">wishuwashere.</h2>
                <div class="brand-subtitle-pill">
                    <span>🫧</span>
                    <span>SISTEM INFORMASI AKUNTANSI LAUNDRY</span>
                    <span>✨</span>
                </div>
            </div>

            <!-- KELOMPOK 4 SHOWCASE CARD -->
            <div class="kelompok-section">
                <div class="kelompok-header-tag">
                    <div class="kelompok-title">
                        <i class="fa-solid fa-users" style="color: #0284c7;"></i>
                        <span>Kelompok 4 &mdash; Komak Laundry</span>
                    </div>
                    <span class="kelompok-badge-d2">Kelas D2</span>
                </div>
                <div class="members-grid">
                    <div class="member-card-item">
                        <div class="member-avatar">W</div>
                        <div class="member-info">
                            <span class="member-name">Wardiansyach</span>
                            <span class="member-nim-tag">J0414241069</span>
                        </div>
                    </div>
                    <div class="member-card-item">
                        <div class="member-avatar">B</div>
                        <div class="member-info">
                            <span class="member-name">Besty Anjali Putri S.</span>
                            <span class="member-nim-tag">J0414241089</span>
                        </div>
                    </div>
                    <div class="member-card-item">
                        <div class="member-avatar">S</div>
                        <div class="member-info">
                            <span class="member-name">Solehatus Fuadah</span>
                            <span class="member-nim-tag">J0414241147</span>
                        </div>
                    </div>
                    <div class="member-card-item">
                        <div class="member-avatar">D</div>
                        <div class="member-info">
                            <span class="member-name">Muhammad Dwiki R.</span>
                            <span class="member-nim-tag">J0414241149</span>
                        </div>
                    </div>
                    <div class="member-card-item">
                        <div class="member-avatar">P</div>
                        <div class="member-info">
                            <span class="member-name">Putri Widyastuti</span>
                            <span class="member-nim-tag">J0414241198</span>
                        </div>
                    </div>
                    <div class="member-card-item">
                        <div class="member-avatar">M</div>
                        <div class="member-info">
                            <span class="member-name">Muhammad Satria A.R</span>
                            <span class="member-nim-tag">J0414241298</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LOGIN FORM BODY -->
            <div class="login-body-content">
                <div class="form-section-title">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Sistem</span>
                </div>

                <!-- ALERTS -->
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 border-0 shadow-sm" 
                         style="background:#fef2f2; color:#b91c1c; border-left:4px solid #ef4444 !important; border-radius:12px; font-size:0.86rem; padding:10px 14px;">
                        <i class="fa-solid fa-circle-exclamation fs-5 flex-shrink-0"></i>
                        <div><?= session('error') ?></div>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-3 border-0 shadow-sm" 
                         style="background:#f0fdf4; color:#15803d; border-left:4px solid #22c55e !important; border-radius:12px; font-size:0.86rem; padding:10px 14px;">
                        <i class="fa-solid fa-circle-check fs-5 flex-shrink-0"></i>
                        <div><?= session('success') ?></div>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('login/do') ?>">
                    <?= csrf_field() ?>

                    <!-- USERNAME INPUT -->
                    <div class="input-group-modern">
                        <label for="inputUsername">Username Akun</label>
                        <div class="input-box-wrapper">
                            <i class="fa-solid fa-user input-box-icon"></i>
                            <input type="text" id="inputUsername" name="username" class="form-control-modern" 
                                   placeholder="Masukkan username" required autocomplete="username"
                                   value="<?= old('username') ?>">
                        </div>
                    </div>

                    <!-- PASSWORD INPUT -->
                    <div class="input-group-modern">
                        <label for="inputPassword">Password</label>
                        <div class="input-box-wrapper">
                            <i class="fa-solid fa-lock input-box-icon"></i>
                            <input type="password" id="inputPassword" name="password" class="form-control-modern" 
                                   placeholder="Masukkan password" required autocomplete="current-password">
                            <button type="button" class="btn-toggle-eye" id="btnTogglePassword" title="Lihat Password">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- LOGIN SUBMIT BUTTON -->
                    <button type="submit" class="btn-submit-login">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login ke Dashboard</span>
                    </button>
                </form>

                <!-- QUICK AUTO-FILL HELPER CHIP -->
                <div class="quick-fill-card">
                    <div>
                        <strong style="color:#166534;">Akun Login:</strong>
                        <span style="color:#15803d;">kelompok 4 / kelompok4</span>
                    </div>
                    <button type="button" class="quick-fill-btn" id="btnQuickFill">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Isi Cepat
                    </button>
                </div>
            </div>

        </div>

        <!-- FOOTER COPYRIGHT -->
        <div class="login-footer-text">
            <span>&copy; <?= date('Y') ?> wishuwashere. Laundry & Care &mdash; Kelompok 4 D2 IPB</span>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Password Show/Hide Toggle
        const btnTogglePassword = document.getElementById('btnTogglePassword');
        const inputPassword = document.getElementById('inputPassword');
        const eyeIcon = document.getElementById('eyeIcon');

        if (btnTogglePassword && inputPassword && eyeIcon) {
            btnTogglePassword.addEventListener('click', function() {
                if (inputPassword.type === 'password') {
                    inputPassword.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    inputPassword.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }

        // Quick Autofill Handler
        const btnQuickFill = document.getElementById('btnQuickFill');
        const inputUsername = document.getElementById('inputUsername');

        if (btnQuickFill && inputUsername && inputPassword) {
            btnQuickFill.addEventListener('click', function() {
                inputUsername.value = 'kelompok 4';
                inputPassword.value = 'kelompok4';
                inputUsername.focus();
            });
        }

        // Interactive Click Bubble Burst
        document.addEventListener('pointerdown', function(e) {
            // Skip button click to prevent interference
            if (e.target.closest('button') || e.target.closest('input')) return;

            const x = e.clientX;
            const y = e.clientY;

            for (let i = 0; i < 7; i++) {
                const b = document.createElement('div');
                b.className = 'click-bubble';
                const size = Math.floor(Math.random() * 16) + 12;
                b.style.cssText = `width:${size}px; height:${size}px; left:${x}px; top:${y}px;`;

                const tx = (Math.random() - 0.5) * 90;
                const ty = -(Math.random() * 70 + 25);
                const scale = (Math.random() * 0.4 + 1.1).toFixed(2);
                const dur = (Math.random() * 0.25 + 0.6).toFixed(2);

                b.style.setProperty('--tx', tx + 'px');
                b.style.setProperty('--ty', ty + 'px');
                b.style.setProperty('--scale', scale);
                b.style.animationDuration = dur + 's';

                document.body.appendChild(b);
                setTimeout(() => b.remove(), dur * 1000);
            }
        });
    </script>
</body>
</html>
