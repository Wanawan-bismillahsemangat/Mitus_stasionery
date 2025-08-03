<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Berhasil - Mitus Stationery</title>
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
      <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-4 drop-shadow">Pesanan Berhasil!</h1>
      <p class="text-lg text-gray-700 mb-6">Terima kasih, pesanan Anda telah dikonfirmasi dan akan segera diproses.<br>Silakan cek status pesanan di menu <span class="text-accent font-bold">Orders</span> atau hubungi admin jika ada kendala.</p>
      <a href="index.php" class="inline-block bg-accent hover:bg-yellow-400 text-primary font-bold px-6 py-3 rounded-lg shadow-lg transition">Kembali ke Beranda</a>
    </div>
  </div>
</body>
</html>
