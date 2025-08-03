// cart.js - update subtotal dan total otomatis saat qty berubah di keranjang

document.addEventListener('DOMContentLoaded', function() {
  const qtyInputs = document.querySelectorAll('input[type="number"][name^="qty["]');
  if (!qtyInputs.length) return;

  function updateCartTotals() {
    let total = 0;
    qtyInputs.forEach(function(input) {
      const tr = input.closest('tr');
      const priceCell = tr.querySelector('td:nth-child(3)');
      const subtotalCell = tr.querySelector('td:nth-child(5)');
      if (!priceCell || !subtotalCell) return;
      const price = parseInt(priceCell.textContent.replace(/[^\d]/g, ''));
      const qty = parseInt(input.value);
      const subtotal = price * qty;
      subtotalCell.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
      total += subtotal;
    });
    // Update total di tfoot
    const totalCell = document.querySelector('tfoot td.text-primary');
    if (totalCell) {
      totalCell.textContent = 'Rp' + total.toLocaleString('id-ID');
    }
  }

  qtyInputs.forEach(function(input) {
    input.addEventListener('input', updateCartTotals);
  });
});
