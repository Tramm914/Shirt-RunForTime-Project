<?php
// app/controllers/GhiNhanThanhPhamController.php
require_once 'app/models/GhiNhanThanhPhamModel.php';
require_once 'app/models/KeHoachSanXuatModel.php';
require_once 'app/models/SanPhamModel.php';
require_once 'app/models/NhanVienModel.php';
require_once 'app/models/XuongModel.php';

class GhiNhanThanhPhamController
{
    public function index()
    {
        $ghiNhanModel = new GhiNhanThanhPhamModel();
        $khsxModel = new KeHoachSanXuatModel();
        $xuongModel = new XuongModel();

        $danhSachKHSX = $khsxModel->getKHSXDaDuyet();
        $danhSachXuong = $xuongModel->getAllXuong();

        // Model đã được sửa để trả về slBanThanhPham và slThanhPham
        $lichSuGhiNhan = $ghiNhanModel->getLichSuGhiNhan();

        require_once 'app/views/ghiNhanTP.php';
    }

    public function luu()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['header']) || !isset($data['details'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Dữ liệu gửi lên thiếu header hoặc details'
            ]);
            return;
        }

        $model = new GhiNhanThanhPhamModel();
        $ketQua = $model->luuDanhSach($data['header'], $data['details']);

        if ($ketQua === true) {
            echo json_encode([
                'success' => true,
                'message' => 'Lưu thành công!'
            ]);
        } else {
            $msg = is_string($ketQua) ? $ketQua : 'Lỗi không xác định từ CSDL';
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $msg
            ]);
        }
    }

    public function ajaxGetSanPham()
    {
        $maKHSX = $_GET['maKHSX'] ?? 0;
        require_once 'app/models/KeHoachSanXuatModel.php';
        $model = new KeHoachSanXuatModel();
        $sanPham = $model->getSanPhamTheoKHSX($maKHSX);

        header('Content-Type: application/json');
        if ($sanPham) {
            echo json_encode([$sanPham]); 
        } else {
            echo json_encode([]);
        }
        exit;
    }

    public function ajaxGetNhanVienByXuong()
    {
        $maXuong = $_GET['maXuong'] ?? 0;
        $xuongModel = new XuongModel();
        $nhanVienModel = new NhanVienModel();

        $xuong = $xuongModel->getXuongById($maXuong);
        if ($xuong) {
            echo json_encode($nhanVienModel->getNhanVienTheoPhongBan($xuong['tenXuong']));
        } else {
            echo json_encode([]);
        }
    }

    public function ajaxGetChiTietPhieu()
    {
        $ngay = $_GET['ngay'] ?? '';
        $khsx = $_GET['khsx'] ?? 0;
        $model = new GhiNhanThanhPhamModel();
        echo json_encode($model->getChiTietPhieu($ngay, $khsx));
    }
}