<?php
// views/order_status.php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#6366f1',
              secondary: '#60a5fa',
              accent: '#fbbf24',
              school: '#f1f5f9',
              schoolDark: '#0f172a',
              schoolAccent: '#38bdf8',
            },
            fontFamily: {
              futuristic: ['Poppins', 'Montserrat', 'ui-sans-serif', 'system-ui'],
            },
          },
        },
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-school to-white min-h-screen font-futuristic">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur border-b border-blue-100 shadow-sm sticky top-0 z-30 mb-8">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-extrabold text-primary tracking-tight">Mitus Stationery</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="/Mitus_Web_proyek/index.php" class="text-blue-700 hover:text-primary font-semibold px-3 py-1 rounded transition">Beranda</a>
                <a href="/Mitus_Web_proyek/views/cart.php" class="text-blue-700 hover:text-primary font-semibold px-3 py-1 rounded transition">Keranjang</a>
                <a href="index.php?page=order_status" class="text-accent hover:text-yellow-600 font-bold px-3 py-1 rounded transition underline">Status Order</a>
                <button onclick="window.location.href='logout.php'" class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 font-semibold transition ml-2">Logout</button>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-8 text-center drop-shadow">Status Pesanan Saya</h1>
        <?php
        require_once __DIR__ . '/../models/Order.php';
        $mysqli = new mysqli('localhost', 'root', '', 'mitus_stationery');
        $orderModel = new Order($mysqli);
        $orders = $orderModel->getOrdersByUser($user['id']);
        // Ambil produk untuk setiap order
        $orderItems = [];
        foreach ($orders as $order) {
            $stmt = $mysqli->prepare('SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
            $stmt->bind_param('i', $order['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderItems[$order['id']] = $result->fetch_all(MYSQLI_ASSOC);
        }
        if (!$orders || count($orders) === 0): ?>
            <div class="bg-white/90 rounded-2xl shadow-xl p-8 border border-school/40 text-center">
                <p class="text-gray-400 text-lg">Belum ada pesanan.</p>
                <a href="index.php" class="inline-block mt-6 bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Kembali ke Beranda</a>
            </div>
        <?php else: ?>
        <div class="overflow-x-auto">
        <table class="min-w-full bg-white/90 rounded-2xl shadow-2xl border border-school/40">
            <thead>
                <tr class="bg-school/60 text-primary text-lg">
                    <th class="py-3 px-4 border">ID</th>
                    <th class="py-3 px-4 border">Tanggal</th>
                    <th class="py-3 px-4 border">Produk</th>
                    <th class="py-3 px-4 border">Status</th>
                    <th class="py-3 px-4 border">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr class="border-b border-school/30 hover:bg-school/40 transition">
                    <td class="py-3 px-4 border text-center font-semibold text-primary text-lg">#<?php echo $order['id']; ?></td>
                    <td class="py-3 px-4 border text-center"><?php echo $order['order_date']; ?></td>
                    <td class="py-3 px-4 border text-center">
                      <?php if (!empty($orderItems[$order['id']])): ?>
                        <ul class="list-disc pl-4 text-left inline-block">
                          <?php foreach ($orderItems[$order['id']] as $item): ?>
                            <li><?php echo htmlspecialchars($item['product_name']); ?> <span class="text-xs text-gray-500">x<?php echo $item['quantity']; ?></span></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php else: ?>
                        <span class="text-gray-400">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 border text-center">
                        <span class="inline-block px-3 py-1 rounded-full font-bold <?php
                            switch(strtolower($order['status'])) {
                                case 'pending': echo 'bg-yellow-100 text-yellow-700'; break;
                                case 'proses': echo 'bg-blue-100 text-blue-700'; break;
                                case 'selesai': echo 'bg-green-100 text-green-700'; break;
                                case 'batal': echo 'bg-red-100 text-red-700'; break;
                                default: echo 'bg-gray-100 text-gray-700';
                            }
                        ?>">
                        <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td class="py-3 px-4 border text-center text-accent font-bold">Rp<?php echo number_format($order['total'],0,',','.'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
        <div class="mt-10 text-center">
            <a href="index.php" class="inline-block bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
