<?php
// app/controllers/XuatThanhPhamController.php
require_once 'app/models/XuatThanhPhamModel.php';

class XuatThanhPhamController {
    private $model;

    public function __construct() {
        $this->model = new XuatThanhPhamModel();
    }

    // Hiển thị trang danh sách đơn hàng chờ xuất
    public function index() {
        $donhangs = $this->model->getDonHangChuaXuat();
        // biến $donhangs sẽ được view dùng
        require_once __DIR__ . '/../views/xuatthanhpham.php';
    }
public function chitiet() {
    if (!isset($_GET['id'])) {
        die("Thiếu mã đơn hàng");
    }

    $maDonHang = (int)$_GET['id'];
    $donhang = $this->model->getChiTietDonHang($maDonHang);

    if (!$donhang) {
        die("Không tìm thấy đơn hàng");
    }

    require_once __DIR__ . '/../views/xuatthanhpham_chitiet.php';
}

    // Xử lý POST xuất kho (gọi từ fetch của view)
    public function xuat() {
        // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }

        $maDonHang   = isset($_POST['maDonHang']) ? (int)$_POST['maDonHang'] : 0;
        $soLuongXuat = isset($_POST['soLuongXuat']) ? (int)$_POST['soLuongXuat'] : 0;
        $ghiChu      = isset($_POST['ghiChu']) ? trim($_POST['ghiChu']) : '';

        header('Content-Type: application/json; charset=utf-8');

        if ($maDonHang <= 0 || $soLuongXuat <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $res = $this->model->xuatKho($maDonHang, $soLuongXuat, $ghiChu);

        if ($res === true) {
            echo json_encode(['success' => true]);
        } else {
            // model trả về chuỗi lỗi (ví dụ "Không đủ hàng tồn")
            echo json_encode(['success' => false, 'message' => is_string($res) ? $res : 'Lỗi khi xuất kho']);
        }
        exit;
    }
}
