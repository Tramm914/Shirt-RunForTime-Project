<?php
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/layouts/nav.php';
?>
<div class="main-layout-wrapper">
  <?php require_once __DIR__ . '/layouts/sidebar.php'; ?>

  <main class="main-content" style="padding: 30px;">
    <style>
      .main-content {
          background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
          background-attachment: fixed;
          min-height: 100vh;
      }
    </style>
    <div class="container">
      <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
        
        <h1 style="font-size:26px; color:#ffff; font-weight:700; margin:0; text-align:center;">TẠO YÊU CẦU KIỂM TRA CHẤT LƯỢNG SẢN PHẨM</h1>
      </div>

      <!-- Form chọn kế hoạch -->
      <form action="index.php?page=tao-yeu-cau-kiem-tra-chat-luong-process" method="POST"
            style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:20px; margin-bottom:25px;">
        
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px; flex-wrap:wrap;">
          <label for="planCode" style="font-weight:600; min-width:250px;">🏭 Chọn kế hoạch (Đơn hàng đã hoàn thành):</label>
          <select name="planCode" id="planCode" required onchange="loadProductInfo()"
                  style="padding:8px 12px; border:1px solid #ccc; border-radius:8px; font-size:15px; min-width:350px;">
            <option value="">-- Chọn kế hoạch --</option>
            <?php if (empty($plans)): ?>
              <option value="" disabled>Không có đơn hàng nào hoàn thành</option>
            <?php else: ?>
              <?php foreach ($plans as $p): ?>
                <option value="<?= $p['maKHSX'] ?>" 
                        data-product='<?= json_encode($p) ?>'>
                  <?= htmlspecialchars($p['tenKHSX']) ?> - <?= htmlspecialchars($p['tenSanPham']) ?> (<?= htmlspecialchars($p['tenDonHang']) ?>)
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px; flex-wrap:wrap;">
          <label for="thoiHanHoanThanh" style="font-weight:600; min-width:250px;">⏰ Hạn kiểm tra tối đa :</label>
          <input type="date" name="thoiHanHoanThanh" id="thoiHanHoanThanh" required
                 style="padding:8px 12px; border:1px solid #ccc; border-radius:8px; font-size:15px; min-width:200px;">
          <span style="color:#666; font-size:14px;">📅 Tính từ ngày giao dự kiến </span>
        </div>

        <button type="submit" id="btnCreate" disabled
                style="background:#1d3557; color:white; padding:10px 18px; border:none; border-radius:8px;
                       font-weight:600; cursor:pointer; transition:0.3s; opacity:0.5;">
          Tạo phiếu kiểm tra chất lượng
        </button>
      </form>

      <!-- Bảng thông tin sản phẩm cần kiểm tra -->
      <section style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:20px;">
        <h2 style="font-size:20px; color:#1d3557; font-weight:600; margin-bottom:15px;">
          Thông Tin Sản Phẩm Cần Kiểm Tra Chất Lượng
        </h2>
        <div id="productInfo" style="padding:15px; background:#f8f9fa; border-radius:8px; min-height:100px;">
          <p style="color:#666; text-align:center;">Vui lòng chọn kế hoạch sản xuất để xem thông tin...</p>
        </div>
      </section>
    </div>
  </main>
</div>

<!-- ========== STYLE ========== -->
<style>
.info-row {
  display: flex;
  padding: 10px 0;
  border-bottom: 1px solid #e0e0e0;
}
.info-label {
  font-weight: 600;
  color: #1d3557;
  width: 200px;
}
.info-value {
  color: #333;
  flex: 1;
}
</style>

<!-- ========== SCRIPT ========== -->
<script>
// Hàm tính ngày sau X ngày từ một ngày cụ thể
function getDateAfterDays(dateString, days) {
  const date = new Date(dateString);
  date.setDate(date.getDate() + days);
  return date.toISOString().split('T')[0];
}

function loadProductInfo() {
  const select = document.getElementById('planCode');
  const selectedOption = select.options[select.selectedIndex];
  const btnCreate = document.getElementById('btnCreate');
  const productInfo = document.getElementById('productInfo');
  const thoiHanInput = document.getElementById('thoiHanHoanThanh');
  
  if (!select.value) {
    productInfo.innerHTML = '<p style="color:#666; text-align:center;">Vui lòng chọn kế hoạch sản xuất để xem thông tin...</p>';
    btnCreate.disabled = true;
    btnCreate.style.opacity = '0.5';
    btnCreate.style.cursor = 'not-allowed';
    thoiHanInput.value = '';
    thoiHanInput.min = '';
    return;
  }

  const data = JSON.parse(selectedOption.getAttribute('data-product'));
  
  // Debug: Kiểm tra dữ liệu nhận được
  console.log('📊 Data từ dropdown:', data);
  console.log('📅 ngayKetThuc (kế hoạch):', data.ngayKetThuc);
  console.log('⏰ thoiHanKiemTraMacDinh:', data.thoiHanKiemTraMacDinh);
  
  // Set giá trị mặc định = ngayKetThuc (kết thúc kế hoạch) + 3 ngày
  if (data.thoiHanKiemTraMacDinh) {
    thoiHanInput.value = data.thoiHanKiemTraMacDinh;
    console.log('✅ Đã set thời hạn từ DB:', data.thoiHanKiemTraMacDinh);
  } else if (data.ngayKetThuc) {
    const calculatedDate = getDateAfterDays(data.ngayKetThuc, 3);
    thoiHanInput.value = calculatedDate;
    console.log('✅ Đã tính thời hạn:', data.ngayKetThuc, '+ 3 ngày =', calculatedDate);
  }

  // Set min date = ngayKetThuc (không cho chọn trước ngày kết thúc kế hoạch)
  if (data.ngayKetThuc) {
    thoiHanInput.min = data.ngayKetThuc;
  }

  // Set max date = ngayKetThuc + 3 ngày (tối đa)
  if (data.ngayKetThuc) {
    thoiHanInput.max = getDateAfterDays(data.ngayKetThuc, 3);
  }
  
  productInfo.innerHTML = `
    <div class="info-row">
      <div class="info-label">Tên kế hoạch:</div>
      <div class="info-value">${data.tenKHSX}</div>
    </div>
    <div class="info-row">
      <div class="info-label">Đơn hàng:</div>
      <div class="info-value"><strong>${data.tenDonHang}</strong> <span style="background:#28a745; color:white; padding:3px 10px; border-radius:5px; font-size:13px; margin-left:10px;">✓ Hoàn thành</span></div>
    </div>
    <div class="info-row">
      <div class="info-label">Sản phẩm:</div>
      <div class="info-value"><strong>${data.tenSanPham}</strong></div>
    </div>
    <div class="info-row">
      <div class="info-label">Số lượng cần kiểm tra:</div>
      <div class="info-value"><strong style="color:#d00; font-size:18px;">${data.soLuongSanXuat}</strong> cái</div>
    </div>
    <div class="info-row">
      <div class="info-label">📦 Ngày kết thúc kế hoạch:</div>
      <div class="info-value"><strong>${data.ngayKetThuc || 'N/A'}</strong> <span style="color:#666; font-size:13px; margin-left:5px;">→ Hạn kiểm tra tối đa: ${data.thoiHanKiemTraMacDinh || (data.ngayKetThuc ? getDateAfterDays(data.ngayKetThuc, 3) : 'N/A')}</span></div>
    </div>
  `;
  
  btnCreate.disabled = false;
  btnCreate.style.opacity = '1';
  btnCreate.style.cursor = 'pointer';
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>