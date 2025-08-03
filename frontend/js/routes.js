// Simple SPA Router for Mitus E-Commerce
// Usage: include this script in index.html and other pages
// It will load the correct HTML into <div id="app"></div>

function navigate(path) {
  const file = routes[path] || 'index.html';
  fetch(file)
    .then(res => res.text())
    .then(html => {
      document.getElementById('app').innerHTML = html;
      window.history.pushState({}, '', path);
    });
}

window.addEventListener('popstate', () => {
  const path = window.location.pathname.replace('/Mitus_Web_proyek/frontend', '') || '/';
  navigate(path);
});

// Initial load
window.addEventListener('DOMContentLoaded', () => {
  const path = window.location.pathname.replace('/Mitus_Web_proyek/frontend', '') || '/';
  if (document.getElementById('app')) {
    navigate(path);
  }
});

// Example: <a href="/login" onclick="navigate('/login'); return false;">Login</a>
