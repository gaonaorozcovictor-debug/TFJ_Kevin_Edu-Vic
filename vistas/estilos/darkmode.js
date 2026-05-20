// ── Modo oscuro — persiste en localStorage ──────────────────────────
(function() {
  // Aplicar modo guardado ANTES de que pinte la página (evita flash)
  if (localStorage.getItem('darkmode') === '1') {
    document.documentElement.classList.add('dark');
  }
})();

function toggleDarkMode() {
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.setItem('darkmode', isDark ? '1' : '0');
  actualizarBoton(isDark);
}

function actualizarBoton(isDark) {
  document.querySelectorAll('.btn-darkmode').forEach(btn => {
    btn.textContent = isDark ? '☀️ Modo claro' : '🌙 Modo oscuro';
    btn.title = isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const isDark = document.documentElement.classList.contains('dark');
  actualizarBoton(isDark);
});
