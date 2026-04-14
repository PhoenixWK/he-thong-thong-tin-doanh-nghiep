  </main><!-- end hr-main -->
</div><!-- end hr-layout -->

<!-- Toast container -->
<div class="hr-toast" id="hrToast"></div>

<script>
// Logout
document.getElementById('logoutBtn').addEventListener('click', async function() {
  await fetch('/api/hr/logout.php', { method: 'POST' });
  window.location.href = '/hr/index.php';
});

// Toast helper
window.hrToast = function(msg, type = 'success', duration = 3000) {
  const c = document.getElementById('hrToast');
  const el = document.createElement('div');
  el.className = 'hr-toast__item ' + type;
  el.textContent = msg;
  c.appendChild(el);
  setTimeout(() => el.remove(), duration);
};
</script>
</body>
</html>
