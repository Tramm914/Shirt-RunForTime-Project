<?php
require_once 'app/models/NhapKhoTP_DaCheckQCModel.php';

class NhapKhoTP_DaCheckQCController {
    private $model;

    public function __construct() {
        $this->model = new NhapKhoTP_DaCheckQCModel();
    }

    /**
     * Hiển thị trang nhập kho thành phẩm từ phiếu QC đã đạt
     * Basic flow step 1-2: Truy cập chức năng và hiển thị danh sách phiếu kiểm tra chất lượng đã đạt
     */
    public function index() {
        try {
            // Lấy danh sách phiếu QC đã đạt
            $danhSachPhieuQC = $this->model->getDanhSachPhieuQCDaDat();
            
            // Alternative flow 3.1: Nếu không có phiếu hợp lệ
            if (empty($danhSachPhieuQC)) {
                $thongBaoLoi = "Không có phiếu hợp lệ để nhập kho";
            }
            
            // Nếu có chọn phiếu từ URL (Basic flow step 3-4)
            $chiTietPhieu = null;
            if (isset($_GET['maPhieu']) && !empty($_GET['maPhieu'])) {
                $maPhieu = (int)$_GET['maPhieu'];
                try {
                    $chiTietPhieu = $this->model->getChiTietPhieuQC($maPhieu);
                    if (!$chiTietPhieu) {
                        $thongBaoLoi = "Không tìm thấy phiếu hợp lệ";
                    }
                } catch (Exception $e) {
                    $thongBaoLoi = "Lỗi kết nối hệ thống. Vui lòng thử lại sau.";
                    error_log("Lỗi getChiTietPhieuQC: " . $e->getMessage());
                }
            }
            
            require_once 'app/views/nhapKhoTP_DaCheckQC.php';
        } catch (Exception $e) {
            // Exception 6.1: Kết nối hệ thống bị lỗi
            error_log("Lỗi index NhapKhoTP_DaCheckQCController: " . $e->getMessage());
            $thongBaoLoi = "Lỗi kết nối hệ thống. Vui lòng thử lại sau.";
            $danhSachPhieuQC = [];
            $chiTietPhieu = null;
            require_once 'app/views/nhapKhoTP_DaCheckQC.php';
        }
    }

    /**
     * Xử lý nhập kho thành phẩm
     * Basic flow step 5-7: Kiểm tra thông tin, lưu nhập kho, thông báo thành công
     * Exception 6.1: Xử lý lỗi kết nối hệ thống
     */
    public function luuNhapKho() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=nhap-kho-thanh-pham');
            exit;
        }

        try {
            // Validate dữ liệu
            $maYC = isset($_POST['maPhieu']) ? (int)$_POST['maPhieu'] : 0; // maPhieu trong form là maYC
            $maSanPham = isset($_POST['maSanPham']) ? (int)$_POST['maSanPham'] : 0;
            $soLuong = isset($_POST['soLuong']) ? (int)$_POST['soLuong'] : 0;

            // Kiểm tra dữ liệu bắt buộc
            if ($maYC <= 0 || $maSanPham <= 0 || $soLuong <= 0) {
                header('Location: index.php?page=nhap-kho-thanh-pham&error=1&msg=' . urlencode('Dữ liệu không hợp lệ'));
                exit;
            }

            // Kiểm tra phiếu đã nhập kho chưa
            if ($this->model->kiemTraPhieuDaNhapKho($maYC)) {
                header('Location: index.php?page=nhap-kho-thanh-pham&error=1&msg=' . urlencode('Phiếu này đã được nhập kho'));
                exit;
            }

            // Lấy chi tiết phiếu để kiểm tra số lượng
            $chiTietPhieu = $this->model->getChiTietPhieuQC($maYC);
            
            if (!$chiTietPhieu) {
                header('Location: index.php?page=nhap-kho-thanh-pham&error=1&msg=' . urlencode('Không tìm thấy phiếu hợp lệ'));
                exit;
            }

            // Kiểm tra số lượng không vượt quá số lượng trong phiếu
            if ($soLuong > $chiTietPhieu['soLuong']) {
                header('Location: index.php?page=nhap-kho-thanh-pham&maPhieu=' . $maYC . '&error=1&msg=' . urlencode('Số lượng không được vượt quá số lượng trong phiếu'));
                exit;
            }

            // Thực hiện nhập kho (Basic flow step 6)
            // Lấy thông tin người lập từ session
            $nguoiLap = isset($_SESSION['user']['hoTen']) ? $_SESSION['user']['hoTen'] : 'Hệ thống';
            $hanhDong = 'Nhập kho thành phẩm sau khi kiểm tra chất lượng';
            
            $this->model->nhapKhoThanhPham($maYC, $maSanPham, $soLuong, $nguoiLap, $hanhDong);
            
            // Basic flow step 7: Thông báo thành công
            header('Location: index.php?page=nhap-kho-thanh-pham&success=1&msg=' . urlencode('Nhập kho thành công'));
            exit;

        } catch (Exception $e) {
            // Exception 6.1: Kết nối hệ thống bị lỗi
            error_log("Lỗi luuNhapKho: " . $e->getMessage());
            $maPhieu = isset($_POST['maPhieu']) ? (int)$_POST['maPhieu'] : 0;
            $errorMsg = "Lỗi kết nối hệ thống. Vui lòng thử lại sau.";
            
            if ($maPhieu > 0) {
                header('Location: index.php?page=nhap-kho-thanh-pham&maPhieu=' . $maPhieu . '&error=1&msg=' . urlencode($errorMsg));
            } else {
                header('Location: index.php?page=nhap-kho-thanh-pham&error=1&msg=' . urlencode($errorMsg));
            }
            exit;
        }
    }
}
?>

