<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../models/PhieuYeuCauNhapKhoModel.php';

$model = new PhieuYeuCauNhapKhoModel();

$maKHSX = isset($_GET['maKHSX']) ? intval($_GET['maKHSX']) : 0;
$exists = $model->existsByKeHoach($maKHSX);

echo json_encode(['exists' => $exists]);
exit;
?>
