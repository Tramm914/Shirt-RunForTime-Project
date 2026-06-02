<?php
// Initialize variables with default values to prevent undefined variable errors
$lichLamViec = $lichLamViec ?? [];
$tomTat = $tomTat ?? ['tongSoCa' => 0, 'caGanNhat' => null];
$tuanHienTai = $tuanHienTai ?? [
    'batDau' => date('d/m/Y', strtotime('monday this week')),
    'ketThuc' => date('d/m/Y', strtotime('sunday this week'))
];

// Initialize next week variables
$lichLamViecTuanToi = $lichLamViecTuanToi ?? [];
$tomTatTuanToi = $tomTatTuanToi ?? ['tongSoCa' => 0, 'caGanNhat' => null];
$tuanTiepTheo = $tuanTiepTheo ?? [
    'batDau' => date('d/m/Y', strtotime('monday next week')),
    'ketThuc' => date('d/m/Y', strtotime('sunday next week'))
];

require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<style>
    .main-content {
        background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container-lich-lam-viec {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
    }

    .header-lich {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 2px solid #000;
    }

    .header-lich h1 {
        color: #000;
        font-size: 26px;
        margin-bottom: 5px;
    }

    .header-lich p {
        color: #333;
        font-size: 14px;
    }

    .schedule-section {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        border: 2px solid #000;
        text-align: center;
        margin-bottom: 20px;
    }

    .schedule-section h2 {
        color: #000;
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
    }

    .schedule-summary {
        font-size: 16px;
        margin-bottom: 20px;
        color: #333;
    }

    .btn-view {
        padding: 12px 24px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid #000;
        background: #fff;
        color: #000;
        transition: all 0.3s;
    }

    .btn-view:hover {
        background: #000;
        color: #fff;
    }

    .details-section {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        border: 2px solid #000;
        display: none;
    }

    .details-section.show {
        display: block;
    }

    .details-section h2 {
        color: #000;
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .schedule-table th,
    .schedule-table td {
        padding: 12px;
        border: 1px solid #000;
        font-size: 14px;
        text-align: left;
    }

    .schedule-table th {
        background: #f2f2f2;
        font-weight: 600;
    }

    .schedule-table tr:nth-child(even) {
        background: #f9f9f9;
    }

    .btn-close {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid #000;
        background: #fff;
        color: #000;
        transition: all 0.3s;
        margin-top: 20px;
    }

    .btn-close:hover {
        background: #000;
        color: #fff;
    }

    /* Tabs styling */
    .week-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
    }

    .week-tab {
        padding: 12px 24px;
        border: 2px solid #000;
        border-bottom: none;
        background: #fff;
        color: #000;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 8px 8px 0 0;
        position: relative;
        top: 2px;
    }

    .week-tab:hover {
        background: #f5f5f5;
    }

    .week-tab.active {
        background: #000;
        color: #fff;
    }

    .week-content {
        display: none;
    }

    .week-content.active {
        display: block;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .schedule-table {
            font-size: 12px;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 8px;
        }
    }
</style>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="container-lich-lam-viec">
            <div class="header-lich">
                <h1 style="text-align:center;">XEM LỊCH LÀM VIỆC</h1>
            </div>

            <!-- Week Tabs -->
            <div class="week-tabs">
                <button class="week-tab active" onclick="switchWeek('current')">Tuần Này</button>
                <button class="week-tab" onclick="switchWeek('next')">Tuần Tới</button>
            </div>

            <!-- Current Week Content -->
            <div class="week-content active" id="currentWeekContent">
                <div class="schedule-section">
                    <h2>Tóm Tắt Lịch Làm Việc - Tuần Này</h2>
                    <div class="schedule-summary">
                        <p>Bạn có <strong><?= $tomTat['tongSoCa'] ?? 0 ?> ca làm việc</strong> trong tuần này.</p>
                        <?php if (!empty($tomTat['caGanNhat'])): ?>
                            <p>Ca gần nhất: <strong>
                                <?= htmlspecialchars($tomTat['caGanNhat']['thu']) ?>, 
                                Ngày <?= htmlspecialchars($tomTat['caGanNhat']['ngay']) ?>, 
                                <?= htmlspecialchars($tomTat['caGanNhat']['caLam']) ?> 
                                (<?= htmlspecialchars($tomTat['caGanNhat']['thoiGian']) ?>)
                            </strong></p>
                        <?php else: ?>
                            <p>Ca gần nhất: <strong>Chưa có ca làm việc</strong></p>
                        <?php endif; ?>
                    </div>
                    <button class="btn-view" onclick="viewSchedule('current')">Xem Thông Tin</button>
                </div>

                <div class="details-section" id="detailsSectionCurrent">
                    <h2>Ca Làm Việc Chi Tiết - Tuần Từ <?= htmlspecialchars($tuanHienTai['batDau']) ?> đến <?= htmlspecialchars($tuanHienTai['ketThuc']) ?></h2>
                    
                    <?php if (!empty($lichLamViec)): ?>
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Thứ</th>
                                    <th>Ca Làm</th>
                                    <th>Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lichLamViec as $ca): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ca['ngay']) ?></td>
                                        <td><?= htmlspecialchars($ca['thu']) ?></td>
                                        <td><?= htmlspecialchars($ca['caLam']) ?></td>
                                        <td><?= htmlspecialchars($ca['thoiGian']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; padding: 20px; color: #666;">
                            Không có ca làm việc nào trong tuần này.
                        </p>
                    <?php endif; ?>
                    
                    <div style="text-align: center;">
                        <button class="btn-close" onclick="closeSchedule('current')">Đóng</button>
                    </div>
                </div>
            </div>

            <!-- Next Week Content -->
            <div class="week-content" id="nextWeekContent">
                <div class="schedule-section">
                    <h2>Tóm Tắt Lịch Làm Việc - Tuần Tới</h2>
                    <div class="schedule-summary">
                        <p>Bạn có <strong><?= $tomTatTuanToi['tongSoCa'] ?? 0 ?> ca làm việc</strong> trong tuần tới.</p>
                        <?php if (!empty($tomTatTuanToi['caGanNhat'])): ?>
                            <p>Ca gần nhất: <strong>
                                <?= htmlspecialchars($tomTatTuanToi['caGanNhat']['thu']) ?>, 
                                Ngày <?= htmlspecialchars($tomTatTuanToi['caGanNhat']['ngay']) ?>, 
                                <?= htmlspecialchars($tomTatTuanToi['caGanNhat']['caLam']) ?> 
                                (<?= htmlspecialchars($tomTatTuanToi['caGanNhat']['thoiGian']) ?>)
                            </strong></p>
                        <?php else: ?>
                            <p>Ca gần nhất: <strong>Chưa có ca làm việc</strong></p>
                        <?php endif; ?>
                    </div>
                    <button class="btn-view" onclick="viewSchedule('next')">Xem Thông Tin</button>
                </div>

                <div class="details-section" id="detailsSectionNext">
                    <h2>Ca Làm Việc Chi Tiết - Tuần Từ <?= htmlspecialchars($tuanTiepTheo['batDau']) ?> đến <?= htmlspecialchars($tuanTiepTheo['ketThuc']) ?></h2>
                    
                    <?php if (!empty($lichLamViecTuanToi)): ?>
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Thứ</th>
                                    <th>Ca Làm</th>
                                    <th>Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lichLamViecTuanToi as $ca): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ca['ngay']) ?></td>
                                        <td><?= htmlspecialchars($ca['thu']) ?></td>
                                        <td><?= htmlspecialchars($ca['caLam']) ?></td>
                                        <td><?= htmlspecialchars($ca['thoiGian']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; padding: 20px; color: #666;">
                            Không có ca làm việc nào trong tuần tới.
                        </p>
                    <?php endif; ?>
                    
                    <div style="text-align: center;">
                        <button class="btn-close" onclick="closeSchedule('next')">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>

<script>
    function switchWeek(week) {
        // Hide all week contents
        document.querySelectorAll('.week-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.week-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected week content
        if (week === 'current') {
            document.getElementById('currentWeekContent').classList.add('active');
            document.querySelectorAll('.week-tab')[0].classList.add('active');
        } else {
            document.getElementById('nextWeekContent').classList.add('active');
            document.querySelectorAll('.week-tab')[1].classList.add('active');
        }
        
        // Close any open detail sections
        document.querySelectorAll('.details-section').forEach(section => {
            section.classList.remove('show');
        });
    }

    function viewSchedule(week) {
        const sectionId = week === 'current' ? 'detailsSectionCurrent' : 'detailsSectionNext';
        document.getElementById(sectionId).classList.add('show');
    }

    function closeSchedule(week) {
        const sectionId = week === 'current' ? 'detailsSectionCurrent' : 'detailsSectionNext';
        document.getElementById(sectionId).classList.remove('show');
    }
</script>

