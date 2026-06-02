<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8"/>
  <title>Phê Duyệt Phiếu Yêu Cầu Nhập Kho NVL</title>
  <link rel="stylesheet" href="public/css/style.css"/>
  <style>
    body {
      background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat fixed;
      background-color: #f5f5f5;
    }

    .container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 8px;
      padding: 20px;
      margin: 20px auto;
      max-width: 1200px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .status-pending { color: #FF9800; font-weight: bold; }
    .status-approved { color: #4CAF50; font-weight: bold; }
    .status-rejected { color: #F44336; font-weight: bold; }
    .action-btns button { margin: 0 5px; }
  </style>
</head>
<body>
  <?php require_once 'app/views/navbar.php'; ?>

  <div class="container">
    <h2>📋 Phê Duyệt Phiếu Yêu Cầu Nhập Kho Nguyên Vật Liệu</h2>
    
    <?php if (empty($pendingRequests)): ?>
      <p style="color: #999; font-style: italic;">Không có phiếu nào đang chờ duyệt.</p>
    <?php else: ?>
      <table border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <th>Mã Phiếu</th>
            <th>Tên Phiếu</th>
            <th>Ngày Lập</th>
            <th>Người Lập</th>
            <th>Nhà Cung Cấp</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pendingRequests as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['maYCNK']) ?></td>
              <td><?= htmlspecialchars($row['tenPhieu']) ?></td>
              <td><?= htmlspecialchars($row['ngayLap']) ?></td>
              <td><?= htmlspecialchars($row['tenNguoiLap']) ?></td>
              <td><?= htmlspecialchars($row['nhaCungCap'] ?? 'N/A') ?></td>
              <td class="status-pending"><?= htmlspecialchars($row['trangThai']) ?></td>
              <td class="action-btns">
                <button onclick="viewDetails(<?= $row['maYCNK'] ?>)">👁️ Xem</button>
                <button onclick="approveRequest(<?= $row['maYCNK'] ?>)" style="background: #4CAF50; color: white;">✅ Duyệt</button>
                <button onclick="rejectRequest(<?= $row['maYCNK'] ?>)" style="background: #F44336; color: white;">❌ Từ chối</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Modal Xem Chi Tiết -->
  <div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:80%; max-height:80%; overflow:auto; margin:50px auto; padding:20px; border-radius:8px;">
      <h3>Chi Tiết Phiếu Yêu Cầu Nhập Kho</h3>
      <div id="detailContent"></div>
      <button onclick="closeModal()" style="margin-top:15px;">Đóng</button>
    </div>
  </div>

  <!-- Modal Từ Chối -->
  <div id="rejectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:50%; margin:100px auto; padding:20px; border-radius:8px;">
      <h3>Lý do từ chối</h3>
      <textarea id="lyDoTuChoi" rows="4" style="width:100%; padding:8px;" placeholder="Nhập lý do từ chối..."></textarea>
      <input type="hidden" id="rejectMaYCNK" value=""/>
      <div style="margin-top:15px;">
        <button onclick="submitReject()" style="background:#F44336; color:white;">Xác nhận từ chối</button>
        <button onclick="closeRejectModal()">Hủy</button>
      </div>
    </div>
  </div>

  <script>
    function viewDetails(maYCNK) {
      fetch(`index.php?page=chi-tiet-yc-nhap-kho&maYCNK=${maYCNK}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            let html = '<table border="1" cellpadding="8" cellspacing="0" style="width:100%;">';
            html += '<tr><th>Mã NVL</th><th>Tên NVL</th><th>Số Lượng</th><th>Đơn Vị</th><th>Nhà Cung Cấp</th></tr>';
            data.data.forEach(item => {
              html += `<tr>
                <td>${item.maNVL}</td>
                <td>${item.tenNVL}</td>
                <td>${item.soLuong}</td>
                <td>${item.donViTinh}</td>
                <td>${item.nhaCungCap || ''}</td>
              </tr>`;
            });
            html += '</table>';
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').style.display = 'block';
          } else {
            alert('Không tải được chi tiết phiếu');
          }
        });
    }

    function closeModal() {
      document.getElementById('detailModal').style.display = 'none';
    }

    function approveRequest(maYCNK) {
      if (!confirm('Xác nhận phê duyệt phiếu này?')) return;
      
      const formData = new FormData();
      formData.append('maYCNK', maYCNK);

      fetch('index.php?page=duyet-yc-nhap-kho', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) location.reload();
      });
    }

    function rejectRequest(maYCNK) {
      document.getElementById('rejectMaYCNK').value = maYCNK;
      document.getElementById('rejectModal').style.display = 'block';
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').style.display = 'none';
      document.getElementById('lyDoTuChoi').value = '';
    }

    function submitReject() {
      const maYCNK = document.getElementById('rejectMaYCNK').value;
      const lyDo = document.getElementById('lyDoTuChoi').value.trim();

      if (!lyDo) {
        alert('Vui lòng nhập lý do từ chối');
        return;
      }

      const formData = new FormData();
      formData.append('maYCNK', maYCNK);
      formData.append('lyDoTuChoi', lyDo);

      fetch('index.php?page=tu-choi-yc-nhap-kho', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          closeRejectModal();
          location.reload();
        }
      });
    }
  </script>
</body>
</html>
