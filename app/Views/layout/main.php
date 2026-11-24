<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'wishuwashere.' ?> - Sistem Informasi Akuntansi & Kasir Laundry</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ==================== MODERN SOFT LAUNDRY AESTHETIC ==================== */
        .card {
            border-radius: 16px !important;
            overflow: hidden;
        }
        .btn {
            border-radius: 10px;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }

        :root {
            --primary:        #0ea5e9;
            --primary-dark:   #0369a1;
            --primary-mid:    #0284c7;
            --sidebar-width:  265px;
            --body-bg:        #f0f8ff;
            --card-bg:        #ffffff;
            --pastel-blue:    #e0f2fe;
            --pastel-border:  #bae6fd;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            background-image:
                radial-gradient(circle at 10% 15%, rgba(56, 189, 248, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(244, 114, 182, 0.05) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(186, 230, 253, 0.07) 0%, transparent 60%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ==================== FLOATING BUBBLES ANIMATION ==================== */
        .bubble-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            border-radius: 50% !important; /* dekoratif */
            opacity: 0;
            animation: floatUp linear infinite;
        }

        .bubble:nth-child(1)  { width: 18px; height: 18px; left:  6%; background: rgba(14,165,233,0.18); animation-duration: 9s;  animation-delay: 0s;   }
        .bubble:nth-child(2)  { width: 26px; height: 26px; left: 14%; background: rgba(56,189,248,0.14); animation-duration: 12s; animation-delay: 1.5s; }
        .bubble:nth-child(3)  { width: 12px; height: 12px; left: 22%; background: rgba(14,165,233,0.22); animation-duration: 8s;  animation-delay: 3s;   }
        .bubble:nth-child(4)  { width: 22px; height: 22px; left: 32%; background: rgba(125,211,252,0.15); animation-duration: 10s; animation-delay: 0.5s; }
        .bubble:nth-child(5)  { width: 14px; height: 14px; left: 45%; background: rgba(14,165,233,0.20); animation-duration: 7s;  animation-delay: 2s;   }
        .bubble:nth-child(6)  { width: 30px; height: 30px; left: 56%; background: rgba(56,189,248,0.10); animation-duration: 14s; animation-delay: 4s;   }
        .bubble:nth-child(7)  { width: 16px; height: 16px; left: 66%; background: rgba(14,165,233,0.17); animation-duration: 9s;  animation-delay: 1s;   }
        .bubble:nth-child(8)  { width: 20px; height: 20px; left: 76%; background: rgba(125,211,252,0.18); animation-duration: 11s; animation-delay: 3.5s; }
        .bubble:nth-child(9)  { width: 10px; height: 10px; left: 86%; background: rgba(14,165,233,0.25); animation-duration: 6s;  animation-delay: 0.8s; }
        .bubble:nth-child(10) { width: 24px; height: 24px; left: 93%; background: rgba(56,189,248,0.12); animation-duration: 13s; animation-delay: 2.5s; }

        @keyframes floatUp {
            0%   { transform: translateY(100vh) scale(0.6); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.7; }
            100% { transform: translateY(-120px) scale(1.1); opacity: 0; }
        }

        /* ==================== INTERACTIVE CLICK BUBBLE BURST ==================== */
        .click-bubble {
            position: fixed;
            pointer-events: none;
            border-radius: 50% !important;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.95), rgba(186, 230, 253, 0.75) 45%, rgba(56, 189, 248, 0.5) 75%, rgba(14, 165, 233, 0.35) 100%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.6), inset 0 0 5px rgba(255, 255, 255, 0.9);
            z-index: 99999;
            transform: translate(-50%, -50%);
            animation: bubbleBurst 0.75s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        .click-water-ripple {
            position: fixed;
            pointer-events: none;
            border-radius: 50% !important;
            border: 2px solid rgba(56, 189, 248, 0.65);
            transform: translate(-50%, -50%);
            z-index: 99998;
            animation: rippleExpand 0.5s ease-out forwards;
        }

        @keyframes bubbleBurst {
            0% {
                transform: translate(-50%, -50%) translate(0, 0) scale(0.4);
                opacity: 1;
            }
            60% {
                opacity: 0.9;
            }
            100% {
                transform: translate(-50%, -50%) translate(var(--tx), var(--ty)) scale(var(--scale));
                opacity: 0;
            }
        }

        @keyframes rippleExpand {
            0% {
                width: 0px;
                height: 0px;
                opacity: 0.9;
            }
            100% {
                width: 65px;
                height: 65px;
                opacity: 0;
            }
        }

        /* Bouncy Click on Buttons */
        .btn:active, .nav-link-custom:active, .btn-action-edit:active, .btn-action-delete:active {
            transform: scale(0.96) !important;
            transition: transform 0.08s ease !important;
        }

        /* ==================== SIDEBAR PASTEL CERAH & CANTIK ==================== */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fdff 50%, #edf7ff 100%);
            border-right: 2px solid #bae6fd;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease-in-out;
            box-shadow: 2px 0 15px rgba(2, 132, 199, 0.08);
            overflow: hidden;
        }

        /* Brand Header di Sidebar */
        .sidebar-brand {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            border-bottom: 2px solid #bae6fd;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
            transition: transform 0.2s ease;
        }
        .sidebar-brand:hover .brand-icon {
            transform: scale(1.05);
        }

        .brand-text h5 {
            font-weight: 800;
            font-size: 1.15rem;
            margin: 0;
            color: #0369a1;
            letter-spacing: -0.5px;
            text-transform: lowercase;
        }

        .brand-text span {
            font-size: 0.68rem;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: block;
        }

        /* Menu Container */
        .sidebar-menu {
            padding: 12px 0;
            overflow-y: auto;
            flex-grow: 1;
            position: relative;
            z-index: 1;
            scrollbar-width: thin;
            scrollbar-color: #bae6fd transparent;
        }

        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: #bae6fd; }

        .menu-header {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: #0284c7;
            padding: 14px 20px 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0f2fe;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            color: #334155;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.18s ease;
            position: relative;
            margin: 2px 8px;
        }

        /* Nav Icon dengan kotak pastel manis warna-warni */
        .nav-link-custom .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        /* Variasi warna pastel icon */
        .icon-dashboard  { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
        .icon-akun       { background: #ffe4e6; color: #e11d48; border-color: #fecdd3; }
        .icon-paket      { background: #fef3c7; color: #d97706; border-color: #fde68a; }
        .icon-pelanggan  { background: #f3e8ff; color: #9333ea; border-color: #e9d5ff; }
        .icon-kasir      { background: #d1fae5; color: #059669; border-color: #a7f3d0; }
        .icon-jurnal     { background: #cffafe; color: #0891b2; border-color: #a5f3fc; }
        .icon-neraca     { background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe; }
        .icon-laporan    { background: #fee2e2; color: #dc2626; border-color: #fecaca; }

        .nav-link-custom:hover {
            color: #0369a1;
            background-color: #f0f9ff;
            border-left-color: #0ea5e9;
            transform: translateX(2px);
        }

        .nav-link-custom:hover .nav-icon {
            transform: scale(1.08);
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.2);
        }

        .nav-link-custom.active {
            color: #0369a1;
            background: linear-gradient(90deg, #e0f2fe 0%, #f0f9ff 100%);
            border-left-color: #0284c7;
            font-weight: 700;
        }

        .nav-link-custom.active .nav-icon {
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
            font-weight: bold;
        }

        /* ==================== LAUNDRY PASTEL BLUE PILL MENU STYLES ==================== */
        .pill-menu-item {
            display: flex;
            align-items: center;
            background: linear-gradient(90deg, #0ea5e9 0%, #38bdf8 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 28px;
            padding: 9px 18px 9px 12px;
            margin: 4px 10px;
            box-shadow: 0 3px 10px rgba(14, 165, 233, 0.22);
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .pill-menu-item:hover {
            color: #ffffff !important;
            transform: translateX(3px);
            background: linear-gradient(90deg, #0284c7 0%, #0ea5e9 100%);
            box-shadow: 0 5px 16px rgba(2, 132, 199, 0.35);
        }

        .pill-menu-item.active {
            background: linear-gradient(90deg, #0369a1 0%, #0284c7 100%);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.45);
            font-weight: 800;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
        }

        .pill-dropdown-container {
            margin: 4px 10px;
        }

        .pill-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #0ea5e9 0%, #38bdf8 100%);
            color: #ffffff;
            border-radius: 28px;
            padding: 9px 18px 9px 12px;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(14, 165, 233, 0.22);
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .pill-dropdown-toggle:hover {
            transform: translateX(3px);
            background: linear-gradient(90deg, #0284c7 0%, #0ea5e9 100%);
            box-shadow: 0 5px 16px rgba(2, 132, 199, 0.35);
        }

        .pill-dropdown-container.open .pill-dropdown-toggle,
        .pill-dropdown-toggle.active {
            background: linear-gradient(90deg, #0284c7 0%, #0ea5e9 100%);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        }

        .pill-dropdown-toggle .chevron-icon {
            font-size: 0.85rem;
            color: #ffffff;
            transition: transform 0.25s ease;
        }

        .pill-dropdown-container.open .chevron-icon {
            transform: rotate(180deg);
        }

        .pill-squircle-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: #ffffff;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .pill-label {
            font-weight: 700;
            font-size: 0.92rem;
            letter-spacing: 0.2px;
            color: #ffffff;
        }

        .pill-sub-items {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 5px 0 2px 8px;
            margin-top: 3px;
        }

        .pill-dropdown-container.open .pill-sub-items,
        .pill-sub-items.show {
            display: flex;
        }

        .sub-pill-link {
            background: linear-gradient(90deg, #38bdf8 0%, #60a5fa 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 24px;
            padding: 8px 16px 8px 10px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(56, 189, 248, 0.25);
            transition: all 0.2s ease;
        }

        .sub-pill-link:hover {
            color: #ffffff !important;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.38);
            background: linear-gradient(90deg, #0ea5e9 0%, #38bdf8 100%);
        }

        .sub-pill-link.active {
            background: linear-gradient(90deg, #0369a1 0%, #0284c7 100%);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.45);
            font-weight: 800;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
        }

        .sub-pill-link .sub-squircle-icon {
            width: 28px;
            height: 28px;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #ffffff;
            margin-right: 11px;
            flex-shrink: 0;
        }

        .sub-pill-link .sub-label {
            font-weight: 700;
            font-size: 0.88rem;
            color: #ffffff;
            letter-spacing: 0.2px;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 14px 18px;
            background: #ffffff;
            border-top: 1.5px solid #bae6fd;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .sidebar-footer .laundry-anim {
            font-size: 1.2rem;
            display: inline-block;
            animation: spinLaundry 6s linear infinite;
        }

        @keyframes spinLaundry {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ==================== MAIN WRAPPER ==================== */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease-in-out;
        }

        /* FIX BOOTSTRAP MODAL STACKING & CLICKABLE */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1065 !important;
        }

        /* ==================== TOP NAVBAR ==================== */
        .top-navbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 2px solid #bae6fd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.05);
        }

        .search-box {
            position: relative;
            max-width: 300px;
            width: 100%;
        }

        .search-box input {
            padding-left: 36px;
            border: 1.5px solid #bae6fd;
            font-size: 0.87rem;
            background-color: #f8fdff;
            transition: all 0.2s;
        }

        .search-box input:focus {
            border-color: #0ea5e9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.15);
        }

        .search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .admin-badge {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
            padding: 7px 16px;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        }

        /* ==================== CARDS & UI (LANCIP PRESISI) ==================== */
        .card {
            border: 1.5px solid #bae6fd;
            box-shadow: 0 2px 10px rgba(14,165,233,0.06);
            background: #ffffff;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 4px 18px rgba(14,165,233,0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-bottom: 1.5px solid #bae6fd;
            font-weight: 700;
            padding: 13px 20px;
        }

        .stat-card-icon {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn {
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.18s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border-color: #0284c7;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            border-color: #0369a1;
            box-shadow: 0 4px 14px rgba(14,165,233,0.35);
        }

        .table thead th {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            color: #0369a1;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 2px solid #7dd3fc;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f9ff;
        }

        .badge {
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .nav-tabs .nav-link {
            border-radius: 0;
            color: #64748b;
            font-weight: 700;
            padding: 11px 18px;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-mid);
            background: #f0f9ff;
        }

        .nav-tabs .nav-link.active {
            color: #0369a1;
            background-color: #ffffff;
            border-color: #bae6fd #bae6fd #ffffff;
            border-top: 3px solid #0ea5e9;
        }

        .form-control, .form-select {
            border: 1.5px solid #bae6fd;
            background-color: #f8fdff;
            transition: all 0.18s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.15);
            background-color: #ffffff;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #15803d;
        }

        .alert-danger {
            background: #fff1f2;
            border: 1.5px solid #fda4af;
            color: #be123c;
        }

        /* ==================== FOOTER ==================== */
        .main-footer {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            border-top: 1.5px solid #bae6fd;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 0.82rem;
            color: #475569;
        }

        .footer-brand {
            font-weight: 800;
            color: #0284c7;
        }

        .footer-icons {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
        }

        @media (max-width: 575.98px) {
            .admin-info-text { display: none; }
        }
    </style>
</head>
<body>

    <!-- ===== FLOATING BUBBLES BACKGROUND ===== -->
    <div class="bubble-bg">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <!-- ===== SIDEBAR PASTEL CERAH ===== -->
    <aside id="sidebar">
        <!-- BRAND HEADER LAUNDRY -->
        <a href="<?= base_url() ?>" class="sidebar-brand">
            <div class="brand-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="brand-text">
                <h5>wishuwashere.</h5>
                <span>🫧 SIA Laundry &amp; Care</span>
            </div>
        </a>

        <!-- MENU ITEMS (SESUAI URUTAN REFERENSI DENGAN TEMA LAUNDRY) -->
        <div class="sidebar-menu">

            <div class="menu-header">Dashboard</div>
            <a href="<?= base_url() ?>" class="pill-menu-item <?= ($menu ?? '') === 'dashboard' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-gauge"></i></div>
                <span class="pill-label">Dashboard</span>
            </a>

            <!-- 1. KODE AKUN (DROPDOWN: AKUN 1, 2, 3) -->
            <?php 
                $isAkunOpen = in_array($menu ?? '', ['akun', 'akun1', 'akun2', 'akun3']);
            ?>
            <div class="pill-dropdown-container <?= $isAkunOpen ? 'open' : '' ?>" id="kodeAkunContainer">
                <div class="pill-dropdown-toggle <?= $isAkunOpen ? 'active' : '' ?>" id="btnToggleKodeAkun">
                    <div class="d-flex align-items-center">
                        <div class="pill-squircle-icon">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <span class="pill-label">Kode Akun</span>
                    </div>
                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                </div>

                <div class="pill-sub-items <?= $isAkunOpen ? 'show' : '' ?>" id="kodeAkunSubmenu">
                    <!-- Akun - 1 -->
                    <a href="<?= base_url('akun/1') ?>" class="sub-pill-link <?= ($menu ?? '') === 'akun1' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span class="sub-label">Akun - 1</span>
                    </a>

                    <!-- Akun - 2 -->
                    <a href="<?= base_url('akun/2') ?>" class="sub-pill-link <?= ($menu ?? '') === 'akun2' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-bars-staggered"></i>
                        </div>
                        <span class="sub-label">Akun - 2</span>
                    </a>

                    <!-- Akun - 3 -->
                    <a href="<?= base_url('akun/3') ?>" class="sub-pill-link <?= in_array($menu ?? '', ['akun', 'akun3']) ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        <span class="sub-label">Akun - 3</span>
                    </a>
                </div>
            </div>

            <!-- 2. JURNAL UMUM -->
            <a href="<?= base_url('jurnal') ?>" class="pill-menu-item <?= ($menu ?? '') === 'jurnal' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <span class="pill-label">Jurnal Umum</span>
            </a>

            <!-- 3. JURNAL PENYESUAIAN (AJE) -->
            <a href="<?= base_url('penyesuaian') ?>" class="pill-menu-item <?= ($menu ?? '') === 'penyesuaian' ? 'active' : '' ?>" style="<?= ($menu ?? '') === 'penyesuaian' ? '' : '' ?>">
                <div class="pill-squircle-icon" style="<?= ($menu ?? '') === 'penyesuaian' ? '' : 'background:rgba(147,51,234,0.25);' ?>">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <span class="pill-label">Jurnal Penyesuaian</span>
            </a>

            <!-- 4. POSTING (BUKU BESAR) -->
            <a href="<?= base_url('buku-besar') ?>" class="pill-menu-item <?= ($menu ?? '') === 'buku_besar' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-book-bookmark"></i></div>
                <span class="pill-label">Posting (Buku Besar)</span>
            </a>

            <!-- 5. NERACA SALDO -->
            <a href="<?= base_url('neraca-saldo') ?>" class="pill-menu-item <?= ($menu ?? '') === 'neraca_saldo' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                <span class="pill-label">Neraca Saldo</span>
            </a>

            <!-- 6. NERACA LAJUR -->
            <a href="<?= base_url('neraca-lajur') ?>" class="pill-menu-item <?= ($menu ?? '') === 'neraca_lajur' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-table-cells"></i></div>
                <span class="pill-label">Neraca Lajur</span>
            </a>

            <!-- 7. TRANSAKSI (DROPDOWN: TRANSAKSI JURNAL, KASIR) -->
            <?php 
                $isTransaksiOpen = in_array($menu ?? '', ['jurnal_tambah', 'kasir']);
            ?>
            <div class="pill-dropdown-container <?= $isTransaksiOpen ? 'open' : '' ?>" id="transaksiContainer">
                <div class="pill-dropdown-toggle <?= $isTransaksiOpen ? 'active' : '' ?>" id="btnToggleTransaksi">
                    <div class="d-flex align-items-center">
                        <div class="pill-squircle-icon">
                            <i class="fa-solid fa-table-columns"></i>
                        </div>
                        <span class="pill-label">Transaksi</span>
                    </div>
                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                </div>

                <div class="pill-sub-items <?= $isTransaksiOpen ? 'show' : '' ?>" id="transaksiSubmenu">
                    <!-- Transaksi Jurnal -->
                    <a href="<?= base_url('jurnal/tambah') ?>" class="sub-pill-link <?= ($menu ?? '') === 'jurnal_tambah' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <span class="sub-label">Transaksi Jurnal</span>
                    </a>

                    <!-- Kasir / Order Laundry (Fitur Utama Laundry) -->
                    <a href="<?= base_url('kasir') ?>" class="sub-pill-link <?= ($menu ?? '') === 'kasir' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-cash-register"></i>
                        </div>
                        <span class="sub-label">Kasir / Order Baru</span>
                    </a>
                </div>
            </div>

            <!-- 7. LAPORAN KEUANGAN (DROPDOWN: LABA RUGI, PERUBAHAN MODAL, NERACA, ARUS KAS) -->
            <?php 
                $isLaporanOpen = ($menu ?? '') === 'laporan';
                $curTab = $_GET['tab'] ?? 'labarugi';
            ?>
            <div class="pill-dropdown-container <?= $isLaporanOpen ? 'open' : '' ?>" id="laporanContainer">
                <div class="pill-dropdown-toggle <?= $isLaporanOpen ? 'active' : '' ?>" id="btnToggleLaporan">
                    <div class="d-flex align-items-center">
                        <div class="pill-squircle-icon">
                            <i class="fa-solid fa-table-cells-large"></i>
                        </div>
                        <span class="pill-label">Laporan Keuangan</span>
                    </div>
                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                </div>

                <div class="pill-sub-items <?= $isLaporanOpen ? 'show' : '' ?>" id="laporanSubmenu">
                    <!-- Laba Rugi -->
                    <a href="<?= base_url('laporan?tab=labarugi') ?>" class="sub-pill-link <?= $isLaporanOpen && $curTab === 'labarugi' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <span class="sub-label">Laba Rugi</span>
                    </a>

                    <!-- Perubahan Modal -->
                    <a href="<?= base_url('laporan?tab=ekuitas') ?>" class="sub-pill-link <?= $isLaporanOpen && $curTab === 'ekuitas' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <span class="sub-label">Perubahan Modal</span>
                    </a>

                    <!-- Neraca -->
                    <a href="<?= base_url('laporan?tab=neraca') ?>" class="sub-pill-link <?= $isLaporanOpen && $curTab === 'neraca' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <span class="sub-label">Neraca</span>
                    </a>

                    <!-- Arus Kas -->
                    <a href="<?= base_url('laporan?tab=aruskas') ?>" class="sub-pill-link <?= $isLaporanOpen && $curTab === 'aruskas' ? 'active' : '' ?>">
                        <div class="sub-squircle-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <span class="sub-label">Arus Kas</span>
                    </a>
                </div>
            </div>

            <!-- MASTER LAUNDRY (TETAP ADA LENGKAP) -->
            <div class="menu-header">Master Laundry</div>
            <a href="<?= base_url('paket') ?>" class="pill-menu-item <?= ($menu ?? '') === 'paket' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-shirt"></i></div>
                <span class="pill-label">Paket Laundry</span>
            </a>
            <a href="<?= base_url('pelanggan') ?>" class="pill-menu-item <?= ($menu ?? '') === 'pelanggan' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-users"></i></div>
                <span class="pill-label">Data Pelanggan</span>
            </a>

            <!-- ADMINISTRASI (TETAP ADA LENGKAP) -->
            <div class="menu-header">Administrasi</div>
            <a href="<?= base_url('users') ?>" class="pill-menu-item <?= ($menu ?? '') === 'users' ? 'active' : '' ?>">
                <div class="pill-squircle-icon"><i class="fa-solid fa-users-gear"></i></div>
                <span class="pill-label">Manajemen User</span>
            </a>

        </div>

        <!-- SIDEBAR FOOTER -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <span class="laundry-anim">🫧</span>
                <small class="fw-bold" style="color: #0284c7; font-size: 0.75rem;">wishuwashere. v2.0</small>
                <span>✨</span>
            </div>
            <div style="font-size: 0.65rem; color: #64748b; margin-top: 3px;">
                Bersih · Rapi · Tepat Waktu
            </div>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT WRAPPER ===== -->
    <div id="main-wrapper">
        
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none border-0" id="sidebarToggle" style="background: #f0f9ff;">
                    <i class="fa-solid fa-bars" style="color: #0369a1;"></i>
                </button>

                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span style="font-size: 1.3rem;">🧺</span>
                        <div class="small text-muted">
                            <strong style="color: #0369a1;">wishuwashere.</strong>
                            <span class="ms-1">/ Sistem Informasi Akuntansi</span>
                        </div>
                    </div>
                    <div class="search-box d-none d-lg-block">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="topLiveSearch" class="form-control" placeholder="Cari nota / pelanggan...">
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-block text-end" style="line-height: 1.3;">
                    <small class="text-muted d-block" style="font-size: 0.72rem;">
                        <?= date('l, d F Y') ?>
                    </small>
                    <small class="fw-bold" style="color: #0369a1; font-size: 0.78rem;">
                        <i class="fa-regular fa-clock me-1"></i>
                        <span id="liveClock">--:--:--</span>
                    </small>
                </div>

                <span style="font-size: 1.3rem;" class="d-none d-sm-block">🫧</span>

                <div class="d-flex align-items-center gap-2">
                    <div class="admin-badge">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="admin-info-text"><?= esc(session()->get('user_name') ?? 'Admin') ?></span>
                    </div>
                    <a href="/logout" class="btn btn-sm d-flex align-items-center gap-1 fw-bold"
                       style="background:#fee2e2; color:#dc2626; border:1px solid #fecaca;"
                       onclick="return confirm('Yakin ingin logout?')">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="d-none d-sm-inline">Logout</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="p-4 flex-grow-1">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <div><strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                    <div><strong>Error!</strong> <?= session()->getFlashdata('error') ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <!-- FOOTER -->
        <footer class="main-footer">
            <div>
                &copy; <?= date('Y') ?> &nbsp;<span class="footer-brand">wishuwashere.</span>&nbsp; — Sistem Informasi Akuntansi Laundry
            </div>
            <div class="footer-icons">
                <span title="Cuci">👕</span>
                <span title="Setrika">🧺</span>
                <span title="Busa">🫧</span>
                <span title="Bersih">✨</span>
            </div>
        </footer>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Toggle Kode Akun Accordion (Akun 1, Akun 2, Akun 3)
        const btnToggleKodeAkun = document.getElementById('btnToggleKodeAkun');
        const kodeAkunContainer = document.getElementById('kodeAkunContainer');
        const kodeAkunSubmenu   = document.getElementById('kodeAkunSubmenu');

        if (btnToggleKodeAkun && kodeAkunContainer && kodeAkunSubmenu) {
            btnToggleKodeAkun.addEventListener('click', function(e) {
                e.preventDefault();
                kodeAkunContainer.classList.toggle('open');
                kodeAkunSubmenu.classList.toggle('show');
            });
        }

        // Toggle Transaksi Accordion (Transaksi Jurnal, Penyesuaian, Kasir)
        const btnToggleTransaksi = document.getElementById('btnToggleTransaksi');
        const transaksiContainer = document.getElementById('transaksiContainer');
        const transaksiSubmenu   = document.getElementById('transaksiSubmenu');

        if (btnToggleTransaksi && transaksiContainer && transaksiSubmenu) {
            btnToggleTransaksi.addEventListener('click', function(e) {
                e.preventDefault();
                transaksiContainer.classList.toggle('open');
                transaksiSubmenu.classList.toggle('show');
            });
        }

        // Toggle Laporan Keuangan Accordion (Laba Rugi, Perubahan Modal, Neraca, Arus Kas)
        const btnToggleLaporan = document.getElementById('btnToggleLaporan');
        const laporanContainer = document.getElementById('laporanContainer');
        const laporanSubmenu   = document.getElementById('laporanSubmenu');

        if (btnToggleLaporan && laporanContainer && laporanSubmenu) {
            btnToggleLaporan.addEventListener('click', function(e) {
                e.preventDefault();
                laporanContainer.classList.toggle('open');
                laporanSubmenu.classList.toggle('show');
            });
        }

        function updateClock() {
            const el = document.getElementById('liveClock');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ==================== INTERACTIVE CLICK BUBBLE BURST ====================
        document.addEventListener('pointerdown', function (e) {
            const x = e.clientX;
            const y = e.clientY;

            // 1. Water Ripple Effect
            const ripple = document.createElement('div');
            ripple.className = 'click-water-ripple';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            document.body.appendChild(ripple);
            setTimeout(() => ripple.remove(), 550);

            // 2. 6-8 Cute Soap Bubbles Bursting Outwards
            const bubbleCount = 7;
            for (let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.className = 'click-bubble';

                const size = Math.floor(Math.random() * 14) + 10; // 10px - 24px
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';
                bubble.style.left = x + 'px';
                bubble.style.top = y + 'px';

                // Random direction flying upwards like real soap bubbles
                const tx = (Math.random() - 0.5) * 80; // -40px to +40px
                const ty = -(Math.random() * 60 + 20); // -20px to -80px (fly up)
                const scale = (Math.random() * 0.4 + 1.1).toFixed(2);
                const duration = (Math.random() * 0.25 + 0.6).toFixed(2); // 0.6s - 0.85s

                bubble.style.setProperty('--tx', tx + 'px');
                bubble.style.setProperty('--ty', ty + 'px');
                bubble.style.setProperty('--scale', scale);
                bubble.style.animationDuration = duration + 's';

                document.body.appendChild(bubble);
                setTimeout(() => bubble.remove(), duration * 1000);
            }
        });
    </script>
</body>
</html>
