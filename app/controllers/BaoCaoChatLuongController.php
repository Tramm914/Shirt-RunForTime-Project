<?php
require_once __DIR__ . '/../models/BaoCaoChatLuongModel.php';

class BaoCaoChatLuongController
{
    private $model;

    public function __construct()
    {
        $this->model = new BaoCaoChatLuongModel();
    }

    // Trang báo cáo chất lượng (1 file duy nhất)
    public function index()
    {
        $dsBaoCao = $this->model->getAllReports();
        require_once 'app/views/baocaochatluong.php';
    }

    // API lấy chi tiết báo cáo (AJAX modal)
    public function getDetail()
    {
        if (!isset($_GET['maYC'])) die("Thiếu mã YC");

        $maYC = intval($_GET['maYC']);
        $data = $this->model->getReportDetail($maYC);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Xuất CSV
    public function export()
    {
        if (!isset($_GET['maYC'])) die("Thiếu mã YC");

        $maYC = intval($_GET['maYC']);
$data = $this->model->getReportDetail($maYC);

$filename = "BaoCao_QC_" . $maYC . "_" . date("Ymd_His") . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename");

$output = fopen("php://output", "w");
fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ["Mã QC", "Sản phẩm","Tổng SL", "SL Đạt", "SL Hỏng", "Ghi chú","Người lập","Ngày kiểm tra"]);
fputcsv($output, [
    $data["maYC"],
    $data["tenSanPham"],
    $data["soLuong"],
    $data["soLuongDat"],
    $data["soLuongHong"],
    $data["ghiChu"],
    $data["tenNguoiLap"],
    date("d/m/Y H:i", strtotime($data["ngayKiemTra"]))

]);

fclose($output);
exit;

    }
}
?>
