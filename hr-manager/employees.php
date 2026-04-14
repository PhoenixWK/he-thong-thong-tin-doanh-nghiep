<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Nhân sự'; $activeMenu = 'employees';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-users"></i> Quản lý Nhân sự</h1>

<div class="card">
  <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between">
    <div style="display:flex; gap:10px; flex-wrap:wrap">
      <button class="btn btn-primary" id="btnAddEmp"><i class="fa-solid fa-user-plus"></i> Thêm nhân viên</button>
    </div>
    <div style="display:flex; gap:8px; align-items:center">
      <label style="font-size:.83rem; font-weight:600">Trạng thái:</label>
      <select class="form-control" id="filterStatus" style="width:140px">
        <option value="">Tất cả</option>
        <option value="Dang lam" selected>Đang làm</option>
        <option value="Da nghi">Đã nghỉ</option>
      </select>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2 id="tableTitle">Danh sách nhân viên</h2></div>
  <table class="hr-table" id="empTable">
    <thead>
      <tr>
        <th>#</th><th>Họ và tên</th><th>Chức vụ</th><th>Lương CB</th><th>Hệ số</th>
        <th>Ngày vào</th><th>Trạng thái</th><th>Thao tác</th>
      </tr>
    </thead>
    <tbody id="empTbody"><tr><td colspan="8" style="text-align:center;color:#999">Đang tải...</td></tr></tbody>
  </table>
</div>

<!-- Modal: Thêm nhân viên -->
<div class="modal-overlay" id="modalAdd">
  <div class="modal-box">
    <h3><i class="fa-solid fa-user-plus"></i> Thêm nhân viên mới</h3>
    <div class="form-group">
      <label>Tài khoản</label>
      <select class="form-control" id="addAccount"><option value="">Chọn tài khoản...</option></select>
    </div>
    <div class="form-group">
      <label>Chức vụ</label>
      <input class="form-control" id="addChucVu" placeholder="Nhân viên bán hàng, Kế toán...">
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
      <div class="form-group">
        <label>Lương cơ bản (VNĐ)</label>
        <input class="form-control" type="number" id="addLuong" value="5000000" min="0">
      </div>
      <div class="form-group">
        <label>Hệ số lương</label>
        <input class="form-control" type="number" id="addHeSo" value="1.0" step="0.1" min="0.1">
      </div>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
      <div class="form-group">
        <label>Phụ cấp cố định</label>
        <input class="form-control" type="number" id="addPhuCap" value="0" min="0">
      </div>
      <div class="form-group">
        <label>Ngày vào làm</label>
        <input class="form-control" type="date" id="addNgayVao" value="<?= date('Y-m-d') ?>">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('modalAdd')">Hủy</button>
      <button class="btn btn-primary" id="btnSaveAdd"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
    </div>
  </div>
</div>

<!-- Modal: Đổi chức vụ -->
<div class="modal-overlay" id="modalChangePOS">
  <div class="modal-box">
    <h3><i class="fa-solid fa-arrow-up-right-dots"></i> Thay đổi chức vụ</h3>
    <input type="hidden" id="cpMaNhanVien">
    <div class="alert alert-info" id="cpCurrentInfo" style="margin-bottom:16px; font-size:.85rem"></div>
    <div class="form-group">
      <label>Chức vụ mới</label>
      <input class="form-control" id="cpChucVuMoi" placeholder="Tên chức vụ mới">
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
      <div class="form-group">
        <label>Lương cơ bản mới</label>
        <input class="form-control" type="number" id="cpLuong" min="0">
      </div>
      <div class="form-group">
        <label>Hệ số lương mới</label>
        <input class="form-control" type="number" id="cpHeSo" step="0.1" min="0.1">
      </div>
    </div>
    <div class="form-group">
      <label>Ngày hiệu lực</label>
      <input class="form-control" type="date" id="cpNgayHL" value="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group">
      <label>Ghi chú</label>
      <input class="form-control" id="cpGhiChu" placeholder="Lý do thay đổi...">
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('modalChangePOS')">Hủy</button>
      <button class="btn btn-primary" id="btnSavePOS"><i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi</button>
    </div>
  </div>
</div>

<script>
const fmt = n => Number(n).toLocaleString('vi-VN');
let employees = [];

async function loadEmployees() {
  const status = document.getElementById('filterStatus').value;
  const r = await fetch('/api/hr-manager/employees/list.php');
  const d = await r.json();
  employees = d.nhanVien || [];
  renderTable(employees.filter(e => !status || e.trangThai === status));
}

function renderTable(rows) {
  const tbody = document.getElementById('empTbody');
  if (!rows.length) { tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#999">Không có dữ liệu</td></tr>'; return; }
  tbody.innerHTML = rows.map((e,i) => `<tr>
    <td>${i+1}</td>
    <td><strong>${e.hoVaTen}</strong><br><small style="color:#888">${e.email||''}</small></td>
    <td>${e.chucVu}</td>
    <td>${fmt(e.luongCoBan)}</td>
    <td>${e.heSoLuong}</td>
    <td>${e.ngayVaoLam}</td>
    <td><span class="${e.trangThai==='Dang lam'?'badge-active':'badge-inactive'}">${e.trangThai==='Dang lam'?'Đang làm':'Đã nghỉ'}</span></td>
    <td style="white-space:nowrap">
      ${e.trangThai==='Dang lam'?`
        <button class="btn btn-outline btn-sm" onclick="openChangePOS(${e.maNhanVien},'${e.chucVu}',${e.luongCoBan},${e.heSoLuong})" title="Đổi chức vụ"><i class="fa-solid fa-pen"></i></button>
        <button class="btn btn-danger btn-sm" onclick="deactivate(${e.maNhanVien},'${e.hoVaTen}')" title="Cho nghỉ việc"><i class="fa-solid fa-user-slash"></i></button>
      `:'<span style="color:#999; font-size:.8rem">—</span>'}
    </td>
  </tr>`).join('');
}

document.getElementById('filterStatus').addEventListener('change', () => {
  const status = document.getElementById('filterStatus').value;
  renderTable(employees.filter(e => !status || e.trangThai === status));
});

// Add employee
document.getElementById('btnAddEmp').addEventListener('click', async () => {
  const r = await fetch('/api/hr-manager/employees/accounts.php');
  const d = await r.json();
  const sel = document.getElementById('addAccount');
  sel.innerHTML = '<option value="">Chọn tài khoản...</option>' +
    (d.accounts||[]).map(a => `<option value="${a.maNguoiDung}">${a.hoVaTen} (${a.tenTaiKhoan})</option>`).join('');
  openModal('modalAdd');
});

document.getElementById('btnSaveAdd').addEventListener('click', async () => {
  const payload = {
    maNguoiDung:   parseInt(document.getElementById('addAccount').value),
    chucVu:        document.getElementById('addChucVu').value.trim(),
    luongCoBan:    parseFloat(document.getElementById('addLuong').value),
    heSoLuong:     parseFloat(document.getElementById('addHeSo').value),
    phuCapCoDinh:  parseFloat(document.getElementById('addPhuCap').value),
    ngayVaoLam:    document.getElementById('addNgayVao').value,
  };
  if (!payload.maNguoiDung || !payload.chucVu || !payload.luongCoBan) { hrToast('Vui lòng điền đầy đủ thông tin', 'error'); return; }
  const r = await fetch('/api/hr-manager/employees/add.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)});
  const d = await r.json();
  if (d.success) { hrToast('Thêm nhân viên thành công!'); closeModal('modalAdd'); loadEmployees(); }
  else hrToast(d.error || 'Lỗi', 'error');
});

// Change position
function openChangePOS(id, chucVu, luong, heSo) {
  document.getElementById('cpMaNhanVien').value = id;
  document.getElementById('cpCurrentInfo').innerHTML = `Hiện tại: <strong>${chucVu}</strong> | Lương CB: <strong>${fmt(luong)} đ</strong> | Hệ số: <strong>${heSo}</strong>`;
  document.getElementById('cpChucVuMoi').value = chucVu;
  document.getElementById('cpLuong').value = luong;
  document.getElementById('cpHeSo').value = heSo;
  openModal('modalChangePOS');
}

document.getElementById('btnSavePOS').addEventListener('click', async () => {
  const payload = {
    maNhanVien:    parseInt(document.getElementById('cpMaNhanVien').value),
    chucVuMoi:     document.getElementById('cpChucVuMoi').value.trim(),
    luongCoBanMoi: parseFloat(document.getElementById('cpLuong').value),
    heSoLuongMoi:  parseFloat(document.getElementById('cpHeSo').value),
    ngayHieuLuc:   document.getElementById('cpNgayHL').value,
    ghiChu:        document.getElementById('cpGhiChu').value.trim(),
  };
  if (!payload.chucVuMoi) { hrToast('Chức vụ không được trống', 'error'); return; }
  const r = await fetch('/api/hr-manager/employees/change_position.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)});
  const d = await r.json();
  if (d.success) { hrToast('Cập nhật chức vụ thành công!'); closeModal('modalChangePOS'); loadEmployees(); }
  else hrToast(d.error || 'Lỗi', 'error');
});

// Deactivate
async function deactivate(id, name) {
  if (!confirm(`Cho nhân viên "${name}" nghỉ việc?`)) return;
  const r = await fetch('/api/hr-manager/employees/deactivate.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({maNhanVien:id})});
  const d = await r.json();
  if (d.success) { hrToast('Đã cập nhật trạng thái nghỉ việc'); loadEmployees(); }
  else hrToast(d.error || 'Lỗi', 'error');
}

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); }));

loadEmployees();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
