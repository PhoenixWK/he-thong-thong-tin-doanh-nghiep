<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Tính lương'; $activeMenu = 'salary';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-calculator"></i> Tính lương nhân viên</h1>

<div class="card">
  <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end">
    <div class="form-group" style="margin:0">
      <label style="font-size:.83rem; font-weight:600; display:block; margin-bottom:6px">Tháng</label>
      <select class="form-control" id="selThang" style="width:110px">
        <?php for($m=1;$m<=12;$m++): ?>
          <option value="<?=$m?>" <?=$m==date('n')?'selected':''?>>Tháng <?=$m?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label style="font-size:.83rem; font-weight:600; display:block; margin-bottom:6px">Năm</label>
      <select class="form-control" id="selNam" style="width:100px">
        <?php for($y=date('Y');$y>=date('Y')-3;$y--): ?>
          <option value="<?=$y?>" <?=$y==date('Y')?'selected':''?>><?=$y?></option>
        <?php endfor; ?>
      </select>
    </div>
    <button class="btn btn-primary" id="btnLoadCalc"><i class="fa-solid fa-list"></i> Xem danh sách</button>
  </div>
</div>

<div class="card" id="calcCard" style="display:none">
  <div class="card-header"><h2 id="calcTitle"></h2></div>
  <table class="hr-table">
    <thead>
      <tr>
        <th>Nhân viên</th><th>Chức vụ</th><th>Lương CB</th><th>Hệ số</th>
        <th>Ngày làm</th><th>Phụ cấp</th><th>Thưởng</th><th>Khấu trừ</th><th>Thực lĩnh</th><th>Tính lương</th>
      </tr>
    </thead>
    <tbody id="calcTbody"></tbody>
  </table>
</div>

<!-- Modal tính lương -->
<div class="modal-overlay" id="modalCalc">
  <div class="modal-box">
    <h3 id="modalCalcTitle"><i class="fa-solid fa-calculator"></i> Tính lương</h3>
    <input type="hidden" id="calcMaNhanVien">
    <div class="alert alert-info" id="calcInfo" style="font-size:.85rem; margin-bottom:16px"></div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
      <div class="form-group">
        <label>Số ngày làm việc</label>
        <input class="form-control" type="number" id="calcNgayLam" value="22" min="0" max="31">
      </div>
      <div class="form-group">
        <label>Nghỉ phép (ngày)</label>
        <input class="form-control" type="number" id="calcNgayNghi" value="0" min="0">
      </div>
      <div class="form-group">
        <label>Thưởng (VNĐ)</label>
        <input class="form-control" type="number" id="calcThuong" value="0" min="0">
      </div>
      <div class="form-group">
        <label>Khấu trừ (VNĐ)</label>
        <input class="form-control" type="number" id="calcKhauTru" value="0" min="0">
      </div>
    </div>
    <div class="form-group">
      <label>Ghi chú</label>
      <input class="form-control" id="calcGhiChu" placeholder="Ghi chú...">
    </div>
    <div class="alert alert-info" style="margin-top:8px; font-size:.83rem">
      <i class="fa-solid fa-info-circle"></i> <strong>Công thức:</strong>
      Lương thực lĩnh = Lương CB × Hệ số × (Ngày làm / 22) + Phụ cấp + Thưởng − Khấu trừ
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('modalCalc')">Hủy</button>
      <button class="btn btn-primary" id="btnDoCalc"><i class="fa-solid fa-floppy-disk"></i> Tính & Lưu</button>
    </div>
  </div>
</div>

<script>
const fmt = n => Number(n).toLocaleString('vi-VN');
let allEmployees = [];
let salaryMap = {};
let currentThang, currentNam;

async function loadCalc() {
  const thang = parseInt(document.getElementById('selThang').value);
  const nam   = parseInt(document.getElementById('selNam').value);
  currentThang = thang; currentNam = nam;
  document.getElementById('calcTitle').textContent = `Bảng lương Tháng ${thang} năm ${nam}`;

  // Load employees
  const r1 = await fetch('/api/hr-manager/employees/list.php');
  const d1 = await r1.json();
  allEmployees = (d1.nhanVien || []).filter(e => e.trangThai === 'Dang lam');

  // Load existing salary for this month
  const r2 = await fetch(`/api/hr-manager/stats/summary.php?thang=${thang}&nam=${nam}`);
  const d2 = await r2.json();
  salaryMap = {};
  (d2.rows || []).forEach(s => { salaryMap[s.hoVaTen] = s; });

  const tbody = document.getElementById('calcTbody');
  tbody.innerHTML = allEmployees.map(e => {
    const s = salaryMap[e.hoVaTen];
    return `<tr>
      <td><strong>${e.hoVaTen}</strong></td>
      <td>${e.chucVu}</td>
      <td>${fmt(e.luongCoBan)}</td>
      <td>${e.heSoLuong}</td>
      <td>${s ? s.soNgayLam : '—'}</td>
      <td>${fmt(e.phuCapCoDinh)}</td>
      <td>${s ? fmt(s.thuong) : '—'}</td>
      <td>${s ? fmt(s.khauTru) : '—'}</td>
      <td><strong style="color:#1a3c6e">${s ? fmt(s.thucLinh) : 'Chưa tính'}</strong></td>
      <td><button class="btn btn-primary btn-sm" onclick="openCalcModal(${e.maNhanVien},'${e.hoVaTen}',${e.luongCoBan},${e.heSoLuong},${e.phuCapCoDinh})">
        <i class="fa-solid fa-calculator"></i> ${s ? 'Cập nhật' : 'Tính'}
      </button></td>
    </tr>`;
  }).join('') || '<tr><td colspan="10" style="text-align:center;color:#999">Không có nhân viên đang làm việc</td></tr>';

  document.getElementById('calcCard').style.display = 'block';
}

function openCalcModal(id, name, luong, heSo, phuCap) {
  document.getElementById('calcMaNhanVien').value = id;
  document.getElementById('modalCalcTitle').innerHTML = `<i class="fa-solid fa-calculator"></i> Tính lương: ${name}`;
  document.getElementById('calcInfo').innerHTML = `Lương CB: <strong>${fmt(luong)}đ</strong> × Hệ số: <strong>${heSo}</strong> + Phụ cấp: <strong>${fmt(phuCap)}đ</strong>`;
  openModal('modalCalc');
}

document.getElementById('btnLoadCalc').addEventListener('click', loadCalc);

document.getElementById('btnDoCalc').addEventListener('click', async () => {
  const payload = {
    maNhanVien:     parseInt(document.getElementById('calcMaNhanVien').value),
    thang:          currentThang,
    nam:            currentNam,
    soNgayLam:      parseFloat(document.getElementById('calcNgayLam').value),
    soNgayNghiPhep: parseFloat(document.getElementById('calcNgayNghi').value),
    thuong:         parseFloat(document.getElementById('calcThuong').value),
    khauTru:        parseFloat(document.getElementById('calcKhauTru').value),
    ghiChu:         document.getElementById('calcGhiChu').value.trim(),
  };
  const r = await fetch('/api/hr-manager/salary/calculate.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)});
  const d = await r.json();
  if (d.success) {
    hrToast(`Đã tính lương: ${fmt(d.thucLinh)} đồng`);
    closeModal('modalCalc');
    loadCalc();
  } else hrToast(d.error || 'Lỗi', 'error');
});

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if (e.target===m) m.classList.remove('open'); }));

loadCalc();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
