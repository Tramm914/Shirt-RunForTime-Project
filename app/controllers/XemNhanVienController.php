<?php
require_once 'app/models/XoaNhanVienModel.php';

class XemNhanVienController {
    private $model;

    public function __construct() {
        $this->model = new XoaNhanVienModel(); // dùng lại model có sẵn
    }

    // ✅ Trang chính xem danh sách nhân viên (dùng view xoanhanvien.php vì nó có table)
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

    // ✅ Xem chi tiết 1 nhân viên
    public function show($id) {
        $nhanvien = $this->model->getById($id);
        
        if ($nhanvien) {
            require 'app/views/xemnhanvien.php';
        } else {
            echo "<p style='text-align:center;margin-top:50px;'>❌ Không tìm thấy nhân viên.</p>";
        }
    }
}
?>
