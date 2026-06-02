<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<main class="main-content no-sidebar">

    <div class="about-wrapper">

        <!-- BANNER -->
        <div class="about-banner">
            <h1>HỆ THỐNG QUẢN LÝ SẢN XUẤT ÁO</h1>
            <p>Số hóa – Tối ưu – Quản lý thông minh</p>
        </div>

        <!-- GIỚI THIỆU -->
        <div class="about-section">
            <h2>📌 Giới thiệu hệ thống</h2>
            <p>
                Hệ thống quản lý sản xuất áo được xây dựng nhằm số hóa và tự động hóa
                toàn bộ quy trình quản lý trong công ty. Hệ thống giúp người quản lý theo dõi,
                điều phối và kiểm soát hiệu quả các hoạt động từ nhân sự, nguyên vật liệu
                đến tiến độ sản xuất, thành phẩm và báo cáo thống kê.
            </p>
        </div>

        <!-- MỤC TIÊU -->
        <div class="about-section">
            <h2>🎯 Mục tiêu của hệ thống</h2>
            <ul>
                <li>Quản lý nhân sự, ca làm việc rõ ràng</li>
                <li>Theo dõi tiến độ sản xuất theo công đoạn</li>
                <li>Quản lý kho nguyên vật liệu và thành phẩm</li>
                <li>Giảm sai sót khi làm việc thủ công</li>
                <li>Hỗ trợ ra quyết định nhanh chóng bằng báo cáo</li>
            </ul>
        </div>

        <!-- CHỨC NĂNG CHÍNH -->
        <div class="about-section">
            <h2>⚙️ Chức năng nổi bật</h2>

            <div class="feature-grid">

                <div class="feature-box">
                    <h4>👷 Quản lý nhân sự</h4>
                    <p>Tạo – Sửa – Xóa – Xem thông tin nhân viên, phân công công việc, theo dõi ca làm.</p>
                </div>

                <div class="feature-box">
                    <h4>🧵 Quản lý sản xuất</h4>
                    <p>Lập kế hoạch sản xuất, theo dõi tiến độ, kiểm soát từng công đoạn may.</p>
                </div>

                <div class="feature-box">
                    <h4>📦 Quản lý kho</h4>
                    <p>Quản lý nhập – xuất – tồn nguyên vật liệu và thành phẩm.</p>
                </div>

                <div class="feature-box">
                    <h4>📊 Thống kê – Báo cáo</h4>
                    <p>Tạo báo cáo theo ngày, tháng, năm về sản lượng và hiệu suất.</p>
                </div>

            </div>

        </div>

        <!-- QUY TRÌNH -->
        <div class="about-section">
            <h2>🔄 Quy trình hoạt động</h2>
            <ol>
                <li>Nhập đơn hàng sản xuất</li>
                <li>Lập kế hoạch và chia công đoạn</li>
                <li>Phân công nhân viên theo ca</li>
                <li>Tiến hành sản xuất</li>
                <li>Nhập kho và xuất thành phẩm</li>
                <li>Báo cáo – Thống kê – Đánh giá</li>
            </ol>
        </div>

        <!-- LỢI ÍCH -->
        <div class="about-section">
            <h2>💡 Lợi ích đạt được</h2>
            <ul>
                <li>Tăng năng suất và hiệu quả quản lý</li>
                <li>Giảm thời gian xử lý dữ liệu</li>
                <li>Minh bạch và chính xác thông tin</li>
                <li>Dễ dàng mở rộng và nâng cấp</li>
            </ul>
        </div>

        <!-- BUTTON -->
        <div class="about-btn">
            <a href="?page=home">👉 Bắt đầu sử dụng hệ thống</a>
        </div>

    </div>
</main>


<style>
/* TẮT SIDEBAR ẢNH HƯỞNG */
.main-content.no-sidebar {
    margin-left: 0 !important;
    width: 100% !important;
    background: #f4f6fb;
    padding: 50px 20px;
    display: flex;
    justify-content: center;
}

/* WRAPPER CHÍNH */
.about-wrapper {
    width: 100%;
    max-width: 1000px;
    background: #ffffff;
    border-radius: 20px;
    padding: 40px 50px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    font-family: "Segoe UI", sans-serif;
}

/* BANNER */
.about-banner {
    /* background: linear-gradient(135deg, #0d1a44, #1a4fa3); */
    color: black;
    padding: 40px 30px;
    /* border-radius: 16px; */
    text-align: center;
    margin-bottom: 35px;
}

.about-banner h1 {
    font-size: 26px;
    margin-bottom: 10px;
    font-weight: bold;
}

.about-banner p {
    font-size: 15px;
    opacity: 0.9;
}

/* SECTION */
.about-section {
    margin-bottom: 30px;
}

.about-section h2 {
    color: #0d1a44;
    font-size: 20px;
    margin-bottom: 12px;
}

.about-section p {
    color: #444;
    font-size: 15px;
    line-height: 1.6;
}

/* LIST */
.about-section ul,
.about-section ol {
    padding-left: 20px;
    color: #333;
}

.about-section li {
    margin-bottom: 6px;
    font-size: 15px;
}

/* GRID */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 18px;
    margin-top: 10px;
}

.feature-box {
    background: #f8fafc;
    border-radius: 14px;
    padding: 18px;
    border-left: 5px solid #5a8dee;
    transition: 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}

.feature-box h4 {
    color: #0d1a44;
    margin-bottom: 8px;
    font-size: 15px;
}

.feature-box p {
    font-size: 14px;
    color: #555;
}

/* BUTTON */
.about-btn {
    text-align: center;
    margin-top: 40px;
}

.about-btn a {
    background: #5a8dee;
    color: white;
    border-radius: 10px;
    padding: 12px 28px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    transition: 0.3s;
}

.about-btn a:hover {
    background: #3a6fd8;
    transform: scale(1.05);
}
</style>

<?php require_once 'app/views/layouts/footer.php'; ?>
