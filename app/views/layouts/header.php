<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Sản Xuất</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <header class="header">
        <div class="logo" id="logo-interlock">
            <span>R</span><span class="middle-letter">F</span><span>T</span>
        </div>
        <h1>HỆ THỐNG QUẢN LÝ SẢN XUẤT</h1>
        <div class="user-controls">
            <i class="fa-solid fa-magnifying-glass"></i>
            <i class="fa-regular fa-bell"></i>
            <a href="<?php echo isset($_SESSION['user']) ? 'index.php?page=thong-tin-ca-nhan' : 'index.php?page=login'; ?>"
                class="user-link">
                <i class="fa-regular fa-circle-user"></i>
                <?php
                if (isset($_SESSION['user'])) {
                    $tenHienThi = htmlspecialchars($_SESSION['user']['hoTen'] ?? 'Người dùng');
                    echo '<span class="welcome-text"><b>' . $tenHienThi . '</b></span>';
                } else {
                    echo '<span class="welcome-text"><b>Tài Khoản</b></span>';
                }
                ?>
            </a>

        </div>
    </header>