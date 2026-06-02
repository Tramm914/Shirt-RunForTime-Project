<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../models/PhieuYeuCauNhapKhoModel.php';

$model = new PhieuYeuCauNhapKhoModel();
$maKHSX = isset($_GET['maKHSX']) ? intval($_GET['maKHSX']) : 0;

if ($maKHSX > 0) {
    $data = $model->getMaterialsByPlan($maKHSX);
    echo json_encode($data);
} else {
    echo json_encode([]);
}
exit;
?>
