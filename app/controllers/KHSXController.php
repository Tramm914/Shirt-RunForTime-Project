<?php
require_once 'app/models/DonHangSanXuatModel.php';
require_once 'app/models/LapKHSXModel.php';
require_once 'app/models/XuongModel.php';
require_once 'app/models/NVLModel.php';
require_once 'app/models/GhiNhanThanhPhamModel.php';
require_once 'app/models/SanPhamModel.php';

class KHSXController
{
    private $conn;
    private $keHoachModel;
    private $donHangModel;
    private $xuongModel;
    private $nvlModel;
    private $ghiNhanTPModel;
    private $sanPhamModel;

    public function __construct()
    {
        $database = new KetNoi();
        $this->conn = $database->connect();

        $this->donHangModel = new DonHangSanXuatModel();
        $this->keHoachModel = new LapKHSXModel();
        $this->xuongModel = new XuongModel();
        $this->nvlModel = new NVLModel();
        $this->ghiNhanTPModel = new GhiNhanThanhPhamModel();
        $this->sanPhamModel = new SanPhamModel();
    }

    // -------------------------------------
    // 1. Trang lập KHSX
    // -------------------------------------
    public function create()
    {
        $danhSachDonHang = $this->keHoachModel->getDonHangChuaLapKHSX();
        $danhSachKHSX = $this->keHoachModel->getDanhSachKHSX();

        include __DIR__ . '/../views/lapKHSX.php';
    }

    // -------------------------------------
    // 2. Trang lập chi tiết KHSX
    // -------------------------------------
    public function lapChiTiet()
    {
        $maDonHang = $_GET['id'] ?? 0;

        $donHang = $this->donHangModel->getChiTietDonHang($maDonHang);

        if (!$donHang) {
            echo "Không tìm thấy đơn hàng!";
            exit;
        }

        $danhSachXuong = $this->xuongModel->getAllXuong();
        $danhSachNVL = $this->nvlModel->getAllNVL();
        $soLuongTB = $this->ghiNhanTPModel->getSoLuongTrungBinh();
        $danhSachSanPham = $this->sanPhamModel->getAllSanPham();

        include __DIR__ . '/../views/lapKHSXChiTiet.php';
    }

    // -------------------------------------
    // 3. Lưu kế hoạch sản xuất
    // -------------------------------------
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=lap-ke-hoach");
            exit;
        }

        // Đảm bảo session đã được khởi động để lấy thông tin người dùng
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lấy maND từ session (tên biến tùy thuộc vào lúc bạn viết code Login, thường là 'maND' hoặc 'user_id')
        // Truy cập vào mảng 'user' trước rồi mới lấy 'maND'
        $maND_hien_tai = $_SESSION['user']['maND'] ?? 1;
        

        $this->conn->begin_transaction();

        try {
            // ===== 1. Tạo kế hoạch cha =====
            $maKHSX = $this->keHoachModel->createKHSX([
                'tenKHSX' => "KHSX cho ĐH " . $_POST['maDonHang'],
                'maDonHang' => $_POST['maDonHang'],
                'thoiGianBatDau' => $_POST['ngay_bat_dau'],
                'thoiGianKetThuc' => $_POST['ngay_ket_thuc'],
                'maND' => $maND_hien_tai // ĐÃ SỬA: Thay số 1 bằng biến động
            ]);

            // ... giữ nguyên phần còn lại của code

            if (!$maKHSX) {
                throw new Exception("Không thể tạo kế hoạch!");
            }

            // ============================================================================
            // 2. ĐỌC DỮ LIỆU XƯỞNG CẮT (dạng đơn - không phải mảng)
            // ============================================================================
            $catKPI = $_POST['xuong_cat']['kpi'] ?? 0;
            $catStart = $_POST['xuong_cat']['ngayBatDau'] ?? null;
            $catEnd = $_POST['xuong_cat']['ngayKetThuc'] ?? null;

            $catNVL_IDs = $_POST['xuong_cat']['nvl_id'] ?? [];
            $catDinhMuc = $_POST['xuong_cat']['nvl_dinhMuc'] ?? [];
            $catSoLuongNVL = $_POST['xuong_cat']['nvl_soLuong'] ?? [];

            foreach ($catNVL_IDs as $i => $maNVL) {

                $this->keHoachModel->createChiTietKHSX([
                    'maKHSX' => $maKHSX,
                    'maXuong' => 1,

                    'maNVL' => $maNVL,
                    'soLuongNVL' => $catSoLuongNVL[$i] ?? 0,

                    // 5 trường mới
                    'ngayBatDau' => $catStart,
                    'ngayKetThuc' => $catEnd,
                    'KPI' => $catKPI,
                    'soLuongThanhPham' => 0,
                    'dinhMuc' => $catDinhMuc[$i] ?? 0,

                    // FK ghi nhận TP = NULL (vì chưa có)
                    'maGNTP' => null
                ]);
            }

            // ============================================================================
            // 3. ĐỌC DỮ LIỆU XƯỞNG MAY (dạng đơn - không phải mảng)
            // ============================================================================
            $mayKPI = $_POST['xuong_may']['kpi'] ?? 0;
            $mayStart = $_POST['xuong_may']['ngayBatDau'] ?? null;
            $mayEnd = $_POST['xuong_may']['ngayKetThuc'] ?? null;

            $mayNVL_IDs = $_POST['xuong_may']['nvl_id'] ?? [];
            $mayDinhMuc = $_POST['xuong_may']['nvl_dinhMuc'] ?? [];
            $maySoLuongNVL = $_POST['xuong_may']['nvl_soLuong'] ?? [];

            foreach ($mayNVL_IDs as $i => $maNVL) {

                $this->keHoachModel->createChiTietKHSX([
                    'maKHSX' => $maKHSX,
                    'maXuong' => 2,

                    'maNVL' => $maNVL,
                    'soLuongNVL' => $maySoLuongNVL[$i] ?? 0,

                    'ngayBatDau' => $mayStart,
                    'ngayKetThuc' => $mayEnd,
                    'KPI' => $mayKPI,
                    'soLuongThanhPham' => 0,
                    'dinhMuc' => $mayDinhMuc[$i] ?? 0,

                    'maGNTP' => null
                ]);
            }

            // ===== 4. Cập nhật trạng thái đơn hàng =====
            $this->donHangModel->updateTrangThai($_POST['maDonHang'], "Đang thực hiện");

            $this->conn->commit();
            header("Location: index.php?page=lap-khsx&success=1");
            exit;

        } catch (Exception $e) {
            $this->conn->rollback();
            echo "Lỗi lưu kế hoạch: " . $e->getMessage();
            exit;
        }
    }

}
