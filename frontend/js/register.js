document.addEventListener('alpine:init', () => {
  Alpine.data('registerForm', () => ({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    error: '',
    async submit() {
      this.error = '';
      if (!this.name || !this.email || !this.password || !this.confirm_password) {
        this.error = 'Semua field wajib diisi';
        return;
      }
      if (this.password !== this.confirm_password) {
        this.error = 'Password dan konfirmasi password tidak sama';
        return;
      }
      const res = await fetch('/Mitus_Web_proyek/backend/php/auth/register.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        credentials: 'include',
        body: JSON.stringify({name: this.name, email: this.email, password: this.password})
      });
      const data = await res.json();
      if (data.success) {
        window.location.href = 'routes.php?page=login';
      } else {
        this.error = data.message;
      }
    }
  }))
});
