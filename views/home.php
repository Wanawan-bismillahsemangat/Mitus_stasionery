<?php
// Pastikan $products sudah di-set dari ProductController
// Contoh: $products = $productController->getLatestProducts();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Mitus Stationery</title>
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
    <!-- Hero Section -->
    <section class="relative flex flex-col md:flex-row items-center justify-between gap-10 px-8 py-16 rounded-b-3xl shadow-xl mb-10 overflow-hidden" style="background: url('assets/img/1751274028_Buku.jpeg') center/cover no-repeat;">
      <div class="max-w-xl z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-black mb-4 leading-tight drop-shadow-lg">Belanja Alat Tulis <span class="text-accent">Sekolah</span> Modern & Futuristik</h1>
        <p class="text-lg text-black-900 mb-6">Temukan perlengkapan sekolah terbaik, harga terjangkau, dan desain kekinian untuk generasi masa depan. Belanja mudah, cepat, dan aman di Mitus School Store!</p>
        <a href="#produk" class="inline-block bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Lihat Produk</a>
      </div>
      <div class="absolute right-0 bottom-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl opacity-60"></div>
      <div class="absolute left-0 top-0 w-40 h-40 bg-primary/10 rounded-full blur-2xl opacity-60"></div>
    </section>
    <!-- Produk Section -->
    <div class="container mx-auto px-4 py-8" id="produk">
        <h2 class="text-3xl font-bold mb-8 text-center text-schoolDark">Produk Terbaru & Unggulan</h2>
        <?php $productCount = !empty($products) ? count($products) : 0; ?>
        <style>
        /* Animasi transisi slider produk */
        #produkSlider {
          transition: transform 0.5s cubic-bezier(.4,2,.6,1), opacity 0.5s;
        }
        .produk-card {
          transition: box-shadow 0.3s, transform 0.5s cubic-bezier(.4,2,.6,1), opacity 0.5s;
          opacity: 0.7;
        }
        .produk-card.active {
          box-shadow: 0 8px 32px 0 rgba(99,102,241,0.15);
          transform: scale(1.08) translateY(-10px);
          opacity: 1;
          z-index: 2;
        }
        </style>
        <div class="relative">
          <div id="produkSlider" class="flex gap-8 overflow-x-auto snap-x snap-mandatory pb-4 scrollbar-thin scrollbar-thumb-primary/30 scrollbar-track-school/20" style="scroll-behavior:smooth;">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $i => $product): ?>
                    <?php
                      // Ambil nama file gambar, fallback ke default jika kosong atau file tidak ada
                      $imgFile = !empty($product['image']) ? $product['image'] : 'default-product.png';
                      // Jika admin upload, path bisa 'assets/img/namafile.jpg' atau hanya 'namafile.jpg'
                      if (strpos($imgFile, 'assets/img/') === 0) {
                        $imgPath = realpath(__DIR__ . '/../' . $imgFile);
                        $imgWeb = '/Mitus_Web_proyek/' . $imgFile;
                      } else {
                        $imgPath = realpath(__DIR__ . '/../assets/img/' . $imgFile);
                        $imgWeb = '/Mitus_Web_proyek/assets/img/' . $imgFile;
                      }
                      $imgDir = realpath(__DIR__ . '/../assets/img/');
                      $allowedExt = ['jpg','jpeg','png','gif','webp'];
                      $ext = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                      // Validasi: file harus ada, berada di folder img, dan ekstensi gambar
                      if (!$imgPath || strpos($imgPath, $imgDir) !== 0 || !is_file($imgPath) || !in_array($ext, $allowedExt)) {
                          $imgWeb = '/Mitus_Web_proyek/assets/img/default-product.png';
                      }
                    ?>
                    <div class="produk-card bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center border border-school/40 hover:scale-105 hover:shadow-2xl transition-transform duration-200 min-w-[260px] max-w-[260px] snap-center">
                      <img src="<?= htmlspecialchars($imgWeb) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-32 w-32 object-contain mb-3 rounded-lg border border-schoolAccent/30 bg-school" onerror="this.onerror=null;this.src='/Mitus_Web_proyek/assets/img/default-product.png';this.classList.add('border-red-500');this.parentNode.insertAdjacentHTML('beforeend', '<div class=\'text-xs text-red-500 mt-1\'>Gambar tidak ditemukan</div>');">
                      <h3 class="font-bold text-lg text-primary mb-1"><?= htmlspecialchars($product['name']) ?></h3>
                      <p class="text-accent font-bold text-base mb-1">Rp<?= number_format($product['price'],0,',','.') ?></p>
                      <p class="text-sm text-gray-500 mb-1 text-center min-h-[40px]">Kategori: <?= htmlspecialchars($product['category']) ?></p>
                      <p class="text-xs mb-2 <?= ($product['stock'] <= 0 ? 'text-red-500 font-bold' : 'text-gray-500') ?>">Stok: <?= (int)$product['stock'] ?></p>
                      <a href="index.php?page=product_detail&id=<?= $product['id'] ?>" class="mt-auto inline-block bg-primary hover:bg-secondary text-white px-5 py-2 rounded-lg font-semibold shadow transition">Lihat Detail</a>
                      <form action="index.php?page=cart_add&id=<?= $product['id'] ?>" method="post" class="w-full mt-2">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="w-full px-5 py-2 rounded-lg shadow transition flex items-center justify-center gap-2 font-bold text-primary bg-accent hover:bg-yellow-400 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed" <?= ($product['stock'] <= 0 ? 'disabled' : '') ?>>
                          <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.35 2.7A1 1 0 007.5 17h9a1 1 0 00.9-1.45L17 13M9 21h6a2 2 0 100-4H9a2 2 0 100 4z' /></svg>
                          <?= ($product['stock'] <= 0 ? '<span class="text-red-500 font-bold">Stok Habis</span>' : 'Masukkan ke Keranjang') ?>
                        </button>
                      </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-4 text-center text-gray-400">Tidak ada produk tersedia.</p>
            <?php endif; ?>
          </div>
          <?php if ($productCount > 4): ?>
          <button id="prevProduk" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-primary text-primary hover:text-white border border-primary/20 rounded-full p-2 shadow-lg transition z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
          <button id="nextProduk" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-primary text-primary hover:text-white border border-primary/20 rounded-full p-2 shadow-lg transition z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
          <?php endif; ?>
        </div>
        <script>
        // Slider produk dengan animasi transisi dan highlight card aktif
        const produkSlider = document.getElementById('produkSlider');
        const produkCards = produkSlider.querySelectorAll('.produk-card');
        let activeIdx = 0;
        function setActiveCard(idx) {
          produkCards.forEach((card, i) => {
            card.classList.toggle('active', i === idx);
          });
        }
        setActiveCard(activeIdx);
        <?php if ($productCount > 4): ?>
        const prevBtn = document.getElementById('prevProduk');
        const nextBtn = document.getElementById('nextProduk');
        prevBtn.onclick = () => {
          activeIdx = Math.max(0, activeIdx - 1);
          produkSlider.scrollBy({left: -280, behavior: 'smooth'});
          setActiveCard(activeIdx);
        };
        nextBtn.onclick = () => {
          activeIdx = Math.min(produkCards.length - 1, activeIdx + 1);
          produkSlider.scrollBy({left: 280, behavior: 'smooth'});
          setActiveCard(activeIdx);
        };
        produkSlider.addEventListener('scroll', () => {
          // Deteksi card paling tengah
          let minDiff = Infinity, idx = 0;
          produkCards.forEach((card, i) => {
            const rect = card.getBoundingClientRect();
            const sliderRect = produkSlider.getBoundingClientRect();
            const diff = Math.abs((rect.left + rect.right)/2 - (sliderRect.left + sliderRect.right)/2);
            if (diff < minDiff) { minDiff = diff; idx = i; }
          });
          setActiveCard(idx);
        });
        <?php endif; ?>
        </script>
    </div>
    <!-- Tentang Toko Section -->
    <section class="container mx-auto px-4 py-12 flex flex-col md:flex-row items-center gap-10">
      <div class="flex-1">
        <h2 class="text-2xl md:text-3xl font-bold text-primary mb-4">Tentang Mitus School Store</h2>
        <p class="text-gray-700 text-lg mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequuntur, sunt deleniti? Sequi, inventore in illum incidunt officiis nesciunt totam amet dolor doloremque, ipsa mollitia ad. Aspernatur illum vitae nemo delectus.</p>
        <ul class="list-disc pl-6 text-gray-600">
          <li>Produk original &amp; lengkap</li>
          <li>Pelayanan ramah &amp; profesional</li>
          <li>Transaksi aman &amp; cepat</li>
        </ul>
      </div>
      <div class="flex-1 flex justify-center">
        <img src="assets/img/1751124416_Untitled design (3).png" alt="Tentang Toko" class="w-72 h-72 object-contain rounded-2xl shadow-xl border border-schoolAccent/30 bg-school">
      </div>
    </section>
    <!-- Bagian Toko Section -->
    <section class="container mx-auto px-4 py-12">
      <h2 class="text-2xl md:text-3xl font-bold text-primary mb-8 text-center">Bagian &amp; Layanan Toko</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center border border-school/40">
          <span class="text-4xl mb-3">🛒</span>
          <h3 class="font-bold text-lg text-primary mb-1">Alat Tulis &amp; Sekolah</h3>
          <p class="text-gray-600 text-center">Pensil, pulpen, buku, penggaris, dan perlengkapan sekolah lainnya.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center border border-school/40">
          <span class="text-4xl mb-3">🖨️</span>
          <h3 class="font-bold text-lg text-primary mb-1">Print &amp; Fotokopi</h3>
          <p class="text-gray-600 text-center">Layanan print, fotokopi, jilid, dan scan dokumen untuk kebutuhan sekolah &amp; kantor.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center border border-school/40">
          <span class="text-4xl mb-3">🎁</span>
          <h3 class="font-bold text-lg text-primary mb-1">Aksesoris &amp; Hadiah</h3>
          <p class="text-gray-600 text-center">Aneka aksesoris lucu, hadiah, dan perlengkapan kreatif untuk pelajar.</p>
        </div>
      </div>
    </section>
    <!-- Lokasi Section -->
    <section id="lokasi" class="container mx-auto px-4 py-12 flex flex-col md:flex-row items-center gap-10">
      <div class="flex-1 flex justify-center mb-6 md:mb-0">
        <iframe class="rounded-2xl shadow-xl border border-schoolAccent/30 w-full h-64 md:w-96 md:h-72" src="https://maps.app.goo.gl/bNtDj3biuG7v4xVA8" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="flex-1">
        <h2 class="text-2xl md:text-3xl font-bold text-primary mb-4">Lokasi Toko</h2>
        <p class="text-gray-700 text-lg mb-2">Kunjungi toko kami di:</p>
        <p class="text-gray-800 font-semibold mb-2">Jl. Contoh Alamat No.123, Kota, Provinsi</p>
        <p class="text-gray-600 mb-4">Dekat dengan SPBU dan Pusat Perbelanjaan</p>
        <a href="https://goo.gl/maps/abc123" target="_blank" class="inline-block bg-primary hover:bg-secondary text-white font-bold px-6 py-3 rounded-lg shadow-lg transition">Buka di Google Maps</a>
      </div>
    </section>
    <!-- Footer -->
    <footer class="mt-16 py-8 bg-schoolDark text-white text-center rounded-t-3xl shadow-inner">
      <div class="mb-2">
        <span class="font-bold text-accent">Mitra Usaha Sampurna</span> &copy; 2025. All Rights Reserved.
      </div>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.querySelector('a[href="#produk"]');
  if (btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.getElementById('produk');
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }
});
</script>
<script>
        // Intercept form add to cart agar tidak reload halaman
        document.querySelectorAll('#produkSlider form[action*="cart_add"]').forEach(form => {
          form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const fd = new FormData(form);
            const url = form.action;
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.classList.add('opacity-60');
            try {
              const res = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
              });
              let data = {};
              try { data = await res.json(); } catch {}
              // Update badge keranjang jika ada event custom
              window.dispatchEvent(new Event('cart-updated'));
              // Ambil jumlah item keranjang dari backend (session)
              try {
                const resp = await fetch('index.php?page=cart_count', {headers: {'Accept':'application/json'}});
                const json = await resp.json();
                if (typeof json.count !== 'undefined') {
                  window.dispatchEvent(new CustomEvent('cart-badge-update', {detail: {count: json.count}}));
                }
              } catch(e) {}
              // Tampilkan notifikasi sukses
              let notif = document.createElement('div');
              notif.className = 'fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 animate-fadeIn';
              notif.innerHTML = `<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="font-semibold">Produk berhasil ditambahkan ke keranjang!</span><button onclick="this.parentNode.remove()" class="ml-4 text-green-600 hover:text-green-800 font-bold">Tutup</button>`;
              document.body.appendChild(notif);
              setTimeout(()=>{notif.remove();}, 2500);
            } catch(err) {
              alert('Gagal menambahkan ke keranjang!');
            } finally {
              btn.disabled = false;
              btn.classList.remove('opacity-60');
            }
          });
        });
      </script>
  </body>
</html>
