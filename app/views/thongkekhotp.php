<?php require 'app/views/layouts/header.php'; ?>
<?php require 'app/views/layouts/nav.php'; ?>

<style>
.main-content { 
    padding:20px;
    background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
}
.title { font-size:22px; font-weight:700; color:#0d1a44; margin-bottom:15px; }

/* FILTER */
.filter-box {
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}
.filter-box label {
    font-size:14px;
    font-weight:600;
    color:#555;
    align-self:center;
}
.filter-box input {
    padding:7px 12px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* BUTTON */
.btn { padding:8px 16px; border:none; border-radius:6px; color:#fff; cursor:pointer; }
.btn-blue { background:#007bff; }
.btn-green { background:#28a745; text-decoration: none;}

/* TABLE */
.table-wrapper {
    background:#fff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
table { width:100%; border-collapse:collapse; margin-top:10px; }
th,td {
    padding:10px;
    border:1px solid #e1e4eb;
    text-align:center;
}
th { background:#eef3ff; font-weight:600; }

.top-actions { text-align:right; margin-bottom:10px; margin-left: auto;}

/* CHART */
.chart-box {
    margin-top:30px;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

</style>

<div class="main-layout-wrapper">
<?php require 'app/views/layouts/sidebar.php'; ?>

<main class="main-content">

    <h2 class="main-title" style="text-align: center; font-size: 1.5em; margin-bottom: 30px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
                THỐNG KÊ KHO THÀNH PHẨM
            </h2>

    <!-- FILTER -->
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="thongke">

        <div class="filter-box">
            <label  style="color:#ffff;">Tên sản phẩm</label>
            <input type="text"
                   name="search"
                   placeholder="Nhập tên sản phẩm..."
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

            <label  style="color:#ffff;">Từ ngày</label>
            <input type="date"
                   name="from"
                   value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">

            <label  style="color:#ffff;">Đến ngày</label>
            <input type="date"
                   name="to"
                   value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">

            <button class="btn btn-blue">Lọc</button>
            <!-- EXPORT CSV -->
        <div class="top-actions">
            <a class="btn btn-green"
   href="index.php?page=export_thongke&search=<?= urlencode($_GET['search'] ?? '') ?>&from=<?= urlencode($_GET['from'] ?? '') ?>&to=<?= urlencode($_GET['to'] ?? '') ?>">
   Xuất CSV
</a>

        </div>
        </div>
        
    </form>

    <div class="table-wrapper">

        <!-- TABLE -->
        <table>
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Tên SP</th>
                    <th>Đơn vị</th>
                    <th>Tổng nhập</th>
                    <th>Tổng xuất</th>
                    <th>Tồn kho</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['maSanPham'] ?></td>
                    <td><?= $row['tenSanPham'] ?></td>
                    <td><?= $row['donVi'] ?></td>
                    <td><?= $row['tongNhap'] ?></td>
                    <td><?= $row['tongXuat'] ?></td>
                    <td><?= $row['tonKho'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có dữ liệu</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- CHART -->
    <div class="chart-box">
        <canvas id="chartKhoTP" height="120"></canvas>
    </div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels   = <?= json_encode(array_column($data ?? [], 'tenSanPham')) ?>;
const tongNhap = <?= json_encode(array_column($data ?? [], 'tongNhap')) ?>;
const tongXuat = <?= json_encode(array_column($data ?? [], 'tongXuat')) ?>;
const tonKho   = <?= json_encode(array_column($data ?? [], 'tonKho')) ?>;

if (labels.length > 0) {
    new Chart(document.getElementById('chartKhoTP'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Tổng nhập', data: tongNhap, backgroundColor:'#4CAF50' },
                { label: 'Tổng xuất', data: tongXuat, backgroundColor:'#F44336' },
                { label: 'Tồn kho', data: tonKho, backgroundColor:'#2196F3' }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}
</script>

<?php require 'app/views/layouts/footer.php'; ?>
