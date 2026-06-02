<?php
require_once './app/models/YeuCauKiemTraChatLuongModel.php';

class YeuCauKiemTraChatLuongController {

    // Hiển thị form
    public function index() {
        $model = new YeuCauKiemTraChatLuongModel();
        $plans = $model->getApprovedPlans(); // lấy danh sách kế hoạch
        require_once './app/views/taoYCKiemTraChatLuong.php';
    }

    // Xử lý tạo phiếu
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=tao-yeu-cau-kiem-tra-chat-luong");
            return;
        }

        $maKHSX = $_POST['planCode'] ?? null;
        $thoiHanHoanThanh = $_POST['thoiHanHoanThanh'] ?? null;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $tenNguoiLap = isset($_SESSION['user']['hoTen']) ? $_SESSION['user']['hoTen'] : 'Admin';

        if (!$maKHSX) {
            echo "<script>alert('❌ Vui lòng chọn kế hoạch sản xuất!');history.back();</script>";
            exit;
        }

        if (!$thoiHanHoanThanh) {
            echo "<script>alert('❌ Vui lòng chọn thời hạn hoàn thành kiểm tra!');history.back();</script>";
            exit;
        }

        $model = new YeuCauKiemTraChatLuongModel();
        $product = $model->getProductByPlan($maKHSX);

        if (!$product) {
            echo "<script>alert('❌ Không tìm thấy thông tin sản phẩm hoặc kế hoạch chưa Hoàn thành!');history.back();</script>";
            exit;
        }

        // Validate: Thời hạn phải >= ngayKetThuc của kế hoạch (không cho phép chọn trước ngày kết thúc kế hoạch)
        if (isset($product['ngayKetThuc']) && $thoiHanHoanThanh < $product['ngayKetThuc']) {
            echo "<script>alert('❌ Thời hạn kiểm tra không được trước ngày kết thúc kế hoạch ({$product['ngayKetThuc']})!');history.back();</script>";
            exit;
        }
        
        // Validate: Thời hạn không được vượt quá 3 ngày từ ngayKetThuc
        $maxDate = date('Y-m-d', strtotime($product['ngayKetThuc'] . ' +3 days'));
        if ($thoiHanHoanThanh > $maxDate) {
            echo "<script>alert('❌ Thời hạn kiểm tra tối đa là {$maxDate} (Ngày kết thúc kế hoạch + 3 ngày)!');history.back();</script>";
            exit;
        }

        // Tạo tên phiếu
        $tenPhieu = 'Phiếu KTCL - ' . $product['tenKHSX'];
        
        // Thêm phiếu chính (có thời hạn hoàn thành)
        $maYC = $model->themPhieuYeuCau(
            $tenNguoiLap, 
            $tenPhieu, 
            $product['maSanPham'], 
            $maKHSX,
            $thoiHanHoanThanh
        );

        if ($maYC) {
            // Thêm chi tiết phiếu (1 sản phẩm duy nhất)
            $resultDetail = $model->themChiTietPhieu(
                $maYC,
                $product['maSanPham'],
                $product['tenSanPham'],
                $product['soLuongSanXuat'],
                $product['donVi']
            );
            
            if ($resultDetail) {
                echo "<script>
                    alert('✅ Tạo phiếu kiểm tra chất lượng thành công!');
                    window.location='index.php?page=tao-yeu-cau-kiem-tra-chat-luong';
                </script>";
            } else {
                echo "<script>alert('❌ Lỗi khi thêm chi tiết phiếu!');history.back();</script>";
            }
        } else {
            echo "<script>alert('❌ Lỗi khi tạo phiếu!');history.back();</script>";
        }
    }
}
?>
