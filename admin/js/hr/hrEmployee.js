/**
 * HR Employee Module — admin panel inline views
 * Exports: HR_PROFILE_HTML, HR_LEAVE_HTML, HR_SALARY_HTML  (HTML templates)
 *          initProfile(), initLeave(), initSalary()         (init functions)
 */

// ── Private helpers ───────────────────────────────────────────────────────────
function _yearOptions() {
  const cur = new Date().getFullYear();
  let o = '';
  for (let y = cur; y >= cur - 3; y--)
    o += `<option value="${y}"${y === cur ? ' selected' : ''}>${y}</option>`;
  return o;
}

function adminToast(msg, type = 'success') {
  if (typeof showToast === 'function') { showToast(msg, type); return; }
  const toast = document.getElementById('toast');
  if (!toast) return;
  const el = document.createElement('div');
  el.className = `toast toast--${type}`;
  el.textContent = msg;
  toast.appendChild(el);
  setTimeout(() => el.remove(), 3000);
}

// ── HTML Templates (exported for use in mainContentMap) ───────────────────────
export const HR_PROFILE_HTML = `
  <h1 class="main__title"><i class="fa-solid fa-user-pen"></i> Thông tin cá nhân</h1>

  <div class="hr-card" id="viewMode">
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
      <div style="width:52px;height:52px;border-radius:50%;background:#1a3c6e;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <i class="fa-solid fa-user" style="color:#fff;font-size:1.3rem"></i>
      </div>
      <div style="flex:1">
        <div style="font-size:1.15rem;font-weight:700;margin-bottom:2px" id="v_hoVaTen">—</div>
        <div style="color:#666;font-size:.88rem" id="v_chucVu">—</div>
      </div>
      <button class="main__icon-btn" id="editBtn" style="display:none;width:auto;padding:0 14px">
        <i class="fa-solid fa-pen"></i> Chỉnh sửa
      </button>
    </div>
    <div style="border-top:1px solid #eee">
      <div class="hr-info-row"><span class="hr-info-label"><i class="fa-solid fa-calendar-check"></i> Ngày vào làm</span><span id="v_ngayVaoLam">—</span></div>
      <div class="hr-info-row"><span class="hr-info-label"><i class="fa-solid fa-phone"></i> Số điện thoại</span><span id="v_soDT">—</span></div>
      <div class="hr-info-row"><span class="hr-info-label"><i class="fa-solid fa-envelope"></i> Email</span><span id="v_email">—</span></div>
    </div>
  </div>

  <div class="hr-card" id="editMode" style="display:none">
    <h3 class="hr-card__title"><i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div>
        <label class="hr-label">Họ và tên <span style="color:red">*</span></label>
        <input class="hr-input" type="text" id="e_hoVaTen" placeholder="Nhập họ và tên">
      </div>
      <div>
        <label class="hr-label">Số điện thoại</label>
        <input class="hr-input" type="tel" id="e_soDT" placeholder="Nhập số điện thoại">
      </div>
    </div>
    <div style="margin-bottom:20px;max-width:340px">
      <label class="hr-label">Email</label>
      <input class="hr-input" type="email" id="e_email" placeholder="Nhập email">
    </div>
    <p style="font-size:.8rem;color:#9ca3af;margin-bottom:18px">
      <i class="fa-solid fa-lock"></i> Chức vụ và ngày vào làm chỉ quản lý mới sửa được.
    </p>
    <div style="display:flex;gap:8px">
      <button class="main__add-btn" id="saveBtn" style="width:auto;padding:0 18px">
        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
      </button>
      <button class="main__filter-btn" id="cancelBtn" style="width:auto;padding:0 14px">Huỷ</button>
    </div>
  </div>
`;

export const HR_LEAVE_HTML = `
  <h1 class="main__title"><i class="fa-solid fa-file-medical"></i> Đơn xin nghỉ phép</h1>
  <div class="main__row">
    <button class="main__filter-btn hr-tab-btn active" data-tab="list" style="width:auto;padding:0 16px">
      <i class="fa-solid fa-list"></i> Danh sách đơn
    </button>
    <button class="main__add-btn hr-tab-btn" data-tab="new" style="width:auto;padding:0 16px">
      <i class="fa-solid fa-plus"></i> Nộp đơn mới
    </button>
  </div>

  <div id="hr-tab-list">
    <div class="main__data" style="min-height:200px">
      <div id="leaveListArea">
        <p style="color:#888;padding:16px"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</p>
      </div>
    </div>
  </div>

  <div id="hr-tab-new" style="display:none">
    <div class="hr-form-card">
      <h2 style="text-align:center;font-size:1rem;font-weight:700;color:#1a3c6e;border-bottom:2px solid #1a3c6e;padding-bottom:12px;margin-bottom:22px">
        Nộp đơn xin nghỉ
      </h2>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label class="hr-label">Loại nghỉ <span style="color:red">*</span></label>
          <select id="hr_loaiNghi" class="hr-select">
            <option value="">-- Chọn loại nghỉ --</option>
            <option value="Nghi phep">Nghỉ phép</option>
            <option value="Nghi om dau">Nghỉ ốm đau</option>
            <option value="Nghi thai san">Nghỉ thai sản</option>
            <option value="Nghi viec">Nghỉ việc</option>
          </select>
        </div>
        <div>
          <label class="hr-label">Ngày bắt đầu <span style="color:red">*</span></label>
          <input type="date" id="hr_ngayBatDau" class="hr-input">
        </div>
      </div>
      <div style="margin-bottom:16px;max-width:260px">
        <label class="hr-label">Ngày kết thúc <span style="font-weight:400;color:#9ca3af">(tuỳ chọn)</span></label>
        <input type="date" id="hr_ngayKetThuc" class="hr-input">
      </div>
      <div style="margin-bottom:22px">
        <label class="hr-label">Lý do <span style="color:red">*</span></label>
        <textarea id="hr_lyDo" rows="4" placeholder="Nhập lý do xin nghỉ..." class="hr-input" style="resize:vertical"></textarea>
      </div>
      <div style="text-align:center">
        <button class="main__add-btn" id="submitLeaveBtn" style="width:auto;padding:0 32px;height:36px">
          <i class="fa-solid fa-paper-plane"></i> Nộp đơn
        </button>
      </div>
    </div>
  </div>
`;

export const HR_SALARY_HTML = `
  <h1 class="main__title"><i class="fa-solid fa-money-bill-wave"></i> Bảng lương</h1>

  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:12px 0 0 30px">
    <label style="font-size:.9rem;font-weight:600;color:#444">Năm:</label>
    <select id="hr_namSlt" class="hr-select" style="width:100px">${_yearOptions()}</select>
    <button class="main__filter-btn" id="loadSalaryBtn" style="width:auto;padding:0 16px">
      <i class="fa-solid fa-eye"></i> Xem bảng lương
    </button>
    <button class="main__add-btn" id="exportYearPdfBtn" style="display:none;width:auto;padding:0 14px">
      <i class="fa-solid fa-file-pdf"></i> Xuất PDF năm
    </button>
  </div>

  <div id="hr_formulaCard" style="display:none;margin:10px 30px 0;padding:12px 18px;background:#eaf4fd;border:1.5px solid #1a6fa3;border-radius:8px;max-width:calc(100% - 60px);font-size:.88rem;color:#1a4070">
    <i class="fa-solid fa-calculator" style="color:#1a6fa3;margin-right:6px"></i>
    <strong>Công thức:</strong> <span id="hr_formulaText"></span>
  </div>

  <div id="hr_salaryYearCard" style="display:none" class="main__data">
    <h2 class="main__title" id="hr_yearCardTitle" style="margin-bottom:12px;font-size:1rem"></h2>
    <table class="main__table">
      <thead><tr>
        <th>Tháng</th><th>Ngày làm</th><th>Lương CB (VNĐ)</th><th>Hệ số</th>
        <th>Phụ cấp (VNĐ)</th><th>Thưởng (VNĐ)</th><th>Khấu trừ (VNĐ)</th>
        <th>Thực lĩnh (VNĐ)</th><th style="width:56px">Chi tiết</th>
      </tr></thead>
      <tbody id="hr_salaryYearTbody"></tbody>
      <tfoot id="hr_salaryYearTfoot"></tfoot>
    </table>
  </div>

  <div id="hr_monthDetail" style="display:none" class="main__data">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
      <h2 class="main__title" id="hr_monthDetailTitle" style="margin:0;font-size:1rem"></h2>
      <div style="display:flex;gap:8px">
        <button class="main__print-btn" id="exportMonthPdfBtn" style="width:auto;padding:0 14px">
          <i class="fa-solid fa-file-pdf"></i> Xuất PDF
        </button>
        <button class="main__filter-btn" id="hr_closeMonthDetail" style="width:auto;padding:0 12px">
          <i class="fa-solid fa-xmark"></i> Đóng
        </button>
      </div>
    </div>
    <table class="main__table" style="max-width:560px">
      <tbody id="hr_monthDetailTbody"></tbody>
    </table>
  </div>
`;

// ── Thông tin cá nhân ──────────────────────────────────────────────────────────
export function initProfile() {
  let profile = null;

  async function loadProfile() {
    const r = await fetch('/api/hr/employee/profile.php');
    if (!r.ok) { adminToast('Không thể tải thông tin cá nhân', 'error'); return; }
    profile = await r.json();
    document.getElementById('v_hoVaTen').textContent    = profile.hoVaTen    || '—';
    document.getElementById('v_chucVu').textContent     = profile.chucVu     || '—';
    document.getElementById('v_ngayVaoLam').textContent = profile.ngayVaoLam
      ? new Date(profile.ngayVaoLam).toLocaleDateString('vi-VN') : '—';
    document.getElementById('v_soDT').textContent  = profile.soDT   || '—';
    document.getElementById('v_email').textContent = profile.email  || '—';
    document.getElementById('editBtn').style.display = 'inline-flex';
  }

  document.getElementById('editBtn').addEventListener('click', () => {
    document.getElementById('viewMode').style.display = 'none';
    document.getElementById('editMode').style.display = 'block';
    document.getElementById('e_hoVaTen').value = profile?.hoVaTen || '';
    document.getElementById('e_soDT').value    = profile?.soDT    || '';
    document.getElementById('e_email').value   = profile?.email   || '';
  });

  document.getElementById('cancelBtn').addEventListener('click', () => {
    document.getElementById('editMode').style.display = 'none';
    document.getElementById('viewMode').style.display = 'block';
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
          email:   document.getElementById('e_email').value.trim(),
        }),
      });
      const res = await r.json();
      if (res.success) {
        adminToast('Cập nhật thành công!', 'success');
        await loadProfile();
        document.getElementById('cancelBtn').click();
      } else {
        adminToast(res.error || 'Có lỗi xảy ra', 'error');
      }
    } catch (e) { adminToast('Lỗi kết nối', 'error'); }
    btn.disabled = false;
  });

  loadProfile();
}

// ── Đơn xin nghỉ phép ─────────────────────────────────────────────────────────
export function initLeave() {
  const loaiLabel   = { 'Nghi phep': 'Nghỉ phép', 'Nghi om dau': 'Nghỉ ốm đau', 'Nghi thai san': 'Nghỉ thai sản', 'Nghi viec': 'Nghỉ việc' };
  const statusClass = { 'Cho duyet': 'warning', 'Da duyet': 'success', 'Tu choi': 'danger' };
  const statusLabel = { 'Cho duyet': 'Chờ duyệt', 'Da duyet': 'Đã duyệt', 'Tu choi': 'Từ chối' };

  // Tabs
  document.querySelectorAll('.hr-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.hr-tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const tab = btn.dataset.tab;
      document.getElementById('hr-tab-list').style.display = tab === 'list' ? 'block' : 'none';
      document.getElementById('hr-tab-new').style.display  = tab === 'new'  ? 'block' : 'none';
    });
  });

  async function loadLeaves() {
    const r = await fetch('/api/hr/leave/list.php');
    if (!r.ok) { adminToast('Không thể tải danh sách đơn', 'error'); return; }
    const data = await r.json();
    const area = document.getElementById('leaveListArea');
    if (!Array.isArray(data) || data.length === 0) {
      area.innerHTML = '<p style="color:#888;padding:16px">Chưa có đơn nào.</p>';
      return;
    }
    let rows = '';
    data.forEach(d => {
      rows += `<tr>
        <td>${d.maDon}</td>
        <td>${loaiLabel[d.loaiNghi] || d.loaiNghi}</td>
        <td>${d.ngayBatDau}</td>
        <td>${d.ngayKetThuc || '—'}</td>
        <td>${d.lyDo.length > 40 ? d.lyDo.slice(0, 40) + '…' : d.lyDo}</td>
        <td><span class="badge badge-${statusClass[d.trangThai] || 'info'}">${statusLabel[d.trangThai] || d.trangThai}</span></td>
        <td style="font-size:.8rem;color:#888">${d.ngayNop ? d.ngayNop.slice(0, 10) : ''}</td>
      </tr>`;
    });
    area.innerHTML = `<table class="main__table">
      <thead><tr><th>ID</th><th>Loại nghỉ</th><th>Từ ngày</th><th>Đến ngày</th><th>Lý do</th><th>Trạng thái</th><th>Ngày nộp</th></tr></thead>
      <tbody>${rows}</tbody>
    </table>`;
  }

  document.getElementById('submitLeaveBtn').addEventListener('click', async () => {
    const loaiNghi    = document.getElementById('hr_loaiNghi').value;
    const ngayBatDau  = document.getElementById('hr_ngayBatDau').value;
    const ngayKetThuc = document.getElementById('hr_ngayKetThuc').value;
    const lyDo        = document.getElementById('hr_lyDo').value.trim();

    if (!loaiNghi)   { adminToast('Vui lòng chọn loại nghỉ', 'error');       return; }
    if (!ngayBatDau) { adminToast('Vui lòng chọn ngày bắt đầu', 'error');    return; }
    if (!lyDo)       { adminToast('Vui lòng nhập lý do', 'error');           return; }

    const btn = document.getElementById('submitLeaveBtn');
    btn.disabled = true;
    try {
      const r = await fetch('/api/hr/leave/create.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ loaiNghi, ngayBatDau, ngayKetThuc: ngayKetThuc || null, lyDo }),
      });
      const res = await r.json();
      if (res.success) {
        adminToast('Nộp đơn thành công! Chờ quản lý duyệt.', 'success');
        document.getElementById('hr_loaiNghi').value   = '';
        document.getElementById('hr_ngayBatDau').value = '';
        document.getElementById('hr_ngayKetThuc').value= '';
        document.getElementById('hr_lyDo').value       = '';
        document.querySelector('.hr-tab-btn[data-tab="list"]').click();
        loadLeaves();
      } else {
        adminToast(res.error || 'Có lỗi xảy ra', 'error');
      }
    } catch (e) { adminToast('Lỗi kết nối', 'error'); }
    btn.disabled = false;
  });

  loadLeaves();
}

// ── Bảng lương ────────────────────────────────────────────────────────────────
export function initSalary() {
  const fmt    = n => Number(n).toLocaleString('vi-VN');
  const months = ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6',
                  'Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];
  let currentYear      = new Date().getFullYear();
  let formulaText      = '';
  let salaryData       = [];
  let currentMonthData = null;

  document.getElementById('loadSalaryBtn').addEventListener('click', loadSalary);

  async function loadSalary() {
    const nam = document.getElementById('hr_namSlt').value;
    currentYear = nam;
    const r = await fetch(`/api/hr/salary/list.php?nam=${nam}`);
    if (!r.ok) { adminToast('Không thể tải bảng lương', 'error'); return; }
    const data = await r.json();
    if (data.error) { adminToast(data.error, 'error'); return; }

    formulaText = data.formula?.moTa || '';
    salaryData  = data.bangLuong || [];

    document.getElementById('hr_formulaCard').style.display   = 'block';
    document.getElementById('hr_formulaText').textContent     = formulaText;
    document.getElementById('hr_salaryYearCard').style.display = 'block';
    document.getElementById('hr_yearCardTitle').textContent   = `Bảng lương năm ${nam}`;
    document.getElementById('exportYearPdfBtn').style.display = 'inline-flex';

    const map = {};
    salaryData.forEach(s => { map[s.thang] = s; });

    const tbody = document.getElementById('hr_salaryYearTbody');
    const tfoot = document.getElementById('hr_salaryYearTfoot');
    tbody.innerHTML = '';
    let totalThucLinh = 0;

    for (let m = 1; m <= 12; m++) {
      const s = map[m];
      if (!s) {
        tbody.innerHTML += `<tr><td>${months[m-1]}</td><td colspan="7" style="color:#888;font-style:italic">Chưa có dữ liệu</td><td>—</td></tr>`;
      } else {
        totalThucLinh += parseFloat(s.thucLinh);
        tbody.innerHTML += `<tr>
          <td><strong>${months[m-1]}</strong></td>
          <td>${s.soNgayLam}</td>
          <td>${fmt(s.luongCoBan)}</td>
          <td>${s.heSoLuong}</td>
          <td>${fmt(s.phuCap)}</td>
          <td>${s.thuong > 0 ? '<span style="color:green">+' + fmt(s.thuong) + '</span>' : '0'}</td>
          <td>${s.khauTru > 0 ? '<span style="color:red">-' + fmt(s.khauTru) + '</span>' : '0'}</td>
          <td><strong style="color:#1a3c6e">${fmt(s.thucLinh)}</strong></td>
          <td>
            <button class="main__icon-btn" onclick="window._hrShowMonthDetail(${m},${nam})">
              <i class="fa-solid fa-eye"></i>
            </button>
          </td>
        </tr>`;
      }
    }
    tfoot.innerHTML = `<tr style="background:#f0f4ff">
      <td colspan="7"><strong>Tổng thực lĩnh cả năm</strong></td>
      <td><strong style="color:#1a3c6e">${fmt(totalThucLinh)}</strong></td>
      <td></td>
    </tr>`;
  }

  // expose to inline onclick
  window._hrShowMonthDetail = async function(thang, nam) {
    const r = await fetch(`/api/hr/salary/detail.php?thang=${thang}&nam=${nam}`);
    const d = await r.json();
    if (d.error) { adminToast(d.error, 'error'); return; }
    currentMonthData = { ...d, thang, nam };

    document.getElementById('hr_monthDetailTitle').textContent = `Chi tiết lương ${months[thang-1]} năm ${nam}`;
    document.getElementById('hr_monthDetailTbody').innerHTML = `
      <tr><td style="color:#888;width:220px">Họ và tên</td><td><strong>${d.hoVaTen}</strong></td></tr>
      <tr><td style="color:#888">Chức vụ</td><td>${d.chucVu}</td></tr>
      <tr><td style="color:#888">Email</td><td>${d.email || '—'}</td></tr>
      <tr><td style="color:#888">Tháng/Năm</td><td>${months[thang-1]} / ${nam}</td></tr>
      <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
      <tr><td style="color:#888">Số ngày làm</td><td>${d.soNgayLam} ngày</td></tr>
      <tr><td style="color:#888">Số ngày nghỉ phép</td><td>${d.soNgayNghiPhep} ngày</td></tr>
      <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
      <tr><td style="color:#888">Lương cơ bản</td><td>${fmt(d.luongCoBan)} đ</td></tr>
      <tr><td style="color:#888">Hệ số lương</td><td>${d.heSoLuong}</td></tr>
      <tr><td style="color:#888">Phụ cấp</td><td>${fmt(d.phuCap)} đ</td></tr>
      <tr><td style="color:#888">Thưởng</td><td style="color:green">+ ${fmt(d.thuong)} đ</td></tr>
      <tr><td style="color:#888">Khấu trừ</td><td style="color:red">- ${fmt(d.khauTru)} đ</td></tr>
      <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
      <tr style="background:#e8f4fd">
        <td><strong>Thực lĩnh</strong></td>
        <td><strong style="color:#1a3c6e;font-size:1.1rem">${fmt(d.thucLinh)} đ</strong></td>
      </tr>
      ${d.ghiChu ? `<tr><td style="color:#888">Ghi chú</td><td style="font-style:italic">${d.ghiChu}</td></tr>` : ''}
    `;
    document.getElementById('hr_monthDetail').style.display = 'block';
    document.getElementById('hr_monthDetail').scrollIntoView({ behavior: 'smooth' });
  };

  document.getElementById('hr_closeMonthDetail').addEventListener('click', () => {
    document.getElementById('hr_monthDetail').style.display = 'none';
  });

  // ---- jsPDF export ----
  const HEADER_COLOR = [26, 60, 110];
  const ACCENT_COLOR = [26, 111, 163];

  function addPdfHeader(doc, title, subtitle) {
    doc.setFont('helvetica', 'bold'); doc.setFontSize(16);
    doc.setTextColor(...HEADER_COLOR);
    doc.text(title, doc.internal.pageSize.getWidth() / 2, 18, { align: 'center' });
    if (subtitle) {
      doc.setFont('helvetica', 'normal'); doc.setFontSize(9);
      doc.setTextColor(100, 100, 100);
      doc.text(subtitle, doc.internal.pageSize.getWidth() / 2, 24, { align: 'center' });
    }
    return 32;
  }

  function addFormulaBox(doc, y, text) {
    const pw = doc.internal.pageSize.getWidth() - 20;
    doc.setFillColor(234, 244, 253); doc.setDrawColor(...ACCENT_COLOR); doc.setLineWidth(0.8);
    const lines = doc.splitTextToSize('Cong thuc: ' + text, pw - 16);
    doc.rect(10, y, pw, lines.length * 5 + 8, 'FD');
    doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor(30, 60, 100);
    doc.text(lines, 18, y + 6);
  }

  document.getElementById('exportYearPdfBtn').addEventListener('click', () => {
    const btn = document.getElementById('exportYearPdfBtn');
    btn.disabled = true;
    try {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
      const map = {}; let total = 0;
      salaryData.forEach(s => { map[s.thang] = s; total += parseFloat(s.thucLinh); });

      const y = addPdfHeader(doc, 'BANG LUONG NAM ' + currentYear,
        new Date().toLocaleDateString('vi-VN'));
      const head = [['Thang','Ngay lam','Luong CB','He so','Phu cap','Thuong','Khau tru','Thuc linh']];
      const body = [];
      for (let m = 1; m <= 12; m++) {
        const s = map[m];
        body.push(!s ? ['Thang ' + m,'—','—','—','—','—','—','Chua co']
          : ['Thang '+m, s.soNgayLam, fmt(s.luongCoBan), s.heSoLuong,
             fmt(s.phuCap), s.thuong>0?'+'+fmt(s.thuong):'0',
             s.khauTru>0?'-'+fmt(s.khauTru):'0', fmt(s.thucLinh)]);
      }
      body.push(['Tong thuc linh','','','','','','', fmt(total)]);
      doc.autoTable({ startY: y, head, body,
        styles: { font:'helvetica', fontSize:8, cellPadding:3 },
        headStyles: { fillColor: HEADER_COLOR, textColor:255, fontStyle:'bold' },
        didParseCell(d) { if (d.row.index === body.length-1) { d.cell.styles.fillColor=[240,244,255]; d.cell.styles.fontStyle='bold'; } }
      });
      addFormulaBox(doc, doc.lastAutoTable.finalY + 6, formulaText);
      doc.save('bang-luong-nam-' + currentYear + '.pdf');
      adminToast('Xuất PDF thành công!', 'success');
    } catch(e) { adminToast('Lỗi khi tạo PDF: ' + e.message, 'error'); }
    btn.disabled = false;
  });

  document.getElementById('exportMonthPdfBtn').addEventListener('click', () => {
    if (!currentMonthData) return;
    const btn = document.getElementById('exportMonthPdfBtn');
    btn.disabled = true;
    try {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
      const d = currentMonthData;
      const y = addPdfHeader(doc, 'PHIEU LUONG THANG ' + d.thang + ' NAM ' + d.nam,
        'Ngay xuat: ' + new Date().toLocaleDateString('vi-VN'));
      doc.autoTable({
        startY: y,
        body: [
          ['Ho va ten', d.hoVaTen], ['Chuc vu', d.chucVu],
          ['So dien thoai', d.soDT||'—'], ['Ky luong','Thang '+d.thang+'/'+d.nam],
          ['So ngay lam', d.soNgayLam+' ngay'], ['So ngay nghi phep', d.soNgayNghiPhep+' ngay'],
          ['Luong co ban', fmt(d.luongCoBan)+' d'], ['He so luong', String(d.heSoLuong)],
          ['Phu cap', fmt(d.phuCap)+' d'], ['Thuong','+ '+fmt(d.thuong)+' d'],
          ['Khau tru','- '+fmt(d.khauTru)+' d'], ['THUC LINH', fmt(d.thucLinh)+' dong'],
          ...(d.ghiChu ? [['Ghi chu', d.ghiChu]] : []),
        ],
        columnStyles:{ 0:{cellWidth:60,fontStyle:'bold',fillColor:[245,247,252]}, 1:{cellWidth:100} },
        styles:{ font:'helvetica', fontSize:9, cellPadding:4 },
        didParseCell(h){ if(h.row.raw[0]==='THUC LINH'){ h.cell.styles.fillColor=[232,244,253]; h.cell.styles.fontStyle='bold'; h.cell.styles.fontSize=11; h.cell.styles.textColor=HEADER_COLOR; } }
      });
      addFormulaBox(doc, doc.lastAutoTable.finalY + 6, formulaText);
      doc.save('phieu-luong-thang'+d.thang+'-'+d.nam+'.pdf');
      adminToast('Xuất PDF thành công!', 'success');
    } catch(e) { adminToast('Lỗi khi tạo PDF: ' + e.message, 'error'); }
    btn.disabled = false;
  });

  // Auto-load current year on open
  loadSalary();
}
