<?php
require_once __DIR__ . '/auth_check.php';
$pageTitle = 'Thống kê'; $activeMenu = 'stats';
include __DIR__ . '/partials/layout_header.php';
?>

<h1 class="hr-main__title"><i class="fa-solid fa-chart-bar"></i> Thống kê lương & thưởng</h1>

<div class="card">
  <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end">
    <div class="form-group" style="margin:0">
      <label style="font-size:.83rem; font-weight:600; display:block; margin-bottom:6px">Loại</label>
      <select class="form-control" id="statsType" style="width:140px">
        <option value="monthly">Theo tháng</option>
        <option value="yearly" selected>Theo năm</option>
      </select>
    </div>
    <div class="form-group" id="grpThang" style="margin:0; display:none">
      <label style="font-size:.83rem; font-weight:600; display:block; margin-bottom:6px">Tháng</label>
      <select class="form-control" id="statsThang" style="width:110px">
        <?php for($m=1;$m<=12;$m++): ?><option value="<?=$m?>" <?=$m==date('n')?'selected':''?>>Tháng <?=$m?></option><?php endfor; ?>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label style="font-size:.83rem; font-weight:600; display:block; margin-bottom:6px">Năm</label>
      <select class="form-control" id="statsNam" style="width:100px">
        <?php for($y=date('Y');$y>=date('Y')-3;$y--): ?><option value="<?=$y?>" <?=$y==date('Y')?'selected':''?>><?=$y?></option><?php endfor; ?>
      </select>
    </div>
    <button class="btn btn-primary" id="btnLoadStats"><i class="fa-solid fa-chart-bar"></i> Xem thống kê</button>
    <button class="btn btn-outline" id="btnExportPdf" style="display:none"><i class="fa-solid fa-file-pdf"></i> Xuất PDF</button>
  </div>
</div>

<!-- Summary cards -->
<div id="summaryCards" style="display:none; display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-bottom:16px"></div>

<div class="card" id="statsCard" style="display:none">
  <div class="card-header"><h2 id="statsTitle"></h2></div>
  <table class="hr-table" id="statsTable">
    <thead id="statsThead"></thead>
    <tbody id="statsTbody"></tbody>
    <tfoot id="statsTfoot"></tfoot>
  </table>
</div>

<script>
const fmt = n => Number(n).toLocaleString('vi-VN');
const HEADER_COLOR = [26, 60, 110];
let statsData = null;

document.getElementById('statsType').addEventListener('change', () => {
  document.getElementById('grpThang').style.display =
    document.getElementById('statsType').value === 'monthly' ? '' : 'none';
});

async function loadStats() {
  const type  = document.getElementById('statsType').value;
  const nam   = document.getElementById('statsNam').value;
  const thang = document.getElementById('statsThang').value;

  let url = `/api/hr-manager/stats/summary.php?nam=${nam}`;
  if (type === 'monthly') url += `&thang=${thang}`;

  const r = await fetch(url);
  const d = await r.json();
  statsData = d;

  const statsCard = document.getElementById('statsCard');
  const cards = document.getElementById('summaryCards');

  if (d.type === 'yearly') {
    document.getElementById('statsTitle').textContent = `Thống kê lương năm ${d.nam}`;
    document.getElementById('statsThead').innerHTML = `<tr>
      <th>#</th><th>Nhân viên</th><th>Chức vụ</th>
      <th>Số tháng có BL</th><th>Tổng thưởng (VNĐ)</th><th>Tổng thực lĩnh (VNĐ)</th>
    </tr>`;
    document.getElementById('statsTbody').innerHTML = (d.rows||[]).map((r,i) => `<tr>
      <td>${i+1}</td><td><strong>${r.hoVaTen}</strong></td><td>${r.chucVu}</td>
      <td>${r.soThangCoBangLuong}</td>
      <td>${fmt(r.tongThuong)}</td>
      <td><strong style="color:#1a3c6e">${fmt(r.tongThucLinh)}</strong></td>
    </tr>`).join('') || '<tr><td colspan="6" style="text-align:center;color:#999">Chưa có dữ liệu</td></tr>';
    document.getElementById('statsTfoot').innerHTML = `<tr style="background:#f0f4ff">
      <td colspan="5"><strong>Tổng chi lương cả năm</strong></td>
      <td><strong style="font-size:1rem">${fmt(d.total)}</strong></td>
    </tr>`;

    // Summary cards
    cards.innerHTML = `
      <div class="card" style="margin:0; text-align:center; padding:20px">
        <div style="font-size:1.6rem; font-weight:700; color:#1a3c6e">${d.rows.length}</div>
        <div style="font-size:.85rem; color:#666">Nhân viên có bảng lương</div>
      </div>
      <div class="card" style="margin:0; text-align:center; padding:20px">
        <div style="font-size:1.3rem; font-weight:700; color:#1a3c6e">${fmt(d.total)} đ</div>
        <div style="font-size:.85rem; color:#666">Tổng chi lương năm ${d.nam}</div>
      </div>
      <div class="card" style="margin:0; text-align:center; padding:20px">
        <div style="font-size:1.3rem; font-weight:700; color:#27ae60">${fmt(d.rows.reduce((s,r)=>s+parseFloat(r.tongThuong),0))} đ</div>
        <div style="font-size:.85rem; color:#666">Tổng thưởng năm ${d.nam}</div>
      </div>`;
  } else {
    const mo = `Tháng ${d.thang}/${d.nam}`;
    document.getElementById('statsTitle').textContent = `Thống kê lương ${mo}`;
    document.getElementById('statsThead').innerHTML = `<tr>
      <th>#</th><th>Nhân viên</th><th>Chức vụ</th>
      <th>Ngày làm</th><th>Lương CB</th><th>Hệ số</th>
      <th>Phụ cấp</th><th>Thưởng</th><th>Khấu trừ</th><th>Thực lĩnh (VNĐ)</th>
    </tr>`;
    document.getElementById('statsTbody').innerHTML = (d.rows||[]).map((r,i) => `<tr>
      <td>${i+1}</td><td><strong>${r.hoVaTen}</strong></td><td>${r.chucVu}</td>
      <td>${r.soNgayLam}</td><td>${fmt(r.luongCoBan)}</td><td>${r.heSoLuong}</td>
      <td>${fmt(r.phuCap)}</td><td>${fmt(r.thuong)}</td><td>${fmt(r.khauTru)}</td>
      <td><strong style="color:#1a3c6e">${fmt(r.thucLinh)}</strong></td>
    </tr>`).join('') || '<tr><td colspan="10" style="text-align:center;color:#999">Chưa có dữ liệu</td></tr>';
    document.getElementById('statsTfoot').innerHTML = `<tr style="background:#f0f4ff">
      <td colspan="9"><strong>Tổng thực lĩnh</strong></td>
      <td><strong>${fmt(d.total)}</strong></td>
    </tr>`;

    cards.innerHTML = `
      <div class="card" style="margin:0; text-align:center; padding:20px">
        <div style="font-size:1.6rem; font-weight:700; color:#1a3c6e">${d.rows.length}</div>
        <div style="font-size:.85rem; color:#666">Nhân viên tháng ${d.thang}/${d.nam}</div>
      </div>
      <div class="card" style="margin:0; text-align:center; padding:20px">
        <div style="font-size:1.3rem; font-weight:700; color:#1a3c6e">${fmt(d.total)} đ</div>
        <div style="font-size:.85rem; color:#666">Tổng chi lương tháng ${d.thang}</div>
      </div>`;
  }

  cards.style.display = 'grid';
  statsCard.style.display = 'block';
  document.getElementById('btnExportPdf').style.display = 'inline-flex';
}

// PDF Export
document.getElementById('btnExportPdf').addEventListener('click', () => {
  if (!statsData) return;
  const { jsPDF } = window.jspdf;
  const isYearly = statsData.type === 'yearly';
  const doc = new jsPDF({ orientation: isYearly ? 'landscape' : 'landscape', unit: 'mm', format: 'a4' });

  doc.setFont('helvetica','bold');
  doc.setFontSize(15);
  doc.setTextColor(...HEADER_COLOR);
  const title = isYearly ? `THONG KE LUONG NAM ${statsData.nam}` : `THONG KE LUONG THANG ${statsData.thang}/${statsData.nam}`;
  doc.text(title, doc.internal.pageSize.getWidth()/2, 16, {align:'center'});

  let head, body;
  if (isYearly) {
    head = [['#','Ho va ten','Chuc vu','So thang','Tong thuong (VND)','Tong thuc linh (VND)']];
    body = (statsData.rows||[]).map((r,i) => [i+1, r.hoVaTen, r.chucVu, r.soThangCoBangLuong, fmt(r.tongThuong), fmt(r.tongThucLinh)]);
    body.push(['','Tong','','','', fmt(statsData.total)]);
  } else {
    head = [['#','Ho va ten','Chuc vu','Ngay lam','Luong CB','He so','Phu cap','Thuong','Khau tru','Thuc linh']];
    body = (statsData.rows||[]).map((r,i) => [i+1, r.hoVaTen, r.chucVu, r.soNgayLam, fmt(r.luongCoBan), r.heSoLuong, fmt(r.phuCap), fmt(r.thuong), fmt(r.khauTru), fmt(r.thucLinh)]);
    body.push(['','Tong','','','','','','','',fmt(statsData.total)]);
  }

  doc.autoTable({
    startY: 22, head, body,
    styles: { fontSize: 8, cellPadding: 3 },
    headStyles: { fillColor: HEADER_COLOR, textColor: 255, fontStyle: 'bold' },
    didParseCell(data) {
      if (data.row.index === body.length - 1) {
        data.cell.styles.fillColor = [240,244,255];
        data.cell.styles.fontStyle = 'bold';
      }
    }
  });
  doc.save(`thong-ke-luong-${isYearly ? statsData.nam : statsData.thang+'-'+statsData.nam}.pdf`);
  hrToast('Đã xuất PDF!');
});

document.getElementById('btnLoadStats').addEventListener('click', loadStats);
loadStats();
</script>

<?php include __DIR__ . '/partials/layout_footer.php'; ?>
