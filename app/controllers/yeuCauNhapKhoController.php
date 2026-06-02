<?php
require_once './app/models/KeHoachSanXuatModel.php';
require_once './app/models/PhieuYeuCauNhapKhoModel.php';

class YeuCauNhapKhoController {

    // ============================
    // 1️⃣ DANH SÁCH + FORM LẬP PHIẾU
    // ============================
    public function index() {
        // 🔹 Lấy danh sách kế hoạch sản xuất đã duyệt
       $phieuModel = new PhieuYeuCauNhapKhoModel();
$danhSachKHSX = $phieuModel->getAllPlansForNhapKho();


        // 🔹 Lấy danh sách phiếu yêu cầu nhập kho NVL
        $phieuModel = new PhieuYeuCauNhapKhoModel();
        $danhSachPhieu = $phieuModel->getAll();

        // 🔹 Gọi view
        require __DIR__ . '/../views/taoYCNhapKhoNVL.php';
    }

    // ============================
    // 2️⃣ XEM CHI TIẾT KẾ HOẠCH
    // ============================
    public function chiTiet() {
        $maKHSX = intval($_GET['maKHSX'] ?? 0);
        if ($maKHSX <= 0) {
            echo "Thiếu mã kế hoạch sản xuất!";
            return;
        }

        $m = new KeHoachSanXuatModel();
        $thongTinKHSX = $m->getPlanById($maKHSX);
        $danhSachNVL  = $m->getMaterialsForPlan($maKHSX);

        require_once __DIR__ . '/../views/taoYCNhapKhoNVL.php';
    }

    // ============================
    // 3️⃣ LƯU PHIẾU YÊU CẦU
    // ============================
public function luuPhieu() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "404 - Trang không tồn tại!";
        return;
    }

    // Debug: Log dữ liệu POST
    error_log("POST Data: " . print_r($_POST, true));

    $model = new PhieuYeuCauNhapKhoModel();

    $maKHSX = intval($_POST['maKHSX'] ?? 0);
    $dsNVL = $_POST['nvl'] ?? [];
    
    // Kiểm tra dữ liệu cơ bản
    if ($maKHSX <= 0) {
        echo "<script>
            alert('⚠️ Vui lòng chọn kế hoạch sản xuất!');
            window.history.back();
        </script>";
        return;
    }

    if (empty($dsNVL)) {
        echo "<script>
            alert('⚠️ Vui lòng chọn ít nhất một nguyên vật liệu!');
            window.history.back();
        </script>";
        return;
    }
    
    // ✅ Lấy nhà cung cấp cho từng NVL
    $nhaCungCap = [];
    foreach ($dsNVL as $maNVL) {
        $key = 'nhaCungCap_' . $maNVL;
        if (isset($_POST[$key])) {
            $nhaCungCap[$maNVL] = $_POST[$key];
        }
    }

    $data = [
        'maKHSX' => $maKHSX,
        'ngayLap' => $_POST['ngayLap'] ?? date('Y-m-d'),
        'ghiChu' => $_POST['ghiChu'] ?? '',
        'nvl' => $dsNVL,
        'nhaCungCap' => $nhaCungCap
    ];

    // Debug
    error_log("Data to save: " . print_r($data, true));

    // ✅ Kiểm tra đã có phiếu cho kế hoạch này chưa
    if ($model->existsByKeHoach($maKHSX)) {
        echo "<script>
            alert('⚠️ Kế hoạch sản xuất này đã được lập phiếu yêu cầu nhập kho NVL rồi!');
            window.location.href = 'index.php?page=tao-yeu-cau-nhap-kho';
        </script>";
        return;
    }

    $ok = $model->createPhieuYeuCauNhapKho($data);

    if ($ok) {
        echo "<script>
            alert('✅ Đã lưu phiếu yêu cầu nhập kho thành công!');
            window.location.href = 'index.php?page=tao-yeu-cau-nhap-kho';
        </script>";
    } else {
        echo "<script>
            alert('❌ Lưu phiếu thất bại! Vui lòng kiểm tra lại.');
            window.history.back();
        </script>";
    }
}

}
