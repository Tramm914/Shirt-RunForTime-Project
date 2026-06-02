<?php
require_once 'app/models/ThongKeNVLModel.php';

class ThongKeNVLController {
    private $model;

    public function __construct() {
        $this->model = new ThongKeNVLModel();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $start = $_POST['start_date'] ?? '';
            $end = $_POST['end_date'] ?? '';
            $tenNVL = $_POST['tenNVL'] ?? '';
            $loai = $_POST['loai_baocao'] ?? '';
$allNVL = isset($_POST['all_nvl']);

if ($allNVL) {
    $tenNVL = ''; // Bỏ lọc theo tên
}
            // Trả về JSON
            $rows = $this->model->layThongKeKhoNVL($start, $end, $tenNVL, $loai);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($rows);
            exit;
        }

        // Hiển thị view
        require_once 'app/views/thongkekhonvl.php';
    }

    public function xuatCSV() {
    // Nhận tham số lọc từ form xuất CSV (ẩn trong view)
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    $tenNVL = $_GET['tenNVL'] ?? '';
    $loai = $_GET['loai'] ?? '';
$allNVL = !empty($_GET['all_nvl']); // chỉ true khi có giá trị và khác rỗng

if ($allNVL) {
    $tenNVL = '';
}

    // Nếu thiếu ngày, báo lỗi nhẹ
    if (empty($start_date) || empty($end_date)) {
        die('⚠️ Vui lòng chọn khoảng thời gian trước khi xuất CSV.');
    }

    // Lấy dữ liệu thống kê theo bộ lọc đang hiển thị
    $data = $this->model->layThongKeKhoNVL($start_date, $end_date, $tenNVL, $loai);

    // Tên file CSV có ngày giờ
    $filename = "ThongKe_KhoNVL_" . date('Ymd_His') . ".csv";

    // Header để tải file CSV
    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo "\xEF\xBB\xBF"; // BOM UTF-8 để Excel đọc đúng tiếng Việt

    $output = fopen("php://output", "w");

    // Ghi tiêu đề cột
    fputcsv($output, [
        "Mã NVL",
        "Tên nguyên vật liệu",
        "Đơn vị tính",
        "Tổng nhập (trong kỳ)",
        "Tổng xuất (trong kỳ)",
        "Tồn kho hiện tại"
    ]);

    // Ghi dữ liệu từng dòng
    foreach ($data as $row) {
        fputcsv($output, [
            $row['maNVL'],
            $row['tenNVL'],
            $row['donViTinh'],
            $row['tongNhap'],
            $row['tongXuat'],
            $row['tonKho']
        ]);
    }

    fclose($output);
    exit;
}

}
?>
