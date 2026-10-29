<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiaGa Bencana')</title>
    <!-- Style Internal Sederhana yang Pasti Jalan Tanpa CDN -->
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .wrapper {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 260px;
            background-color: #212529;
            color: white;
            padding: 20px;
        }
        .sidebar h3 {
            font-size: 1.2rem;
            border-bottom: 1px solid #495057;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #0d6efd;
            color: white;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .navbar-top {
            background-color: white;
            padding: 15px 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-body {
            padding: 25px;
        }
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #badbcc;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h3>SiaGa Bencana</h3>
            <ul>
                <li><a href="#" class="active">Dashboard</a></li>
                <li><a href="#">Data Bencana</a></li>
                <li><a href="#">Relawan & Donasi</a></li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Navbar Atas -->
            <header class="navbar-top">
                <span><strong>Dashboard Utama</strong></span>
                <span>Halo, <strong>Arkan</strong></span>
            </header>

            <!-- Konten Dinamis Halaman -->
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>