<?php
require_once 'ketNoi.php';

class XoaNhanVienModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect(); // mysqli connection
    }

    // ✅ Lấy tất cả nhân viên/công nhân đang hoạt động (loại trừ các chức vụ quản lý)
    public function getAll() {
        $sql = "SELECT maND, hoTen, gioiTinh, ngaySinh, phongBan, chucVu, diaChi, email, soDienThoai 
                FROM nguoidung 
                WHERE trangThai = 1 
                AND chucVu NOT IN ('Giám đốc', 'Quản lý nhân sự', 'Quản lý sản xuất', 'Quản lý kho NVL', 'Quản lý kho TP', 'Nhân viên QC')
                ORDER BY maND ASC";

        $result = $this->conn->query($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // ✅ Lấy nhân viên theo ID
    public function getById($maNV) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoidung WHERE maND = ?");
        $stmt->bind_param("i", $maNV);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        return $data;
    }

    // ✅ Xóa mềm nhân viên (ẩn khỏi giao diện, vẫn còn trong DB)
    public function deleteById($maNV) {
        $stmt = $this->conn->prepare("UPDATE nguoidung AS nd JOIN taikhoan AS t ON nd.maTK = t.maTK
                        SET
                            nd.trangThai = 0,
                            t.trangThai = 'không hoạt động'
                        WHERE nd.maND = ?");
        $stmt->bind_param("i", $maNV);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ✅ Lấy danh sách nhân viên đã bị xóa (nếu cần)
    public function getDeleted() {
        $sql = "SELECT * FROM nguoidung WHERE trangThai = 0";
        $result = $this->conn->query($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // ✅ Khôi phục nhân viên đã bị xóa
    public function restoreById($maNV) {
        $stmt = $this->conn->prepare("UPDATE nguoidung AS nd JOIN taikhoan AS t ON nd.maTK = t.maTK
                        SET
                            nd.trangThai = 0,
                            t.trangThai = 'không hoạt động'
                        WHERE nd.maND = ?");
        $stmt->bind_param("i", $maNV);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ✅ Tìm kiếm nhân viên/công nhân (loại trừ các role quản lý)
    public function search($keyword) {
        $like = "%{$keyword}%";
        $stmt = $this->conn->prepare("
            SELECT maND, hoTen, gioiTinh, ngaySinh, phongBan, chucVu, diaChi, email, soDienThoai
            FROM nguoidung
            WHERE trangThai = 1
            AND chucVu NOT IN ('Giám đốc', 'Quản lý nhân sự', 'Quản lý sản xuất', 'Quản lý kho NVL', 'Quản lý kho TP', 'Nhân viên QC')
            AND (hoTen LIKE ? OR chucVu LIKE ? OR phongBan LIKE ? OR email LIKE ?)
            ORDER BY maND ASC
        ");
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $stmt->close();

        return $data;
    }
}
?>
