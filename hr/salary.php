<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Bảng lương'; $activeMenu = 'salary';
include __DIR__ . '/partials/layout_header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<h1 class="hr-main__title no-print"><i class="fa-solid fa-money-bill-wave"></i> Bảng lương</h1>

<!-- Controls -->
<div class="card no-print">
  <div style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:6px">Năm</label>
      <select class="form-control" id="namSlt" style="width:120px">
        <?php for($y = date('Y'); $y >= date('Y')-3; $y--): ?>
          <option value="<?=$y?>" <?=$y==date('Y')?'selected':''?>><?=$y?></option>
        <?php endfor; ?>
      </select>
    </div>
    <button class="btn btn-primary" id="loadSalaryBtn">
      <i class="fa-solid fa-eye"></i> Xem bảng lương năm
    </button>
    <button class="btn btn-outline" id="exportYearPdfBtn" style="display:none">
      <i class="fa-solid fa-file-pdf"></i> Xuất PDF năm
    </button>
  </div>
</div>

<!-- Formula explanation -->
<div class="card no-print" id="formulaCard" style="display:none">
  <div class="alert alert-info" style="margin:0">
    <strong><i class="fa-solid fa-calculator"></i> Công thức tính lương:</strong><br>
    <span id="formulaText"></span>
  </div>
</div>

<!-- Salary Table - All months of year (for printing) -->
<div class="card" id="salaryYearCard" style="display:none">
  <div class="card-header no-print">
    <h2 id="yearCardTitle"></h2>
  </div>
  <!-- Print header (only shows on print) -->
  <div style="display:none" class="print-only" id="printHeader">
    <h2 style="text-align:center; font-size:1.3rem; margin-bottom:4px">BẢNG LƯƠNG NĂM</h2>
    <p style="text-align:center; color:#555; margin-bottom:16px" id="printSubtitle"></p>
  </div>
  <table class="hr-table">
    <thead>
      <tr>
        <th>Tháng</th>
        <th>Ngày làm</th>
        <th>Lương CB (VNĐ)</th>
        <th>Hệ số</th>
        <th>Phụ cấp (VNĐ)</th>
        <th>Thưởng (VNĐ)</th>
        <th>Khấu trừ (VNĐ)</th>
        <th>Thực lĩnh (VNĐ)</th>
        <th class="no-print">Chi tiết</th>
      </tr>
    </thead>
    <tbody id="salaryYearTbody"></tbody>
    <tfoot id="salaryYearTfoot"></tfoot>
  </table>
</div>

<!-- Monthly detail modal -->
<div id="monthDetail" style="display:none">
  <div class="card" id="monthDetailCard">
    <div class="card-header">
      <h2 id="monthDetailTitle"></h2>
      <div class="no-print">
        <button class="btn btn-success btn-sm" id="exportMonthPdfBtn">
          <i class="fa-solid fa-file-pdf"></i> Xuất PDF tháng
        </button>
        <button class="btn btn-outline btn-sm" onclick="document.getElementById('monthDetail').style.display='none'">
          <i class="fa-solid fa-xmark"></i> Đóng
        </button>
      </div>
    </div>
    <!-- Print header for month -->
    <div style="display:none" class="print-only" id="printMonthHeader">
      <h2 style="text-align:center; font-size:1.3rem; margin-bottom:4px">BẢNG LƯƠNG THÁNG</h2>
      <p style="text-align:center; color:#555; margin-bottom:16px" id="printMonthSubtitle"></p>
    </div>
    <table class="hr-table" style="max-width:600px">
      <tbody id="monthDetailTbody"></tbody>
    </table>
    <div class="alert alert-info no-print" style="margin-top:16px; font-size:.85rem">
      <i class="fa-solid fa-circle-info"></i> <strong>Công thức:</strong> <span class="formula-inline"></span>
    </div>
  </div>
</div>

<style>
@media print {
  .print-only { display: block !important; }
  #monthDetail { display: block !important; }
}
</style>

<script>
const fmt = n => Number(n).toLocaleString('vi-VN');
const months = ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];
const emp = <?= json_encode($hrEmp, JSON_UNESCAPED_UNICODE) ?>;
let currentYear = <?= date('Y') ?>;
let formulaText = '';
let salaryData = [];
let currentMonthData = null;

document.getElementById('loadSalaryBtn').addEventListener('click', loadSalary);

async function loadSalary() {
  const nam = document.getElementById('namSlt').value;
  currentYear = nam;
  const r = await fetch(`/api/hr/salary/list.php?nam=${nam}`);
  const data = await r.json();
  if (data.error) { hrToast(data.error, 'error'); return; }

  formulaText = data.formula?.moTa || '';
  salaryData  = data.bangLuong || [];

  document.getElementById('formulaCard').style.display = 'block';
  document.getElementById('formulaText').textContent    = formulaText;
  document.querySelector('.formula-inline').textContent = formulaText;

  document.getElementById('salaryYearCard').style.display = 'block';
  document.getElementById('yearCardTitle').textContent    = `Bảng lương năm ${nam}`;
  document.getElementById('exportYearPdfBtn').style.display = 'inline-flex';

  // Build a map by month
  const map = {};
  salaryData.forEach(s => { map[s.thang] = s; });

  const tbody = document.getElementById('salaryYearTbody');
  const tfoot = document.getElementById('salaryYearTfoot');
  tbody.innerHTML = '';
  let totalThucLinh = 0;

  for (let m = 1; m <= 12; m++) {
    const s = map[m];
    if (!s) {
      tbody.innerHTML += `<tr><td>${months[m-1]}</td><td colspan="7" style="color:var(--muted);font-style:italic">Chưa có dữ liệu</td><td class="no-print">—</td></tr>`;
    } else {
      totalThucLinh += parseFloat(s.thucLinh);
      tbody.innerHTML += `<tr>
        <td><strong>${months[m-1]}</strong></td>
        <td>${s.soNgayLam}</td>
        <td>${fmt(s.luongCoBan)}</td>
        <td>${s.heSoLuong}</td>
        <td>${fmt(s.phuCap)}</td>
        <td>${s.thuong > 0 ? '<span style="color:var(--accent)">+'+fmt(s.thuong)+'</span>' : '0'}</td>
        <td>${s.khauTru > 0 ? '<span style="color:var(--danger)">-'+fmt(s.khauTru)+'</span>' : '0'}</td>
        <td><strong style="color:var(--primary)">${fmt(s.thucLinh)}</strong></td>
        <td class="no-print">
          <button class="btn btn-outline btn-sm" onclick="showMonthDetail(${m},${nam})">
            <i class="fa-solid fa-eye"></i>
          </button>
        </td>
      </tr>`;
    }
  }

  tfoot.innerHTML = `<tr style="background:#f0f4ff">
    <td colspan="7"><strong>Tổng thực lĩnh cả năm</strong></td>
    <td><strong style="color:var(--primary);font-size:1rem">${fmt(totalThucLinh)}</strong></td>
    <td class="no-print"></td>
  </tr>`;
}

async function showMonthDetail(thang, nam) {
  const r = await fetch(`/api/hr/salary/detail.php?thang=${thang}&nam=${nam}`);
  const d = await r.json();
  if (d.error) { hrToast(d.error, 'error'); return; }

  currentMonthData = { ...d, thang, nam };
  document.getElementById('monthDetailTitle').textContent = `Chi tiết lương ${months[thang-1]} năm ${nam}`;

  const tbody = document.getElementById('monthDetailTbody');
  tbody.innerHTML = `
    <tr><td style="color:var(--muted);width:220px">Họ và tên</td><td><strong>${d.hoVaTen}</strong></td></tr>
    <tr><td style="color:var(--muted)">Chức vụ</td><td>${d.chucVu}</td></tr>
    <tr><td style="color:var(--muted)">Email</td><td>${d.email || '—'}</td></tr>
    <tr><td style="color:var(--muted)">Tháng/Năm</td><td>${months[thang-1]} / ${nam}</td></tr>
    <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
    <tr><td style="color:var(--muted)">Số ngày làm thực tế</td><td>${d.soNgayLam} ngày</td></tr>
    <tr><td style="color:var(--muted)">Số ngày nghỉ có phép</td><td>${d.soNgayNghiPhep} ngày</td></tr>
    <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
    <tr><td style="color:var(--muted)">Lương cơ bản</td><td>${fmt(d.luongCoBan)} đ</td></tr>
    <tr><td style="color:var(--muted)">Hệ số lương</td><td>${d.heSoLuong}</td></tr>
    <tr><td style="color:var(--muted)">Phụ cấp</td><td>${fmt(d.phuCap)} đ</td></tr>
    <tr><td style="color:var(--muted)">Thưởng</td><td style="color:var(--accent)">+ ${fmt(d.thuong)} đ</td></tr>
    <tr><td style="color:var(--muted)">Khấu trừ</td><td style="color:var(--danger)">- ${fmt(d.khauTru)} đ</td></tr>
    <tr><td colspan="2" style="background:#f8f9fb"></td></tr>
    <tr style="background:#e8f4fd">
      <td><strong>Thực lĩnh</strong></td>
      <td><strong style="color:var(--primary);font-size:1.1rem">${fmt(d.thucLinh)} đ</strong></td>
    </tr>
    ${d.ghiChu ? `<tr><td style="color:var(--muted)">Ghi chú</td><td style="font-style:italic">${d.ghiChu}</td></tr>` : ''}
  `;

  document.getElementById('monthDetail').style.display = 'block';
  document.getElementById('monthDetail').scrollIntoView({ behavior: 'smooth' });
}

// ---- jsPDF helpers ----
const HEADER_COLOR = [26, 60, 110];   // #1a3c6e
const ACCENT_COLOR = [26, 111, 163];  // #1a6fa3

function setBtn(id, disabled, html) {
  const b = document.getElementById(id);
  b.disabled = disabled; b.innerHTML = html;
}

function addPdfHeader(doc, title, subtitle) {
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(16);
  doc.setTextColor(...HEADER_COLOR);
  doc.text(title, doc.internal.pageSize.getWidth() / 2, 18, { align: 'center' });
  if (subtitle) {
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(9);
    doc.setTextColor(100, 100, 100);
    doc.text(subtitle, doc.internal.pageSize.getWidth() / 2, 24, { align: 'center' });
  }
  return 32;
}

function addFormulaBox(doc, y, text) {
  const pw = doc.internal.pageSize.getWidth() - 20;
  doc.setFillColor(234, 244, 253);
  doc.setDrawColor(...ACCENT_COLOR);
  doc.setLineWidth(0.8);
  const lines = doc.splitTextToSize('Cong thuc: ' + text, pw - 16);
  const boxH = lines.length * 5 + 8;
  doc.rect(10, y, pw, boxH, 'FD');
  doc.setFont('helvetica', 'normal');
  doc.setFontSize(8);
  doc.setTextColor(30, 60, 100);
  doc.text(lines, 18, y + 6);
}

// Export Year PDF
document.getElementById('exportYearPdfBtn').addEventListener('click', () => {
  setBtn('exportYearPdfBtn', true, '<i class="fa-solid fa-spinner fa-spin"></i> Đang tạo PDF...');
  try {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

    const y = addPdfHeader(
      doc,
      'BANG LUONG NAM ' + currentYear,
      'Nhan vien: ' + emp.hoVaTen + '  |  Chuc vu: ' + emp.chucVu + '  |  Ngay xuat: ' + new Date().toLocaleDateString('vi-VN')
    );

    const map = {};
    salaryData.forEach(s => { map[s.thang] = s; });
    let total = 0;
    salaryData.forEach(s => { total += parseFloat(s.thucLinh); });

    const head = [['Thang','Ngay lam','Luong CB (VND)','He so','Phu cap (VND)','Thuong (VND)','Khau tru (VND)','Thuc linh (VND)']];
    const body = [];
    for (let m = 1; m <= 12; m++) {
      const s = map[m];
      if (!s) { body.push(['Thang ' + m, '—', '—', '—', '—', '—', '—', 'Chua co du lieu']); }
      else {
        body.push([
          'Thang ' + m, s.soNgayLam,
          fmt(s.luongCoBan), s.heSoLuong,
          fmt(s.phuCap),
          s.thuong > 0 ? '+' + fmt(s.thuong) : '0',
          s.khauTru > 0 ? '-' + fmt(s.khauTru) : '0',
          fmt(s.thucLinh)
        ]);
      }
    }
    body.push(['Tong thuc linh ca nam', '', '', '', '', '', '', fmt(total)]);

    doc.autoTable({
      startY: y,
      head,
      body,
      styles: { font: 'helvetica', fontSize: 8, cellPadding: 3 },
      headStyles: { fillColor: HEADER_COLOR, textColor: 255, fontStyle: 'bold' },
      footStyles: { fillColor: [240, 244, 255], textColor: [30, 30, 30], fontStyle: 'bold' },
      rowPageBreak: 'avoid',
      didParseCell(data) {
        if (data.row.index === body.length - 1) {
          data.cell.styles.fillColor = [240, 244, 255];
          data.cell.styles.fontStyle = 'bold';
        }
      }
    });

    addFormulaBox(doc, doc.lastAutoTable.finalY + 6, formulaText);
    doc.save('bang-luong-nam-' + currentYear + '.pdf');
    hrToast('Da xuat file PDF thanh cong!', 'success');
  } catch(e) {
    hrToast('Loi khi tao PDF: ' + e.message, 'error');
  }
  setBtn('exportYearPdfBtn', false, '<i class="fa-solid fa-file-pdf"></i> Xuat PDF nam');
});

// Export Month PDF
document.getElementById('exportMonthPdfBtn').addEventListener('click', () => {
  if (!currentMonthData) return;
  setBtn('exportMonthPdfBtn', true, '<i class="fa-solid fa-spinner fa-spin"></i> Dang tao PDF...');
  try {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
    const d = currentMonthData;

    const y = addPdfHeader(
      doc,
      'PHIEU LUONG THANG ' + d.thang + ' NAM ' + d.nam,
      'Ngay xuat: ' + new Date().toLocaleDateString('vi-VN')
    );

    doc.autoTable({
      startY: y,
      body: [
        ['Ho va ten', d.hoVaTen],
        ['Chuc vu', d.chucVu],
        ['Email', d.email || '—'],
        ['So dien thoai', d.soDT || '—'],
        ['Ky luong', 'Thang ' + d.thang + ' / ' + d.nam],
        ['So ngay lam viec', d.soNgayLam + ' ngay'],
        ['So ngay nghi phep', d.soNgayNghiPhep + ' ngay'],
        ['Luong co ban', fmt(d.luongCoBan) + ' d'],
        ['He so luong', String(d.heSoLuong)],
        ['Phu cap co dinh', fmt(d.phuCap) + ' d'],
        ['Thuong', '+ ' + fmt(d.thuong) + ' d'],
        ['Khau tru', '- ' + fmt(d.khauTru) + ' d'],
        ['THUC LINH', fmt(d.thucLinh) + ' dong'],
        ...(d.ghiChu ? [['Ghi chu', d.ghiChu]] : [])
      ],
      columnStyles: { 0: { cellWidth: 60, fontStyle: 'bold', fillColor: [245, 247, 252] }, 1: { cellWidth: 100 } },
      styles: { font: 'helvetica', fontSize: 9, cellPadding: 4 },
      didParseCell(data) {
        // highlight THUC LINH row
        if (data.row.raw[0] === 'THUC LINH') {
          data.cell.styles.fillColor = [232, 244, 253];
          data.cell.styles.fontStyle = 'bold';
          data.cell.styles.fontSize = 11;
          data.cell.styles.textColor = HEADER_COLOR;
        }
      }
    });

    const sigY = doc.lastAutoTable.finalY + 6;
    addFormulaBox(doc, sigY, formulaText);
    const pw = doc.internal.pageSize.getWidth();
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(8);
    doc.setTextColor(150);
    doc.text('Chu ky nhan vien: ___________________', pw - 14, sigY + 30, { align: 'right' });

    doc.save('phieu-luong-thang' + d.thang + '-' + d.nam + '.pdf');
    hrToast('Da xuat file PDF thanh cong!', 'success');
  } catch(e) {
    hrToast('Loi khi tao PDF: ' + e.message, 'error');
  }
  setBtn('exportMonthPdfBtn', false, '<i class="fa-solid fa-file-pdf"></i> Xuat PDF thang');
});

// Auto-load current year on page load
loadSalary();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
