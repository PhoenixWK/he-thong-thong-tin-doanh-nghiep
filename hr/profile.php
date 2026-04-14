<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Thông tin cá nhân'; $activeMenu = 'profile';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-user-pen"></i> Thông tin cá nhân</h1>

<div class="card">
  <div class="card-header">
    <h2>Hồ sơ nhân viên</h2>
    <div>
      <button class="btn btn-primary btn-sm" id="editBtn" style="display:none">
        <i class="fa-solid fa-pen"></i> Chỉnh sửa
      </button>
      <button class="btn btn-success btn-sm" id="saveBtn" style="display:none">
        <i class="fa-solid fa-floppy-disk"></i> Lưu
      </button>
      <button class="btn btn-outline btn-sm" id="cancelBtn" style="display:none">
        Huỷ
      </button>
    </div>
  </div>

  <!-- View mode -->
  <div id="viewMode">
    <table class="hr-table" id="profileTable" style="max-width:600px">
      <tbody>
        <tr><td style="width:200px;color:var(--muted)"><i class="fa-solid fa-user"></i> Họ và tên</td><td><strong id="v_hoVaTen"></strong></td></tr>
        <tr><td style="color:var(--muted)"><i class="fa-solid fa-briefcase"></i> Chức vụ</td><td id="v_chucVu"></td></tr>
        <tr><td style="color:var(--muted)"><i class="fa-solid fa-calendar-check"></i> Ngày vào làm</td><td id="v_ngayVaoLam"></td></tr>
        <tr><td style="color:var(--muted)"><i class="fa-solid fa-phone"></i> Số điện thoại</td><td id="v_soDT"></td></tr>
        <tr><td style="color:var(--muted)"><i class="fa-solid fa-envelope"></i> Email</td><td id="v_email"></td></tr>
      </tbody>
    </table>
  </div>

  <!-- Edit mode -->
  <div id="editMode" style="display:none; max-width:480px">
    <div class="form-row">
      <div class="form-group">
        <label>Họ và tên <span style="color:var(--danger)">*</span></label>
        <input class="form-control" type="text" id="e_hoVaTen" required>
      </div>
      <div class="form-group">
        <label>Số điện thoại</label>
        <input class="form-control" type="tel" id="e_soDT" placeholder="09xxxxxxxx">
      </div>
    </div>
    <div class="form-row single">
      <div class="form-group">
        <label>Email</label>
        <input class="form-control" type="email" id="e_email" placeholder="email@domain.com">
      </div>
    </div>
    <p style="font-size:.8rem;color:var(--muted)"><i class="fa-solid fa-lock"></i> Chức vụ và ngày vào làm chỉ có quản lý mới sửa được.</p>
  </div>
</div>

<script>
let profile = null;

async function loadProfile() {
  const r = await fetch('/api/hr/employee/profile.php');
  profile = await r.json();
  document.getElementById('v_hoVaTen').textContent  = profile.hoVaTen   || '—';
  document.getElementById('v_chucVu').textContent   = profile.chucVu    || '—';
  document.getElementById('v_ngayVaoLam').textContent = profile.ngayVaoLam ? new Date(profile.ngayVaoLam).toLocaleDateString('vi-VN') : '—';
  document.getElementById('v_soDT').textContent     = profile.soDT      || '—';
  document.getElementById('v_email').textContent    = profile.email     || '—';
  document.getElementById('editBtn').style.display  = 'inline-flex';
}

document.getElementById('editBtn').addEventListener('click', () => {
  document.getElementById('viewMode').style.display = 'none';
  document.getElementById('editMode').style.display = 'block';
  document.getElementById('editBtn').style.display  = 'none';
  document.getElementById('saveBtn').style.display  = 'inline-flex';
  document.getElementById('cancelBtn').style.display= 'inline-flex';
  document.getElementById('e_hoVaTen').value = profile.hoVaTen || '';
  document.getElementById('e_soDT').value    = profile.soDT    || '';
  document.getElementById('e_email').value   = profile.email   || '';
});

document.getElementById('cancelBtn').addEventListener('click', () => {
  document.getElementById('viewMode').style.display  = 'block';
  document.getElementById('editMode').style.display  = 'none';
  document.getElementById('editBtn').style.display   = 'inline-flex';
  document.getElementById('saveBtn').style.display   = 'none';
  document.getElementById('cancelBtn').style.display = 'none';
});

document.getElementById('saveBtn').addEventListener('click', async () => {
  const btn = document.getElementById('saveBtn');
  btn.disabled = true;
  try {
    const r = await fetch('/api/hr/employee/profile.php', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        hoVaTen: document.getElementById('e_hoVaTen').value.trim(),
        soDT:    document.getElementById('e_soDT').value.trim(),
        email:   document.getElementById('e_email').value.trim()
      })
    });
    const data = await r.json();
    if (data.success) {
      hrToast('Cập nhật thành công!', 'success');
      await loadProfile();
      document.getElementById('cancelBtn').click();
    } else {
      hrToast(data.error || 'Có lỗi xảy ra', 'error');
    }
  } catch(e) { hrToast('Lỗi kết nối', 'error'); }
  btn.disabled = false;
});

loadProfile();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
