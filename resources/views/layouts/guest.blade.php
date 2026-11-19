<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiaGa Bencana')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/siaga-icon.svg') }}?v=2">
    <link rel="alternate icon" type="image/png" href="{{ asset('images/siaga-icon.png') }}?v=2">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sg-accent: #5C3D2E;
            --sg-accent-hover: #4A3024;
            --sg-tan: #C9A27A;
            --sg-page-bg: #FAF6EE;
            --sg-card-border: #EDE3D3;
            --sg-text: #2B1D15;
            --sg-muted: #8A7B6E;
            --bs-body-font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --bs-body-color: var(--sg-text);
            --bs-body-bg: var(--sg-page-bg);
        }
        body { font-size: .875rem; -webkit-font-smoothing: antialiased; }

        .guest-navbar {
            background: var(--sg-accent); color: #fff;
            padding: .75rem 2rem;
        }
        .guest-navbar .brand-logo { line-height: 0; }
        .guest-navbar .brand-logo svg { display: block; height: 34px; width: auto; }
        .guest-nav-link { color: #F1E4D3; text-decoration: none; font-size: .8125rem; font-weight: 500; }
        .guest-nav-link:hover, .guest-nav-link.active { color: #fff; }

        /* Tombol "Masuk" dibuat kontras dari background navbar (sama-sama coklat) */
        .guest-navbar .btn-sg {
            background: #F1E4D3;
            border-color: #F1E4D3;
            color: var(--sg-accent);
        }
        .guest-navbar .btn-sg:hover {
            background: #fff;
            border-color: #fff;
            color: var(--sg-accent-hover);
        }

        .guest-content { padding: 2rem; max-width: 1100px; margin: 0 auto; }
        .sg-card { background: #fff; border: 1px solid var(--sg-card-border); border-radius: 12px; }
        .btn-sg { background: var(--sg-accent); border-color: var(--sg-accent); color: #fff; font-weight: 500; font-size: .8125rem; border-radius: 8px; padding: .5rem 1rem; }
        .btn-sg:hover, .btn-sg:focus { background: var(--sg-accent-hover); border-color: var(--sg-accent-hover); color: #fff; }

        .guest-footer { padding: 1.5rem 2rem; text-align: center; font-size: .75rem; color: var(--sg-muted); }
    </style>
    @stack('styles')
</head>
<body>
@php
    // Menu publik untuk warga tanpa login. 'Posko Aktif' mengacu ke DAHIF-2.
    // 'route' diisi null dulu untuk semua -> belum ada yang pasti route-nya,
    // menunggu kabar dari PIC masing-masing PBI (Posko Aktif, Pengumuman, Kontak Layanan).
    $menuPublik = [
        ['key' => 'posko-aktif', 'label' => 'Posko Aktif', 'route' => 'posko.publik'],
        ['key' => 'pengumuman', 'label' => 'Pengumuman', 'route' => null],
        ['key' => 'kontak', 'label' => 'Kontak Layanan', 'route' => null],
    ];
    $menuAktif = trim($__env->yieldContent('menu_aktif'));
@endphp

<nav class="guest-navbar d-flex align-items-center justify-content-between flex-wrap gap-2">
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
    <div class="d-flex align-items-center gap-4">
        @foreach ($menuPublik as $item)
            @php
                $href = ($item['route'] ?? null) && \Illuminate\Support\Facades\Route::has($item['route'])
                    ? route($item['route'])
                    : '#';
            @endphp
            <a href="{{ $href }}" class="guest-nav-link {{ $menuAktif === $item['key'] ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
        @guest
            <a href="{{ route('login') }}" class="btn btn-sg btn-sm">Masuk</a>
        @endguest
    </div>
</nav>

<main class="guest-content">
    @yield('content')
</main>

<footer class="guest-footer">
    &copy; {{ date('Y') }} SiaGa Bencana &middot; BPBD
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
