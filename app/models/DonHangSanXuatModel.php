<?php
// app/models/DonHangSanXuatModel.php
require_once 'ketNoi.php';

class DonHangSanXuatModel
{
    private $conn;

    public function __construct()
    {
        $database = new KetNoi();
        $this->conn = $database->connect();
    }

    public function getAllDonHang()
    {
        $query = $this->conn->prepare('SELECT * FROM donhangsanxuat');
        $query->execute();
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function timKiemDonHang($keyWord, $tuNgay = null, $denNgay = null, $trangThai = null)
    {
        $sql = 'SELECT dh.maDonHang, dh.tenDonHang, dh.ngayGiao, dh.trangThai
                FROM donhangsanxuat dh
                WHERE (dh.maDonHang LIKE ? OR dh.tenDonHang LIKE ?)';
        
        $types = "ss";
        $params = [];
        $searchKeyWord = '%' . $keyWord . '%';
        $params[] = &$searchKeyWord;
        $params[] = &$searchKeyWord;

        if (!empty($tuNgay)) {
            $sql .= ' AND dh.ngayGiao >= ?';
            $types .= 's';
            $params[] = &$tuNgay;
        }
        if (!empty($denNgay)) {
            $sql .= ' AND dh.ngayGiao <= ?';
            $types .= 's';
            $params[] = &$denNgay;
        }
        if (!empty($trangThai)) {
            $sql .= ' AND dh.trangThai = ?';
            $types .= 's';
            $params[] = &$trangThai;
        }
        
        $query = $this->conn->prepare($sql);
        if(!empty($types)) {
            $query->bind_param($types, ...$params);
        }
        $query->execute();

        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getChiTietDonHang($id)
    {
        $sql = 'SELECT dh.*, sp.tenSanPham
                FROM donhangsanxuat dh
                JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
                WHERE dh.maDonHang = ?';
        $query = $this->conn->prepare($sql);
        $query->bind_param('i', $id);
        $query->execute();

        $result = $query->get_result();
        return $result->fetch_assoc();
    }

    public function getRecentDonHang($limit = 10, $tuNgay = null, $denNgay = null, $trangThai = null) {
        $sql = 'SELECT dh.maDonHang, dh.tenDonHang, dh.ngayGiao, dh.trangThai
                FROM donhangsanxuat dh
                WHERE 1=1'; 
        
        $types = "";
        $params = [];

        if (!empty($tuNgay)) {
            $sql .= ' AND dh.ngayGiao >= ?';
            $types .= 's';
            $params[] = &$tuNgay;
        }
        if (!empty($denNgay)) {
            $sql .= ' AND dh.ngayGiao <= ?';
            $types .= 's';
            $params[] = &$denNgay;
        }
        if (!empty($trangThai)) {
            $sql .= ' AND dh.trangThai = ?';
            $types .= 's';
            $params[] = &$trangThai;
        }

        $sql .= ' ORDER BY dh.maDonHang DESC LIMIT ?';
        $types .= "i";
        $params[] = &$limit;
        
        $query = $this->conn->prepare($sql);
        if (!empty($types)) {
            $query->bind_param($types, ...$params); 
        }
        $query->execute();
        
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function updateTrangThai($maDonHang, $trangThai) {
        $sql = "UPDATE donhangsanxuat SET trangThai = ? WHERE maDonHang = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL Error in updateTrangThai: " . $this->conn->error);
            return false;
        }


        $stmt->bind_param("si", $trangThai, $maDonHang);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>