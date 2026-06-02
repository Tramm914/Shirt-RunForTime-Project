<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>
<div class="main-layout-wrapper">
  <?php require_once './app/views/layouts/sidebar.php'; ?>

  <main class="main-content">
    <style>
      .main-content {
          background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
          background-attachment: fixed;
          min-height: 100vh;
      }
    </style>
    <div class="container">
      <div class="card form-section">
        <h2>NHẬP KHO NGUYÊN VẬT LIỆU</h2>
        <form id="materialForm">
          <div class="form-group">
            <label for="requestRef">Phiếu Yêu Cầu Nhập Kho (chỉ phiếu đã duyệt)</label>
            <select id="requestRef" onchange="loadFromRequest(this.value)">
              <option value="">-- Chọn phiếu yêu cầu đã duyệt --</option>
              <?php if (!empty($requests)): ?>
                  <?php foreach ($requests as $row): ?>
                      <option value="<?= htmlspecialchars($row['maYCNK']) ?>">
                          Phiếu #<?= htmlspecialchars($row['maYCNK']) ?> - <?= htmlspecialchars($row['tenPhieu']) ?> 
                          (<?= htmlspecialchars($row['ngayLap']) ?>) - Người lập: <?= htmlspecialchars($row['tenNguoiLap']) ?>
                      </option>
                  <?php endforeach; ?>
              <?php else: ?>
                  <option disabled>Không có phiếu nào đã duyệt</option>
              <?php endif; ?>
            </select>

            <small style="color:#555; display:block; margin-top:5px;">
              Hệ thống chỉ cho phép lấy dữ liệu từ các phiếu <b>Đã duyệt</b>.
            </small>
          </div>

          <div class="form-group">
            <label for="notes">Ghi Chú</label>
            <textarea id="notes" placeholder="Nhập ghi chú thêm (nếu có)"></textarea>
          </div>

          <!-- Bảng hiển thị NVL -->
          <div class="table-container">
            <table>
                  <thead>
      <tr>
        <th>Mã NVL</th>
        <th>Tên NVL</th>
        <th>Số lượng nhập</th>
        <th>Đơn vị</th>
        <th>Loại NVL</th>
        <th>Nhà cung cấp</th>
      </tr>
    </thead>
    <tbody id="linesBody"></tbody>
            </table>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Lưu Phiếu Nhập</button>
            <button type="reset" class="btn btn-secondary">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>

<script>
  // Biến tiện dụng và hàm rút gọn
  const $ = (s) => document.querySelector(s);

  // === KHI CHỌN PHIẾU YÊU CẦU ĐÃ DUYỆT ===
  async function loadFromRequest(maYCNK) {
  if (!maYCNK) return;
  const res = await fetch(`index.php?page=ajax-get-details-nhapkho&maYCNK=${maYCNK}`);
  const data = await res.json();
  const tbody = document.getElementById('linesBody');
  tbody.innerHTML = '';

  data.forEach(row => {
    tbody.innerHTML += `
      <tr>
        <td>${row.maNVL}</td>
        <td>${row.tenNVL}</td>
        <td style="text-align:center; font-weight:bold;">${row.soLuongYeuCau}</td>
        <td>${row.donViTinh ?? '-'}</td>
        <td>${row.loaiNVL ?? '-'}</td>
        <td>${row.nhaCungCap ?? '-'}</td>
      </tr>`;
  });

  // ✅ Tự động gán dữ liệu ban đầu (số lượng cố định từ phiếu yêu cầu)
  window.selectedItems = data.map(r => ({
    maNVL: parseInt(r.maNVL),
    soLuong: parseInt(r.soLuongYeuCau) || 0
  }));
}


  // === GỬI FORM LƯU PHIẾU NHẬP ===
document.getElementById('materialForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!window.selectedItems || window.selectedItems.length === 0) {
    alert('⚠️ Không có dữ liệu để nhập kho!');
    return;
  }

  const items = window.selectedItems.filter(i => i.soLuong > 0);
  if (items.length === 0) {
    alert('⚠️ Phiếu yêu cầu không có NVL nào!');
    return;
  }

  const maYCNK = document.getElementById('requestRef').value;
  if (!maYCNK) {
    alert('⚠️ Vui lòng chọn phiếu yêu cầu!');
    return;
  }

  const formData = new FormData();
  formData.append('maYCNK', maYCNK);
  formData.append('ghiChu', document.getElementById('notes').value.trim());
  formData.append('items', JSON.stringify(items));

  try {
    const res = await fetch('index.php?page=luu-phieu-nhap-nvl', {
      method: 'POST',
      body: formData
    });

    // Try parse JSON response
    const text = await res.text();
    let json = null;
    try { json = JSON.parse(text); } catch (e) { /* not JSON */ }

    if (json && typeof json === 'object') {
      if (json.success) {
        alert('✅ ' + (json.message || 'Lưu phiếu nhập thành công'));
        document.getElementById('materialForm').reset();
        document.getElementById('linesBody').innerHTML = '';
        document.getElementById('requestRef').value = '';
        // Tải lại danh sách phiếu
        if (json.redirect) window.location.href = json.redirect;
      } else {
        alert('❌ ' + (json.message || 'Lỗi khi lưu phiếu nhập'));
      }
    } else {
      // Fallback: show raw response
      console.warn('Server returned non-JSON response:', text);
      alert('Đã xảy ra lỗi khi lưu phiếu nhập! Vui lòng kiểm tra log server.');
    }

  } catch (err) {
    console.error('❌ Lỗi khi gửi dữ liệu:', err);
    alert('Đã xảy ra lỗi khi lưu phiếu nhập! (network)');
  }
});

</script>


<style>
.container {
  max-width: 1100px;
  margin: 0 auto;
}

.form-section {
  background: #fff;
  border: 1px solid #e3e3e3;
  border-radius: 10px;
  padding: 25px 30px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  transition: box-shadow 0.3s ease;
  position: relative;
}
.form-section:hover {
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
  transform: none !important;
}

.form-section h2 {
  font-size: 18px;
  font-weight: 600;
  color: #004aad;
  margin-bottom: 20px;
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-weight: 500;
  font-size: 14px;
  color: #333;
  margin-bottom: 6px;
  display: block;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 9px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
  background: #fafafa;
  transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #004aad;
  outline: none;
  background: #fff;
}

.btn {
  display: inline-block;
  border-radius: 5px;
  border: none;
  padding: 8px 15px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: 0.3s;
}

.btn-primary {
  background-color: #004aad;
  color: white;
}
.btn-primary:hover {
  background-color: #003b8e;
}

.btn-secondary {
  background-color: #e0e0e0;
  color: #333;
}
.btn-secondary:hover {
  background-color: #ccc;
}

.table-container {
  margin-top: 15px;
  overflow-x: auto;
}
.table-container table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.table-container th,
.table-container td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}
.table-container th {
  background-color: #f5f7fa;
  font-weight: 600;
  color: #222;
}

.form-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.qty-input {
  width: 80px;
  padding: 5px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.qty-input:focus {
  border-color: #004aad;
  outline: none;
}
</style>

<?php
require_once 'app/views/layouts/footer.php';
?>
