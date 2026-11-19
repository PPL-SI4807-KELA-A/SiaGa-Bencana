<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') - SiaGa Bencana</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/siaga-icon.svg') }}?v=2">
    <link rel="alternate icon" type="image/png" href="{{ asset('images/siaga-icon.png') }}?v=2">

    <!-- Bootstrap 5 + Bootstrap Icons + font Inter (CDN, sesuai stack README: Blade + Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sg-sidebar-bg: #5C3D2E;
            --sg-sidebar-text: #F1E4D3;
            --sg-sidebar-label: #D9B98F;
            --sg-sidebar-active-bg: rgba(232, 200, 160, .22);
            --sg-sidebar-active-border: rgba(232, 200, 160, .38);
            --sg-sidebar-hover: rgba(255, 255, 255, .07);
            --sg-accent: #5C3D2E;
            --sg-accent-hover: #4A3024;
            --sg-tan: #C9A27A;
            --sg-page-bg: #FAF6EE;
            --sg-card-border: #EDE3D3;
            --sg-text: #2B1D15;
            --sg-muted: #8A7B6E;
            --sg-sidebar-width: 240px;
            --bs-body-font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --bs-body-color: var(--sg-text);
            --bs-body-bg: var(--sg-page-bg);
        }

        body { font-size: .875rem; -webkit-font-smoothing: antialiased; }

        .app-shell { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sg-sidebar-width);
            flex-shrink: 0;
            background: var(--sg-sidebar-bg);
            color: var(--sg-sidebar-text);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1040;
        }
        .sidebar-brand {
            padding: 1.125rem 1rem .875rem;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }
        .sidebar-brand .brand-logo { line-height: 0; }
        .sidebar-brand .brand-logo svg { display: block; height: 38px; width: auto; }
        .sidebar-brand .brand-role {
            font-size: .6875rem; color: var(--sg-sidebar-label);
            margin: .5rem 0 0; padding-left: 2px;
        }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: .5rem 1rem 1rem; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.2) transparent; }
        .nav-section-label {
            font-size: .625rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: var(--sg-sidebar-label); padding: 0 .5rem; margin: 1rem 0 .375rem;
        }
        .nav-item-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .5rem .625rem; border-radius: 8px; margin-bottom: 2px;
            color: var(--sg-sidebar-text); text-decoration: none; font-size: .78rem;
            border: 1px solid transparent; transition: background .15s;
        }
        .nav-item-link i { font-size: .95rem; width: 1rem; text-align: center; opacity: .9; }
        .nav-item-link:hover { background: var(--sg-sidebar-hover); color: #fff; }
        .nav-item-link.active {
            background: var(--sg-sidebar-active-bg);
            border-color: var(--sg-sidebar-active-border);
            color: #fff; font-weight: 600;
        }
        .nav-item-link .nav-badge {
            margin-left: auto; background: #fff; color: var(--sg-sidebar-bg);
            font-size: .6875rem; font-weight: 700; min-width: 20px; height: 20px;
            border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; padding: 0 .35rem;
        }

        .sidebar-profile {
            display: flex; align-items: center; gap: .625rem;
            margin: .75rem 1rem 1rem; padding: .625rem .75rem;
            background: rgba(0, 0, 0, .18); border-radius: 10px;
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--sg-tan); color: var(--sg-sidebar-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: .6875rem; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-profile .profile-name { font-size: .75rem; font-weight: 600; color: #fff; line-height: 1.2; }
        .sidebar-profile .profile-role { font-size: .6875rem; color: var(--sg-sidebar-label); }

        /* ===== AREA UTAMA ===== */
        .app-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid var(--sg-card-border);
            padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            position: sticky; top: 0; z-index: 1030;
        }
        .topbar .page-title { font-size: 1.0625rem; font-weight: 700; margin: 0; line-height: 1.3; }
        .topbar .page-subtitle { font-size: .75rem; color: var(--sg-muted); margin: 0; }
        .icon-btn {
            position: relative; width: 36px; height: 36px; border: 0; border-radius: 10px;
            background: #F5EBDD; color: var(--sg-accent); display: flex; align-items: center; justify-content: center;
        }
        .icon-btn:hover { background: #EFE0CB; }
        .icon-btn .dot {
            position: absolute; top: -5px; right: -5px; min-width: 17px; height: 17px; padding: 0 4px;
            border-radius: 999px; background: #B3412F; color: #fff; font-size: .625rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center; border: 2px solid #fff;
        }
        .app-content { padding: 1.5rem 2rem 2rem; background: var(--sg-page-bg); flex: 1; }

        /* ===== KOMPONEN BERSAMA (dipakai semua halaman) ===== */
        .sg-card { background: #fff; border: 1px solid var(--sg-card-border); border-radius: 12px; }
        .btn-sg { background: var(--sg-accent); border-color: var(--sg-accent); color: #fff; font-weight: 500; font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
        .btn-sg:hover, .btn-sg:focus { background: var(--sg-accent-hover); border-color: var(--sg-accent-hover); color: #fff; }

        /* ===== PAGINATION — tema SiaGa (coklat), timpa default Bootstrap (biru) ===== */
        .pagination {
            --bs-pagination-color: var(--sg-accent);
            --bs-pagination-hover-color: var(--sg-accent-hover);
            --bs-pagination-focus-box-shadow: 0 0 0 .25rem rgba(92, 61, 46, .25);
            --bs-pagination-active-bg: var(--sg-accent);
            --bs-pagination-active-border-color: var(--sg-accent);
        }

        /* ===== RESPONSIF ===== */
        .sidebar-toggle { display: none; }
        .sidebar-backdrop { display: none; }
        @media (max-width: 991.98px) {
            .sidebar { position: fixed; left: 0; top: 0; transform: translateX(-100%); transition: transform .2s ease; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open .sidebar-backdrop { display: block; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1035; }
            .sidebar-toggle { display: flex; }
            .topbar, .app-content { padding-left: 1rem; padding-right: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    // ===== Nama & peran pengguna login =====
    // Diambil dari Auth::user() bila CHORE Login (Dhafa) sudah mengisi sesi.
    // Kolom 'name' diasumsikan sesuai konvensi default Laravel (users.name).
    // Field peran (mis. users.role) BELUM ada acuannya -> fallback 'Admin BPBD'
    // dan perlu disepakati dengan Dhafa saat CHORE Login selesai.
    $namaUser  = Auth::check() ? Auth::user()->name : 'Tamu';
    $peranUser = Auth::check() && isset(Auth::user()->role) ? Auth::user()->role : 'Admin BPBD';
    $inisial   = collect(explode(' ', trim($namaUser)))
        ->filter()
        ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
        ->take(2)
        ->implode('');
    $inisial   = $inisial !== '' ? $inisial : 'U';

    $badgeVerifikasi = 5;

    // Menu khusus peran Admin BPBD, sesuai mockup Figma.
    // 'route' diisi nama route bila sudah pasti ada (DAHIF-1: wilayah-terdampak.index).
    // Menu lain 'route' => null dulu, menunggu kabar nama route dari PIC masing-masing PBI.
    $menu = [
        'Utama' => [
            ['key' => 'dasbor', 'label' => 'Dasbor', 'icon' => 'bi-grid-1x2', 'route' => null],
        ],
        'Wilayah & Posko' => [
            ['key' => 'wilayah', 'label' => 'Wilayah Terdampak', 'icon' => 'bi-geo-alt', 'route' => 'wilayah-terdampak.index'],
            ['key' => 'posko', 'label' => 'Data Posko', 'icon' => 'bi-house-door', 'route' => null],
            ['key' => 'fasilitas', 'label' => 'Fasilitas Posko', 'icon' => 'bi-building', 'route' => null],
            ['key' => 'pengungsi', 'label' => 'Data Pengungsi', 'icon' => 'bi-people', 'route' => null],
        ],
        'Logistik' => [
            ['key' => 'barang', 'label' => 'Data Barang', 'icon' => 'bi-tags', 'route' => null],
            ['key' => 'sumber', 'label' => 'Sumber Bantuan', 'icon' => 'bi-gift', 'route' => 'sumber-bantuan.index'],
            ['key' => 'stok', 'label' => 'Stok Logistik', 'icon' => 'bi-box-seam', 'route' => null],
            ['key' => 'penerimaan', 'label' => 'Penerimaan', 'icon' => 'bi-box-arrow-in-down', 'route' => null],
            ['key' => 'distribusi', 'label' => 'Distribusi', 'icon' => 'bi-truck', 'route' => null],
        ],
        'Permintaan' => [
            ['key' => 'mendesak', 'label' => 'Permintaan Mendesak', 'icon' => 'bi-megaphone', 'route' => null],
            ['key' => 'tindaklanjut', 'label' => 'Tindak Lanjut Permintaan', 'icon' => 'bi-list-check', 'route' => null],
        ],
        'Laporan' => [
            ['key' => 'kategori', 'label' => 'Kategori Bencana', 'icon' => 'bi-bookmark', 'route' => 'kategori-bencana.index'],
            ['key' => 'kejadian', 'label' => 'Laporan Kejadian', 'icon' => 'bi-file-earmark-text', 'route' => 'laporan-kejadian.index'],
            ['key' => 'verifikasi', 'label' => 'Verifikasi Laporan', 'icon' => 'bi-patch-check', 'route' => null, 'badge' => $badgeVerifikasi],
        ],
        'Informasi Publik' => [
            ['key' => 'pengumuman', 'label' => 'Pengumuman', 'icon' => 'bi-newspaper', 'route' => null],
            ['key' => 'kontak', 'label' => 'Kontak Layanan', 'icon' => 'bi-telephone', 'route' => null],
        ],
        'Sistem' => [
            // Menu Keluar sengaja dibiarkan '#' -> menunggu CHORE Login (Dhafa)
            ['key' => 'keluar', 'label' => 'Keluar', 'icon' => 'bi-box-arrow-left', 'route' => null],
        ],
    ];

    // Halaman anak menentukan menu aktif lewat @section('menu_aktif', 'dasbor')
    $menuAktif = trim($__env->yieldContent('menu_aktif'));
@endphp

<div class="app-shell">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo" role="img" aria-label="SiaGa Bencana">
                <svg viewBox="0 0 220 64" xmlns="http://www.w3.org/2000/svg">
                    <rect width="64" height="64" rx="14" fill="#F4E7D7"/>
                    <path d="M13 31 L32 15 L51 31" fill="none" stroke="#7A4A2E" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="32" cy="27.5" r="4" fill="#D9822B"/>
                    <path d="M14 40 q4.5 -4 9 0 t9 0 t9 0 t9 0" fill="none" stroke="#7A4A2E" stroke-width="4" stroke-linecap="round"/>
                    <path d="M19 48.5 q4.33 -3.6 8.66 0 t8.66 0 t8.66 0" fill="none" stroke="#C9A585" stroke-width="3.5" stroke-linecap="round"/>
                    <text x="78" y="31" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="22" font-weight="700" fill="#FFF7EE">Sia<tspan fill="#F0A75B">Ga</tspan></text>
                    <text x="78" y="51" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="13" font-weight="500" letter-spacing="2.2" fill="#E6CDB4">BENCANA</text>
                </svg>
            </div>
            <p class="brand-role">{{ $peranUser }}</p>
        </div>

        <nav class="sidebar-nav">
            @foreach ($menu as $section => $items)
                <div class="nav-section-label">{{ $section }}</div>
                @foreach ($items as $item)
                    @php
                        $href = ($item['route'] ?? null) && \Illuminate\Support\Facades\Route::has($item['route'])
                            ? route($item['route'])
                            : '#';
                    @endphp
                    <a href="{{ $href }}"
                       class="nav-item-link {{ $menuAktif === $item['key'] ? 'active' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                        @isset($item['badge'])
                            <span class="nav-badge">{{ $item['badge'] }}</span>
                        @endisset
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="sidebar-profile">
            <div class="avatar">{{ $inisial }}</div>
            <div class="overflow-hidden">
                <div class="profile-name text-truncate">{{ $namaUser }}</div>
                <div class="profile-role text-truncate">{{ $peranUser }}</div>
            </div>
        </div>
    </aside>
    <div class="sidebar-backdrop" onclick="document.body.classList.remove('sidebar-open')"></div>

    <!-- KONTEN UTAMA -->
    <div class="app-main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="icon-btn sidebar-toggle" aria-label="Buka menu"
                        onclick="document.body.classList.toggle('sidebar-open')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h1 class="page-title">@yield('judul', 'SiaGa Bencana')</h1>
                    <p class="page-subtitle">@yield('subjudul')</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="icon-btn" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="dot">{{ $badgeVerifikasi }}</span>
                </button>
                <div class="avatar">{{ $inisial }}</div>
            </div>
        </header>

        <main class="app-content">
            @yield('content')
        </main>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>