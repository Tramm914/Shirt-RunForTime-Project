<?php
require_once 'ketNoi.php';

class NhapKhoTP_DaCheckQCModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    /**
     * Lấy danh sách phiếu kiểm tra chất lượng chưa nhập kho
     * Lấy từ bảng: phieuyeucaukiemtrachatluong và chitietphieuyeucaukiemtrachatluong
     * Chỉ lấy những phiếu chưa có trong nhapkhotp (chưa nhập kho) để có thể nhập kho mới
     */
    public function getDanhSachPhieuQCDaDat() {
        try {
            // Lấy danh sách phiếu QC chưa nhập kho - CHỈ LẤY SỐ LƯỢNG ĐẠT (soLuongDat)
            $sql = "SELECT 
                        p.maYC AS maPhieu,
                        p.maYC AS maYC,
                        COALESCE(ct.tenSanPham, sp.tenSanPham) AS sanPham,
                        ct.soLuongDat AS soLuong,
                        ct.soLuongDat AS soLuongDat,
                        ct.ngayKiemTra AS ngayKiemTra,
                        p.maSanPham,
                        p.tenNguoiLap,
                        p.tenPhieu,
                        p.trangThai,
                        ct.tenSanPham AS tenSanPhamChiTiet
                    FROM phieuyeucaukiemtrachatluong p
                    LEFT JOIN chitietphieuyeucaukiemtrachatluong ct ON p.maYC = ct.maYC
                    LEFT JOIN san_pham sp ON p.maSanPham = sp.maSanPham
                    LEFT JOIN nhapkhotp n ON p.maYC = n.maYC
                    WHERE n.maPhieu IS NULL
                    ORDER BY p.maYC DESC";
            
            $result = $this->conn->query($sql);

            if (!$result) {
                error_log("Lỗi truy vấn danh sách phiếu QC. SQL: " . $sql . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi getDanhSachPhieuQCDaDat: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lấy chi tiết phiếu kiểm tra chất lượng theo mã phiếu (maYC)
     * Lấy từ bảng: phieuyeucaukiemtrachatluong và chitietphieuyeucaukiemtrachatluong
     * Chỉ lấy những phiếu chưa nhập kho để có thể nhập kho mới
     */
    public function getChiTietPhieuQC($maYC) {
        try {
            $sql = "SELECT 
                        p.maYC AS maPhieu,
                        p.maYC AS maYC,
                        COALESCE(ct.tenSanPham, sp.tenSanPham) AS sanPham,
                        ct.soLuongDat AS soLuong,
                        ct.soLuongDat AS soLuongDat,
                        ct.ngayKiemTra AS ngayKiemTra,
                        p.maSanPham,
                        p.tenNguoiLap,
                        p.tenPhieu,
                        p.ngayLap,
                        p.trangThai,
                        sp.tenSanPham AS tenSanPhamFull,
                        ct.tenSanPham AS tenSanPhamChiTiet,
                        ct.donViTinh,
                        ct.trangThaiSanPham
                    FROM phieuyeucaukiemtrachatluong p
                    LEFT JOIN chitietphieuyeucaukiemtrachatluong ct ON p.maYC = ct.maYC
                    LEFT JOIN san_pham sp ON p.maSanPham = sp.maSanPham
                    LEFT JOIN nhapkhotp n ON p.maYC = n.maYC
                    WHERE p.maYC = ? 
                        AND n.maPhieu IS NULL
                    LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log("Lỗi chuẩn bị truy vấn chi tiết phiếu QC. SQL: " . $sql . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $stmt->bind_param("i", $maYC);

            if (!$stmt->execute()) {
                error_log("Lỗi thực thi truy vấn chi tiết phiếu QC. SQL: " . $sql . " | Params: maYC=" . $maYC . " | Error: " . $stmt->error);
                $stmt->close();
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();

            return $data;
        } catch (Exception $e) {
            error_log("Lỗi getChiTietPhieuQC: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Nhập kho thành phẩm từ phiếu QC đã đạt
     * Hậu điều kiện: Thành phẩm được ghi nhận vào kho thành phẩm, cập nhật số lượng tồn kho
     * Lưu vào bảng: nhapkhotp và chitiet_nhapkhotp
     * @param int $maYC Mã yêu cầu kiểm tra chất lượng (maYC từ phieuyeucaukiemtrachatluong)
     */
    public function nhapKhoThanhPham($maYC, $maSanPham, $soLuong, $nguoiLap = null, $hanhDong = 'Nhập kho thành phẩm') {
        // Bắt đầu transaction
        $this->conn->begin_transaction();

        try {
            // Kiểm tra kết nối database
            if (!$this->conn || $this->conn->connect_error) {
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            // 1. Kiểm tra lại phiếu có hợp lệ không và chưa nhập kho
            // Lấy thông tin chi tiết từ cả 2 bảng
            $checkSql = "SELECT p.maYC, p.trangThai, p.tenNguoiLap, 
                                ct.soLuongDat, ct.trangThaiSanPham, ct.ngayKiemTra, 
                                ct.tenSanPham, sp.tenSanPham AS tenSanPhamSP
                        FROM phieuyeucaukiemtrachatluong p
                        LEFT JOIN chitietphieuyeucaukiemtrachatluong ct ON p.maYC = ct.maYC
                        LEFT JOIN san_pham sp ON p.maSanPham = sp.maSanPham
                        LEFT JOIN nhapkhotp n ON p.maYC = n.maYC
                        WHERE p.maYC = ? 
                        AND n.maPhieu IS NULL
                        LIMIT 1";
            
            $checkStmt = $this->conn->prepare($checkSql);
            if (!$checkStmt) {
                error_log("Lỗi chuẩn bị truy vấn check phiếu. SQL: " . $checkSql . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }
            $checkStmt->bind_param("i", $maYC);
            if (!$checkStmt->execute()) {
                error_log("Lỗi thực thi truy vấn check phiếu. SQL: " . $checkSql . " | Params: maYC=" . $maYC . " | Error: " . $checkStmt->error);
                $checkStmt->close();
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }
            $checkResult = $checkStmt->get_result();
            $phieuData = $checkResult->fetch_assoc();
            
            if (!$phieuData || $checkResult->num_rows === 0) {
                $checkStmt->close();
                throw new Exception("Phiếu này không hợp lệ hoặc đã được nhập kho.");
            }
            $checkStmt->close();

            // Lấy thông tin từ kết quả
            $ngayKiemTra = $phieuData['ngayKiemTra'] ? date('Y-m-d', strtotime($phieuData['ngayKiemTra'])) : date('Y-m-d');
            $tenNguoiLapPhieu = $nguoiLap ? $nguoiLap : $phieuData['tenNguoiLap'];
            $tenSanPham = $phieuData['tenSanPham'] ? $phieuData['tenSanPham'] : $phieuData['tenSanPhamSP'];

            // 2. Tạo phiếu nhập kho trong bảng nhapkhotp
            $sqlNhapKho = "INSERT INTO nhapkhotp (maYC, ngayKiemTra, nguoiLap, hanhDong) 
                          VALUES (?, ?, ?, ?)";
            
            $stmtNhapKho = $this->conn->prepare($sqlNhapKho);
            if (!$stmtNhapKho) {
                error_log("Lỗi chuẩn bị truy vấn tạo nhapkhotp. SQL: " . $sqlNhapKho . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $stmtNhapKho->bind_param("isss", $maYC, $ngayKiemTra, $tenNguoiLapPhieu, $hanhDong);

            if (!$stmtNhapKho->execute()) {
                $errorMsg = "Lỗi tạo phiếu nhập kho. SQL: " . $sqlNhapKho . " | Params: maYC=" . $maYC . ", ngayKiemTra=" . $ngayKiemTra . ", nguoiLap=" . $tenNguoiLapPhieu . " | Error: " . $stmtNhapKho->error;
                error_log($errorMsg);
                throw new Exception("Lỗi tạo phiếu nhập kho. Vui lòng thử lại sau.");
            }
            
            $maPhieuNhapKho = $this->conn->insert_id;
            $stmtNhapKho->close();

            // 3. Tạo chi tiết nhập kho trong bảng chitiet_nhapkhotp
            $sqlChiTiet = "INSERT INTO chitiet_nhapkhotp (maPhieu, maSanPham, tenSanPham, soLuong, hanhDong) 
                          VALUES (?, ?, ?, ?, ?)";
            
            $stmtChiTiet = $this->conn->prepare($sqlChiTiet);
            if (!$stmtChiTiet) {
                error_log("Lỗi chuẩn bị truy vấn chitiet_nhapkhotp. SQL: " . $sqlChiTiet . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $stmtChiTiet->bind_param("iisis", $maPhieuNhapKho, $maSanPham, $tenSanPham, $soLuong, $hanhDong);

            if (!$stmtChiTiet->execute()) {
                $errorMsg = "Lỗi tạo chi tiết nhập kho. SQL: " . $sqlChiTiet . " | Params: maPhieu=" . $maPhieuNhapKho . ", maSanPham=" . $maSanPham . ", soLuong=" . $soLuong . " | Error: " . $stmtChiTiet->error;
                error_log($errorMsg);
                throw new Exception("Lỗi tạo chi tiết nhập kho. Vui lòng thử lại sau.");
            }
            
            $stmtChiTiet->close();

            // 4. Cập nhật số lượng tồn kho trong bảng san_pham
            $sqlUpdateKho = "UPDATE san_pham 
                           SET soLuongTon = soLuongTon + ? 
                           WHERE maSanPham = ?";
            
            $stmtUpdateKho = $this->conn->prepare($sqlUpdateKho);

            if (!$stmtUpdateKho) {
                error_log("Lỗi chuẩn bị truy vấn cập nhật kho. SQL: " . $sqlUpdateKho . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $stmtUpdateKho->bind_param("ii", $soLuong, $maSanPham);

            if (!$stmtUpdateKho->execute()) {
                error_log("Lỗi cập nhật kho. SQL: " . $sqlUpdateKho . " | Params: soLuong=" . $soLuong . ", maSanPham=" . $maSanPham . " | Error: " . $stmtUpdateKho->error);
                throw new Exception("Lỗi cập nhật kho. Vui lòng thử lại sau.");
            }
            
            $stmtUpdateKho->close();

            // 5. Cập nhật trạng thái phiếu QC thành "Đã nhập kho"
            $sqlUpdateTrangThai = "UPDATE phieuyeucaukiemtrachatluong 
                                  SET trangThai = 'Đã nhập kho'
                                  WHERE maYC = ?";
            
            $stmtUpdateTrangThai = $this->conn->prepare($sqlUpdateTrangThai);

            if (!$stmtUpdateTrangThai) {
                error_log("Lỗi chuẩn bị truy vấn cập nhật trạng thái phiếu. SQL: " . $sqlUpdateTrangThai . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }

            $stmtUpdateTrangThai->bind_param("i", $maYC);

            if (!$stmtUpdateTrangThai->execute()) {
                error_log("Lỗi cập nhật trạng thái phiếu. SQL: " . $sqlUpdateTrangThai . " | Params: maYC=" . $maYC . " | Error: " . $stmtUpdateTrangThai->error);
                throw new Exception("Lỗi cập nhật trạng thái phiếu. Vui lòng thử lại sau.");
            }
            
            $stmtUpdateTrangThai->close();

            // 6️⃣ Cập nhật trạng thái đơn hàng thành "Chờ xuất kho"
            $sqlUpdateDH = "UPDATE donhangsanxuat 
                           SET trangThai = 'Chờ xuất kho'
                           WHERE maSanPham = ? 
                           AND trangThai != 'Chờ xuất kho'
                           LIMIT 1";
            
            $stmtUpdateDH = $this->conn->prepare($sqlUpdateDH);

            if (!$stmtUpdateDH) {
                error_log("Lỗi chuẩn bị truy vấn cập nhật đơn hàng. SQL: " . $sqlUpdateDH . " | Error: " . $this->conn->error);
                throw new Exception("Lỗi cập nhật trạng thái đơn hàng. Vui lòng thử lại sau.");
            }

            $stmtUpdateDH->bind_param("i", $maSanPham);

            if (!$stmtUpdateDH->execute()) {
                error_log("Lỗi cập nhật đơn hàng. SQL: " . $sqlUpdateDH . " | Params: maSanPham=" . $maSanPham . " | Error: " . $stmtUpdateDH->error);
                throw new Exception("Lỗi cập nhật trạng thái đơn hàng. Vui lòng thử lại sau.");
            }
            
            $stmtUpdateDH->close();

            // Commit transaction
            if (!$this->conn->commit()) {
                throw new Exception("Lỗi kết nối hệ thống. Vui lòng thử lại sau.");
            }
            
            return true;

        } catch (Exception $e) {
            // Rollback nếu có lỗi
            if ($this->conn && !$this->conn->connect_error) {
                $this->conn->rollback();
            }
            error_log("Lỗi nhập kho thành phẩm: " . $e->getMessage());
            throw $e; // Re-throw để controller xử lý
        }
    }

    /**
     * Kiểm tra phiếu đã được nhập kho chưa
     * Kiểm tra trong bảng: phieuyeucaukiemtrachatluong (trangThai) và nhapkhotp (maYC)
     * @param int $maYC Mã yêu cầu kiểm tra chất lượng
     */
    public function kiemTraPhieuDaNhapKho($maYC) {
        try {
            // Kiểm tra trong bảng nhapkhotp (ưu tiên) hoặc trangThai trong phieuyeucaukiemtrachatluong
            $sql = "SELECT p.trangThai, n.maPhieu 
                    FROM phieuyeucaukiemtrachatluong p
                    LEFT JOIN nhapkhotp n ON p.maYC = n.maYC
                    WHERE p.maYC = ?";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param("i", $maYC);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();

            // Nếu có bản ghi trong nhapkhotp hoặc trangThai = 'Đã nhập kho' thì đã nhập kho
            return isset($data) && (
                (isset($data['maPhieu']) && $data['maPhieu'] > 0) ||
                (isset($data['trangThai']) && $data['trangThai'] === 'Đã nhập kho')
            );
        } catch (Exception $e) {
            error_log("Lỗi kiemTraPhieuDaNhapKho: " . $e->getMessage());
            return false;
        }
    }
}
?>

