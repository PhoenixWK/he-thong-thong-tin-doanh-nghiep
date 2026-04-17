/**
 * HR Manager Module — admin panel inline views
 * Renders full HR manager UI inside the admin #main-content div.
 * Exports: HTML templates + init functions for employees, salary, leaves, stats
 */

// ── Private helpers ────────────────────────────────────────────────────────────
const _fmt = n => Number(n).toLocaleString('vi-VN');

function _toast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  if (!toast) return;
  const el = document.createElement('div');
  el.className = `toast toast--${type}`;
  el.textContent = msg;
  toast.appendChild(el);
  setTimeout(() => el.remove(), 3000);
}

function _open(id)  { document.getElementById(id)?.classList.add('open');    }
function _close(id) { document.getElementById(id)?.classList.remove('open'); }

// Expose close to inline onclick handlers
window.hrmCloseModal    = _close;
window.hrmOpenChangePOS = () => {};  // overridden in initHRMEmployees
window.hrmDeactivate    = () => {};
window.hrmOpenCalcModal = () => {};
window.hrmOpenApprove   = () => {};

function _bindOverlayClose() {
  document.querySelectorAll('.hrm-modal-overlay').forEach(m =>
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); })
  );
}

function _today() { return new Date().toISOString().split('T')[0]; }

function _monthOptions(selId) {
  const cur = new Date().getMonth() + 1;
  return [...Array(12)].map((_, i) => {
    const m = i + 1;
    return `<option value="${m}"${m === cur ? ' selected' : ''}>Tháng ${m}</option>`;
  }).join('');
}

function _yearOptions() {
  const cur = new Date().getFullYear();
  return [0, 1, 2, 3].map(i => {
    const y = cur - i;
    return `<option value="${y}"${i === 0 ? ' selected' : ''}>${y}</option>`;
  }).join('');
}

// ── HTML Templates ─────────────────────────────────────────────────────────────

export const HRM_EMPLOYEES_HTML = `
<div class="hrm-wrapper">
  <div class="hrm-page-header">
    <h1 class="hrm-title"><i class="fa-solid fa-users"></i> Quản lý Nhân sự</h1>
  </div>

  <div class="hrm-card hrm-toolbar">
    <button class="hrm-btn hrm-btn-primary" id="hrm-btnAddEmp">
      <i class="fa-solid fa-user-plus"></i> Thêm nhân viên
    </button>
    <div class="hrm-filter-group">
      <label>Trạng thái:</label>
      <select class="hrm-input" id="hrm-filterStatus" style="width:150px">
        <option value="">Tất cả</option>
        <option value="Dang lam" selected>Đang làm</option>
        <option value="Da nghi">Đã nghỉ</option>
      </select>
    </div>
  </div>

  <div class="hrm-card">
    <div class="hrm-card-header"><h2>Danh sách nhân viên</h2></div>
    <div class="hrm-table-wrap">
      <table class="hrm-table" id="hrm-empTable">
        <thead><tr>
          <th>#</th><th>Họ và tên</th><th>Chức vụ</th>
          <th>Lương CB</th><th>Hệ số</th><th>Ngày vào</th>
          <th>Trạng thái</th><th>Thao tác</th>
        </tr></thead>
        <tbody id="hrm-empTbody">
          <tr><td colspan="8" class="hrm-empty"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal: Thêm nhân viên -->
  <div class="hrm-modal-overlay" id="hrm-modalAdd">
    <div class="hrm-modal-box">
      <div class="hrm-modal-head"><h3><i class="fa-solid fa-user-plus"></i> Thêm nhân viên mới</h3></div>
      <div class="hrm-form-group">
        <label>Tài khoản</label>
        <select class="hrm-input" id="hrm-addAccount"><option value="">Chọn tài khoản...</option></select>
      </div>
      <div class="hrm-form-group">
        <label>Chức vụ</label>
        <input class="hrm-input" id="hrm-addChucVu" placeholder="Nhân viên bán hàng, Kế toán...">
      </div>
      <div class="hrm-form-row">
        <div class="hrm-form-group">
          <label>Lương cơ bản (VNĐ)</label>
          <input class="hrm-input" type="number" id="hrm-addLuong" value="5000000" min="0">
        </div>
        <div class="hrm-form-group">
          <label>Hệ số lương</label>
          <input class="hrm-input" type="number" id="hrm-addHeSo" value="1.0" step="0.1" min="0.1">
        </div>
      </div>
      <div class="hrm-form-row">
        <div class="hrm-form-group">
          <label>Phụ cấp cố định</label>
          <input class="hrm-input" type="number" id="hrm-addPhuCap" value="0" min="0">
        </div>
        <div class="hrm-form-group">
          <label>Ngày vào làm</label>
          <input class="hrm-input" type="date" id="hrm-addNgayVao">
        </div>
      </div>
      <div class="hrm-modal-footer">
        <button class="hrm-btn hrm-btn-outline" onclick="hrmCloseModal('hrm-modalAdd')">Hủy</button>
        <button class="hrm-btn hrm-btn-primary" id="hrm-btnSaveAdd">
          <i class="fa-solid fa-floppy-disk"></i> Lưu
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Đổi chức vụ -->
  <div class="hrm-modal-overlay" id="hrm-modalChangePOS">
    <div class="hrm-modal-box">
      <div class="hrm-modal-head"><h3><i class="fa-solid fa-arrow-up-right-dots"></i> Thay đổi chức vụ</h3></div>
      <input type="hidden" id="hrm-cpMaNhanVien">
      <div class="hrm-alert hrm-alert-info" id="hrm-cpCurrentInfo"></div>
      <div class="hrm-form-group">
        <label>Chức vụ mới</label>
        <input class="hrm-input" id="hrm-cpChucVuMoi" placeholder="Tên chức vụ mới">
      </div>
      <div class="hrm-form-row">
        <div class="hrm-form-group">
          <label>Lương cơ bản mới</label>
          <input class="hrm-input" type="number" id="hrm-cpLuong" min="0">
        </div>
        <div class="hrm-form-group">
          <label>Hệ số lương mới</label>
          <input class="hrm-input" type="number" id="hrm-cpHeSo" step="0.1" min="0.1">
        </div>
      </div>
      <div class="hrm-form-row">
        <div class="hrm-form-group">
          <label>Ngày hiệu lực</label>
          <input class="hrm-input" type="date" id="hrm-cpNgayHL">
        </div>
        <div class="hrm-form-group">
          <label>Ghi chú</label>
          <input class="hrm-input" id="hrm-cpGhiChu" placeholder="Lý do thay đổi...">
        </div>
      </div>
      <div class="hrm-modal-footer">
        <button class="hrm-btn hrm-btn-outline" onclick="hrmCloseModal('hrm-modalChangePOS')">Hủy</button>
        <button class="hrm-btn hrm-btn-primary" id="hrm-btnSavePOS">
          <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
        </button>
      </div>
    </div>
  </div>
</div>`;

// ─────────────────────────────────────────────────────────────────────────────

export const HRM_SALARY_HTML = `
<div class="hrm-wrapper">
  <div class="hrm-page-header">
    <h1 class="hrm-title"><i class="fa-solid fa-calculator"></i> Tính lương nhân viên</h1>
  </div>

  <div class="hrm-card hrm-toolbar">
    <div class="hrm-filter-group">
      <label>Tháng</label>
      <select class="hrm-input" id="hrm-selThang" style="width:110px">${_monthOptions()}</select>
    </div>
    <div class="hrm-filter-group">
      <label>Năm</label>
      <select class="hrm-input" id="hrm-selNam" style="width:100px">${_yearOptions()}</select>
    </div>
    <button class="hrm-btn hrm-btn-primary" id="hrm-btnLoadCalc">
      <i class="fa-solid fa-list"></i> Xem danh sách
    </button>
  </div>

  <div class="hrm-card" id="hrm-calcCard" style="display:none">
    <div class="hrm-card-header"><h2 id="hrm-calcTitle"></h2></div>
    <div class="hrm-table-wrap">
      <table class="hrm-table">
        <thead><tr>
          <th>Nhân viên</th><th>Chức vụ</th><th>Lương CB</th><th>Hệ số</th>
          <th>Ngày làm</th><th>Phụ cấp</th><th>Thưởng</th><th>Khấu trừ</th>
          <th>Thực lĩnh</th><th>Thao tác</th>
        </tr></thead>
        <tbody id="hrm-calcTbody"></tbody>
      </table>
    </div>
  </div>

  <!-- Modal tính lương -->
  <div class="hrm-modal-overlay" id="hrm-modalCalc">
    <div class="hrm-modal-box">
      <div class="hrm-modal-head"><h3 id="hrm-modalCalcTitle"><i class="fa-solid fa-calculator"></i> Tính lương</h3></div>
      <input type="hidden" id="hrm-calcMaNhanVien">
      <div class="hrm-alert hrm-alert-info" id="hrm-calcInfo"></div>
      <div class="hrm-form-row">
        <div class="hrm-form-group">
          <label>Số ngày làm việc</label>
          <input class="hrm-input" type="number" id="hrm-calcNgayLam" value="22" min="0" max="31">
        </div>
        <div class="hrm-form-group">
          <label>Nghỉ phép (ngày)</label>
          <input class="hrm-input" type="number" id="hrm-calcNgayNghi" value="0" min="0">
        </div>
        <div class="hrm-form-group">
          <label>Thưởng (VNĐ)</label>
          <input class="hrm-input" type="number" id="hrm-calcThuong" value="0" min="0">
        </div>
        <div class="hrm-form-group">
          <label>Khấu trừ (VNĐ)</label>
          <input class="hrm-input" type="number" id="hrm-calcKhauTru" value="0" min="0">
        </div>
      </div>
      <div class="hrm-form-group">
        <label>Ghi chú</label>
        <input class="hrm-input" id="hrm-calcGhiChu" placeholder="Ghi chú...">
      </div>
      <div class="hrm-alert hrm-alert-info" style="font-size:.83rem">
        <i class="fa-solid fa-info-circle"></i> <strong>Công thức:</strong>
        Lương thực lĩnh = Lương CB × Hệ số × (Ngày làm / 22) + Phụ cấp + Thưởng − Khấu trừ
      </div>
      <div class="hrm-modal-footer">
        <button class="hrm-btn hrm-btn-outline" onclick="hrmCloseModal('hrm-modalCalc')">Hủy</button>
        <button class="hrm-btn hrm-btn-primary" id="hrm-btnDoCalc">
          <i class="fa-solid fa-floppy-disk"></i> Tính & Lưu
        </button>
      </div>
    </div>
  </div>
</div>`;

// ─────────────────────────────────────────────────────────────────────────────

export const HRM_LEAVES_HTML = `
<div class="hrm-wrapper">
  <div class="hrm-page-header">
    <h1 class="hrm-title"><i class="fa-solid fa-calendar-check"></i> Duyệt đơn xin nghỉ</h1>
  </div>

  <div class="hrm-card hrm-toolbar">
    <div class="hrm-filter-group">
      <label>Trạng thái:</label>
      <select class="hrm-input" id="hrm-filterLeave" style="width:160px">
        <option value="Cho duyet" selected>Chờ duyệt</option>
        <option value="Da duyet">Đã duyệt</option>
        <option value="Tu choi">Từ chối</option>
        <option value="">Tất cả</option>
      </select>
    </div>
    <button class="hrm-btn hrm-btn-primary" id="hrm-btnLoadLeaves">
      <i class="fa-solid fa-rotate"></i> Tải lại
    </button>
  </div>

  <div class="hrm-card">
    <div class="hrm-table-wrap">
      <table class="hrm-table">
        <thead><tr>
          <th>Nhân viên</th><th>Chức vụ</th><th>Loại nghỉ</th>
          <th>Từ ngày</th><th>Đến ngày</th><th>Lý do</th>
          <th>Ngày nộp</th><th>Trạng thái</th><th>Thao tác</th>
        </tr></thead>
        <tbody id="hrm-leavesTbody">
          <tr><td colspan="9" class="hrm-empty"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal duyệt -->
  <div class="hrm-modal-overlay" id="hrm-modalApprove">
    <div class="hrm-modal-box">
      <div class="hrm-modal-head"><h3 id="hrm-approveTitle"></h3></div>
      <input type="hidden" id="hrm-approveMaDon">
      <div class="hrm-alert hrm-alert-info" id="hrm-approveInfo"></div>
      <div class="hrm-form-group">
        <label>Ghi chú duyệt</label>
        <input class="hrm-input" id="hrm-approveGhiChu" placeholder="Ghi chú (tùy chọn)...">
      </div>
      <div class="hrm-modal-footer">
        <button class="hrm-btn hrm-btn-outline" onclick="hrmCloseModal('hrm-modalApprove')">Hủy</button>
        <button class="hrm-btn hrm-btn-danger" id="hrm-btnReject">
          <i class="fa-solid fa-xmark"></i> Từ chối
        </button>
        <button class="hrm-btn hrm-btn-success" id="hrm-btnApprove">
          <i class="fa-solid fa-check"></i> Duyệt
        </button>
      </div>
    </div>
  </div>
</div>`;

// ─────────────────────────────────────────────────────────────────────────────

export const HRM_STATS_HTML = `
<div class="hrm-wrapper">
  <div class="hrm-page-header">
    <h1 class="hrm-title"><i class="fa-solid fa-chart-bar"></i> Thống kê lương & thưởng</h1>
  </div>

  <div class="hrm-card hrm-toolbar">
    <div class="hrm-filter-group">
      <label>Loại</label>
      <select class="hrm-input" id="hrm-statsType" style="width:140px">
        <option value="monthly">Theo tháng</option>
        <option value="yearly" selected>Theo năm</option>
      </select>
    </div>
    <div class="hrm-filter-group" id="hrm-statsMonthGroup" style="display:none">
      <label>Tháng</label>
      <select class="hrm-input" id="hrm-statsThang" style="width:110px">${_monthOptions()}</select>
    </div>
    <div class="hrm-filter-group">
      <label>Năm</label>
      <select class="hrm-input" id="hrm-statsNam" style="width:100px">${_yearOptions()}</select>
    </div>
    <button class="hrm-btn hrm-btn-primary" id="hrm-btnLoadStats">
      <i class="fa-solid fa-chart-bar"></i> Xem thống kê
    </button>
    <button class="hrm-btn hrm-btn-outline" id="hrm-btnExportPdf" style="display:none">
      <i class="fa-solid fa-file-pdf"></i> Xuất PDF
    </button>
  </div>

  <!-- Summary cards -->
  <div class="hrm-stats-grid" id="hrm-summaryCards" style="display:none"></div>

  <div class="hrm-card" id="hrm-statsCard" style="display:none">
    <div class="hrm-card-header"><h2 id="hrm-statsTitle"></h2></div>
    <div class="hrm-table-wrap">
      <table class="hrm-table" id="hrm-statsTable">
        <thead id="hrm-statsThead"></thead>
        <tbody id="hrm-statsTbody"></tbody>
        <tfoot id="hrm-statsTfoot"></tfoot>
      </table>
    </div>
  </div>
</div>`;

// ── Init Functions ─────────────────────────────────────────────────────────────

export function initHRMEmployees() {
  let employees = [];
  const todayInput = document.getElementById('hrm-addNgayVao');
  if (todayInput) todayInput.value = _today();
  const cpNgayHL = document.getElementById('hrm-cpNgayHL');
  if (cpNgayHL) cpNgayHL.value = _today();

  async function loadEmployees() {
    const status = document.getElementById('hrm-filterStatus').value;
    try {
      const r = await fetch('/api/hr-manager/employees/list.php');
      const d = await r.json();
      employees = d.nhanVien || [];
      renderTable(employees.filter(e => !status || e.trangThai === status));
    } catch (e) { _toast('Không thể tải danh sách nhân viên', 'error'); }
  }

  function renderTable(rows) {
    const tbody = document.getElementById('hrm-empTbody');
    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="8" class="hrm-empty">Không có dữ liệu</td></tr>';
      return;
    }
    tbody.innerHTML = rows.map((e, i) => `<tr>
      <td>${i + 1}</td>
      <td><strong>${e.hoVaTen}</strong><br><small style="color:#888">${e.email || ''}</small></td>
      <td>${e.chucVu}</td>
      <td>${_fmt(e.luongCoBan)}</td>
      <td>${e.heSoLuong}</td>
      <td>${e.ngayVaoLam}</td>
      <td><span class="hrm-badge ${e.trangThai === 'Dang lam' ? 'hrm-badge-active' : 'hrm-badge-inactive'}">
        ${e.trangThai === 'Dang lam' ? 'Đang làm' : 'Đã nghỉ'}
      </span></td>
      <td class="hrm-actions">
        ${e.trangThai === 'Dang lam' ? `
          <button class="hrm-btn hrm-btn-outline hrm-btn-sm"
            onclick="hrmOpenChangePOS(${e.maNhanVien},'${e.chucVu.replace(/'/g,"\\'")}',${e.luongCoBan},${e.heSoLuong})"
            title="Đổi chức vụ"><i class="fa-solid fa-pen"></i></button>
          <button class="hrm-btn hrm-btn-danger hrm-btn-sm"
            onclick="hrmDeactivate(${e.maNhanVien},'${e.hoVaTen.replace(/'/g,"\\'")}')"
            title="Cho nghỉ việc"><i class="fa-solid fa-user-slash"></i></button>
        ` : '<span style="color:#999;font-size:.8rem">—</span>'}
      </td>
    </tr>`).join('');
  }

  document.getElementById('hrm-filterStatus').addEventListener('change', () => {
    const status = document.getElementById('hrm-filterStatus').value;
    renderTable(employees.filter(e => !status || e.trangThai === status));
  });

  document.getElementById('hrm-btnAddEmp').addEventListener('click', async () => {
    const r = await fetch('/api/hr-manager/employees/accounts.php');
    const d = await r.json();
    const sel = document.getElementById('hrm-addAccount');
    sel.innerHTML = '<option value="">Chọn tài khoản...</option>' +
      (d.accounts || []).map(a => `<option value="${a.maNguoiDung}">${a.hoVaTen} (${a.tenTaiKhoan})</option>`).join('');
    _open('hrm-modalAdd');
  });

  document.getElementById('hrm-btnSaveAdd').addEventListener('click', async () => {
    const payload = {
      maNguoiDung:  parseInt(document.getElementById('hrm-addAccount').value),
      chucVu:       document.getElementById('hrm-addChucVu').value.trim(),
      luongCoBan:   parseFloat(document.getElementById('hrm-addLuong').value),
      heSoLuong:    parseFloat(document.getElementById('hrm-addHeSo').value),
      phuCapCoDinh: parseFloat(document.getElementById('hrm-addPhuCap').value),
      ngayVaoLam:   document.getElementById('hrm-addNgayVao').value,
    };
    if (!payload.maNguoiDung || !payload.chucVu || !payload.luongCoBan) {
      _toast('Vui lòng điền đầy đủ thông tin', 'error'); return;
    }
    const r = await fetch('/api/hr-manager/employees/add.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
    });
    const d = await r.json();
    if (d.success) { _toast('Thêm nhân viên thành công!'); _close('hrm-modalAdd'); loadEmployees(); }
    else _toast(d.error || 'Lỗi', 'error');
  });

  window.hrmOpenChangePOS = (id, chucVu, luong, heSo) => {
    document.getElementById('hrm-cpMaNhanVien').value = id;
    document.getElementById('hrm-cpCurrentInfo').innerHTML =
      `Hiện tại: <strong>${chucVu}</strong> | Lương CB: <strong>${_fmt(luong)} đ</strong> | Hệ số: <strong>${heSo}</strong>`;
    document.getElementById('hrm-cpChucVuMoi').value = chucVu;
    document.getElementById('hrm-cpLuong').value = luong;
    document.getElementById('hrm-cpHeSo').value = heSo;
    _open('hrm-modalChangePOS');
  };

  document.getElementById('hrm-btnSavePOS').addEventListener('click', async () => {
    const payload = {
      maNhanVien:    parseInt(document.getElementById('hrm-cpMaNhanVien').value),
      chucVuMoi:     document.getElementById('hrm-cpChucVuMoi').value.trim(),
      luongCoBanMoi: parseFloat(document.getElementById('hrm-cpLuong').value),
      heSoLuongMoi:  parseFloat(document.getElementById('hrm-cpHeSo').value),
      ngayHieuLuc:   document.getElementById('hrm-cpNgayHL').value,
      ghiChu:        document.getElementById('hrm-cpGhiChu').value.trim(),
    };
    if (!payload.chucVuMoi) { _toast('Chức vụ không được trống', 'error'); return; }
    const r = await fetch('/api/hr-manager/employees/change_position.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
    });
    const d = await r.json();
    if (d.success) { _toast('Cập nhật chức vụ thành công!'); _close('hrm-modalChangePOS'); loadEmployees(); }
    else _toast(d.error || 'Lỗi', 'error');
  });

  window.hrmDeactivate = async (id, name) => {
    if (!confirm(`Cho nhân viên "${name}" nghỉ việc?`)) return;
    const r = await fetch('/api/hr-manager/employees/deactivate.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ maNhanVien: id })
    });
    const d = await r.json();
    if (d.success) { _toast('Đã cập nhật trạng thái nghỉ việc'); loadEmployees(); }
    else _toast(d.error || 'Lỗi', 'error');
  };

  _bindOverlayClose();
  loadEmployees();
}

// ─────────────────────────────────────────────────────────────────────────────

export function initHRMSalary() {
  let currentThang, currentNam;

  async function loadCalc() {
    const thang = parseInt(document.getElementById('hrm-selThang').value);
    const nam   = parseInt(document.getElementById('hrm-selNam').value);
    currentThang = thang; currentNam = nam;
    document.getElementById('hrm-calcTitle').textContent = `Bảng lương Tháng ${thang} năm ${nam}`;

    const [r1, r2] = await Promise.all([
      fetch('/api/hr-manager/employees/list.php'),
      fetch(`/api/hr-manager/stats/summary.php?thang=${thang}&nam=${nam}`)
    ]);
    const d1 = await r1.json();
    const d2 = await r2.json();

    const allEmployees = (d1.nhanVien || []).filter(e => e.trangThai === 'Dang lam');
    const salaryMap = {};
    (d2.rows || []).forEach(s => { salaryMap[s.hoVaTen] = s; });

    const tbody = document.getElementById('hrm-calcTbody');
    tbody.innerHTML = allEmployees.map(e => {
      const s = salaryMap[e.hoVaTen];
      return `<tr>
        <td><strong>${e.hoVaTen}</strong></td>
        <td>${e.chucVu}</td>
        <td>${_fmt(e.luongCoBan)}</td>
        <td>${e.heSoLuong}</td>
        <td>${s ? s.soNgayLam : '—'}</td>
        <td>${_fmt(e.phuCapCoDinh)}</td>
        <td>${s ? _fmt(s.thuong) : '—'}</td>
        <td>${s ? _fmt(s.khauTru) : '—'}</td>
        <td><strong style="color:#1a3c6e">${s ? _fmt(s.thucLinh) : '<span style="color:#999">Chưa tính</span>'}</strong></td>
        <td>
          <button class="hrm-btn hrm-btn-primary hrm-btn-sm"
            onclick="hrmOpenCalcModal(${e.maNhanVien},'${e.hoVaTen.replace(/'/g,"\\'")}',${e.luongCoBan},${e.heSoLuong},${e.phuCapCoDinh})">
            <i class="fa-solid fa-calculator"></i> ${s ? 'Cập nhật' : 'Tính'}
          </button>
        </td>
      </tr>`;
    }).join('') || '<tr><td colspan="10" class="hrm-empty">Không có nhân viên đang làm việc</td></tr>';

    document.getElementById('hrm-calcCard').style.display = 'block';
  }

  window.hrmOpenCalcModal = (id, name, luong, heSo, phuCap) => {
    document.getElementById('hrm-calcMaNhanVien').value = id;
    document.getElementById('hrm-modalCalcTitle').innerHTML =
      `<i class="fa-solid fa-calculator"></i> Tính lương: ${name}`;
    document.getElementById('hrm-calcInfo').innerHTML =
      `Lương CB: <strong>${_fmt(luong)}đ</strong> × Hệ số: <strong>${heSo}</strong> + Phụ cấp: <strong>${_fmt(phuCap)}đ</strong>`;
    _open('hrm-modalCalc');
  };

  document.getElementById('hrm-btnLoadCalc').addEventListener('click', loadCalc);

  document.getElementById('hrm-btnDoCalc').addEventListener('click', async () => {
    const payload = {
      maNhanVien:     parseInt(document.getElementById('hrm-calcMaNhanVien').value),
      thang:          currentThang,
      nam:            currentNam,
      soNgayLam:      parseFloat(document.getElementById('hrm-calcNgayLam').value),
      soNgayNghiPhep: parseFloat(document.getElementById('hrm-calcNgayNghi').value),
      thuong:         parseFloat(document.getElementById('hrm-calcThuong').value),
      khauTru:        parseFloat(document.getElementById('hrm-calcKhauTru').value),
      ghiChu:         document.getElementById('hrm-calcGhiChu').value.trim(),
    };
    const r = await fetch('/api/hr-manager/salary/calculate.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload)
    });
    const d = await r.json();
    if (d.success) {
      _toast(`Đã tính lương: ${_fmt(d.thucLinh)} đồng`);
      _close('hrm-modalCalc');
      loadCalc();
    } else _toast(d.error || 'Lỗi', 'error');
  });

  _bindOverlayClose();
  loadCalc();
}

// ─────────────────────────────────────────────────────────────────────────────

export function initHRMLeaves() {
  const LEAVE_LABELS = {
    'Nghi phep': 'Nghỉ phép', 'Nghi om dau': 'Nghỉ ốm đau',
    'Nghi thai san': 'Nghỉ thai sản', 'Nghi viec': 'Nghỉ việc'
  };
  const STATUS_BADGE  = { 'Cho duyet': 'hrm-badge-pending', 'Da duyet': 'hrm-badge-approved', 'Tu choi': 'hrm-badge-rejected' };
  const STATUS_LABEL  = { 'Cho duyet': 'Chờ duyệt', 'Da duyet': 'Đã duyệt', 'Tu choi': 'Từ chối' };

  async function loadLeaves() {
    const status = document.getElementById('hrm-filterLeave').value;
    const r = await fetch(`/api/hr-manager/leaves/list.php?trangThai=${encodeURIComponent(status)}`);
    const d = await r.json();
    const rows = d.donNghi || [];
    const tbody = document.getElementById('hrm-leavesTbody');
    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="9" class="hrm-empty">Không có đơn nào</td></tr>';
      return;
    }
    tbody.innerHTML = rows.map(l => `<tr>
      <td><strong>${l.hoVaTen}</strong></td>
      <td>${l.chucVu}</td>
      <td><span style="font-weight:600">${LEAVE_LABELS[l.loaiNghi] || l.loaiNghi}</span></td>
      <td>${l.ngayBatDau}</td>
      <td>${l.ngayKetThuc}</td>
      <td class="hrm-td-ellipsis" title="${l.lyDo || ''}">${l.lyDo || '—'}</td>
      <td>${l.ngayNop}</td>
      <td><span class="hrm-badge ${STATUS_BADGE[l.trangThai] || ''}">${STATUS_LABEL[l.trangThai] || l.trangThai}</span></td>
      <td>
        ${l.trangThai === 'Cho duyet'
          ? `<button class="hrm-btn hrm-btn-primary hrm-btn-sm"
              onclick="hrmOpenApprove(${l.maDon},'${l.hoVaTen.replace(/'/g,"\\'")}','${LEAVE_LABELS[l.loaiNghi] || l.loaiNghi}','${l.ngayBatDau}','${l.ngayKetThuc}')">
              <i class="fa-solid fa-pen-to-square"></i> Xử lý
            </button>`
          : `<small style="color:#999">${l.ghiChuDuyet || ''}</small>`
        }
      </td>
    </tr>`).join('');
  }

  window.hrmOpenApprove = (maDon, name, loai, from, to) => {
    document.getElementById('hrm-approveMaDon').value = maDon;
    document.getElementById('hrm-approveTitle').innerHTML =
      `<i class="fa-solid fa-calendar-check"></i> Xử lý đơn xin nghỉ`;
    document.getElementById('hrm-approveInfo').innerHTML =
      `Nhân viên: <strong>${name}</strong><br>Loại: <strong>${loai}</strong><br>Từ <strong>${from}</strong> đến <strong>${to}</strong>`;
    document.getElementById('hrm-approveGhiChu').value = '';
    _open('hrm-modalApprove');
  };

  async function sendDecision(trangThai) {
    const maDon  = parseInt(document.getElementById('hrm-approveMaDon').value);
    const ghiChu = document.getElementById('hrm-approveGhiChu').value.trim();
    const r = await fetch('/api/hr-manager/leaves/approve.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ maDon, trangThai, ghiChu })
    });
    const d = await r.json();
    if (d.success) {
      _toast(trangThai === 'Da duyet' ? 'Đã duyệt đơn nghỉ' : 'Đã từ chối đơn nghỉ',
             trangThai === 'Da duyet' ? 'success' : 'error');
      _close('hrm-modalApprove');
      loadLeaves();
    } else _toast(d.error || 'Lỗi', 'error');
  }

  document.getElementById('hrm-btnApprove').addEventListener('click', () => sendDecision('Da duyet'));
  document.getElementById('hrm-btnReject').addEventListener('click',  () => sendDecision('Tu choi'));
  document.getElementById('hrm-btnLoadLeaves').addEventListener('click', loadLeaves);
  _bindOverlayClose();
  loadLeaves();
}

// ─────────────────────────────────────────────────────────────────────────────

export function initHRMStats() {
  const HEADER_COLOR = [26, 60, 110];
  let statsData = null;

  document.getElementById('hrm-statsType').addEventListener('change', () => {
    document.getElementById('hrm-statsMonthGroup').style.display =
      document.getElementById('hrm-statsType').value === 'monthly' ? '' : 'none';
  });

  async function loadStats() {
    const type  = document.getElementById('hrm-statsType').value;
    const nam   = document.getElementById('hrm-statsNam').value;
    const thang = document.getElementById('hrm-statsThang').value;
    let url = `/api/hr-manager/stats/summary.php?nam=${nam}`;
    if (type === 'monthly') url += `&thang=${thang}`;

    const r = await fetch(url);
    const d = await r.json();
    statsData = d;

    const cards = document.getElementById('hrm-summaryCards');
    const statsCard = document.getElementById('hrm-statsCard');

    if (d.type === 'yearly') {
      document.getElementById('hrm-statsTitle').textContent = `Thống kê lương năm ${d.nam}`;
      document.getElementById('hrm-statsThead').innerHTML = `<tr>
        <th>#</th><th>Nhân viên</th><th>Chức vụ</th>
        <th>Số tháng có BL</th><th>Tổng thưởng (VNĐ)</th><th>Tổng thực lĩnh (VNĐ)</th>
      </tr>`;
      document.getElementById('hrm-statsTbody').innerHTML = (d.rows || []).map((row, i) => `<tr>
        <td>${i + 1}</td>
        <td><strong>${row.hoVaTen}</strong></td>
        <td>${row.chucVu}</td>
        <td>${row.soThangCoBangLuong}</td>
        <td>${_fmt(row.tongThuong)}</td>
        <td><strong style="color:#1a3c6e">${_fmt(row.tongThucLinh)}</strong></td>
      </tr>`).join('') || '<tr><td colspan="6" class="hrm-empty">Chưa có dữ liệu</td></tr>';
      document.getElementById('hrm-statsTfoot').innerHTML = `<tr class="hrm-tfoot-total">
        <td colspan="5"><strong>Tổng chi lương cả năm</strong></td>
        <td><strong style="font-size:1rem">${_fmt(d.total)}</strong></td>
      </tr>`;

      cards.innerHTML = `
        <div class="hrm-stat-card"><div class="hrm-stat-value">${(d.rows || []).length}</div><div class="hrm-stat-label">Nhân viên có bảng lương</div></div>
        <div class="hrm-stat-card"><div class="hrm-stat-value" style="font-size:1.2rem">${_fmt(d.total)} đ</div><div class="hrm-stat-label">Tổng chi lương năm ${d.nam}</div></div>
        <div class="hrm-stat-card accent"><div class="hrm-stat-value" style="font-size:1.2rem">${_fmt((d.rows || []).reduce((s, row) => s + parseFloat(row.tongThuong || 0), 0))} đ</div><div class="hrm-stat-label">Tổng thưởng năm ${d.nam}</div></div>
      `;
    } else {
      const mo = `Tháng ${d.thang}/${d.nam}`;
      document.getElementById('hrm-statsTitle').textContent = `Thống kê lương ${mo}`;
      document.getElementById('hrm-statsThead').innerHTML = `<tr>
        <th>#</th><th>Nhân viên</th><th>Chức vụ</th>
        <th>Ngày làm</th><th>Lương CB</th><th>Hệ số</th>
        <th>Phụ cấp</th><th>Thưởng</th><th>Khấu trừ</th><th>Thực lĩnh (VNĐ)</th>
      </tr>`;
      document.getElementById('hrm-statsTbody').innerHTML = (d.rows || []).map((row, i) => `<tr>
        <td>${i + 1}</td>
        <td><strong>${row.hoVaTen}</strong></td>
        <td>${row.chucVu}</td>
        <td>${row.soNgayLam}</td>
        <td>${_fmt(row.luongCoBan)}</td>
        <td>${row.heSoLuong}</td>
        <td>${_fmt(row.phuCap)}</td>
        <td>${_fmt(row.thuong)}</td>
        <td>${_fmt(row.khauTru)}</td>
        <td><strong style="color:#1a3c6e">${_fmt(row.thucLinh)}</strong></td>
      </tr>`).join('') || '<tr><td colspan="10" class="hrm-empty">Chưa có dữ liệu</td></tr>';
      document.getElementById('hrm-statsTfoot').innerHTML = `<tr class="hrm-tfoot-total">
        <td colspan="9"><strong>Tổng thực lĩnh</strong></td>
        <td><strong>${_fmt(d.total)}</strong></td>
      </tr>`;

      cards.innerHTML = `
        <div class="hrm-stat-card"><div class="hrm-stat-value">${(d.rows || []).length}</div><div class="hrm-stat-label">Nhân viên tháng ${d.thang}/${d.nam}</div></div>
        <div class="hrm-stat-card"><div class="hrm-stat-value" style="font-size:1.2rem">${_fmt(d.total)} đ</div><div class="hrm-stat-label">Tổng chi lương tháng ${d.thang}</div></div>
      `;
    }

    cards.style.display = 'grid';
    statsCard.style.display = 'block';
    document.getElementById('hrm-btnExportPdf').style.display = 'inline-flex';
  }

  // PDF Export
  document.getElementById('hrm-btnExportPdf').addEventListener('click', () => {
    if (!statsData || !window.jspdf) return;
    const { jsPDF } = window.jspdf;
    const isYearly = statsData.type === 'yearly';
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(15);
    doc.setTextColor(...HEADER_COLOR);
    const title = isYearly
      ? `THONG KE LUONG NAM ${statsData.nam}`
      : `THONG KE LUONG THANG ${statsData.thang}/${statsData.nam}`;
    doc.text(title, doc.internal.pageSize.getWidth() / 2, 16, { align: 'center' });

    let head, body;
    if (isYearly) {
      head = [['#', 'Ho va ten', 'Chuc vu', 'So thang', 'Tong thuong (VND)', 'Tong thuc linh (VND)']];
      body = (statsData.rows || []).map((row, i) => [i + 1, row.hoVaTen, row.chucVu, row.soThangCoBangLuong, _fmt(row.tongThuong), _fmt(row.tongThucLinh)]);
      body.push(['', 'Tong', '', '', '', _fmt(statsData.total)]);
    } else {
      head = [['#', 'Ho va ten', 'Chuc vu', 'Ngay lam', 'Luong CB', 'He so', 'Phu cap', 'Thuong', 'Khau tru', 'Thuc linh']];
      body = (statsData.rows || []).map((row, i) => [i + 1, row.hoVaTen, row.chucVu, row.soNgayLam, _fmt(row.luongCoBan), row.heSoLuong, _fmt(row.phuCap), _fmt(row.thuong), _fmt(row.khauTru), _fmt(row.thucLinh)]);
      body.push(['', 'Tong', '', '', '', '', '', '', '', _fmt(statsData.total)]);
    }

    doc.autoTable({
      startY: 22, head, body,
      styles: { fontSize: 8, cellPadding: 3 },
      headStyles: { fillColor: HEADER_COLOR, textColor: 255, fontStyle: 'bold' },
      didParseCell(data) {
        if (data.row.index === body.length - 1) {
          data.cell.styles.fillColor = [240, 244, 255];
          data.cell.styles.fontStyle = 'bold';
        }
      }
    });
    doc.save(`thong-ke-luong-${isYearly ? statsData.nam : statsData.thang + '-' + statsData.nam}.pdf`);
    _toast('Đã xuất PDF!');
  });

  document.getElementById('hrm-btnLoadStats').addEventListener('click', loadStats);
  loadStats();
}
