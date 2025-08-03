document.addEventListener('alpine:init', () => {
  Alpine.data('cart', () => ({
    items: JSON.parse(localStorage.getItem('cart') || '[]'),
    add(item) {
      const idx = this.items.findIndex(i => i.id === item.id);
      if (idx > -1) {
        this.items[idx].qty += 1;
      } else {
        this.items.push({...item, qty: 1});
      }
      this.save();
    },
    remove(id) {
      this.items = this.items.filter(i => i.id !== id);
      this.save();
    },
    updateQty(id, qty) {
      const idx = this.items.findIndex(i => i.id === id);
      if (idx > -1) {
        this.items[idx].qty = qty;
        this.save();
      }
    },
    save() {
      localStorage.setItem('cart', JSON.stringify(this.items));
    },
    clear() {
      this.items = [];
      this.save();
    },
    async checkout() {
      const res = await fetch('../backend/php/order/create_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({items: this.items})
      });
      const data = await res.json();
      if (data.success) {
        this.clear();
        alert('Order berhasil!');
      } else {
        alert(data.message || 'Gagal order');
      }
    }
  }))
});
