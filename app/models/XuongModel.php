<?php
class XuongModel
{
    private $conn;
    public function __construct()
    {
        // Giả sử tệp ketNoi.php định nghĩa class Database với hàm connect()
        $this->conn = (new KetNoi())->connect();
    }

    public function getAllXuong()
    {
        $sql = 'SELECT maXuong, tenXuong FROM xuong';
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getXuongById($maXuong)
    {
        $sql = "SELECT * FROM xuong WHERE maXuong = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maXuong);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    

}
