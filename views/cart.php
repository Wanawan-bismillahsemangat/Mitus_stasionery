<?php
// Pastikan session selalu aktif
if (session_status() === PHP_SESSION_NONE) session_start();
// Pastikan $cartItems sudah di-set dari CartController
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
<?php if (isset($_GET['error']) && $_GET['error'] === 'stok'): ?>
  <div id="notifStok" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn">
    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/></svg>
    <span class="font-semibold">Stok produk tidak mencukupi!</span>
    <button onclick="document.getElementById('notifStok').remove()" class="ml-4 text-red-500 hover:text-red-700 font-bold">Tutup</button>
  </div>
  <style>@keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}.animate-fadeIn{animation:fadeIn .5s;}</style>
  <script>setTimeout(()=>{const n=document.getElementById('notifStok');if(n)n.remove();},4000);</script>
<?php endif; ?>
<?php if (isset($_GET['notif']) && $_GET['notif'] === 'stok_disesuaikan'): ?>
  <div id="notifStok" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn">
    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
    <span class="font-semibold">Jumlah produk pada keranjang telah disesuaikan dengan stok terbaru.</span>
    <button onclick="document.getElementById('notifStok').remove()" class="ml-4 text-yellow-600 hover:text-yellow-800 font-bold">Tutup</button>
  </div>
  <style>@keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}.animate-fadeIn{animation:fadeIn .5s;}</style>
  <script>setTimeout(()=>{const n=document.getElementById('notifStok');if(n)n.remove();},4000);</script>
<?php endif; ?>
<?php if (isset($_GET['notif']) && $_GET['notif'] === 'qty_updated'): ?>
  <div id="notifQty" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn">
    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span class="font-semibold">Jumlah produk di keranjang berhasil diupdate.</span>
    <button onclick="document.getElementById('notifQty').remove()" class="ml-4 text-green-600 hover:text-green-800 font-bold">Tutup</button>
  </div>
  <style>@keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}.animate-fadeIn{animation:fadeIn .5s;}</style>
  <script>setTimeout(()=>{const n=document.getElementById('notifQty');if(n)n.remove();},4000);</script>
<?php endif; ?>
<?php if (isset($_GET['notif']) && $_GET['notif'] === 'qty_updated_checkout'): ?>
  <div id="notifCheckout" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-blue-100 border border-blue-400 text-blue-800 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn">
    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span class="font-semibold">Jumlah produk di keranjang berhasil diupdate. Melanjutkan ke checkout...</span>
    <button onclick="document.getElementById('notifCheckout').remove()" class="ml-4 text-blue-600 hover:text-blue-800 font-bold">Tutup</button>
  </div>
  <style>@keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}.animate-fadeIn{animation:fadeIn .5s;}</style>
  <script>setTimeout(()=>{const n=document.getElementById('notifCheckout');if(n)n.remove();},3000);</script>
<?php endif; ?>
<?php if (isset($_GET['notif']) && $_GET['notif'] === 'qty_updated_checkout' && isset($_GET['checkout']) && $_GET['checkout'] == '1'): ?>
<script>
setTimeout(function() {
  window.location.href = 'index.php?page=checkout';
}, 1500);
</script>
<?php endif; ?>
<?php if (isset($_GET['notif']) && $_GET['notif'] === 'item_deleted'): ?>
  <div id="notifDelete" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn">
    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/></svg>
    <span class="font-semibold">Produk berhasil dihapus dari keranjang.</span>
    <button onclick="document.getElementById('notifDelete').remove()" class="ml-4 text-red-500 hover:text-red-700 font-bold">Tutup</button>
  </div>
  <style>@keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}.animate-fadeIn{animation:fadeIn .5s;}</style>
  <script>setTimeout(()=>{const n=document.getElementById('notifDelete');if(n)n.remove();},3000);</script>
<?php endif; ?>
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-8 text-center drop-shadow">Keranjang Belanja</h1>
        <?php if (!empty($cartItems)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white/90 rounded-2xl shadow-2xl border border-school/40">
                <thead>
                    <tr class="bg-school/60 text-primary text-lg">
                        <th class="p-3">Gambar</th>
                        <th class="p-3">Nama Produk</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Subtotal</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $total = 0; foreach ($cartItems as $item): ?>
                    <?php 
                        $qty = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                        $subtotal = $item['price'] * $qty;
                        $total += $subtotal;
                        // Fallback gambar
                        $imgFile = !empty($item['image']) ? $item['image'] : 'default-product.png';
                        $imgPath = realpath(__DIR__ . '/../assets/img/' . $imgFile);
                        $imgDir = realpath(__DIR__ . '/../assets/img/');
                        if (!$imgPath || strpos($imgPath, $imgDir) !== 0 || !is_file($imgPath)) {
                            $imgFile = 'default-product.png';
                        }
                    ?>
                    <tr class="border-b border-school/30 hover:bg-school/40 transition">
                        <td class="p-3 text-center">
                          <img src="/Mitus_Web_proyek/assets/img/<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-16 w-16 object-contain mx-auto rounded-lg border border-schoolAccent/30 bg-school" onerror="this.onerror=null;this.src='/Mitus_Web_proyek/assets/img/default-product.png';this.classList.add('border-red-500');this.parentNode.insertAdjacentHTML('beforeend', '<div class=\'text-xs text-red-500 mt-1\'>Gambar tidak ditemukan</div>');">
                        </td>
                        <td class="p-3 text-center font-semibold text-primary text-lg"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="p-3 text-center text-accent font-bold">Rp<?= number_format($item['price'],0,',','.') ?></td>
                        <td class="p-3 text-center">
                            <form action="index.php?page=cart_update" method="post" style="display:inline">
                                <input type="number" name="qty[<?= $item['product_id'] ?>]" value="<?= $qty ?>" min="1" max="<?= isset($item['stock']) ? (int)$item['stock'] : 99 ?>" class="w-16 border border-primary/30 rounded px-2 py-1 text-center focus:ring-2 focus:ring-primary/40 transition">
                                <button type="submit" class="hidden"></button>
                            </form>
                        </td>
                        <td class="p-3 text-center text-secondary font-bold">Rp<?= number_format($subtotal,0,',','.') ?></td>
                        <td class="p-3 text-center">
                            <form action="index.php?page=cart_remove" method="post" style="display:inline">
                                <input type="hidden" name="id" value="<?= $item['product_id'] ?>">
                                <button type="submit" class="text-red-600 hover:underline font-bold" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-extrabold text-lg bg-school/60">
                        <td colspan="4" class="p-3 text-right">Total</td>
                        <td class="p-3 text-center text-primary">Rp<?= number_format($total,0,',','.') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="flex flex-col md:flex-row gap-3 mt-6 justify-between items-center">
            <form action="index.php?page=cart_update" method="post" class="flex gap-3">
                <?php foreach ($cartItems as $item): ?>
                    <input type="hidden" name="qty[<?= $item['product_id'] ?>]" value="<?= isset($item['quantity']) ? $item['quantity'] : $item['qty'] ?>">
                <?php endforeach; ?>
                <button type="submit" class="bg-gradient-to-r from-accent to-yellow-400 hover:from-yellow-400 hover:to-accent text-primary font-bold px-6 py-2 rounded-lg shadow-lg transition-all duration-200">Update Jumlah</button>
                <button type="submit" name="checkout" value="1" class="bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-all duration-200">Lanjut ke Checkout</button>
            </form>
            <a href="index.php" class="inline-block bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-indigo-500 hover:to-blue-500 text-white font-bold px-6 py-2 rounded-lg shadow-lg transition-all duration-200">Kembali ke Beranda</a>
        </div>
        <?php else: ?>
            <div class="bg-white/90 rounded-2xl shadow-xl p-8 border border-school/40 text-center">
                <p class="text-gray-400 text-lg">Keranjang belanja kosong.</p>
                <a href="index.php" class="inline-block mt-6 bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>
    <script src="/Mitus_Web_proyek/assets/js/cart.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  const qtyInputs = document.querySelectorAll('input[type="number"][name^="qty["]');
  qtyInputs.forEach(function(input) {
    input.addEventListener('input', function() {
      const max = parseInt(input.max);
      if (parseInt(input.value) > max) {
        input.value = max;
      }
      if (parseInt(input.value) < 1) {
        input.value = 1;
      }
    });
    // Submit form otomatis saat qty diubah
    input.addEventListener('change', function() {
      input.form.submit();
    });
  });
});
</script>
</body>
</html>
