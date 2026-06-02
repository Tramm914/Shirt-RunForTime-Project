<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../models/PhieuYeuCauNhapKhoModel.php';

$maYCNK = intval($_GET['maYCNK'] ?? 0);

if ($maYCNK <= 0) {
    echo json_encode(['error' => 'Mã phiếu không hợp lệ']);
    exit;
}

$model = new PhieuYeuCauNhapKhoModel();
$details = $model->getDetailsByRequest($maYCNK);

echo json_encode($details, JSON_UNESCAPED_UNICODE);
