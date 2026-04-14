<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Tổng quan'; $activeMenu = 'dashboard';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-house"></i> Tổng quan</h1>

<div class="card">
  <p style="font-size:1.05rem">Xin chào, <strong id="empName"></strong>!</p>
  <p style="color:var(--muted); margin-top:6px" id="empRole"></p>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
  <div class="card" style="text-align:center; cursor:pointer" onclick="location.href='/hr/profile.php'">
    <i class="fa-solid fa-user-pen" style="font-size:2rem; color:var(--primary); margin-bottom:12px"></i>
    <div style="font-weight:700">Thông tin cá nhân</div>
    <div style="color:var(--muted); font-size:.85rem; margin-top:4px">Xem & cập nhật hồ sơ</div>
  </div>
  <div class="card" style="text-align:center; cursor:pointer" onclick="location.href='/hr/leave.php'">
    <i class="fa-solid fa-file-medical" style="font-size:2rem; color:var(--warning); margin-bottom:12px"></i>
    <div style="font-weight:700">Đơn xin nghỉ</div>
    <div style="color:var(--muted); font-size:.85rem; margin-top:4px">Nộp & theo dõi đơn</div>
  </div>
  <div class="card" style="text-align:center; cursor:pointer" onclick="location.href='/hr/salary.php'">
    <i class="fa-solid fa-money-bill-wave" style="font-size:2rem; color:var(--accent); margin-bottom:12px"></i>
    <div style="font-weight:700">Bảng lương</div>
    <div style="color:var(--muted); font-size:.85rem; margin-top:4px">Xem & in bảng lương</div>
  </div>
</div>

<div class="card" id="recentLeaveCard" style="display:none">
  <div class="card-header"><h2>Đơn nghỉ gần đây</h2></div>
  <table class="hr-table">
    <thead><tr><th>Loại nghỉ</th><th>Từ ngày</th><th>Đến ngày</th><th>Trạng thái</th></tr></thead>
    <tbody id="recentLeaveTbody"></tbody>
  </table>
</div>

<script>
const emp = <?= json_encode($hrEmp, JSON_UNESCAPED_UNICODE) ?>;
document.getElementById('empName').textContent = emp.hoVaTen;
document.getElementById('empRole').textContent = emp.chucVu;

// Load recent leaves
fetch('/api/hr/leave/list.php').then(r => r.json()).then(data => {
  if (!Array.isArray(data) || data.length === 0) return;
  document.getElementById('recentLeaveCard').style.display = 'block';
  const tbody = document.getElementById('recentLeaveTbody');
  const statusClass = { 'Cho duyet': 'warning', 'Da duyet': 'success', 'Tu choi': 'danger' };
  const statusLabel = { 'Cho duyet': 'Chờ duyệt', 'Da duyet': 'Đã duyệt', 'Tu choi': 'Từ chối' };
  const loaiLabel   = { 'Nghi phep': 'Nghỉ phép', 'Nghi om dau': 'Nghỉ ốm đau', 'Nghi thai san': 'Nghỉ thai sản', 'Nghi viec': 'Nghỉ việc' };
  data.slice(0, 3).forEach(d => {
    tbody.innerHTML += `<tr>
      <td>${loaiLabel[d.loaiNghi] || d.loaiNghi}</td>
      <td>${d.ngayBatDau}</td>
      <td>${d.ngayKetThuc || '—'}</td>
      <td><span class="badge badge-${statusClass[d.trangThai] || 'info'}">${statusLabel[d.trangThai] || d.trangThai}</span></td>
    </tr>`;
  });
});
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
