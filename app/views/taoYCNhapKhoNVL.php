<?php
require_once './app/views/layouts/header.php';
require_once './app/views/layouts/nav.php';
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

      <!-- Tabs -->
      <div class="page-header">
        <h2 style="text-align:center;color: #ffff;">TẠO YÊU CẦU NHẬP KHO NGUYÊN VẬT LIỆU</h2>
        <div class="tabs">
          <button class="tab active" onclick="switchTab('list', event)" style="background-color:#ffff; color:0; margin-right:2px;"> Danh sách phiếu </button>        
          <button class="tab" onclick="switchTab('create', event)" style="background-color:#ffff; color: 0;"> Lập phiếu mới </button>        </div>
      </div>

      <!-- Section: Danh sách phiếu -->
      <section id="list" class="section active">
        <div class="card">
          <div class="card-header">
            <h2>Danh sách phiếu yêu cầu nhập kho</h2>
          </div>

          <!-- Bộ lọc theo ngày -->
          <div style="padding: 15px; background: #f8f9fa; border-bottom: 1px solid #ddd;">
            <div style="display: flex; gap: 15px; align-items: center;">
              <div>
                <label style="font-weight: 600; margin-right: 8px;">Từ ngày:</label>
                <input type="date" id="filterFromDate" class="filter-date-input">
              </div>
              <div>
                <label style="font-weight: 600; margin-right: 8px;">Đến ngày:</label>
                <input type="date" id="filterToDate" class="filter-date-input">
              </div>
              <button onclick="filterByDate()" class="btn-primary btn-small">Lọc</button>
              <button onclick="resetFilter()" class="btn-secondary btn-small">Đặt lại</button>
            </div>
          </div>

          <div class="table-container">
            <table id="tablePhieu">
              <thead>
                <tr>
                  <th style="width: 40px;">STT</th>
                  <th>Tên phiếu</th>
                  <th style="width: 120px;">Ngày lập</th>
                  <th style="width: 100px;">Số loại NVL</th>
                  <th style="width: 130px;">Trạng thái</th>
                  <th style="width: 100px;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($danhSachPhieu)): ?>
                  <?php $stt = 1; foreach ($danhSachPhieu as $phieu): ?>
                    <tr data-ngaylap="<?= htmlspecialchars($phieu['ngayLap']) ?>">
                      <td style="text-align: center;"><?= $stt++ ?></td>
                      <td style="font-weight: 600;"><?= htmlspecialchars($phieu['tenPhieu']) ?></td>
                      <td><?= date('d/m/Y', strtotime($phieu['ngayLap'])) ?></td>
                      <td style="text-align: center;"><?= htmlspecialchars($phieu['soLoaiNVL']) ?> loại</td>
                      <td>
                        <?php if ($phieu['trangThai'] === 'Đã duyệt'): ?>
                        <span class="status-badge status-approved">Đã duyệt</span>
                      <?php elseif ($phieu['trangThai'] === 'Đã nhập kho'): ?>
                        <span class="status-badge status-success">Đã nhập kho</span>
                      <?php elseif ($phieu['trangThai'] === 'Từ chối'): ?>
                        <span class="status-badge status-rejected">Từ chối</span>
                      <?php else: ?>
                        <span class="status-badge status-pending">Chờ duyệt</span>
                      <?php endif; ?>
                      </td>
                      <td>
                        <button class="btn-primary btn-small" onclick="xemChiTietPhieu(<?= $phieu['maYCNK'] ?>, '<?= htmlspecialchars($phieu['tenPhieu']) ?>')">
                          👁 Xem
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" style="text-align:center; color: #999;">Không có phiếu nào</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Section: Lập phiếu -->
      <section id="create" class="section">
        <div class="card">
          <div class="card-header">
            <h2>Lập phiếu yêu cầu nhập kho nguyên vật liệu</h2>
          </div>

          <form method="POST" action="index.php?page=luu-yeu-cau-nhap-kho">
            <div class="form-row">
              <div class="form-group">
                <label for="ngayLap" class="required">Ngày lập phiếu</label>
                <input type="date" id="ngayLap" name="ngayLap" required>
              </div>

              <div class="form-group">
                <label for="maKHSX" class="required">Kế hoạch sản xuất</label>
                <select id="maKHSX" name="maKHSX" required>
                  <option value="">-- Chọn kế hoạch --</option>
                  <?php if (!empty($danhSachKHSX)): ?>
                    <?php foreach ($danhSachKHSX as $khsx): ?>
                      <option value="<?= htmlspecialchars($khsx['maKHSX']) ?>">
                        <?= htmlspecialchars($khsx['tenKHSX']) ?> (<?= $khsx['thoiGianBatDau'] ?> → <?= $khsx['thoiGianKetThuc'] ?>)
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="ghiChu">Ghi chú</label>
              <textarea id="ghiChu" name="ghiChu" placeholder="Nhập ghi chú nếu có..."></textarea>
            </div>

            <div class="table-container">
              <table id="tableNVL">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Mã NVL</th>
                    <th>Tên NVL</th>
                    <th>Số lượng</th>
                    <th>Đơn vị</th>
                    <th style="min-width: 200px;">Nhà cung cấp</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="6" style="text-align:center; color:gray;">Vui lòng chọn kế hoạch để hiển thị NVL</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div style="margin-top: 1rem; padding: 1rem; background-color: var(--gray-100); border-radius: var(--radius);">
              <strong>Số lượng NVL được chọn:</strong> <span id="selectedCount">0</span>
            </div>

            <div class="button-group">
              <button type="button" class="btn-secondary" onclick="switchTab('list', event)">Hủy</button>
              <button type="submit" class="btn-primary">Lưu phiếu</button>
            </div>
          </form>
        </div>
      </section>

    </div>
  </main>
</div>

<!-- Modal xem chi tiết phiếu -->
<div class="modal" id="modalChiTiet">
  <div class="modal-overlay" onclick="dongModalChiTiet()"></div>
  <div class="modal-container" style="max-width: 900px;">
    <div class="modal-header">
      <h3 id="modalTitle">Chi tiết phiếu yêu cầu nhập kho</h3>
      <button class="modal-close" onclick="dongModalChiTiet()">✕</button>
    </div>
    <div class="modal-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
        <div>
          <strong>Mã phiếu:</strong> <span id="detailMaPhieu">—</span>
        </div>
        <div>
          <strong>Ngày lập:</strong> <span id="detailNgayLap">—</span>
        </div>
        <div>
          <strong>Người lập:</strong> <span id="detailNguoiLap">—</span>
        </div>
        <div>
          <strong>Trạng thái:</strong> <span id="detailTrangThai">—</span>
        </div>
      </div>

      <h4 style="margin-bottom: 10px; color: #142850;">Danh sách nguyên vật liệu</h4>
      <div class="table-container">
        <table class="detail-table">
          <thead>
            <tr>
              <th style="width: 60px;">STT</th>
              <th>Mã NVL</th>
              <th>Tên NVL</th>
              <th>Số lượng</th>
              <th>Đơn vị</th>
              <th>Nhà cung cấp</th>
            </tr>
          </thead>
          <tbody id="detailTableBody">
            <tr><td colspan="6" style="text-align:center;">Đang tải...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="dongModalChiTiet()">Đóng</button>
    </div>
  </div>
</div>

<?php require_once './app/views/layouts/footer.php'; ?>

<style>
<?php include './public/css/phieu-nhap.css'; ?>

.ncc-select {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.ncc-select:disabled {
  background-color: #f5f5f5;
  cursor: not-allowed;
}

.ncc-select:focus {
  outline: none;
  border-color: #3b7ddd;
  box-shadow: 0 0 0 2px rgba(59, 125, 221, 0.1);
}

/* Date filter input */
.filter-date-input {
  padding: 6px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.filter-date-input:focus {
  outline: none;
  border-color: #3b7ddd;
  box-shadow: 0 0 0 2px rgba(59, 125, 221, 0.1);
}

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.modal.active {
  display: flex;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}

.modal-container {
  position: relative;
  background: white;
  border-radius: 8px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  z-index: 10000;
  width: 90%;
  max-width: 800px;
}

.modal-header {
  padding: 20px 25px;
  border-bottom: 2px solid #e0e0e0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8f9fa;
}

.modal-header h3 {
  margin: 0;
  color: #142850;
  font-size: 20px;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
}

.modal-close:hover {
  background: #e0e0e0;
  color: #333;
}

.modal-body {
  padding: 25px;
}

.modal-footer {
  padding: 15px 25px;
  border-top: 1px solid #e0e0e0;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  background: #f8f9fa;
}

.detail-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.detail-table thead {
  background: #142850;
  color: white;
}

.detail-table th {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
  color: white !important;
  font-weight: 700;
  font-size: 14px;
}

.detail-table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

.detail-table tbody tr:hover {
  background: #f1f6ff;
}

.detail-table tbody tr td:first-child {
  text-align: center;
}
</style>

<script>
// Danh sách nhà cung cấp mẫu
const nhaCungCapList = [
  'Công ty Vải Việt Nam',
  'Công ty Sợi Quốc Tế',
  'Công ty Phụ liệu May Mặc',
  'Công ty Vải Cotton Cao Cấp',
  'Công ty Phụ Kiện Thời Trang'
];

document.addEventListener('DOMContentLoaded', () => {
  const dateInput = document.getElementById('ngayLap');
  dateInput.value = new Date().toISOString().split('T')[0];
});

function switchTab(tab, e) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.getElementById(tab).classList.add('active');
  e.target.classList.add('active');
}

// Cập nhật số lượng NVL được chọn
function updateSelectedCount() {
  const checked = document.querySelectorAll('input[name="nvl[]"]:checked').length;
  document.getElementById('selectedCount').textContent = checked;
}

// Khi chọn kế hoạch sản xuất
document.getElementById('maKHSX').addEventListener('change', async (e) => {
  const maKHSX = e.target.value;
  if (!maKHSX) return;

  // ✅ Bước 1: Kiểm tra kế hoạch đã lập phiếu hay chưa
  try {
    const checkRes = await fetch(`app/controllers/ajax_check_khsx.php?maKHSX=${maKHSX}`);
    const { exists } = await checkRes.json();

    if (exists) {
      alert('⚠️ Kế hoạch sản xuất này đã được lập phiếu yêu cầu nhập kho NVL rồi!');
      e.target.value = ''; // reset chọn
      document.querySelector('#tableNVL tbody').innerHTML = `
        <tr><td colspan="6" style="text-align:center; color:gray;">Vui lòng chọn kế hoạch khác</td></tr>`;
      return;
    }
  } catch (error) {
    console.error('Lỗi kiểm tra kế hoạch:', error);
  }

  // ✅ Bước 2: Nếu chưa tồn tại, tiếp tục load danh sách NVL
  try {
   const res = await fetch(`app/controllers/ajax_get_nvl.php?maKHSX=${maKHSX}`);
    const data = await res.json();
    const tbody = document.querySelector('#tableNVL tbody');

    if (!Array.isArray(data) || data.length === 0) {
      tbody.innerHTML = `
        <tr><td colspan="6" style="text-align:center; color:gray;">
          Không có nguyên vật liệu cho kế hoạch này
        </td></tr>`;
      return;
    }

    // Tạo options cho nhà cung cấp
    const nccOptions = nhaCungCapList.map(ncc => `<option value="${ncc}">${ncc}</option>`).join('');

    tbody.innerHTML = data.map((item, index) => {
      return `
      <tr>
        <td><input type="checkbox" name="nvl[]" value="${item.maNVL}"></td>
        <td>${item.maNVL}</td>
        <td>${item.tenNVL}</td>
        <td>${item.soLuongCan}</td>
        <td>${item.donViTinh ?? '-'}</td>
        <td>
          <select name="nhaCungCap_${item.maNVL}" class="ncc-select" disabled required>
            <option value="">-- Chọn nhà cung cấp --</option>
            ${nccOptions}
          </select>
        </td>
      </tr>
    `}).join('');
    
    updateSelectedCount();
  } catch (error) {
    console.error('Lỗi tải NVL:', error);
  }
});

// Cập nhật khi checkbox thay đổi
document.addEventListener('change', (e) => {
  if (e.target.name === 'nvl[]') {
    const row = e.target.closest('tr');
    const nccSelect = row.querySelector('.ncc-select');
    
    if (e.target.checked) {
      nccSelect.disabled = false;
      nccSelect.required = true;
    } else {
      nccSelect.disabled = true;
      nccSelect.required = false;
      nccSelect.value = '';
    }
    
    updateSelectedCount();
  }

  if (e.target.id === 'selectAll') {
    const all = document.querySelectorAll('input[name="nvl[]"]');
    all.forEach(cb => {
      cb.checked = e.target.checked;
      const row = cb.closest('tr');
      const nccSelect = row.querySelector('.ncc-select');
      if (nccSelect) {
        nccSelect.disabled = !e.target.checked;
        nccSelect.required = e.target.checked;
      }
    });
    updateSelectedCount();
  }
});

// ✅ Kiểm tra trước khi submit form
document.querySelector('form').addEventListener('submit', function(e) {
  const checkedBoxes = document.querySelectorAll('input[name="nvl[]"]:checked');
  
  if (checkedBoxes.length === 0) {
    e.preventDefault();
    alert("⚠️ Vui lòng chọn ít nhất một nguyên vật liệu cần nhập kho!");
    return false;
  }

  // Kiểm tra nhà cung cấp đã được chọn chưa
  let missingNCC = false;
  checkedBoxes.forEach(checkbox => {
    const row = checkbox.closest('tr');
    const nccSelect = row.querySelector('.ncc-select');
    if (nccSelect && !nccSelect.value) {
      missingNCC = true;
      nccSelect.style.border = '2px solid red';
    }
  });

  if (missingNCC) {
    e.preventDefault();
    alert("⚠️ Vui lòng chọn nhà cung cấp cho tất cả các NVL được chọn!");
    return false;
  }
});

// ✅ Lọc theo ngày
function filterByDate() {
  const fromDate = document.getElementById('filterFromDate').value;
  const toDate = document.getElementById('filterToDate').value;
  const rows = document.querySelectorAll('#tablePhieu tbody tr');

  let visibleCount = 0;

  rows.forEach(row => {
    const ngayLap = row.getAttribute('data-ngaylap');
    
    if (!ngayLap) {
      row.style.display = '';
      return;
    }

    let showRow = true;

    if (fromDate && ngayLap < fromDate) {
      showRow = false;
    }

    if (toDate && ngayLap > toDate) {
      showRow = false;
    }

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  });

  if (visibleCount === 0) {
    const tbody = document.querySelector('#tablePhieu tbody');
    if (!document.getElementById('noResultRow')) {
      tbody.innerHTML = '<tr id="noResultRow"><td colspan="6" style="text-align:center; color: #999;">Không tìm thấy phiếu trong khoảng thời gian này</td></tr>';
    }
  } else {
    const noResultRow = document.getElementById('noResultRow');
    if (noResultRow) noResultRow.remove();
  }
}

// ✅ Đặt lại bộ lọc
function resetFilter() {
  document.getElementById('filterFromDate').value = '';
  document.getElementById('filterToDate').value = '';
  
  const rows = document.querySelectorAll('#tablePhieu tbody tr');
  rows.forEach(row => row.style.display = '');
  
  const noResultRow = document.getElementById('noResultRow');
  if (noResultRow) noResultRow.remove();
}

// ✅ Xem chi tiết phiếu
async function xemChiTietPhieu(maYCNK, tenPhieu) {
  const modal = document.getElementById('modalChiTiet');
  modal.classList.add('active');
  
  document.getElementById('modalTitle').textContent = tenPhieu;
  document.getElementById('detailMaPhieu').textContent = 'YCNK-' + maYCNK;
  document.getElementById('detailTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center;">⏳ Đang tải dữ liệu...</td></tr>';

  try {
    const res = await fetch(`app/controllers/ajax_get_phieu_detail.php?maYCNK=${maYCNK}`);
    const data = await res.json();

    if (!data || data.length === 0) {
      document.getElementById('detailTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; color: #999;">Không có dữ liệu</td></tr>';
      return;
    }

    // Lấy thông tin header từ dòng đầu tiên
    const firstRow = data[0];
    document.getElementById('detailNgayLap').textContent = firstRow.ngayLap ? new Date(firstRow.ngayLap).toLocaleDateString('vi-VN') : '—';
    document.getElementById('detailNguoiLap').textContent = firstRow.tenNguoiLap || '—';
    
    let statusHTML = '';
    switch (firstRow.trangThai) {
      case 'Đã duyệt':
        statusHTML = '<span class="status-badge status-approved">✓ Đã duyệt</span>';
        break;
      case 'Đã nhập kho':
        statusHTML = '<span class="status-badge status-success">✓ Đã nhập kho</span>';
        break;
      case 'Từ chối':
        statusHTML = '<span class="status-badge status-rejected">✕ Từ chối</span>';
        break;
      default:
        statusHTML = '<span class="status-badge status-pending">⏳ Chờ duyệt</span>';
    }
    document.getElementById('detailTrangThai').innerHTML = statusHTML;

    // Hiển thị danh sách NVL
    const tbody = document.getElementById('detailTableBody');
    tbody.innerHTML = data.map((item, index) => `
      <tr>
        <td>${index + 1}</td>
        <td>${item.maNVL || '—'}</td>
        <td>${item.tenNVL || '—'}</td>
        <td style="text-align: right;">${item.soLuong || 0}</td>
        <td>${item.donViTinh || '—'}</td>
        <td>${item.nhaCungCap || '—'}</td>
      </tr>
    `).join('');

  } catch (error) {
    console.error('Lỗi tải chi tiết phiếu:', error);
    document.getElementById('detailTableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; color: red;">❌ Lỗi tải dữ liệu</td></tr>';
  }
}

// ✅ Đóng modal
function dongModalChiTiet() {
  document.getElementById('modalChiTiet').classList.remove('active');
}

// Đóng modal khi nhấn ESC
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    dongModalChiTiet();
  }
});

</script>
