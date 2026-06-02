<?php
require_once 'app/models/XemCaLamViecModel.php';

class XemLichLamViecController {
    private $model;

    public function __construct() {
        $this->model = new XemCaLamViecModel();
    }

    /**
     * Hiển thị trang xem lịch làm việc
     */
    public function index() {
        // Đảm bảo session đã start
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lấy mã người dùng từ session
        $maND = null;
        if (isset($_SESSION['user']) && isset($_SESSION['user']['maTK'])) {
            $maTK = $_SESSION['user']['maTK'];
            // Lấy maND từ maTK thông qua bảng nguoidung
            $maND = $this->model->getMaNDFromMaTKPublic($maTK);
        }

        // Lấy dữ liệu lịch làm việc tuần này (weekOffset = 0)
        $lichLamViec = $this->model->getLichLamViec($maND, 0);
        $tomTat = $this->model->getTomTatLichLamViec($maND, 0);
        $tuanHienTai = $this->model->getTuanHienTai(0);
        
        // Lấy dữ liệu lịch làm việc tuần tới (weekOffset = 1)
        $lichLamViecTuanToi = $this->model->getLichLamViec($maND, 1);
        $tomTatTuanToi = $this->model->getTomTatLichLamViec($maND, 1);
        $tuanTiepTheo = $this->model->getTuanHienTai(1);

        require_once 'app/views/xemCaLamViec.php';
    }
}
?>

