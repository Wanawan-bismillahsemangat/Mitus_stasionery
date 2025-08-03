// cart-badge.js - update badge keranjang di navbar dan animasi notifikasi

document.addEventListener('DOMContentLoaded', function() {
  // Saat halaman dimuat, ambil jumlah barang dari backend/session
  fetch('/Mitus_Web_proyek/index.php?page=cart_count', {headers: {'Accept':'application/json'}})
    .then(res => res.json())
    .then(json => {
      if (typeof json.count !== 'undefined') {
        const event = new CustomEvent('cart-badge-update', {detail: {count: json.count}});
        window.dispatchEvent(event);
      }
    });

  // Cari elemen badge dan ikon keranjang
  const cartBadge = document.getElementById('cart-badge');
  const cartIcon = document.getElementById('cart-icon');

  // Ambil jumlah item dari localStorage (frontend) atau session (backend)
  function getCartCount() {
    // Cek localStorage (jika ada)
    let count = 0;
    try {
      const items = JSON.parse(localStorage.getItem('cart') || '[]');
      count = items.reduce((sum, item) => sum + (item.qty || 1), 0);
    } catch (e) {}
    return count;
  }

  function updateCartBadge() {
    const count = getCartCount();
    if (cartBadge) {
      cartBadge.textContent = count > 0 ? count : '';
      cartBadge.style.display = count > 0 ? 'inline-flex' : 'none';
    }
  }

  // Animasi notifikasi saat barang ditambah ke keranjang
  window.showCartNotif = function() {
    if (!cartIcon) return;
    cartIcon.classList.remove('animate-bounce');
    void cartIcon.offsetWidth; // trigger reflow
    cartIcon.classList.add('animate-bounce');
    setTimeout(() => cartIcon.classList.remove('animate-bounce'), 700);
  };

  // Update badge saat halaman dimuat
  updateCartBadge();

  // Event listener custom untuk update badge dari halaman lain
  window.addEventListener('cart-updated', updateCartBadge);

  // Event listener custom untuk update badge dari backend/session
  window.addEventListener('cart-badge-update', function(e) {
    if (cartBadge && e.detail && typeof e.detail.count !== 'undefined') {
      var count = parseInt(e.detail.count, 10);
      if (isNaN(count) || count < 1) {
        cartBadge.textContent = '';
        cartBadge.style.display = 'none';
        var badgeMobile = document.getElementById('cart-badge-mobile');
        if (badgeMobile) {
          badgeMobile.textContent = '';
          badgeMobile.style.display = 'none';
        }
      } else {
        cartBadge.textContent = count;
        cartBadge.style.display = 'inline-flex';
        cartBadge.classList.remove('badge-pop');
        void cartBadge.offsetWidth;
        cartBadge.classList.add('badge-pop');
        var badgeMobile = document.getElementById('cart-badge-mobile');
        if (badgeMobile) {
          badgeMobile.textContent = count;
          badgeMobile.style.display = 'inline-flex';
          badgeMobile.classList.remove('badge-pop');
          void badgeMobile.offsetWidth;
          badgeMobile.classList.add('badge-pop');
        }
      }
    }
  });

  // Jika ada perubahan localStorage (tab lain)
  window.addEventListener('storage', function(e) {
    if (e.key === 'cart') updateCartBadge();
  });
});
