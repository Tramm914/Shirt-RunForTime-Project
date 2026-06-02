<?php
require_once 'app/models/XoaNhanVienModel.php';

class XoaNhanVienController {
    private $model;

    public function __construct() {
        $this->model = new XoaNhanVienModel();
    }

    // ✅ Trang chính quản lý nhân viên
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';

        if ($keyword) {
            $nhanviens = $this->model->search($keyword);
        } else {
            $nhanviens = $this->model->getAll();
        }

        require 'app/views/xoanhanvien.php';
    }

    // ✅ API tìm kiếm AJAX
    public function searchAjax() {
        header('Content-Type: application/json; charset=utf-8');

        if (isset($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
            $results = $this->model->search($keyword);
            echo json_encode($results);
            exit;
        }

        echo json_encode([]);
        exit;
    }
    // Xử lý AJAX xóa (POST) - trả JSON
    public function deleteAjax() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $maNV = $_GET['id'];
            $result = $this->model->deleteById($maNV);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Xóa thất bại (DB).']);
            }
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
        exit;
    }

    // Hiển thị trang xác nhận xóa (nếu bạn dùng route riêng)
    public function xacNhanXoa() {
        if (isset($_GET['id'])) {
            $maNV = $_GET['id'];
            $nhanvien = $this->model->getById($maNV);
            require 'app/views/xoanhanvien.php'; // nếu bạn có view xác nhận
        } else {
            echo "<script>alert('Không tìm thấy nhân viên!'); window.location='index.php?page=xoanhanvien';</script>";
        }
    }

    // Xóa bằng form POST truyền thống (nếu cần)
    public function deletePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maND'])) {
            $maNV = $_POST['maND'];
            $this->model->deleteById($maNV);
            echo "<script>alert('Đã xóa nhân viên thành công!'); window.location='index.php?page=xoanhanvien';</script>";
        }
    }
}
?>
