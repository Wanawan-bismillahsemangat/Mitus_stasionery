<?php
// Pastikan $product sudah di-set dari ProductController
// Fallback gambar jika kosong atau file tidak ada
$imgFile = !empty($product['image']) ? $product['image'] : 'default-product.png';
$imgPath = realpath(__DIR__ . '/../assets/img/' . $imgFile);
$imgDir = realpath(__DIR__ . '/../assets/img/');
if (!$imgPath || strpos($imgPath, $imgDir) !== 0 || !is_file($imgPath)) {
    $imgFile = 'default-product.png';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Detail Produk</title>
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
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-10 bg-white/90 rounded-3xl shadow-2xl p-8 border border-school/40">
            <div class="md:w-1/2 flex flex-col items-center justify-center">
                <div class="bg-school rounded-2xl shadow-xl border border-schoolAccent/30 p-4 w-full flex justify-center">
                  <img src="/Mitus_Web_proyek/assets/img/<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-64 w-64 object-contain rounded-xl bg-white border border-schoolAccent/30" onerror="this.onerror=null;this.src='/Mitus_Web_proyek/assets/img/default-product.png';this.classList.add('border-red-500');this.parentNode.insertAdjacentHTML('beforeend', '<div class=\'text-xs text-red-500 mt-1\'>Gambar tidak ditemukan</div>');">
                </div>
            </div>
            <div class="md:w-1/2 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2 leading-tight drop-shadow"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="text-accent font-bold text-lg mb-2">Rp<?= number_format($product['price'],0,',','.') ?></p>
                <p class="text-gray-600 mb-2">Kategori: <span class="font-semibold text-schoolAccent"><?= htmlspecialchars($product['category']) ?></span></p>
                <p class="mb-2">Stok: <span class="font-semibold text-secondary"><?= htmlspecialchars($product['stock']) ?></span></p>
                <?php if ((int)$product['stock'] <= 0): ?>
                  <p class="text-red-600 font-bold mb-4">Stok habis, tidak dapat dibeli.</p>
                <?php endif; ?>
                <form action="index.php?page=cart_add&id=<?= $product['id'] ?>" method="post" class="flex items-center gap-3 mt-4">
                    <input type="number" name="qty" value="1" min="1" max="<?= isset($product['stock']) ? (int)$product['stock'] : 99 ?>" class="w-20 border border-primary/30 rounded px-2 py-1 focus:ring-2 focus:ring-primary/40 transition" required <?= ((int)$product['stock'] <= 0) ? 'disabled' : '' ?>>
                    <button type="submit" class="bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-all duration-200" <?= ((int)$product['stock'] <= 0) ? 'disabled' : '' ?>>Beli</button>
                </form>
            </div>
        </div>
        <div class="mt-12 text-center">
            <a href="index.php" class="inline-block bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
