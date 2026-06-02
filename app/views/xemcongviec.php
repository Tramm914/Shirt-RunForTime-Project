<?php require 'app/views/layouts/header.php'; ?>
<?php require 'app/views/layouts/nav.php'; ?>

<div class="main-layout-wrapper">
<?php require 'app/views/layouts/sidebar.php'; ?>

<main class="main-content">

<style>
    .main-content {
        background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }
    .plan-container {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        margin: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-align: center;
    }

    .plan-container h2 {
        font-size: 24px;
        color: #0d1a44;
        font-weight: 700;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    table.plan-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    table.plan-table th, 
    table.plan-table td {
        padding: 12px 10px;
        text-align: center;
        border-bottom: 1px solid #e3e6f0;
    }

    table.plan-table th {
        background-color: #eef3ff;
        font-weight: 600;
        color: #1f3c88;
    }

    table.plan-table tr:hover {
        background-color: #f7f9ff;
    }

    .btn-view {
        padding: 6px 12px;
        border-radius: 6px;
        background: #1f75fe;
        color: #fff !important;
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
    }

    .btn-view:hover {
        background: #0b5ed7;
    }

    .btn-view i {
        font-size: 15px;
    }
</style>

<div class="plan-container">
    <h2 class="main-title" style="text-align: center; font-size: 1.5em; margin-bottom: 30px; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
                DANH SÁCH KẾ HOẠCH SẢN XUẤT
            </h2>

    <table class="plan-table">
        <thead>
            <tr>
                <th>Mã KHXS</th>
                <th>Tên kế hoạch</th>
                <th>Thời gian bắt đầu</th>
                <th>Thời gian kết thúc</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plans as $p): ?>
            <tr>
                <td><?= $p['maKHSX'] ?></td>
                <td><?= $p['tenKHSX'] ?></td>
                <td><?= $p['thoiGianBatDau'] ?></td>
                <td><?= $p['thoiGianKetThuc'] ?></td>
                <td>
                    <a class="btn-view" 
                        href="index.php?page=chitietkhxs&id=<?= $p['maKHSX'] ?>">
                        <i class="fa fa-eye"></i> Xem 
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</main>

</div>

<?php require 'app/views/layouts/footer.php'; ?>