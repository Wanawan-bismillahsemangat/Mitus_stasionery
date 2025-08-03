<?php
// Proteksi admin
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Pesanan</title>
    <link rel="stylesheet" href="assets/css/tailwind.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur border-b border-blue-100 shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-extrabold text-indigo-600 tracking-tight">Mitra Usaha Sampurna  Admin</span>
                <span class="hidden md:inline text-gray-400 text-sm ml-2">Pesanan</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="index.php?page=admin_dashboard" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Dashboard</a>
                <a href="index.php?page=admin_produk" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Produk</a>
                <a href="index.php?page=admin_pesanan" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Pesanan</a>
                <a href="index.php?page=admin_statistik" class="text-blue-700 hover:text-indigo-600 font-semibold px-3 py-1 rounded transition">Statistik</a>
                <a href="index.php" class="text-gray-500 hover:text-blue-700 font-semibold px-3 py-1 rounded transition">Beranda</a>
                <button onclick="window.location.href='logout.php'" class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 font-semibold transition ml-2">Logout</button>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-10">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl shadow-lg px-8 py-7 mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white drop-shadow">Kelola Pesanan</h1>
                <p class="text-lg text-indigo-100 opacity-90">Pantau, update status, dan hapus pesanan pelanggan dengan mudah.</p>
            </div>
        </div>
        <div class="bg-white/90 rounded-2xl shadow-xl p-8 mb-8">
            <?php
            require_once __DIR__ . '/../models/Order.php';
            $mysqli = new mysqli('localhost', 'root', '', 'mitus_stationery');
            $orderModel = new Order($mysqli);
            // Handle update/hapus pesanan
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['aksi']) && $_POST['aksi'] === 'update' && isset($_POST['id'])) {
                    $stmt = $mysqli->prepare('UPDATE orders SET status=? WHERE id=?');
                    $stmt->bind_param('si', $_POST['status'], $_POST['id']);
                    $stmt->execute();
                } elseif (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus' && isset($_POST['id'])) {
                    $stmt = $mysqli->prepare('DELETE FROM orders WHERE id=?');
                    $stmt->bind_param('i', $_POST['id']);
                    $stmt->execute();
                }
                echo '<script>location.href="index.php?page=admin_pesanan"</script>';
            }
            $orders = $orderModel->getAllOrders();
            // Ambil produk untuk setiap order
            $orderItems = [];
            foreach ($orders as $order) {
                $stmt = $mysqli->prepare('SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
                $stmt->bind_param('i', $order['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $orderItems[$order['id']] = $result->fetch_all(MYSQLI_ASSOC);
            }
            ?>
            <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-xl">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="py-3 px-4 border-b text-left">ID</th>
                        <th class="py-3 px-4 border-b text-left">User</th>
                        <th class="py-3 px-4 border-b text-left">Tanggal</th>
                        <th class="py-3 px-4 border-b text-left">Produk</th>
                        <th class="py-3 px-4 border-b text-left">Status</th>
                        <th class="py-3 px-4 border-b text-left">Total</th>
                        <th class="py-3 px-4 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$orders || count($orders) === 0): ?>
                    <tr><td colspan="6" class="py-4 px-4 text-center text-gray-400">Tidak ada pesanan.</td></tr>
                <?php else: foreach ($orders as $order): ?>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="py-2 px-4 border-b">#<?php echo $order['id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $order['user_id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $order['order_date']; ?></td>
                        <td class="py-2 px-4 border-b">
                          <?php if (!empty($orderItems[$order['id']])): ?>
                            <ul class="list-disc pl-4">
                              <?php foreach ($orderItems[$order['id']] as $item): ?>
                                <li><?php echo htmlspecialchars($item['product_name']); ?> <span class="text-xs text-gray-500">x<?php echo $item['quantity']; ?></span></li>
                              <?php endforeach; ?>
                            </ul>
                          <?php else: ?>
                            <span class="text-gray-400">-</span>
                          <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <form method="post" class="flex gap-2 items-center">
                                <input type="hidden" name="aksi" value="update">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="border rounded px-2 py-1">
                                    <option value="pending" <?php if($order['status']==='pending')echo'selected';?>>Pending</option>
                                    <option value="proses" <?php if($order['status']==='proses')echo'selected';?>>Proses</option>
                                    <option value="selesai" <?php if($order['status']==='selesai')echo'selected';?>>Selesai</option>
                                    <option value="batal" <?php if($order['status']==='batal')echo'selected';?>>Batal</option>
                                </select>
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow text-sm font-semibold transition">Update</button>
                            </form>
                        </td>
                        <td class="py-2 px-4 border-b">Rp<?php echo number_format($order['total'],0,',','.'); ?></td>
                        <td class="py-2 px-4 border-b">
                            <form method="post" onsubmit="return confirm('Yakin hapus pesanan ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
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
    </div>
</body>
</html>
