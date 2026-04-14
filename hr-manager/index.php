<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['hrm_manager'])) {
    header('Location: /hr-manager/employees.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Đăng nhập - Quản lý Nhân sự</title>
  <link rel="stylesheet" href="/hr/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous">
  <style>
    body { background: #1a3c6e; display:flex; align-items:center; justify-content:center; min-height:100vh; }
    .login-box { background:#fff; border-radius:16px; padding:48px 40px; width:360px; box-shadow:0 8px 40px rgba(0,0,0,.25); }
    .login-box h1 { font-size:1.4rem; color:#1a3c6e; margin-bottom:4px; text-align:center; }
    .login-box p  { font-size:.83rem; color:#888; text-align:center; margin-bottom:28px; }
    .login-brand  { text-align:center; font-size:2.2rem; color:#1a3c6e; margin-bottom:12px; }
    .err-msg { color:#e53935; font-size:.85rem; margin-top:8px; display:none; }
  </style>
</head>
<body>
<div class="login-box">
  <div class="login-brand"><i class="fa-solid fa-users-gear"></i></div>
  <h1>Quản lý Nhân sự</h1>
  <p>Đăng nhập vào cổng quản trị nhân sự</p>
  <div class="form-group">
    <label>Tên đăng nhập</label>
    <input class="form-control" type="text" id="username" placeholder="Nhập tên đăng nhập" autofocus>
  </div>
  <div class="form-group">
    <label>Mật khẩu</label>
    <input class="form-control" type="password" id="password" placeholder="Nhập mật khẩu">
  </div>
  <div class="err-msg" id="errMsg"></div>
  <button class="btn btn-primary btn-block" id="loginBtn" style="margin-top:20px">
    <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
  </button>
</div>
<script>
async function doLogin() {
  const btn = document.getElementById('loginBtn');
  const err = document.getElementById('errMsg');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
  err.style.display = 'none';
  const r = await fetch('/api/hr-manager/login.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      username: document.getElementById('username').value,
      password: document.getElementById('password').value
    })
  });
  const d = await r.json();
  if (d.success) {
    window.location.href = '/hr-manager/employees.php';
  } else {
    err.textContent = d.error || 'Đăng nhập thất bại';
    err.style.display = 'block';
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Đăng nhập';
  }
}
document.getElementById('loginBtn').addEventListener('click', doLogin);
document.addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });
</script>
</body>
</html>
