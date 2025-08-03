<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Gunakan hanya satu Tailwind: CDN untuk dev/demo, hapus file lokal agar tidak bentrok -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#6366f1',
              secondary: '#60a5fa',
              accent: '#fbbf24',
            },
          },
        },
      }
    </script>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen">
    <!-- Taskbar/Navbar khusus admin -->
    <nav class="bg-white/80 backdrop-blur border-b border-blue-100 shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-extrabold text-indigo-600 tracking-tight">Mitra Usaha Sampurna Admin</span>
                <span class="hidden md:inline text-gray-400 text-sm ml-2">Dashboard</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="/Mitus_Web_proyek/index.php?page=admin_dashboard" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Dashboard</a>
                <a href="/Mitus_Web_proyek/index.php?page=admin_produk" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Produk</a>
                <a href="/Mitus_Web_proyek/index.php?page=admin_pesanan" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Pesanan</a>
                <a href="/Mitus_Web_proyek/index.php" class="text-gray-500 hover:text-blue-700 font-semibold px-3 py-1 rounded transition">Beranda</a>
                <button onclick="window.location.href='/Mitus_Web_proyek/logout.php'" class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 font-semibold transition ml-2">Logout</button>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-10">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl shadow-lg px-8 py-7 mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white drop-shadow">Admin Dashboard</h1>
                <p class="text-lg text-indigo-100 opacity-90">Selamat datang, <span class="font-semibold text-white"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>! Kelola toko Anda dengan mudah dan profesional.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-center items-center gap-2 mb-10" id="adminMenu">
            <a href="/Mitus_Web_proyek/index.php?page=admin_produk" class="group bg-gradient-to-br from-blue-100 to-blue-300 border-2 border-blue-200 hover:border-blue-400 p-16 rounded-3xl shadow-2xl flex flex-col items-center transition hover:scale-105 hover:bg-blue-50 min-w-[480px] max-w-[48vw]">
                <div class="bg-blue-200 group-hover:bg-blue-400 transition p-4 rounded-full mb-3 shadow-md">
                    <span class="text-5xl">📦</span>
                </div>
                <span class="font-bold text-lg tracking-wide text-blue-700 mb-1">Tambah Produk</span>
                <span class="opacity-80 text-sm text-gray-500">Tambah, edit, hapus & kelola produk</span>
            </a>
            <a href="/Mitus_Web_proyek/index.php?page=admin_pesanan" class="group bg-gradient-to-br from-green-100 to-emerald-100 border-2 border-green-200 hover:border-green-400 p-16 rounded-3xl shadow-2xl flex flex-col items-center transition hover:scale-105 hover:bg-green-50 min-w-[480px] max-w-[48vw]">
                <div class="bg-green-200 group-hover:bg-green-400 transition p-4 rounded-full mb-3 shadow-md">
                    <span class="text-5xl">📝</span>
                </div>
                <span class="font-bold text-lg tracking-wide text-green-700 mb-1">Kelola Pesanan</span>
                <span class="opacity-80 text-sm text-gray-500">Pantau & proses pesanan masuk</span>
            </a>
        </div>
        <div class="md:hidden flex justify-center mb-4">
            <button id="toggleMenu" class="bg-blue-600 text-white px-4 py-2 rounded shadow">Tampilkan Menu</button>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
    <script>
    // Responsive menu admin dengan JS
    const toggleBtn = document.getElementById('toggleMenu');
    const menu = document.getElementById('adminMenu');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            menu.classList.toggle('hidden');
        });
        // Sembunyikan menu di mobile secara default
        if (window.innerWidth < 768) {
            menu.classList.add('hidden');
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    }
    </script>
</body>
</html>
