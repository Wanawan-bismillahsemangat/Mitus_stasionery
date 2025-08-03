<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Pesanan - Mitus Stationery</title>
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
  <div class="container mx-auto px-4 py-16 min-h-[60vh] flex flex-col justify-center items-center">
    <div class="bg-white/90 rounded-3xl shadow-2xl p-10 border border-school/40 max-w-xl w-full flex flex-col items-center">
      <svg class="w-20 h-20 text-accent mb-6 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#fbbf24"/><path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-4 drop-shadow">Pembayaran Pesanan</h1>
      <p class="text-lg text-gray-700 mb-6 text-center">Silakan klik tombol di bawah untuk konfirmasi dan pembayaran pesanan melalui WhatsApp admin.<br>Admin akan memproses pesanan Anda setelah konfirmasi.</p>
      <div class="mb-4 text-lg text-primary font-bold">ID Pesanan: <span class="bg-schoolAccent/10 px-2 py-1 rounded"><?php echo isset($order_id) ? htmlspecialchars($order_id) : 'ORDER-' . time(); ?></span></div>
      <form id="checkoutForm" method="post" action="index.php?page=checkout_proses" class="w-full flex flex-col items-center gap-4">
        <input type="hidden" name="order_id" value="<?php echo isset($order_id) ? htmlspecialchars($order_id) : 'ORDER-' . time(); ?>">
        <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full px-4 py-2 rounded border border-schoolAccent/40 focus:outline-accent">
        <input type="text" name="alamat" placeholder="Alamat Lengkap" required class="w-full px-4 py-2 rounded border border-schoolAccent/40 focus:outline-accent">
        <input type="text" name="no_hp" placeholder="Nomor HP" required class="w-full px-4 py-2 rounded border border-schoolAccent/40 focus:outline-accent">
        <button type="button" onclick="sendWA()" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition mb-2">Kirim & Konfirmasi ke WhatsApp Admin</button>
        <button type="submit" class="inline-block bg-accent hover:bg-yellow-400 text-primary font-bold px-8 py-3 rounded-lg shadow-lg transition mb-4">Pesanan Selesai & Simpan</button>
      </form>
      <a href="index.php?page=cart" class="inline-block text-primary hover:underline mt-2">Kembali ke Keranjang</a>
      <script>
        function sendWA() {
          var form = document.getElementById('checkoutForm');
          var nama = form.nama.value;
          var alamat = form.alamat.value;
          var no_hp = form.no_hp.value;
          var order_id = form.order_id.value;
          var waText = `Halo admin, saya ingin konfirmasi pesanan dengan detail:%0AID Pesanan: ${order_id}%0ANama: ${nama}%0AAlamat: ${alamat}%0ANo HP: ${no_hp}`;
          window.open('https://web.whatsapp.com/send?phone=6285285957850&text=' + waText, '_blank');
        }
      </script>
    </div>
  </div>
</body>
</html>
