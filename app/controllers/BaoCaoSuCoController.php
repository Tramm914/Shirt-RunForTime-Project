<?php
require_once 'app/models/BaoCaoSuCoModel.php';
require_once 'app/models/ketNoi.php'; // để có kết nối khi cần lấy hoTen từ DB

class BaoCaoSuCoController {
    private $model;

    public function __construct() {
        $this->model = new BaoCaoSuCoModel();
    }

    public function luuBaoCao() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // đảm bảo session đã start
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // kiểm tra người dùng đã đăng nhập
            if (empty($_SESSION['user']['maTK'])) {
                // nếu chưa đăng nhập, redirect về trang login
                echo "<script>
                        alert('Bạn cần đăng nhập để gửi báo cáo.');
                        window.location='index.php?page=login';
                    </script>";
                exit;
            }

            // Lấy dữ liệu từ form
            $maThietBi  = isset($_POST['ma_thiet_bi']) ? $_POST['ma_thiet_bi'] : null;
            $tenThietBi = isset($_POST['ten_thiet_bi']) ? trim($_POST['ten_thiet_bi']) : ''; // do frontend vẫn gửi tên thiết bị
            $loaiLoi    = isset($_POST['loai_loi']) ? $_POST['loai_loi'] : null;
            $moTa       = isset($_POST['mo_ta']) ? $_POST['mo_ta'] : null;

            // Lấy maND (mã tài khoản) từ session
            $maND = null;

// Nếu session có sẵn maND thì dùng luôn
if (!empty($_SESSION['user']['maND'])) {
    $maND = $_SESSION['user']['maND'];
} else {
    // Nếu chỉ có maTK -> truy vấn để tìm maND tương ứng
    $maTK = $_SESSION['user']['maTK'];
    $db = new KetNoi();
    $conn = $db->connect();
    $sql = "SELECT maND FROM nguoidung WHERE maTK = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maTK);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $maND = $row['maND'];
    }
    $stmt->close();
}


            // Lấy hoTen người dùng: ưu tiên session nếu có, nếu không lấy từ bảng nguoidung
            $hoTen = '';
            if (!empty($_SESSION['user']['hoTen'])) {
                $hoTen = $_SESSION['user']['hoTen'];
            } else {
                // fallback: truy vấn DB để lấy họ tên
                $db = new KetNoi();
                $conn = $db->connect();
                $sql = "SELECT hoTen FROM nguoidung WHERE maTK = ? LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $maND);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $hoTen = $row['hoTen'] ?? '';
                }
                $stmt->close();
            }

            // Tự sinh tên báo cáo (server-side) — đảm bảo không thể giả mạo từ client
            // Mẫu: "Báo cáo sự cố - <Tên thiết bị>" (nếu có họ tên: thêm " - <Họ tên>")
            $tenBaoCao = "Báo cáo sự cố";
            if (!empty($tenThietBi)) {
                $tenBaoCao .= " - " . $tenThietBi;
            }
            // Trạng thái mặc định
        $trangThai = "đang chờ xử lý"; // <-- THÊM DÒNG NÀY

            // Xử lý upload hình ảnh (nếu có)
            $hinhAnh = null;
            if (!empty($_FILES['hinh_anh']['name'])) {
                $targetDir = "uploads/img/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // bảo mật tên file: time + basename
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['hinh_anh']['name']));
                $hinhAnh = $targetDir . $fileName;

                move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $hinhAnh);
            }

            // Gọi model để lưu (lưu maND, maThietBi, tenBaoCao tự sinh, loaiLoi, moTa, hinhAnh)
            $result = $this->model->themBaoCao($maND, $maThietBi, $tenBaoCao, $loaiLoi, $moTa, $hinhAnh, $trangThai);

            // Phản hồi
            if ($result) {
                echo "<script>
                        alert('Đã gửi báo cáo thành công!');
                        window.location='index.php?page=home';
                        </script>";
            } else {
                echo "<script>
                        alert('Lỗi khi lưu báo cáo!');
                        window.history.back();
                        </script>";
            }
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }
}
