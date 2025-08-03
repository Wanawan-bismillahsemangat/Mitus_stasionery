<?php
// Cek jika sudah login, redirect ke dashboard/user/home
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user'])) {
    // Redirect ke dashboard admin jika role admin, atau ke home jika user
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: index.php?page=admin_dashboard');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}

// Tampilkan error jika ada
if (!empty($_SESSION['login_error'])) {
    echo '<div class="bg-red-100 text-red-700 p-2 mb-4 rounded">' . $_SESSION['login_error'] . '</div>';
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mitus Stationery</title>
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
<body class="bg-gradient-to-br from-school to-white min-h-screen font-futuristic flex flex-col min-h-screen">
  <main class="flex-1 flex items-center justify-center">
    <div class="w-full max-w-md bg-white/90 rounded-2xl shadow-2xl border border-school/40 p-8">
      <h2 class="text-3xl font-extrabold text-primary mb-6 text-center">Login</h2>
      <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      <form action="index.php?page=login" method="post" class="space-y-5 w-full">
        <div>
          <label class="block text-primary font-semibold mb-1" for="email">Email</label>
          <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-primary/30 rounded-lg focus:ring-2 focus:ring-primary/40 transition" placeholder="Email">
        </div>
        <div>
          <label class="block text-primary font-semibold mb-1" for="password">Password</label>
          <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-primary/30 rounded-lg focus:ring-2 focus:ring-primary/40 transition" placeholder="Password">
        </div>
        <button type="submit" name="login" class="w-full bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary text-white font-bold py-2 rounded-lg shadow-lg transition-all duration-200">Login</button>
      </form>
      <p class="mt-6 text-center text-gray-500">Belum punya akun? <a href="index.php?page=register" class="text-accent font-bold hover:underline">Daftar</a></p>
      <a href="index.php" class="block mt-4 text-center text-primary hover:underline">&larr; Kembali ke Beranda</a>
    </div>
  </main>
  <!-- Hapus salah satu include footer jika sudah ada di index.php atau layout utama -->
  <?php /* include __DIR__.'/footer.php'; */ ?>
</body>
</html>
