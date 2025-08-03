document.addEventListener('alpine:init', () => {
  Alpine.data('produkList', () => ({
    produk: [],
    async fetchProduk() {
      const res = await fetch('../backend/php/produk/get_produk.php');
      this.produk = await res.json();
    }
  }))
});
