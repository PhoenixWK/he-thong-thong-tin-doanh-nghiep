<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Duyệt đơn nghỉ'; $activeMenu = 'leaves';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-calendar-check"></i> Duyệt đơn xin nghỉ</h1>

<div class="card">
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap">
    <label style="font-size:.83rem; font-weight:600">Trạng thái:</label>
    <select class="form-control" id="filterLeave" style="width:160px">
      <option value="Cho duyet" selected>Chờ duyệt</option>
      <option value="Da duyet">Đã duyệt</option>
      <option value="Tu choi">Từ chối</option>
      <option value="">Tất cả</option>
    </select>
    <button class="btn btn-primary" id="btnLoadLeaves"><i class="fa-solid fa-rotate"></i> Tải</button>
  </div>
</div>

<div class="card">
  <table class="hr-table">
    <thead>
      <tr><th>Nhân viên</th><th>Chức vụ</th><th>Loại nghỉ</th><th>Từ ngày</th><th>Đến ngày</th><th>Lý do</th><th>Ngày nộp</th><th>Trạng thái</th><th>Thao tác</th></tr>
    </thead>
    <tbody id="leavesTbody"><tr><td colspan="9" style="text-align:center;color:#999">Đang tải...</td></tr></tbody>
  </table>
</div>

<!-- Modal duyệt -->
<div class="modal-overlay" id="modalApprove">
  <div class="modal-box">
    <h3 id="approveTitle"></h3>
    <input type="hidden" id="approveMaDon">
    <div class="alert alert-info" id="approveInfo" style="font-size:.85rem; margin-bottom:16px"></div>
    <div class="form-group">
      <label>Ghi chú duyệt</label>
      <input class="form-control" id="approveGhiChu" placeholder="Ghi chú...">
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('modalApprove')">Hủy</button>
      <button class="btn btn-danger" id="btnReject"><i class="fa-solid fa-xmark"></i> Từ chối</button>
      <button class="btn btn-success" id="btnApprove"><i class="fa-solid fa-check"></i> Duyệt</button>
    </div>
  </div>
</div>

<script>
const LEAVE_LABELS = {'Nghi phep':'Nghỉ phép','Nghi om dau':'Nghỉ ốm đau','Nghi thai san':'Nghỉ thai sản','Nghi viec':'Nghỉ việc'};
const STATUS_BADGE = {'Cho duyet':'badge-pending','Da duyet':'badge-approved','Tu choi':'badge-rejected'};
const STATUS_LABEL = {'Cho duyet':'Chờ duyệt','Da duyet':'Đã duyệt','Tu choi':'Từ chối'};

async function loadLeaves() {
  const status = document.getElementById('filterLeave').value;
  const r = await fetch(`/api/hr-manager/leaves/list.php?trangThai=${encodeURIComponent(status)}`);
  const d = await r.json();
  const rows = d.donNghi || [];
  const tbody = document.getElementById('leavesTbody');
  if (!rows.length) { tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#999">Không có đơn nào</td></tr>'; return; }
  tbody.innerHTML = rows.map(l => `<tr>
    <td><strong>${l.hoVaTen}</strong></td>
    <td>${l.chucVu}</td>
    <td><span style="font-weight:600">${LEAVE_LABELS[l.loaiNghi]||l.loaiNghi}</span></td>
    <td>${l.ngayBatDau}</td>
    <td>${l.ngayKetThuc}</td>
    <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap" title="${l.lyDo||''}">${l.lyDo||'—'}</td>
    <td>${l.ngayNop}</td>
    <td><span class="${STATUS_BADGE[l.trangThai]||''}">${STATUS_LABEL[l.trangThai]||l.trangThai}</span></td>
    <td>${l.trangThai==='Cho duyet'
      ? `<button class="btn btn-primary btn-sm" onclick="openApprove(${l.maDon},'${l.hoVaTen}','${LEAVE_LABELS[l.loaiNghi]||l.loaiNghi}','${l.ngayBatDau}','${l.ngayKetThuc}')">
          <i class="fa-solid fa-pen-to-square"></i> Xử lý
        </button>`
      : `<small style="color:#999">${l.ghiChuDuyet||''}</small>`
    }</td>
  </tr>`).join('');
}

function openApprove(maDon, name, loai, from, to) {
  document.getElementById('approveMaDon').value = maDon;
  document.getElementById('approveTitle').innerHTML = `<i class="fa-solid fa-calendar-check"></i> Xử lý đơn xin nghỉ`;
  document.getElementById('approveInfo').innerHTML = `Nhân viên: <strong>${name}</strong><br>Loại: <strong>${loai}</strong><br>Từ <strong>${from}</strong> đến <strong>${to}</strong>`;
  document.getElementById('approveGhiChu').value = '';
  openModal('modalApprove');
}

async function sendDecision(trangThai) {
  const maDon  = parseInt(document.getElementById('approveMaDon').value);
  const ghiChu = document.getElementById('approveGhiChu').value.trim();
  const r = await fetch('/api/hr-manager/leaves/approve.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ maDon, trangThai, ghiChu })
  });
  const d = await r.json();
  if (d.success) {
    hrToast(trangThai === 'Da duyet' ? 'Đã duyệt đơn nghỉ' : 'Đã từ chối đơn nghỉ',
            trangThai === 'Da duyet' ? 'success' : 'error');
    closeModal('modalApprove');
    loadLeaves();
  } else hrToast(d.error || 'Lỗi', 'error');
}

document.getElementById('btnApprove').addEventListener('click', () => sendDecision('Da duyet'));
document.getElementById('btnReject' ).addEventListener('click', () => sendDecision('Tu choi'));
document.getElementById('btnLoadLeaves').addEventListener('click', loadLeaves);

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if (e.target===m) m.classList.remove('open'); }));

loadLeaves();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
