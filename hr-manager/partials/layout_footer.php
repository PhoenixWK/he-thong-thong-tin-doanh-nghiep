  </main>
</div>
<div class="hr-toast" id="hrToast"></div>
<script>
document.getElementById('logoutBtn').addEventListener('click', async () => {
  await fetch('/api/hr-manager/logout.php', { method: 'POST' });
  window.location.href = '/hr-manager/index.php';
});
window.hrToast = function(msg, type='success', dur=3000) {
  const c = document.getElementById('hrToast');
  const el = document.createElement('div');
  el.className = 'hr-toast__item ' + type;
  el.textContent = msg;
  c.appendChild(el);
  setTimeout(() => el.remove(), dur);
};
</script>
</body>
</html>
