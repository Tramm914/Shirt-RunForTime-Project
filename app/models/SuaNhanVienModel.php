<?php
require_once 'ketNoi.php';

class SuaNhanVienModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    // Trả về: ['ok'=>bool, 'affected'=>int, 'error'=>string|null]
    public function update($data) {
        $sql = "UPDATE nguoidung
                SET hoTen=?, gioiTinh=?, ngaySinh=?, phongBan=?, diaChi=?, email=?, soDienThoai=?
                WHERE maND=?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['ok' => false, 'affected' => 0, 'error' => $this->conn->error];
        }

        $stmt->bind_param(
            "sssssssi",
            $data['hoTen'],
            $data['gioiTinh'],
            $data['ngaySinh'],
            $data['phongBan'],
            $data['diaChi'],
            $data['email'],
            $data['soDienThoai'],
            $data['maND']
        );

        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;
        $err = $stmt->error ?: null;

        $stmt->close();

        return ['ok' => $ok, 'affected' => $affected, 'error' => $err];
    }
}
