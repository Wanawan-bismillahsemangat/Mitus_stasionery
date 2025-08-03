document.addEventListener('alpine:init', () => {
  Alpine.data('loginForm', () => ({
    email: '',
    password: '',
    error: '',
    async submit() {
      this.error = '';
      if (!this.email || !this.password) {
        this.error = 'Email dan password wajib diisi';
        return;
      }
      const res = await fetch('/Mitus_Web_proyek/backend/php/auth/login.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        credentials: 'include',
        body: JSON.stringify({email: this.email, password: this.password})
      });
      const data = await res.json();
      if (data.success) {
        window.location.href = 'routes.php?page=dashboard';
      } else {
        this.error = data.message;
      }
    }
  }))
});
