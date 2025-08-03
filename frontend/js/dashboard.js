document.addEventListener('alpine:init', () => {
  Alpine.data('dashboard', () => ({
    user: null,
    async fetchUser() {
      const res = await fetch('../backend/php/user/get_user.php');
      const data = await res.json();
      if (data.success) {
        this.user = data.user;
      } else {
        window.location.href = 'login.html';
      }
    },
    async logout() {
      await fetch('../backend/php/auth/logout.php');
      window.location.href = 'login.html';
    }
  }))
});
