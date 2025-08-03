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
    <title>Admin Produk</title>
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
                <span class="hidden md:inline text-gray-400 text-sm ml-2">Produk</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="/Mitus_Web_proyek/index.php?page=admin_dashboard" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Dashboard</a>
                <a href="/Mitus_Web_proyek/index.php?page=admin_produk" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Produk</a>
                <a href="/Mitus_Web_proyek/index.php?page=admin_pesanan" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Pesanan</a>
                <a href="/Mitus_Web_proyek/index.php?page=admin_statistik" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Statistik</a>
                <a href="/Mitus_Web_proyek/index.php" class="text-gray-500 hover:text-blue-700 font-semibold px-3 py-1 rounded transition">Beranda</a>
                <button onclick="window.location.href='/Mitus_Web_proyek/logout.php'" class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 font-semibold transition ml-2">Logout</button>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-10">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl shadow-lg px-8 py-7 mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white drop-shadow">Tambah Produk</h1>
                <p class="text-lg text-indigo-100 opacity-90">Kelola produk toko Anda: tambah, edit, hapus, dan lihat daftar produk dengan mudah.</p>
            </div>
        </div>
        <div class="bg-white/90 rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <form method="get" class="flex gap-2 w-full md:w-auto">
                    <input type="hidden" name="page" value="admin_produk">
                    <input type="text" name="search" placeholder="Cari produk..." class="px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-full md:w-64" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition">Cari</button>
                </form>
                <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold shadow transition">+ Tambah Produk</button>
            </div>
            <?php
            require_once __DIR__ . '/../models/Product.php';
            $mysqli = new mysqli('localhost', 'root', '', 'mitus_stationery');
            $productModel = new Product($mysqli);
            // Handle CRUD actions
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Handle upload gambar
                $imageUrl = isset($_POST['image']) ? $_POST['image'] : '';
                if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = __DIR__ . '/../assets/img/';
                    $fileName = time() . '_' . basename($_FILES['image_upload']['name']);
                    $targetFile = $targetDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (in_array($fileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
                            $imageUrl = 'assets/img/' . $fileName;
                        }
                    }
                }
                if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
                    $productModel->addProduct($_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['stock'], $imageUrl);
                    echo '<script>location.href="index.php?page=admin_produk"</script>';
                } elseif (isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {
                    $productModel->updateProduct($_POST['id'], $_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['stock'], $imageUrl);
                    echo '<script>location.href="index.php?page=admin_produk"</script>';
                } elseif (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
                    $productModel->deleteProduct($_POST['id']);
                    echo '<script>location.href="index.php?page=admin_produk"</script>';
                }
            }
            $products = isset($_GET['search']) && $_GET['search'] !== ''
                ? $productModel->searchProducts($_GET['search'])
                : $productModel->getAllProducts();
            ?>
            <!-- Modal Tambah Produk -->
            <div id="modalTambah" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-lg relative">
                    <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                    <h2 class="text-xl font-bold mb-4">Tambah Produk</h2>
                    <form method="post" enctype="multipart/form-data" class="flex flex-col gap-3">
                        <input type="hidden" name="aksi" value="tambah">
                        <input type="text" name="name" required placeholder="Nama Produk" class="border px-3 py-2 rounded">
                        <input type="text" name="category" required placeholder="Kategori" class="border px-3 py-2 rounded">
                        <textarea name="description" required placeholder="Deskripsi" class="border px-3 py-2 rounded"></textarea>
                        <input type="number" name="price" required placeholder="Harga" class="border px-3 py-2 rounded">
                        <input type="number" name="stock" required placeholder="Stok" class="border px-3 py-2 rounded">
                        <input type="file" name="image_upload" accept="image/*" class="border px-3 py-2 rounded">
                        <input type="text" name="image" placeholder="URL Gambar (opsional)" class="border px-3 py-2 rounded">
                        <div class="flex gap-2 mt-2">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">Simpan</button>
                            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded font-semibold">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Edit Produk (dinamis) -->
            <div id="modalEdit" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-lg relative">
                    <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                    <h2 class="text-xl font-bold mb-4">Edit Produk</h2>
                    <form id="formEdit" method="post" enctype="multipart/form-data" class="flex flex-col gap-3"></form>
                </div>
            </div>
            <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-xl">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="py-3 px-4 border-b text-left">ID</th>
                        <th class="py-3 px-4 border-b text-left">Nama</th>
                        <th class="py-3 px-4 border-b text-left">Kategori</th>
                        <th class="py-3 px-4 border-b text-left">Harga</th>
                        <th class="py-3 px-4 border-b text-left">Stok</th>
                        <th class="py-3 px-4 border-b text-left">Gambar</th>
                        <th class="py-3 px-4 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$products || count($products) === 0): ?>
                    <tr><td colspan="7" class="py-4 px-4 text-center text-gray-400">Tidak ada produk.</td></tr>
                <?php else: foreach ($products as $product): ?>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="py-2 px-4 border-b">#<?php echo $product['id']; ?></td>
                        <td class="py-2 px-4 border-b font-semibold text-blue-700"><?php echo htmlspecialchars($product['name']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($product['category']); ?></td>
                        <td class="py-2 px-4 border-b">Rp<?php echo number_format($product['price'],0,',','.'); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $product['stock']; ?></td>
                        <td class="py-2 px-4 border-b">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Gambar Produk" class="w-16 h-16 object-contain rounded border border-blue-200 bg-white" />
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b flex gap-2">
                            <button type="button" onclick='showEditModal(<?php echo json_encode($product); ?>)' class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow text-sm font-semibold transition">Edit</button>
                            <form method="post" onsubmit="return confirm('Yakin hapus produk ini?')" style="display:inline">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
            </div>
            <a href="index.php?page=admin_dashboard" class="inline-block mt-8 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition">Kembali ke Dashboard</a>
        </div>
        <script>
        function showEditModal(product) {
            const form = document.getElementById('formEdit');
            form.innerHTML = `
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id" value="${product.id}">
                <input type="text" name="name" required placeholder="Nama Produk" class="border px-3 py-2 rounded" value="${product.name}">
                <input type="text" name="category" required placeholder="Kategori" class="border px-3 py-2 rounded" value="${product.category}">
                <textarea name="description" required placeholder="Deskripsi" class="border px-3 py-2 rounded">${product.description}</textarea>
                <input type="number" name="price" required placeholder="Harga" class="border px-3 py-2 rounded" value="${product.price}">
                <input type="number" name="stock" required placeholder="Stok" class="border px-3 py-2 rounded" value="${product.stock}">
                <input type="file" name="image_upload" accept="image/*" class="border px-3 py-2 rounded">
                <input type="text" name="image" placeholder="URL Gambar (opsional)" class="border px-3 py-2 rounded" value="${product.image ?? ''}">
                <div class="flex gap-2 mt-2">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-semibold">Simpan Perubahan</button>
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded font-semibold">Batal</button>
                </div>
            `;
            document.getElementById('modalEdit').classList.remove('hidden');
        }
        </script>
    </div>
    
</body>
</html>
