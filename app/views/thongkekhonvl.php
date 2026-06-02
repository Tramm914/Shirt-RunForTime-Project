<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <style>
            .main-content {
                background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .report-container {
                background-color: #fff;
                border-radius: 12px;
                padding: 40px 50px;
                margin: 20px auto;
                max-width: 980px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            }

            h2 {
                text-align: center;
                color: #0d1a44;
                margin-bottom: 20px;
                font-weight: 700;
                font-size: 24px;
            }

            /* ====== FILTER FORM ====== */
            .filter-section {
                background: #f8fafc;
                border-radius: 12px;
                padding: 25px;
                margin-bottom: 30px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            .filter-header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 2px solid #e2e8f0;
            }

            .filter-header h3 {
                font-size: 18px;
                font-weight: 600;
                color: #0d1a44;
                margin: 0;
                display: inline-block;
                padding: 0 20px;
                background: white;
                position: relative;
                top: -1px;
            }

            .filter-row-top {
    display: flex;
    gap: 20px;
    align-items: flex-start; /* ✅ SỬA DÒNG NÀY */
}

            .filter-item {
                display: flex;
                flex-direction: column;
                gap: 6px;
                min-width: 180px;
                flex: 1;
            }

            .filter-label {
                font-size: 13px;
                font-weight: 600;
                color: #0d1a44;
                margin: 0;
            }

            .filter-input {
                width: 100%;
                padding: 9px 12px;
                border-radius: 8px;
                border: 1px solid #d1d5db;
                font-size: 14px;
                background-color: #fff;
                transition: all 0.2s ease;
                box-sizing: border-box;
            }

            .filter-input:focus {
                outline: none;
                border-color: #5a8dee;
                box-shadow: 0 0 0 2px rgba(90,141,238,0.15);
            }

            .filter-input::placeholder {
                color: #9ca3af;
                font-style: italic;
            }

            .chk-all-wrapper {
    margin-top: 6px;
}

.chk-all-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 6px;
    border-radius: 6px;
}

.chk-all-item label {
    font-size: 13px;
    font-weight: 500;
    color: #0d1a44;
}

            /* ====== NVL SUGGEST BOX ====== */
            #suggestionsNVL {
                position: absolute;
                z-index: 1000;
                width: 100%;
                background: white;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-height: 180px;
                overflow-y: auto;
                display: none;
            }

            #suggestionsNVL li {
                padding: 8px 12px;
                font-size: 14px;
                cursor: pointer;
                transition: background-color 0.15s;
            }

            #suggestionsNVL li:hover {
                background-color: #f1f5fb;
            }

            /* ====== BUTTONS ====== */
            .btn {
                padding: 10px 22px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                border: none;
                transition: all 0.2s;
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .btn-primary {
                background-color: #5a8dee;
                color: white;
            }

            .btn-primary:hover {
                background-color: #4076db;
                transform: translateY(-1px);
            }

            .btn-success {
                background-color: #cfe9ff;
                color: #0d1a44;
            }

            .btn-success:hover {
                background-color: #b7dcff;
                transform: translateY(-1px);
            }

            .btn-icon {
                font-size: 16px;
            }

            /* ====== TABLE ====== */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }

            th, td {
                border: 1px solid #e2e8f0;
                padding: 12px;
                text-align: center;
            }

            th {
                background-color: #f1f5fb;
                font-weight: 600;
                color: #0d1a44;
                font-size: 14px;
            }

            tbody tr:hover {
                background-color: #f8fafc;
            }

            /* ====== CHART ====== */
            .chart-container {
                margin-top: 30px;
                padding: 20px;
                background: #f8fafc;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }

            .chart-title {
                text-align: center;
                font-size: 18px;
                font-weight: 600;
                color: #0d1a44;
                margin-bottom: 15px;
            }

            #chartKhoNVL {
                max-height: 300px;
            }

            /* ====== RESPONSIVE ====== */
            @media (max-width: 900px) {
                .filter-row-top {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 15px;
                }

                .chk-all-wrapper {
                    margin-top: 5px;
                    justify-content: flex-start;
                }

                .report-container {
                    padding: 25px 20px;
                    margin: 15px;
                }

                .filter-section {
                    padding: 20px 15px;
                }
            }

           @media (max-width: 900px) {
    .filter-row-top {
        grid-template-columns: 1fr;
    }
}

        </style>

        <div class="report-container">

            <!-- Form lọc -->
            <div class="filter-section">
                <div class="filter-header">
                    <h3>Thống kê kho nguyên vật liệu</h3>
                </div>

                <form id="formThongKe">
                    <div class="filter-row-top">
                        <div class="filter-item">
                            <label class="filter-label">Thời gian bắt đầu:</label>
                            <input type="date" id="start_date" name="start_date" class="filter-input" required>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">Thời gian kết thúc:</label>
                            <input type="date" id="end_date" name="end_date" class="filter-input" required>
                        </div>

                        <div class="filter-item" style="position: relative;">
                            <label class="filter-label">Tên NVL:</label>
                            <input type="text" name="tenNVL" id="inputTenNVL" class="filter-input" placeholder="Nhập tên NVL...">
                            <ul id="suggestionsNVL"></ul>

                            <!-- Checkbox nằm ngay dưới ô tên NVL -->
                            <div class="chk-all-wrapper">
                                <div class="chk-all-item">
                                    <input type="checkbox" id="chkAllNVL" name="all_nvl" value="1">
                                    <label for="chkAllNVL">Tất cả NVL</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align:center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">📊 Thống kê</button>
                    </div>
                </form>
            </div>

            <!-- Bảng kết quả -->
            <table id="tableKho">
                <thead>
                    <tr>
                        <th>Mã NVL</th>
                        <th>Tên NVL</th>
                        <th>Đơn vị tính</th>
                        <th>Tổng nhập</th>
                        <th>Tổng xuất</th>
                        <th>Tồn kho</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" style="text-align:center;color:gray;">Không có dữ liệu để hiển thị</td>
                    </tr>
                </tbody>
            </table>

            <!-- Nút xuất CSV -->
            <div style="text-align:center; margin-top:25px;">
                <form id="formExport" method="GET" action="index.php">
                    <input type="hidden" name="page" value="xuatcsv-thongkenvl">
                    <input type="hidden" id="csv_all_nvl" name="all_nvl">
                    <input type="hidden" id="csv_start_date" name="start_date">
                    <input type="hidden" id="csv_end_date" name="end_date">
                    <input type="hidden" id="csv_tenNVL" name="tenNVL">
                    <input type="hidden" id="csv_loai" name="loai">
                    <button type="submit" class="btn btn-success"><span class="btn-icon">📄</span> Xuất CSV</button>
                </form>
            </div>

            <!-- Biểu đồ -->
            <div class="chart-container">
                <div class="chart-title">Biểu đồ thống kê nhập - xuất NVL</div>
                <canvas id="chartKhoNVL" height="100"></canvas>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartInstance = null;

const formThongKe = document.getElementById('formThongKe');
const inputNVL = document.getElementById('inputTenNVL');
const suggestBox = document.getElementById('suggestionsNVL');
const chkAllNVL = document.getElementById('chkAllNVL');

formThongKe.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    if (chkAllNVL.checked) {
        formData.set('tenNVL', '');
    }

    const res = await fetch('index.php?page=thongke-khonvl', {
        method: 'POST',
        body: formData
    });

    let data = [];
    try {
        data = await res.json();
    } catch (err) {
        console.error('Lỗi parse JSON:', err);
        document.querySelector('#tableKho tbody').innerHTML =
            `<tr><td colspan="6" style="color:red;text-align:center;">Lỗi server</td></tr>`;
        return;
    }

    const tbody = document.querySelector('#tableKho tbody');
    tbody.innerHTML = '';

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.maNVL}</td>
                <td>${row.tenNVL}</td>
                <td>${row.donViTinh}</td>
                <td>${row.tongNhap}</td>
                <td>${row.tongXuat}</td>
                <td>${row.tonKho}</td>
            `;
            tbody.appendChild(tr);
        });
        renderChart(data);
    } else {
        tbody.innerHTML =
            `<tr><td colspan="6" style="color:gray;text-align:center;">Không có dữ liệu</td></tr>`;
    }

    // Cập nhật dữ liệu cho form xuất CSV
    let finalTenNVL = chkAllNVL.checked ? '' : inputNVL.value.trim();
    document.getElementById('csv_start_date').value = document.getElementById('start_date').value;
    document.getElementById('csv_end_date').value = document.getElementById('end_date').value;
    document.getElementById('csv_tenNVL').value = finalTenNVL;
    document.getElementById('csv_all_nvl').value = chkAllNVL.checked ? '1' : '';
    document.getElementById('csv_loai').value = '';
});

/* ========== CHECKBOX "TẤT CẢ NVL" ========== */
chkAllNVL.addEventListener('change', function() {
    if (this.checked) {
        inputNVL.value = '';
        inputNVL.disabled = true;
        inputNVL.style.background = '#f3f4f6';
    } else {
        inputNVL.disabled = false;
        inputNVL.style.background = '#fff';
    }
});

/* ========== GỢI Ý TÊN NVL ========== */
inputNVL.addEventListener('input', async function() {
    const keyword = this.value.trim();
    if (keyword.length < 1) {
        suggestBox.style.display = 'none';
        return;
    }

    const res = await fetch(`index.php?page=search&type=nvl&keyword=${encodeURIComponent(keyword)}`);
    const data = await res.json();

    suggestBox.innerHTML = '';
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.label;
            li.style.cursor = 'pointer';
            li.onclick = () => {
                inputNVL.value = item.label;
                suggestBox.style.display = 'none';
            };
            suggestBox.appendChild(li);
        });
        suggestBox.style.display = 'block';
    } else {
        suggestBox.style.display = 'none';
    }
});

/* ========== BIỂU ĐỒ ========== */
function renderChart(data) {
    const labels = data.map(i => i.tenNVL);
    const tongNhap = data.map(i => Number(i.tongNhap));
    const tongXuat = data.map(i => Number(i.tongXuat));
    const tonKho   = data.map(i => Number(i.tonKho));

    const ctx = document.getElementById('chartKhoNVL').getContext('2d');
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Tổng nhập', data: tongNhap, backgroundColor: 'rgba(90, 141, 238, 0.6)' },
                { label: 'Tổng xuất', data: tongXuat, backgroundColor: 'rgba(255, 152, 0, 0.6)' },
                { label: 'Tồn kho', data: tonKho, backgroundColor: 'rgba(76, 175, 80, 0.6)' }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString()
                    }
                }
            }
        }
    });
}

/* Ẩn gợi ý khi click ngoài */
document.addEventListener('click', (e) => {
    if (!suggestBox.contains(e.target) && e.target !== inputNVL) {
        suggestBox.style.display = 'none';
    }
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>