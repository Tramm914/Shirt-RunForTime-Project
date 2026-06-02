<?php
require_once 'ketNoi.php';

class TaiKhoanModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    /**
     * 🟢 Tạo tài khoản mới
     * Mặc định trạng thái = 'Hoạt động'
     */
    public function createAccount($tenDangNhap, $matKhau, $trangThai = 'Hoạt động') {
        // Normalize username: lowercase, remove spaces
        $tenDangNhap = trim($tenDangNhap);
        $tenDangNhap = strtolower(preg_replace('/\s+/', '', $tenDangNhap));

        // If username exists, append incrementing number until unique
        $base = $tenDangNhap ?: 'user';
        $i = 0;
        while ($this->checkDuplicate($tenDangNhap)) {
            $i++;
            $tenDangNhap = $base . $i;
        }

        // Ensure password is hashed (avoid double-hashing)
        // Skip hashing if already MD5 (32 hex chars) or bcrypt format
        if (!preg_match('/^[a-f0-9]{32}$/', $matKhau)) {
            // Not MD5, check if bcrypt
            $info = password_get_info($matKhau);
            if ($info['algo'] === 0) {
                $matKhau = password_hash($matKhau, PASSWORD_DEFAULT);
            }
        }

        $sql = "INSERT INTO taikhoan (tenDangNhap, matKhau, trangThai)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("❌ Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $tenDangNhap, $matKhau, $trangThai);
        $result = $stmt->execute();

        if (!$result) {
            error_log("❌ Lỗi khi thêm tài khoản: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $id = $this->conn->insert_id; // ✅ Lấy ID tài khoản vừa thêm
        $stmt->close();

        return $id;
    }

    /**
     * 🟡 Kiểm tra trùng tên đăng nhập
     */
    public function checkDuplicate($tenDangNhap) {
        $sql = "SELECT COUNT(*) AS total FROM taikhoan WHERE tenDangNhap = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("❌ Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param("s", $tenDangNhap);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['total'] > 0;
    }
}
?>
