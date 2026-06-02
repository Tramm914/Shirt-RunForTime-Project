<?php
require_once 'app/models/KetNoi.php';
require_once 'app/models/PheDuyetModel.php';

class PheDuyetController
{
    public function index()
    {
        $db = (new KetNoi())->connect();
        $model = new PheDuyetModel($db);

        $type = $_GET['type'] ?? 'capnvl'; // loại mặc định

        switch ($type) {
            case 'capnvl':
                $danhSachYeuCau = $model->getAllCapNVL();
                break;
            case 'nhapkho':
                $danhSachYeuCau = $model->getAllNhapKho();
                break;
            case 'kiemtra':
                $danhSachYeuCau = $model->getAllKTCL();
                break;
            default:
                $danhSachYeuCau = [];
                break;
        }

        if (!is_array($danhSachYeuCau))
            $danhSachYeuCau = [];
        // echo "<h1>Kiểm tra dữ liệu...</h1>";
        // echo "<p>Loại phiếu đang xem: <strong>" . htmlspecialchars($type) . "</strong></p>";
        // echo "<p>Kết quả từ Model (danhSachYeuCau):</p>";
        // echo "<pre>";
        // print_r($danhSachYeuCau); // In dữ liệu ra
        // echo "</pre>";
        // die();

        require 'app/views/pheDuyetYeuCau.php';


    }

    public function duyet()
    {
        if (!empty($_POST['loai']) && !empty($_POST['id'])) {
            $db = (new KetNoi())->connect();
            $model = new PheDuyetModel($db);
            $ok = $model->capNhatTrangThai($_POST['loai'], $_POST['id'], 'Đã duyệt');
            if ($ok) {
                header("Location: index.php?page=phe-duyet-cac-yeu-cau&type=" . urlencode($_POST['loai']) . "&status=approved");
            } else {
                header("Location: index.php?page=phe-duyet-cac-yeu-cau&type=" . urlencode($_POST['loai']) . "&error=1");
            }
            exit;

        }
    }

    public function tuChoi()
    {
        if (!empty($_POST['loai']) && !empty($_POST['id'])) {
            $db = (new KetNoi())->connect();
            $model = new PheDuyetModel($db);

            $lyDo = $_POST['lyDo'] ?? null; // lấy lý do từ form
            $model->capNhatTrangThai($_POST['loai'], $_POST['id'], 'Từ chối', $lyDo);

            header("Location: index.php?page=phe-duyet-cac-yeu-cau&type=" . urlencode($_POST['loai']) . "&status=rejected");
            exit;
        }
    }

    public function chiTiet()
    {
        if (!isset($_GET['maPhieu']) || !isset($_GET['type'])) {
            header("Location: index.php?page=phe-duyet-cac-yeu-cau");
            exit;
        }

        $maPhieu = $_GET['maPhieu'];
        $type = $_GET['type'];

        $db = (new KetNoi())->connect();
        $model = new PheDuyetModel($db);

        // --- Lấy chi tiết từng loại phiếu ---
        switch ($type) {
            case 'capnvl':
                $chiTiet = $model->getChiTietCapNVL($maPhieu);
                break;
            case 'nhapkho':
                $chiTiet = $model->getChiTietNhapKho($maPhieu);
                break;
            case 'kiemtra':
                $chiTiet = $model->getChiTietKTCL($maPhieu);
                break;
            default:
                $chiTiet = [];
                break;
        }

        // --- Kiểm tra kết quả ---
        if (empty($chiTiet)) {
            echo "Không tìm thấy thông tin phiếu yêu cầu.";
            return;
        }

        // --- Đảm bảo biến này truyền ra view ---
        $type = htmlspecialchars($type);
        $maPhieu = htmlspecialchars($maPhieu);

        // --- Gọi view ---
        include 'app/views/chiTietYeuCau.php';
    }


}
