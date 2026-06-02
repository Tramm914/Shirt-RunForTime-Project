<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>

<div class="main-layout-wrapper">
  <?php require_once 'app/views/layouts/sidebar.php'; ?>

  <main class="main-content">
    <style>
      .main-content {
          background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
          background-attachment: fixed;
          min-height: 100vh;
      }
    </style>
    <div class="content">

      <!-- Kế hoạch chờ duyệt -->
      <div class="section">
        <div class="section-title">Kế Hoạch Chờ Phê Duyệt</div>
        <div class="section-content">
          <ul class="plan-list">
            <?php if (!empty($plans)): ?>
              <?php foreach ($plans as $p): ?>
                <li class="plan-item" data-ma-khsx="<?= $p['maKHSX'] ?>" onclick="selectPlan(this, <?= $p['maKHSX'] ?>)">
                  <div class="plan-item-header">
                    <span class="plan-item-name"><?= htmlspecialchars($p['tenKHSX']) ?></span>
                    <span class="plan-item-status status-pending"><?= htmlspecialchars($p['trangThai']) ?></span>
                  </div>
                  <div class="plan-item-date">
                    Ngày bắt đầu: <?= htmlspecialchars($p['thoiGianBatDau']) ?> –
                    Ngày kết thúc: <?= htmlspecialchars($p['thoiGianKetThuc']) ?>
                  </div>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="color:#777;">Không có kế hoạch nào đang chờ duyệt.</p>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- Chi tiết kế hoạch -->
      <div class="section" id="plan-detail-section" style="display:none;">
        <div class="section-title">Chi Tiết Kế Hoạch</div>
        <div class="section-content">

          <div class="detail-section"><div class="detail-label">Đơn Hàng Sản Xuất</div><div class="detail-value" id="order-code">—</div></div>
          <div class="detail-section"><div class="detail-label">Thời Gian Thực Hiện</div><div class="detail-value"><span id="start-date">—</span> đến <span id="end-date">—</span></div></div>
          <div class="detail-section"><div class="detail-label">Sản Phẩm</div><div class="detail-value" id="product-name">—</div></div>
          <div class="detail-section"><div class="detail-label">Số Lượng Sản Phẩm</div><div class="detail-value" id="product-qty">—</div></div>
          <div class="detail-section"><div class="detail-label">Xưởng Phân Công</div><div class="detail-value" id="workshop-name">—</div></div>

          <div class="detail-section">
            <div class="detail-label">Nguyên Vật Liệu &amp; Phân Công Xưởng</div>
            <table class="detail-table" id="materials-table">
              <thead>
                <tr>
                  <th>Mã NVL</th>
                  <th>Tên NVL</th>
                  <th>Xưởng</th>
                  <th>ĐVT</th>
                  <th>SL cần</th>
                  <th>Tồn kho</th>
                  <th>Ghi chú</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          <div class="detail-section"><div class="detail-label">Ghi Chú</div><div class="detail-value" id="plan-note">—</div></div>

          <div class="action-buttons">
            <button class="btn btn-approve" onclick="openApproveModal()">Phê Duyệt</button>
            <button class="btn btn-reject" onclick="openRejectModal()">Từ Chối</button>
          </div>
        </div>
      </div>

      <!-- Modal Phê duyệt -->
      <div class="modal" id="approveModal">
        <div class="modal-content">
          <div class="modal-title">Xác Nhận Phê Duyệt</div>
          <p>Bạn có chắc chắn muốn phê duyệt kế hoạch này?</p>
          <div class="form-group">
            <label class="form-label">Ghi chú (tùy chọn)</label>
            <textarea class="form-control" placeholder="Nhập ghi chú..."></textarea>
          </div>
          <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeApproveModal()">Hủy</button>
            <button class="btn-confirm" onclick="confirmApprove()">Phê Duyệt</button>
          </div>
        </div>
      </div>

      <!-- Modal Từ chối -->
      <div class="modal" id="rejectModal">
        <div class="modal-content">
          <div class="modal-title">Từ Chối Kế Hoạch</div>
          <label class="form-label">Lý Do Từ Chối</label>
          <textarea class="form-control" placeholder="Nhập lý do từ chối..."></textarea>
          <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeRejectModal()">Hủy</button>
            <button class="btn-confirm" onclick="confirmReject()">Xác Nhận</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
const plans = <?= json_encode($plans, JSON_UNESCAPED_UNICODE) ?>;

// 🔹 Khi chọn kế hoạch
async function selectPlan(el, maKHSX) {
  document.querySelectorAll(".plan-item").forEach(i => i.classList.remove("active"));
  el.classList.add("active");

  try {
    const res = await fetch(`index.php?page=ajax-get-plan-detail&maKHSX=${maKHSX}`);
    const data = await res.json();

    if (data.error) {
      alert("❌ " + data.error);
      return;
    }

    const section = document.getElementById("plan-detail-section");
    section.style.display = "block";

    // Hiển thị thông tin đơn hàng và sản phẩm
    document.getElementById("order-code").textContent = (data.maDonHang ? `DHSX-${data.maDonHang}` : "—") + 
      (data.tenDonHang ? ` (${data.tenDonHang})` : "");
    document.getElementById("start-date").textContent = data.ngayBatDau || "—";
    document.getElementById("end-date").textContent = data.ngayKetThuc || "—";
    document.getElementById("product-name").textContent = data.tenSanPham || "—";
    document.getElementById("product-qty").textContent = (data.soLuongSanXuat || "—") + " cái";
    document.getElementById("workshop-name").textContent = data.tenXuong || "—";
    document.getElementById("plan-note").textContent = data.ghiChu || "Không có ghi chú";

    // Nguyên vật liệu với phân công xưởng
    const tbody = document.querySelector("#materials-table tbody");
    tbody.innerHTML = "";

    if (data.nguyenVatLieu && data.nguyenVatLieu.length > 0) {
      data.nguyenVatLieu.forEach(m => {
        // Only show the "(loaiNVL)" part when loaiNVL is not empty and not the string/number '0'
        const loaiStr = (m.loaiNVL !== null && m.loaiNVL !== undefined && String(m.loaiNVL).trim() !== '' && String(m.loaiNVL) !== '0')
          ? ` (${m.loaiNVL})`
          : '';

        tbody.innerHTML += `
          <tr>
            <td>${m.maNVL}</td>
            <td>${m.tenNVL}${loaiStr}</td>
            <td style="font-weight: 600; color: #142850;">${m.tenXuong || '—'}</td>
            <td>${m.donViTinh || ''}</td>
            <td>${m.soLuongCan}</td>
            <td>${m.soLuongTonKho}</td>
            <td style="color: ${m.ghiChu.includes('Thiếu') ? 'red' : 'green'}; font-weight: bold;">
              ${m.ghiChu}
            </td>
          </tr>`;
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Chưa có thông tin nguyên vật liệu</td></tr>';
    }

  } catch (error) {
    console.error("Lỗi khi tải chi tiết kế hoạch:", error);
    alert("⚠️ Có lỗi xảy ra khi tải thông tin kế hoạch!");
  }
}

// 🔹 Modal logic
function openApproveModal() { document.getElementById("approveModal").classList.add("active"); }
function closeApproveModal() { document.getElementById("approveModal").classList.remove("active"); }
function openRejectModal() { document.getElementById("rejectModal").classList.add("active"); }
function closeRejectModal() { document.getElementById("rejectModal").classList.remove("active"); }

// 🔹 Xử lý duyệt kế hoạch
async function confirmApprove() {
  const activePlan = document.querySelector(".plan-item.active");
  if (!activePlan) {
    alert("⚠️ Vui lòng chọn một kế hoạch!");
    return;
  }
  
  // Lấy maKHSX từ data attribute thay vì parse onclick
  const maKHSX = activePlan.getAttribute("data-ma-khsx");
  
  if (!maKHSX) {
    alert("⚠️ Không tìm thấy mã kế hoạch!");
    console.error("activePlan:", activePlan);
    return;
  }
  
  const ghiChu = document.querySelector("#approveModal textarea").value;

  console.log("Đang phê duyệt kế hoạch:", maKHSX); // Debug

  try {
    const res = await fetch("index.php?page=phe-duyet-ke-hoach-sx-process", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `maKeHoach=${maKHSX}&trangThai=Đã duyệt&ghiChu=${encodeURIComponent(ghiChu)}`
    });
    
    const result = await res.json();
    
    console.log("Kết quả:", result); // Debug
    
    if (result.success) {
      alert("✅ Kế hoạch đã được phê duyệt!");
      window.location.reload();
    } else {
      alert("❌ " + result.message);
    }
  } catch (error) {
    console.error("Lỗi khi phê duyệt:", error);
    alert("⚠️ Có lỗi xảy ra khi phê duyệt kế hoạch!");
  }
}

// 🔹 Xử lý từ chối
async function confirmReject() {
  const activePlan = document.querySelector(".plan-item.active");
  if (!activePlan) {
    alert("⚠️ Vui lòng chọn một kế hoạch!");
    return;
  }
  
  // Lấy maKHSX từ data attribute
  const maKHSX = activePlan.getAttribute("data-ma-khsx");
  
  if (!maKHSX) {
    alert("⚠️ Không tìm thấy mã kế hoạch!");
    return;
  }
  
  const ghiChu = document.querySelector("#rejectModal textarea").value;

  if (!ghiChu.trim()) {
    alert("⚠️ Vui lòng nhập lý do từ chối!");
    return;
  }

  console.log("Đang từ chối kế hoạch:", maKHSX); // Debug

  try {
    const res = await fetch("index.php?page=phe-duyet-ke-hoach-sx-process", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `maKeHoach=${maKHSX}&trangThai=Từ chối&ghiChu=${encodeURIComponent(ghiChu)}`
    });
    
    const result = await res.json();
    
    console.log("Kết quả:", result); // Debug
    
    if (result.success) {
      alert("❌ Kế hoạch đã bị từ chối!");
      window.location.reload();
    } else {
      alert("⚠️ " + result.message);
    }
  } catch (error) {
    console.error("Lỗi khi từ chối:", error);
    alert("⚠️ Có lỗi xảy ra khi từ chối kế hoạch!");
  }
}
</script>


<style>
  .modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 15px;
}

.modal-buttons .btn-cancel,
.modal-buttons .btn-confirm {
  flex: 0; /* ❌ Không giãn */
  min-width: 100px;
  padding: 8px 14px;
  font-weight: 600;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  transition: 0.2s;
}

.btn-cancel {
  background: #dc3545;
  color: #fff;
}
.btn-cancel:hover {
  background: #b02a37;
}

.btn-confirm {
  background: #3b7ddd;
  color: #fff;
}
.btn-confirm:hover {
  background: #295fc5;
}

.main-content { margin-top: 0; padding-top: 10px; width: 100%; }
.content { max-width: 1200px; margin: 0 auto; padding: 20px 30px; background: #f8fafc; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
.section { background: #fff; border: 1px solid #dce2ec; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.section-title { background: #142850; color: #fff; font-weight: 600; padding: 12px 15px; border-radius: 8px 8px 0 0; font-size: 16px; }
.section-content { background: #fff; padding: 18px; border-radius: 0 0 8px 8px; }

/* Danh sách kế hoạch */
.plan-list { list-style: none; padding: 0; margin: 0; max-height: 400px; overflow-y: auto; }
.plan-item { background: #fafafa; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; padding: 12px 15px; cursor: pointer; transition: all 0.3s ease; }
.plan-item:hover { background: #f1f6ff; border-color: #3b7ddd; transform: translateX(5px); }
.plan-item.active { border-color: #3b7ddd; background: #eaf2ff; box-shadow: 0 2px 8px rgba(59, 125, 221, 0.2); }
.plan-item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.plan-item-name { font-weight: 600; color: #142850; }
.plan-item-date { font-size: 13px; color: #666; }
.plan-item-status { padding: 3px 12px; border-radius: 15px; background: #fff3cd; border: 1px solid #ffc107; font-size: 12px; font-weight: 600; color: #856404; }

/* Chi tiết kế hoạch */
.detail-section { margin-bottom: 15px; }
.detail-label { font-weight: 600; color: #142850; margin-bottom: 5px; font-size: 14px; }
.detail-value { color: #333; padding: 8px; background: #f8f9fa; border-radius: 4px; }

/* Bảng nguyên vật liệu */
.detail-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
.detail-table thead { background: #142850; color: #fff; }
.detail-table th, .detail-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
.detail-table tbody tr:hover { background: #f1f6ff; }
.detail-table tfoot { background: #f8f9fa; font-weight: 600; }

/* Nút hành động */
.action-buttons { display: flex; gap: 15px; margin-top: 20px; }
.btn { flex: 1; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; font-size: 15px; }
.btn-approve { background: #28a745; color: #fff; }
.btn-approve:hover { background: #218838; }
.btn-reject { background: #dc3545; color: #fff; }
.btn-reject:hover { background: #c82333; }

/* Modal */
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999; }
.modal.active { display: flex; }
.modal-content { background: #fff; border-radius: 8px; padding: 25px; max-width: 500px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.modal-title { font-size: 20px; font-weight: 600; color: #142850; margin-bottom: 15px; }
.form-group { margin-bottom: 15px; }
.form-label { display: block; font-weight: 600; margin-bottom: 5px; color: #333; }
.form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical; min-height: 80px; }

/* Animation */
#plan-detail-section { animation: fadeIn 0.4s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Scrollbar tùy chỉnh */
.plan-list::-webkit-scrollbar { width: 6px; }
.plan-list::-webkit-scrollbar-track { background: #f1f1f1; }
.plan-list::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
.plan-list::-webkit-scrollbar-thumb:hover { background: #555; }
</style>

<?php require_once 'app/views/layouts/footer.php'; ?>
