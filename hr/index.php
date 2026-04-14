<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cổng nhân viên - Đăng nhập</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
<div class="login-wrapper">
  <div class="login-card">
    <h1><i class="fa-solid fa-id-badge"></i> Cổng Nhân Viên</h1>
    <p class="subtitle">Đăng nhập để xem bảng lương & quản lý nghỉ phép</p>
    <div id="alert" class="alert alert-info" style="display:none"></div>
    <form id="loginForm">
      <div class="form-group">
        <label for="tenTaiKhoan">Tên đăng nhập</label>
        <input class="form-control" type="text" id="tenTaiKhoan" required autocomplete="username">
      </div>
      <div class="form-group">
        <label for="matKhau">Mật khẩu</label>
        <input class="form-control" type="password" id="matKhau" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
        <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
      </button>
    </form>
  </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('loginBtn');
  const alert = document.getElementById('alert');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang đăng nhập...';

  try {
    const res = await fetch('/api/hr/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        tenTaiKhoan: document.getElementById('tenTaiKhoan').value.trim(),
        matKhau: document.getElementById('matKhau').value
      })
    });
    const data = await res.json();
    if (data.success) {
      window.location.href = '/hr/dashboard.php';
    } else {
      alert.style.display = 'block';
      alert.className = 'alert alert-warning';
      alert.textContent = data.error || 'Đăng nhập thất bại';
    }
  } catch(err) {
    alert.style.display = 'block';
    alert.className = 'alert alert-warning';
    alert.textContent = 'Lỗi kết nối máy chủ';
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Đăng nhập';
});
</script>
</body>
</html>
