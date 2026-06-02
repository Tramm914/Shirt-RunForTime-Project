// public/js/khsx.js

document.addEventListener("DOMContentLoaded", function () {

    // --- LẤY CÁC PHẦN TỬ CẦN THIẾT ---
    const searchBox = document.getElementById("search-box");
    const resultsBody = document.getElementById("results-table-body");

    // Modal chính
    const modal = document.getElementById("order-modal");
    const closeModalBtn = document.getElementById("close-modal-btn");

    // Modal xác nhận thoát
    const confirmModal = document.getElementById("confirm-exit-modal");
    const btnStay = document.getElementById("confirm-exit-no");
    const btnExit = document.getElementById("confirm-exit-yes");

    // BỘ LỌC
    const filterTuNgay = document.getElementById("filter-tuNgay");
    const filterDenNgay = document.getElementById("filter-denNgay");
    const btnClearFilters = document.getElementById("btn-clear-filters");

    // Biến toàn cục lưu dữ liệu cache
    let globalData = {
        danhSachNVL: [],
        danhSachSanPham: []
    };


    // --- 1. LOGIC TÌM KIẾM VÀ TẢI BẢNG ---

    function fetchResults(query, tuNgay, denNgay) {
        resultsBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Đang tải...</td></tr>';
        const url = `index.php?page=ajax-tim-kiem&query=${query}&tuNgay=${tuNgay}&denNgay=${denNgay}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                buildTable(data);
            })
            .catch(error => {
                console.error("Lỗi tìm kiếm:", error);
                resultsBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Lỗi khi tìm kiếm...</td></tr>';
            });
    }

    function buildTable(data) {
        resultsBody.innerHTML = '';
        if (!data || data.length === 0) {
            resultsBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Không tìm thấy kết quả.</td></tr>';
            return;
        }

        data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.maDonHang}</td>
                    <td>${item.tenDonHang}</td>
                    <td>${item.ngayGiao}</td>
                    <td>${item.trangThai}</td>
                    <td>
                        <button class="btn-chon" data-id="${item.maDonHang}">
                            Lập kế hoạch
                        </button>
                    </td>
                </tr>
            `;
            resultsBody.innerHTML += row;
        });
    }

    function triggerSearch() {
        const query = searchBox.value || '';
        const tuNgay = filterTuNgay ? filterTuNgay.value : '';
        const denNgay = filterDenNgay ? filterDenNgay.value : '';
        fetchResults(query, tuNgay, denNgay);
    }

    // Gắn sự kiện bộ lọc
    if (searchBox) searchBox.addEventListener("input", triggerSearch);
    if (filterTuNgay) filterTuNgay.addEventListener("change", triggerSearch);
    if (filterDenNgay) filterDenNgay.addEventListener("change", triggerSearch);
    if (btnClearFilters) {
        btnClearFilters.addEventListener("click", function () {
            if (searchBox) searchBox.value = '';
            if (filterTuNgay) filterTuNgay.value = '';
            if (filterDenNgay) filterDenNgay.value = '';
            triggerSearch();
        });
    }

    // Tải lần đầu
    if (resultsBody) triggerSearch();


    // --- 2. LOGIC MODAL POP-UP (QUAN TRỌNG) ---

    function openOrderModal(id) {
        // Gọi AJAX lấy dữ liệu chi tiết
        fetch('index.php?page=ajax-get-modal-data&id=' + id)
            .then(response => response.json())
            .then(data => {
                const donHang = data.donHang;

                if (!donHang) {
                    alert("Không thể tải chi tiết đơn hàng.");
                    return;
                }

                // Lưu vào biến toàn cục để dùng lại cho nút "Thêm NVL"
                globalData.danhSachNVL = data.danhSachNVL || [];
                globalData.danhSachSanPham = data.danhSachSanPham || [];

                // --- A. ĐIỀN THÔNG TIN ĐƠN HÀNG ---
                document.getElementById('modal-maDonHang').value = donHang.maDonHang;
                document.getElementById('modal-maDonHang-display').textContent = donHang.maDonHang;
                document.getElementById("modal-tenDonHang").innerText = donHang.tenDonHang;
                document.getElementById("modal-tenSanPham").innerText = donHang.tenSanPham;
                document.getElementById("modal-donVi").innerText = donHang.donVi;
                document.getElementById("modal-ngayGiao").innerText = donHang.ngayGiao;

                // --- B. ĐIỀN NĂNG SUẤT TỪNG XƯỞNG ---
                const nsCat = parseInt(data.nangSuat.xuongCat) || 0;
                const nsMay = parseInt(data.nangSuat.xuongMay) || 0;

                document.getElementById('modal-ns-cat').textContent = nsCat > 0 ? nsCat : 'Chưa có dữ liệu';
                document.getElementById('modal-ns-may').textContent = nsMay > 0 ? nsMay : 'Chưa có dữ liệu';

                // --- C. TÍNH TOÁN NGÀY BẮT ĐẦU DỰ KIẾN ---
                // Logic: Lấy năng suất thấp nhất (nút thắt cổ chai) để tính số ngày cần thiết
                const slCanLam = parseInt(donHang.soLuongSanXuat) || 0;

                // Tìm năng suất thấp nhất khác 0 (nếu cả 2 đều 0 thì mặc định là 1 để tránh chia cho 0)
                let nsThapNhat = Math.min(nsCat > 0 ? nsCat : Infinity, nsMay > 0 ? nsMay : Infinity);
                if (nsThapNhat === Infinity) nsThapNhat = 50; // Giá trị mặc định nếu chưa có lịch sử

                const soNgayCan = Math.ceil(slCanLam / nsThapNhat);

                // Gán ngày kết thúc = Ngày giao hàng
                const ngayKetThucInput = document.getElementById("ngay_ket_thuc_kh");
                ngayKetThucInput.value = donHang.ngayGiao;

                // Tính ngày bắt đầu = Ngày giao - Số ngày cần
                const ngayBatDauInput = document.getElementById("ngay_bat_dau_kh");
                if (donHang.ngayGiao) {
                    const dateGiao = new Date(donHang.ngayGiao);
                    const dateBatDau = new Date(dateGiao);
                    dateBatDau.setDate(dateGiao.getDate() - soNgayCan);

                    // Format YYYY-MM-DD
                    const yyyy = dateBatDau.getFullYear();
                    const mm = String(dateBatDau.getMonth() + 1).padStart(2, '0');
                    const dd = String(dateBatDau.getDate()).padStart(2, '0');

                    ngayBatDauInput.value = `${yyyy}-${mm}-${dd}`;
                }

                // --- D. XỬ LÝ DROPDOWN SẢN PHẨM & NVL ---
                // Reset danh sách NVL cũ
                document.getElementById("xuong-cat-nvl-list").innerHTML = '';
                document.getElementById("xuong-may-nvl-list").innerHTML = '';

                // Điền dropdown chọn Sản phẩm cho 2 xưởng
                const productSelects = document.querySelectorAll(".product-select");
                productSelects.forEach(select => {
                    select.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
                    globalData.danhSachSanPham.forEach(sp => {
                        select.innerHTML += `<option value="${sp.maSanPham}">${sp.tenSanPham}</option>`;
                    });

                    // Tự động chọn sản phẩm trùng với đơn hàng (Quan trọng để fix lỗi Foreign Key)
                    if (donHang.maSanPham) {
                        select.value = donHang.maSanPham;
                    }
                });

                // Tự động thêm 1 dòng NVL trống cho mỗi xưởng để tiện nhập
                addNvlRow('xuong-cat-nvl-list', 'xuong_cat');
                addNvlRow('xuong-may-nvl-list', 'xuong_may');

                // Hiển thị Modal
                modal.classList.add("show");
            })
            .catch(error => {
                console.error("Lỗi lấy chi tiết:", error);
                alert("Lỗi khi tải chi tiết đơn hàng.");
            });
    }

    // Bắt sự kiện click nút "Lập kế hoạch"
    if (resultsBody) {
        resultsBody.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains('btn-chon')) {
                const donHangId = e.target.dataset.id;
                openOrderModal(donHangId);
            }
        });
    }

    // --- 3. XỬ LÝ ĐÓNG/MỞ MODAL ---
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => confirmModal.classList.add("show"));
    }
    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) confirmModal.classList.add("show");
        });
    }
    if (btnStay) {
        btnStay.addEventListener("click", () => confirmModal.classList.remove("show"));
    }
    if (btnExit) {
        btnExit.addEventListener("click", () => {
            confirmModal.classList.remove("show");
            modal.classList.remove("show");
        });
    }

    // --- 4. LOGIC THÊM/XÓA NVL ĐỘNG ---

    function createNvlRowHTML(formNamePrefix) {
        let nvlOptions = '<option value="">-- Chọn NVL --</option>';
        globalData.danhSachNVL.forEach(nvl => {
            // Hiển thị cả tên và đơn vị tính
            nvlOptions += `<option value="${nvl.maNVL}">${nvl.tenNVL} (${nvl.donViTinh || ''})</option>`;
        });

        return `
            <div class="nvl-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
                <select name="${formNamePrefix}[nvl_id][]" style="flex: 2;" required>
                    ${nvlOptions}
                </select>
                <input type="number" name="${formNamePrefix}[nvl_soLuong][]" placeholder="SL" min="1" style="flex: 1;" required>
                <button type="button" class="btn-remove-nvl" style="background: #dc3545; color: white; border: none; padding: 0 10px; cursor: pointer;">&times;</button>
            </div>
        `;
    }

    function addNvlRow(targetListId, formNamePrefix) {
        const targetList = document.getElementById(targetListId);
        const rowHTML = createNvlRowHTML(formNamePrefix);
        targetList.insertAdjacentHTML('beforeend', rowHTML);
    }

    // Gắn sự kiện click chung (Event Delegation)
    if (modal) {
        modal.addEventListener('click', function (e) {
            // Nút Thêm NVL
            if (e.target && e.target.classList.contains('btn-add-nvl')) {
                const targetListId = e.target.dataset.target;
                const formNamePrefix = (targetListId === 'xuong-cat-nvl-list') ? 'xuong_cat' : 'xuong_may';
                addNvlRow(targetListId, formNamePrefix);
            }
            // Nút Xóa NVL
            if (e.target && e.target.classList.contains('btn-remove-nvl')) {
                e.target.closest('.nvl-row').remove();
            }
        });
    }

});