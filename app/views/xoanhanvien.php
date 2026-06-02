<?php
// Nạp layout chung
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<div class="main-layout-wrapper">

    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">

        <style>
        .nhanvien-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px 50px;
            margin: 20px auto;
            max-width: 1000px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            font-size: 22px;
            color: #0d1a44;
            margin-bottom: 15px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f1f5fb;
            color: #0d1a44;
            font-weight: 600;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s ease;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .btn-cancel {
            background-color: #f3f4f6;
            color: #333;
        }

        .btn-cancel:hover {
            background-color: #e5e7eb;
        }

        /* Modal xác nhận */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px 30px;
            width: 350px;
            margin: 15% auto;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .modal-content h3 {
            color: #0d1a44;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        /* Search box */
        .search-bar {
    display: flex;
    align-items: center;
    gap: 8px; /* khoảng cách nhỏ giữa input & button */
    margin-bottom: 15px;
}

.search-bar input {
    width: 260px;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}

.search-bar button {
    padding: 8px 16px;
    background-color: #5a8dee;
    color: white;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.search-bar button:hover {
    background-color: #4076db;
}
        /* Danh sách gợi ý */
.suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    width: 250px; /* hoặc theo độ rộng ô input */
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 1000;
    list-style: none;
    margin-top: 4px;
    padding: 0;
    max-height: 200px;
    overflow-y: auto;
}

/* Mỗi item gợi ý */
.suggestions-list li {
    padding: 8px 10px;
    cursor: pointer;
    transition: background 0.2s;
}

/* Khi hover vào gợi ý */
.suggestions-list li:hover {
    background-color: #f0f0f0;
}
.suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #fff;
    border: none; /* ❌ bỏ đường viền */
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    list-style: none;
    margin: 4px 0 0 0;
    padding: 0;
    max-height: 200px;
    overflow-y: auto;
}

/* Item không có đường kẻ giữa */
.suggestions-list li {
    padding: 8px 10px;
    border: none; /* ❌ bỏ đường kẻ giữa các dòng */
    cursor: pointer;
}

/* Hiệu ứng hover giữ lại */
.suggestions-list li:hover {
    background-color: #f2f2f2;
}

        </style>
        <h2>QUẢN LÝ NHÂN SỰ</h2>
        <div class="search-bar" style="position: relative;">
    <input type="text" id="search" placeholder="Tìm kiếm nhân viên...">
    <button id="refreshBtn">Tìm</button>
    <ul id="suggestions" class="suggestions-list"></ul>
</div>

            <table id="nhanvienTable">
                <thead>
                    <tr>
                        <th>Mã NV</th>
                        <th>Họ tên</th>
                        <th>Giới Tính</th>
                        <th>Ngày Sinh</th>
                        <!-- <th>Chức vụ</th> -->
                        <th>Phòng ban</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nhanviens as $nv): ?>
                    <tr>
                        <td><?= htmlspecialchars($nv['maND']) ?></td>
                        <td><?= htmlspecialchars($nv['hoTen']) ?></td>
                        <td><?= htmlspecialchars($nv['gioiTinh']) ?></td>
                        <td><?= htmlspecialchars($nv['ngaySinh']) ?></td>
                        <!-- <td><?= htmlspecialchars($nv['chucVu']) ?></td> -->
                        <td><?= htmlspecialchars($nv['phongBan']) ?></td>
                        <td><?= htmlspecialchars($nv['diaChi']) ?></td>
                        <td><?= htmlspecialchars($nv['email']) ?></td>
                        <td><?= htmlspecialchars($nv['soDienThoai']) ?></td>
                        <td>
                            <a href="index.php?page=xemnhanvien&id=<?= $nv['maND'] ?>">
                                <i class="fa fa-eye" style="color:blue;"></i>
                            </a>

                            <a href="index.php?page=suathongtinnv&id=<?= $nv['maND'] ?>" class="btn-action edit" title="Sửa">
                                <i class="fas fa-pen"></i>
                            </a>

                            
                            <button class="btn btn-delete" onclick="showDeleteModal('<?= $nv['maND'] ?>')">Xóa</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal xác nhận xóa -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h3>Xác nhận xóa nhân viên</h3>
                <p>Bạn có chắc chắn muốn xóa nhân viên này không?</p>
                <div class="modal-buttons">
                    <button class="btn btn-delete" id="confirmDelete">Xóa</button>
                    <button class="btn btn-cancel" id="cancelDelete">Hủy</button>
                </div>
            </div>
        </div>

<script>
let currentDeleteId = null;
const modal = document.getElementById("deleteModal");
const confirmBtn = document.getElementById("confirmDelete");
const cancelBtn = document.getElementById("cancelDelete");

const searchInput = document.getElementById("search");
const suggestions = document.getElementById("suggestions");
const tbody = document.querySelector("#nhanvienTable tbody");

// ================= MODAL XOÁ =================
function showDeleteModal(maNV) {
    currentDeleteId = maNV;
    modal.style.display = "block";
}

cancelBtn.onclick = function () {
    modal.style.display = "none";
    currentDeleteId = null;
};

confirmBtn.onclick = async function () {
    if (!currentDeleteId) return;

    try {
        const res = await fetch(`index.php?page=xoanhanvien&id=${currentDeleteId}`, {
            method: "POST",
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await res.json();

        if (data.success) {
            alert("Xóa thành công!");
            location.reload();
        } else {
            alert("Xóa thất bại: " + (data.message || "Lỗi không xác định"));
        }

    } catch (err) {
        console.error(err);
        alert("Không thể xóa nhân viên!");
    }

    modal.style.display = "none";
};

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

// ================= RENDER TABLE =================
function renderTable(data) {
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="color:red;font-weight:600">
                    Không tìm thấy nhân viên
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = data.map(nv => `
        <tr>
            <td>${nv.maND}</td>
            <td>${nv.hoTen}</td>
            <td>${nv.gioiTinh}</td>
            <td>${nv.ngaySinh}</td>
            <td>${nv.phongBan}</td>
            <td>${nv.diaChi}</td>
            <td>${nv.email}</td>
            <td>${nv.soDienThoai}</td>
            <td>
                <a href="index.php?page=xemnhanvien&id=${nv.maND}">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="index.php?page=suathongtinnv&id=${nv.maND}">
                    <i class="fas fa-pen"></i>
                </a>
                <button class="btn btn-delete" onclick="showDeleteModal('${nv.maND}')">
                    Xóa
                </button>
            </td>
        </tr>
    `).join("");
}

// ================= HÀM TÌM KIẾM =================
async function searchNhanVien() {
    const keyword = searchInput.value.trim();

    if (!keyword) {
        return; // không hiện thông báo
    }

    try {
        const res = await fetch(`index.php?page=timkiem-nhanvien&keyword=${encodeURIComponent(keyword)}`);
        const data = await res.json();

        renderTable(data);   // chỉ render bảng
        suggestions.innerHTML = "";

    } catch (error) {
        console.error(error);
    }
}

// ================= NÚT TÌM =================
document.getElementById("refreshBtn").addEventListener("click", () => {
    searchNhanVien();
});

// ================= ENTER ĐỂ TÌM =================
searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        searchNhanVien();
    }
});

// ================= AUTOCOMPLETE =================
searchInput.addEventListener("keyup", async () => {
    const keyword = searchInput.value.trim();

    if (keyword.length < 2) {
        suggestions.innerHTML = "";
        return;
    }

    try {
        const res = await fetch(`index.php?page=timkiem-nhanvien&keyword=${encodeURIComponent(keyword)}`);
        const data = await res.json();

        suggestions.innerHTML = data.map(item =>
            `<li data-id="${item.maND}">${item.hoTen}</li>`
        ).join("");

    } catch (error) {
        console.error(error);
    }
});

// ================= CLICK GỢI Ý =================
suggestions.addEventListener("click", async (e) => {
    if (e.target.tagName !== "LI") return;

    searchInput.value = e.target.textContent;
    suggestions.innerHTML = "";
    searchNhanVien();
});
</script>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
