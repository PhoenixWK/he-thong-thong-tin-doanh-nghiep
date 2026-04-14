<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Đơn xin nghỉ'; $activeMenu = 'leave';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-file-medical"></i> Đơn xin nghỉ</h1>

<!-- Tabs -->
<div class="tabs no-print">
  <button class="tab-btn active" data-tab="list">Danh sách đơn</button>
  <button class="tab-btn" data-tab="new">Nộp đơn mới</button>
</div>

<!-- Tab: Danh sách -->
<div id="tab-list">
  <div class="card">
    <div class="card-header"><h2>Lịch sử đơn xin nghỉ</h2></div>
    <div id="leaveListArea">
      <p style="color:var(--muted)"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</p>
    </div>
  </div>
</div>

<!-- Tab: Nộp đơn -->
<div id="tab-new" style="display:none">
  <div class="card" style="max-width:540px">
    <div class="card-header"><h2>Nộp đơn xin nghỉ</h2></div>
    <div class="alert alert-info">
      <i class="fa-solid fa-circle-info"></i> Đơn sẽ được gửi cho quản lý duyệt. Vui lòng điền đầy đủ thông tin.
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Loại nghỉ <span style="color:var(--danger)">*</span></label>
        <select class="form-control" id="loaiNghi">
          <option value="">-- Chọn loại nghỉ --</option>
          <option value="Nghi phep">Nghỉ phép</option>
          <option value="Nghi om dau">Nghỉ ốm đau</option>
          <option value="Nghi thai san">Nghỉ thai sản</option>
          <option value="Nghi viec">Nghỉ việc</option>
        </select>
      </div>
      <div class="form-group">
        <label>Ngày bắt đầu <span style="color:var(--danger)">*</span></label>
        <input class="form-control" type="date" id="ngayBatDau">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Ngày kết thúc <span style="color:var(--muted);font-weight:400">(tuỳ chọn)</span></label>
        <input class="form-control" type="date" id="ngayKetThuc">
      </div>
      <div></div>
    </div>
    <div class="form-row single">
      <div class="form-group">
        <label>Lý do <span style="color:var(--danger)">*</span></label>
        <textarea class="form-control" id="lyDo" placeholder="Nhập lý do xin nghỉ..."></textarea>
      </div>
    </div>
    <button class="btn btn-success" id="submitLeaveBtn">
      <i class="fa-solid fa-paper-plane"></i> Nộp đơn
    </button>
  </div>
</div>

<script>
// Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const tab = btn.dataset.tab;
    document.getElementById('tab-list').style.display = tab === 'list' ? 'block' : 'none';
    document.getElementById('tab-new').style.display  = tab === 'new'  ? 'block' : 'none';
  });
});

const loaiLabel   = { 'Nghi phep': 'Nghỉ phép', 'Nghi om dau': 'Nghỉ ốm đau', 'Nghi thai san': 'Nghỉ thai sản', 'Nghi viec': 'Nghỉ việc' };
const statusClass = { 'Cho duyet': 'warning', 'Da duyet': 'success', 'Tu choi': 'danger' };
const statusLabel = { 'Cho duyet': 'Chờ duyệt', 'Da duyet': 'Đã duyệt', 'Tu choi': 'Từ chối' };

async function loadLeaves() {
  const r = await fetch('/api/hr/leave/list.php');
  const data = await r.json();
  const area = document.getElementById('leaveListArea');
  if (!Array.isArray(data) || data.length === 0) {
    area.innerHTML = '<p style="color:var(--muted);padding:16px">Chưa có đơn nào.</p>';
    return;
  }
  let rows = '';
  data.forEach(d => {
    rows += `<tr>
      <td>${d.maDon}</td>
      <td>${loaiLabel[d.loaiNghi] || d.loaiNghi}</td>
      <td>${d.ngayBatDau}</td>
      <td>${d.ngayKetThuc || '—'}</td>
      <td>${d.lyDo.length > 40 ? d.lyDo.slice(0,40)+'…' : d.lyDo}</td>
      <td><span class="badge badge-${statusClass[d.trangThai]||'info'}">${statusLabel[d.trangThai]||d.trangThai}</span></td>
      <td style="font-size:.8rem;color:var(--muted)">${d.ngayNop ? d.ngayNop.slice(0,10) : ''}</td>
    </tr>`;
  });
  area.innerHTML = `<table class="hr-table">
    <thead><tr><th>ID</th><th>Loại nghỉ</th><th>Từ ngày</th><th>Đến ngày</th><th>Lý do</th><th>Trạng thái</th><th>Ngày nộp</th></tr></thead>
    <tbody>${rows}</tbody>
  </table>`;
}

document.getElementById('submitLeaveBtn').addEventListener('click', async () => {
  const loaiNghi   = document.getElementById('loaiNghi').value;
  const ngayBatDau = document.getElementById('ngayBatDau').value;
  const ngayKetThuc= document.getElementById('ngayKetThuc').value;
  const lyDo       = document.getElementById('lyDo').value.trim();

  if (!loaiNghi)   { hrToast('Vui lòng chọn loại nghỉ', 'error'); return; }
  if (!ngayBatDau) { hrToast('Vui lòng chọn ngày bắt đầu', 'error'); return; }
  if (!lyDo)       { hrToast('Vui lòng nhập lý do', 'error'); return; }

  const btn = document.getElementById('submitLeaveBtn');
  btn.disabled = true;
  try {
    const r = await fetch('/api/hr/leave/create.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ loaiNghi, ngayBatDau, ngayKetThuc: ngayKetThuc || null, lyDo })
    });
    const data = await r.json();
    if (data.success) {
      hrToast('Nộp đơn thành công! Chờ quản lý duyệt.', 'success');
      document.getElementById('loaiNghi').value = '';
      document.getElementById('ngayBatDau').value = '';
      document.getElementById('ngayKetThuc').value = '';
      document.getElementById('lyDo').value = '';
      // Switch to list tab and reload
      document.querySelector('[data-tab="list"]').click();
      loadLeaves();
    } else {
      hrToast(data.error || 'Có lỗi xảy ra', 'error');
    }
  } catch(e) { hrToast('Lỗi kết nối', 'error'); }
  btn.disabled = false;
});

loadLeaves();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
