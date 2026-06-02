<?php
require_once 'app/models/PhieuXuatKhoModel.php';

class XuatKhoNVLController
{

    private $model;

    public function __construct()
    {
        $this->model = new PhieuXuatNVLModel();
    }

    // ✅ Danh sách phiếu yêu cầu NVL đã duyệt
    public function danhSachPhieu()
    {
        $danhSachPhieu = $this->model->getAllPhieuXuat();
        require_once 'app/views/xuatKhoNVL.php';
    }

    // ✅ Xem và tạo phiếu xuất vật liệu từ YCCC
    public function chiTietPhieu()
    {
        if (!isset($_GET['maYCCC'])) {
            header("Location: index.php?page=xuat-kho-nvl");
            exit();
        }

        $maYCCC = intval($_GET['maYCCC']);

        // ✅ Lấy thông tin phiếu yêu cầu thật từ database
        $thongTinPhieu = $this->model->getThongTinPhieuTheoMaYCCC($maYCCC);

        if (!$thongTinPhieu) {
            header("Location: index.php?page=xuat-kho-nvl&error=notfound");
            exit();
        }

        // ✅ Lấy danh sách NVL và xưởng
        $dsNVL = $this->model->getChiTietNVLTheoYCCC($maYCCC);
        $mapXuongTheoNVL = $this->model->getXuongTheoYCCCTheoNVL($maYCCC);


        require_once 'app/views/xuatKhoNVL_ChiTiet.php';
    }

    // ✅ Lưu phiếu xuất NVL
    public function luuPhieu()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?page=xuat-kho-nvl");
        exit();
    }

    if ($this->model->createPhieuXuatNVL($_POST)) {
        header("Location: index.php?page=xuat-kho-nvl&success=1");
    } else {
        // Lấy thông báo lỗi cụ thể từ session nếu có
        $msg = isset($_SESSION['error_message']) ? urlencode($_SESSION['error_message']) : '1';
        unset($_SESSION['error_message']); // Xóa sau khi dùng
        header("Location: index.php?page=xuat-kho-nvl&error=" . $msg);
    }
    exit();
}

}
