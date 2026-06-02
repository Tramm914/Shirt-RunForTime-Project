<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <style>
        .main-content {
            background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        </style>
        <div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
            <h2 class="main-title" style="text-align: center; font-size: 1.5em; margin-bottom: 30px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
                LẬP BÁO CÁO LỖI & SỰ CỐ
            </h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Lỗi!</strong> <?= htmlspecialchars($_GET['msg'] ?? 'Đã xảy ra lỗi khi gửi báo cáo.') ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    <strong>Thành công!</strong> Báo cáo sự cố đã được gửi.
                </div>
            <?php endif; ?>

            <div class="card" style="background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px;">
                <form action="index.php?page=luu-baocaosuco" method="POST" enctype="multipart/form-data" id="formBaoCao">

                    <!-- Xưởng -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="xuong" class="required" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Xưởng
                        </label>
                        <select id="xuong" name="xuong" class="form-control" required>
                            <option value="">-- Chọn xưởng --</option>
                            <option value="1">Xưởng cắt</option>
                            <option value="2">Xưởng may</option>
                        </select>
                    </div>

                    <!-- Mã thiết bị -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="ma_thiet_bi" class="required" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Mã Thiết Bị
                        </label>
                        <select id="ma_thiet_bi" name="ma_thiet_bi" class="form-control" required>
                            <option value="">-- Chọn thiết bị --</option>
                        </select>
                    </div>

                    <!-- Tên thiết bị -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="ten_thiet_bi" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Tên Thiết Bị
                        </label>
                        <input type="text" id="ten_thiet_bi" name="ten_thiet_bi" class="form-control" readonly>
                    </div>

                    <!-- Loại lỗi -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="loai_loi" class="required" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Loại Lỗi
                        </label>
                        <select id="loai_loi" name="loai_loi" class="form-control" required>
                            <option value="">-- Chọn loại lỗi --</option>
                            <option value="phanmem">Lỗi phần mềm</option>
                            <option value="phancung">Lỗi phần cứng</option>
                            <option value="khac">Khác</option>
                        </select>
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="mo_ta" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Mô Tả Chi Tiết
                        </label>
                        <textarea id="mo_ta" name="mo_ta" class="form-control" rows="4" placeholder="Mô tả chi tiết lỗi hoặc sự cố..."></textarea>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label for="hinh_anh" style="display:block;margin-bottom:8px;font-weight:600;color:#333;">
                            Hình Ảnh Minh Họa (nếu có)
                        </label>
                        <input type="file" id="hinh_anh" name="hinh_anh" class="form-control">
                    </div>

                    <div class="button-group" style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?page=home'">
                            Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Gửi Báo Cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const xuongSelect = document.getElementById("xuong");
    const maThietBiSelect = document.getElementById("ma_thiet_bi");
    const tenThietBiInput = document.getElementById("ten_thiet_bi");

    xuongSelect.addEventListener("change", async function() {
        const xuong = this.value;
        maThietBiSelect.innerHTML = '<option value="">-- Chọn thiết bị --</option>';
        tenThietBiInput.value = '';

        if (!xuong) return;

        try {
            const res = await fetch(`index.php?page=search&type=thietbi&keyword=&xuong=${encodeURIComponent(xuong)}`);
            const data = await res.json();

            if (data.length > 0) {
                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.maThietBi;
                    option.textContent = `${item.maThietBi} - ${item.tenThietBi}`;
                    option.dataset.ten = item.tenThietBi;
                    maThietBiSelect.appendChild(option);
                });
            } else {
                const opt = document.createElement("option");
                opt.textContent = "Không có thiết bị nào trong xưởng này";
                maThietBiSelect.appendChild(opt);
            }
        } catch (err) {
            console.error("Lỗi khi tải thiết bị:", err);
        }
    });

    maThietBiSelect.addEventListener("change", function() {
        const selected = this.options[this.selectedIndex];
        tenThietBiInput.value = selected ? (selected.dataset.ten || "") : "";
    });

    // Validate form
    document.getElementById('formBaoCao').addEventListener('submit', function(e) {
        const xuong = xuongSelect.value.trim();
        const maTB = maThietBiSelect.value.trim();
        const loai = document.getElementById('loai_loi').value.trim();

        if (!xuong || !maTB || !loai) {
            e.preventDefault();
            alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
            return false;
        }
        return true;
    });
});
</script>

<style>
.required::after {
    content: " *";
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #085da7 !important;
    box-shadow: 0 0 0 0.2rem rgba(8, 93, 167, 0.25);
}

.btn-primary {
    background-color: #085da7;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn-primary:hover {
    background-color: #064a85;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    padding: 12px 30px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn-secondary:hover {
    background-color: #5a6268;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px !important;
    }
    .button-group {
        flex-direction: column;
    }
    .button-group button {
        width: 100%;
    }
}
</style>
