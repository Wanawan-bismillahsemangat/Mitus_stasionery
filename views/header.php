<?php
// Header modern responsif dengan Tailwind CSS
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitus Stationery</title>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
      <div class="container mx-auto px-4 py-3 flex items-center justify-between relative">
        <!-- Logo & Nama Toko -->
        <a href="index.php" class="flex items-center gap-2 select-none">
          <img src="/Mitus_Web_proyek/assets/img/1751124416_Untitled design (3).png" alt="Logo" class="h-10 w-10 rounded-lg shadow border border-schoolAccent/30 bg-school object-contain">
          <span class="font-extrabold text-xl md:text-2xl text-primary tracking-tight font-futuristic">Mitus <span class="text-accent">Stationery</span></span>
        </a>
        <!-- Kanan: Navigasi, Logout, Keranjang (desktop) -->
        <div class="hidden md:flex items-center gap-2 ml-auto">
          <nav class="flex gap-2 items-center">
            <a href="index.php#" class="nav-link-desktop text-primary font-semibold hover:text-accent transition px-3 py-1 rounded" data-scroll="home">Beranda</a>
            <a href="index.php#produk" class="nav-link-desktop text-primary font-semibold hover:text-accent transition px-3 py-1 rounded" data-scroll="produk">Produk</a>
            <a href="index.php#lokasi" class="nav-link-desktop text-primary font-semibold hover:text-accent transition px-3 py-1 rounded" data-scroll="lokasi">Lokasi</a>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="/Mitus_Web_proyek/views/order_status.php" class="nav-link-desktop text-accent font-bold hover:text-yellow-600 transition px-3 py-1 rounded underline">Status Order</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
              <a href="/Mitus_Web_proyek/index.php?page=admin_dashboard" class="nav-link-desktop text-indigo-700 font-bold hover:text-accent transition px-3 py-1 rounded bg-indigo-100 border border-indigo-200 ml-2">Dashboard Admin</a>
            <?php endif; ?>
          </nav>
          <?php if (!isset($_SESSION['user'])): ?>
            <a href="index.php?page=login" class="text-primary font-semibold hover:text-accent transition px-3 py-1 rounded">Login</a>
          <?php else: ?>
            <a href="logout.php" class="text-red-500 font-semibold hover:text-accent transition px-3 py-1 rounded">Logout</a>
          <?php endif; ?>
          <a href="index.php?page=cart" class="relative group ml-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary hover:text-accent transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.35 2.7A1 1 0 007.5 17h9a1 1 0 00.9-1.45L17 13M9 21h6a2 2 0 100-4H9a2 2 0 100 4z" />
            </svg>
            <span id="cart-badge" class="absolute -top-2 -right-2 bg-accent text-white text-xs font-bold rounded-full px-2 py-0.5 shadow transition-all">0</span>
          </a>
        </div>
        <!-- Ikon Keranjang & Hamburger (mobile) -->
        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2 md:hidden">
          <a href="index.php?page=cart" class="relative group ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary hover:text-accent transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.35 2.7A1 1 0 007.5 17h9a1 1 0 00.9-1.45L17 13M9 21h6a2 2 0 100-4H9a2 2 0 100 4z" />
            </svg>
            <span id="cart-badge-mobile" class="absolute -top-2 -right-2 bg-accent text-white text-xs font-bold rounded-full px-2 py-0.5 shadow">0</span>
          </a>
          <button id="hamburgerBtn" class="ml-2 p-2 rounded-lg hover:bg-schoolAccent/10 focus:outline-none focus:ring-2 focus:ring-accent transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
      <!-- Mobile Nav -->
      <nav id="mobileNav" class="fixed top-0 left-0 w-full h-full bg-white/95 backdrop-blur-lg z-40 flex flex-col items-center justify-center gap-8 text-xl font-bold text-primary transition-transform duration-300 translate-y-[-100%] md:hidden shadow-2xl">
        <a href="index.php#" class="nav-link hover:text-accent transition" data-scroll="home">Beranda</a>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="/Mitus_Web_proyek/views/order_status.php" class="nav-link hover:text-accent transition font-bold text-accent underline">Status Order</a>
      <?php endif; ?>
        <a href="index.php#produk" class="nav-link hover:text-accent transition" data-scroll="produk">Produk</a>
        <a href="index.php#lokasi" class="nav-link hover:text-accent transition" data-scroll="lokasi">Lokasi</a>
        <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
          <a href="/Mitus_Web_proyek/index.php?page=admin_dashboard" class="nav-link text-indigo-700 font-bold hover:text-accent transition px-3 py-1 rounded bg-indigo-100 border border-indigo-200">Dashboard Admin</a>
        <?php endif; ?>
        <a href="index.php?page=cart" class="hover:text-accent transition flex items-center gap-2">Keranjang
          <span id="cart-badge-mobile" class="bg-accent text-white text-xs font-bold rounded-full px-2 py-0.5 shadow">0</span>
        </a>
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="index.php?page=login" class="hover:text-accent transition">Login</a>
        <?php else: ?>
          <a href="logout.php" class="text-red-500 hover:text-accent transition">Logout</a>
        <?php endif; ?>
        <button id="closeMobileNav" class="absolute top-6 right-6 p-2 rounded-full bg-accent/10 hover:bg-accent/20 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </nav>
      <script>
      // Hamburger menu logic
      const hamburgerBtn = document.getElementById('hamburgerBtn');
      const mobileNav = document.getElementById('mobileNav');
      const closeMobileNav = document.getElementById('closeMobileNav');
      hamburgerBtn && hamburgerBtn.addEventListener('click', () => {
        mobileNav.style.transform = 'translateY(0)';
        document.body.style.overflow = 'hidden';
      });
      closeMobileNav && closeMobileNav.addEventListener('click', () => {
        mobileNav.style.transform = 'translateY(-100%)';
        document.body.style.overflow = '';
      });
      mobileNav && mobileNav.addEventListener('click', (e) => {
        if (e.target === mobileNav) {
          mobileNav.style.transform = 'translateY(-100%)';
          document.body.style.overflow = '';
        }
      });
      // Robust smooth scroll logic untuk nav-link dan hash
      function smoothScrollToSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
          section.classList.add('ring-4','ring-accent','ring-offset-2','transition');
          setTimeout(()=>section.classList.remove('ring-4','ring-accent','ring-offset-2','transition'), 800);
          return true;
        }
        return false;
      }
      function isHomePage() {
        return window.location.pathname.endsWith('index.php') || window.location.pathname === '/' || window.location.pathname === '/Mitus_Web_proyek/' || window.location.pathname.endsWith('/index.php');
      }
      // Scroll ke section jika ada hash di URL setelah load
      document.addEventListener('DOMContentLoaded', function() {
        let count = 0;
        try {
          count = parseInt(localStorage.getItem('cart_count')) || 0;
        } catch {}
        syncCartBadge(count);
        // Scroll ke section jika ada hash
        if (isHomePage() && window.location.hash) {
          const hash = window.location.hash.replace('#','');
          if (hash && document.getElementById(hash)) {
            setTimeout(()=>smoothScrollToSection(hash), 100);
          }
        }
      });
      // Desktop nav-link highlight
      function setActiveNavDesktop() {
        if (!isHomePage()) return;
        const links = document.querySelectorAll('.nav-link-desktop');
        let active = null;
        if(window.location.hash) {
          active = window.location.hash.replace('#','');
        }
        let found = false;
        links.forEach(link => {
          const section = link.getAttribute('data-scroll');
          if((active && section===active) || (!active && section==='home')) {
            link.classList.add('bg-accent/10','text-accent','shadow');
            found = true;
          } else {
            link.classList.remove('bg-accent/10','text-accent','shadow');
          }
        });
        // Default highlight Beranda jika tidak ada hash
        if(!found && links.length) links[0].classList.add('bg-accent/10','text-accent','shadow');
      }
      setActiveNavDesktop();
      window.addEventListener('hashchange', setActiveNavDesktop);
      document.querySelectorAll('.nav-link, .nav-link-desktop').forEach(link => {
        link.addEventListener('click', function(e) {
          const section = this.getAttribute('data-scroll');
          if (section && isHomePage()) {
            e.preventDefault();
            if(mobileNav) {
              mobileNav.style.transform = 'translateY(-100%)';
              document.body.style.overflow = '';
            }
            let scrolled = false;
            if(section==='home') {
              window.scrollTo({top:0,behavior:'smooth'});
              scrolled = true;
            } else {
              scrolled = smoothScrollToSection(section);
            }
            // Update hash di URL tanpa reload jika section ditemukan
            if(scrolled && section!=='home') history.replaceState(null,null,'#'+section);
            else if(scrolled) history.replaceState(null,null,window.location.pathname);
            setActiveNavDesktop();
          }
        });
      });
      // Sinkronisasi badge keranjang desktop & mobile
      function syncCartBadge(count) {
        document.getElementById('cart-badge').textContent = count;
        document.getElementById('cart-badge-mobile').textContent = count;
      }
      // Jika sudah ada script cart-badge.js, gunakan event custom
      document.addEventListener('cart-badge-update', function(e) {
        syncCartBadge(e.detail.count);
      });
      </script>
      <style>
        #mobileNav {
          will-change: transform;
        }
        #mobileNav[style*="translateY(0)"] {
          box-shadow: 0 8px 32px 0 rgba(99,102,241,0.15);
        }
        #cart-badge, #cart-badge-mobile {
          min-width: 1.5rem;
          text-align: center;
          transition: background 0.2s, transform 0.2s;
          animation: badgePop 0.3s;
        }
        @keyframes badgePop {
          0% { transform: scale(0.7); }
          70% { transform: scale(1.2); }
          100% { transform: scale(1); }
        }
        .badge-pop {
          animation: badgePop 0.4s cubic-bezier(.4,2,.6,1);
        }
      </style>
    </header>
    <script src="/Mitus_Web_proyek/assets/js/cart-badge.js"></script>
    <script>
    // Fallback: jika badge tetap kosong setelah 2 detik, fetch ulang dari backend
    setTimeout(function() {
      var badge = document.getElementById('cart-badge');
      if (badge && (!badge.textContent || badge.textContent === '0')) {
        fetch('/Mitus_Web_proyek/index.php?page=cart_count', {headers: {'Accept':'application/json'}})
          .then(res => res.json())
          .then(json => {
            if (typeof json.count !== 'undefined') {
              badge.textContent = json.count > 0 ? json.count : '';
              badge.style.display = json.count > 0 ? 'inline-flex' : 'none';
              var badgeMobile = document.getElementById('cart-badge-mobile');
              if (badgeMobile) {
                badgeMobile.textContent = json.count > 0 ? json.count : '';
                badgeMobile.style.display = json.count > 0 ? 'inline-flex' : 'none';
              }
            }
          });
      }
    }, 2000);
    </script>
  </body>
</html>
