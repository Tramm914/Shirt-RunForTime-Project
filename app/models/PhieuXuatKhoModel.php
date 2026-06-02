<?php
require_once 'ketNoi.php';

class PhieuXuatNVLModel
{

    private $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    /* ✅ Lấy danh sách Phiếu yêu cầu NVL đã duyệt */
    public function getAllPhieuXuat()
    {
        $sql = "SELECT yc.maYCCC, yc.tenPhieu, yc.ngayLap, yc.tenNguoiLap, yc.maKHSX
                FROM phieuyeucaucungcapnvl yc
                WHERE yc.trangThai = 'Đã duyệt'
                ORDER BY yc.maYCCC DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc())
                $data[] = $row;
        }
        return $data;
    }

    public function getThongTinPhieuTheoMaYCCC($maYCCC)
    {
        $sql = "SELECT maYCCC, tenPhieu, ngayLap, tenNguoiLap, maKHSX
            FROM phieuyeucaucungcapnvl
            WHERE maYCCC = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maYCCC);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    /* ✅ Lấy chi tiết NVL theo phiếu yêu cầu */
    public function getChiTietNVLTheoYCCC($maYCCC)
    {
        $sql = "SELECT ct.maNVL, nvl.tenNVL, ct.soLuong AS soLuongYeuCau
                FROM chitiet_phieuyeucaucapnvl ct
                JOIN nvl nvl ON nvl.maNVL = ct.maNVL
                WHERE ct.maYCCC = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maYCCC);
        $stmt->execute();

        $data = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc())
            $data[] = $row;

        return $data;
    }
    public function getXuongTheoYCCCTheoNVL($maYCCC)
    {
        $sql = "SELECT ct.maNVL, x.maXuong, x.tenXuong
            FROM phieuyeucaucungcapnvl y
            JOIN kehoachsanxuat k ON k.maKHSX = y.maKHSX
            JOIN chitietkehoachsanxuat ct ON ct.maKHSX = k.maKHSX
            JOIN xuong x ON x.maXuong = ct.maXuong
            WHERE y.maYCCC = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $maYCCC);
        $stmt->execute();
        $res = $stmt->get_result();

        // Build map: maNVL => ['maXuong'=>..., 'tenXuong'=>...]
        $map = [];
        while ($row = $res->fetch_assoc()) {
            // nếu 1 NVL có nhiều xưởng ở CT-KHSX, bạn có thể quyết định chọn dòng đầu, hoặc ưu tiên theo logic khác
            if (!isset($map[$row['maNVL']])) {
                $map[$row['maNVL']] = [
                    'maXuong' => $row['maXuong'],
                    'tenXuong' => $row['tenXuong'],
                ];
            }
        }
        return $map;
    }


    /* ✅ Tạo phiếu xuất NVL */
    public function createPhieuXuatNVL($data)
    {
        if (!isset($_SESSION['user']))
            return false;

        $conn = $this->conn;
        $maYCCC = intval($data['maYCCC']);
        $tenPhieu = trim($data['tenPhieu']);
        $nguoiLap = $_SESSION['user']['hoTen'];
        $maND = intval($_SESSION['user']['maTK']);
        $ngayLap = date('Y-m-d');

        $maNVL_list = $data['maNVL'] ?? [];
        $tenNVL_list = $data['tenNVL'] ?? [];
        $soLuongXuat_list = $data['soLuongNhap'] ?? []; // Đây là số lượng người dùng nhập để xuất
        $xuong_list = $data['xuongNhan'] ?? [];
        $ghiChu_list = $data['ghiChu'] ?? [];

        if ($maYCCC <= 0 || empty($maNVL_list))
            return false;

        $conn->begin_transaction();

        try {
            // --- BƯỚC 1: KIỂM TRA TỒN KHO TRƯỚC ---
            for ($i = 0; $i < count($maNVL_list); $i++) {
                $maNVL = intval($maNVL_list[$i]);
                $soLuongXuat = intval($soLuongXuat_list[$i]);

                if ($soLuongXuat <= 0)
                    continue;

                // Truy vấn lấy số lượng tồn hiện tại (Giả sử cột là soLuongTon trong bảng nvl)
                $sqlCheck = "SELECT tenNVL, soLuongTonKho FROM nvl WHERE maNVL = ? FOR UPDATE";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->bind_param("i", $maNVL);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result()->fetch_assoc();

                if (!$resCheck || $resCheck['soLuongTonKho'] < $soLuongXuat) {
                    $tenNVL = $resCheck['tenNVL'] ?? "NVL mã $maNVL";
                    $tonHienTai = $resCheck['soLuongTonKho'] ?? 0;
                    // Ném ngoại lệ để nhảy vào block catch
                    throw new Exception("Không đủ tồn kho cho: $tenNVL (Yêu cầu: $soLuongXuat, Hiện có: $tonHienTai)");
                }
            }

            // --- BƯỚC 2: LƯU PHIẾU XUẤT ---
            $sql1 = "INSERT INTO phieuxuatnvl (tenPhieu, tenNguoiLap, ngayLap, maND, maYCCC) VALUES (?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("sssii", $tenPhieu, $nguoiLap, $ngayLap, $maND, $maYCCC);
            $stmt1->execute();
            $maPhieu = $conn->insert_id;

            // --- BƯỚC 3: LƯU CHI TIẾT VÀ CẬP NHẬT TỒN KHO ---
            $sqlInsertCT = "INSERT INTO chitietphieuxuatnvl (maPhieu, maNVL, tenNVL, soLuong, maXuong, ghiChu) VALUES (?, ?, ?, ?, ?, ?)";
            $sqlUpdateTon = "UPDATE nvl SET soLuongTonKho = soLuongTonKho - ? WHERE maNVL = ?";

            $stmt2 = $conn->prepare($sqlInsertCT);
            $stmtUpdate = $conn->prepare($sqlUpdateTon);

            for ($i = 0; $i < count($maNVL_list); $i++) {
                $maNVL = intval($maNVL_list[$i]);
                $tenNVL = trim($tenNVL_list[$i] ?? '');
                $soLuongXuat = intval($soLuongXuat_list[$i]);
                $maXuong = intval($xuong_list[$i] ?? 0);
                $ghiChu = trim($ghiChu_list[$i] ?? '');

                if ($soLuongXuat > 0) {
                    // Lưu chi tiết
                    $stmt2->bind_param("iisiis", $maPhieu, $maNVL, $tenNVL, $soLuongXuat, $maXuong, $ghiChu);
                    $stmt2->execute();

                    // Cập nhật trừ tồn kho
                    $stmtUpdate->bind_param("ii", $soLuongXuat, $maNVL);
                    $stmtUpdate->execute();
                }
            }

            // --- BƯỚC 4: CẬP NHẬT TRẠNG THÁI PHIẾU YÊU CẦU ---
            $sql3 = "UPDATE phieuyeucaucungcapnvl SET trangThai = 'Đang xuất NVL' WHERE maYCCC = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("i", $maYCCC);
            $stmt3->execute();

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            // Lưu thông báo lỗi vào session để hiển thị ở View
            $_SESSION['error_message'] = $e->getMessage();
            error_log("Lỗi xuất kho: " . $e->getMessage());
            return false;
        }
    }

    /* ✅ Danh sách xưởng */
    public function getDanhSachXuong()
    {
        $sql = "SELECT maXuong, tenXuong FROM xuong";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc())
                $data[] = $row;
        }
        return $data;
    }
}
