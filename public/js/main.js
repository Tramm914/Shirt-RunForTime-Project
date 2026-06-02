document.addEventListener("DOMContentLoaded", function() {
    const btnMenu = document.getElementById("menuDanhMuc");
    const dropdown = document.getElementById("dropdownDanhMuc");
    const sidebarList = document.getElementById("sidebarList");

    const danhMucData = {
        tongquan: ["Thông tin cá nhân", "Báo cáo tổng hợp", "Phê duyệt kế hoạch sản xuất"],
        nhansu: ["Thêm nhân viên", "Xem nhân viên", "Xóa nhân viên", "Sửa nhân viên"],
        sanxuat: ["Tạo đơn hàng sản xuất", "Lập kế hoạch sản xuất", "Phê duyệt các yêu cầu sản xuất"],
        khoNVL: ["Tạo yêu cầu nhập nguyên vật liệu", "Nhập kho nguyên vật liệu", "Xuất nguyên vật liệu", "Thống kê kho nguyên vật liệu"],
        xuong: ["Xem công việc", "Theo dõi tiến độ", "Yêu cầu cung cấp NVL", "Yêu cầu kiểm tra chất lượng"],
        qc: ["Cập nhật thành phẩm", "Báo cáo chất lượng"],
        khoTP: ["Nhập kho thành phẩm", "Xuất kho thành phẩm", "Thống kê kho thành phẩm"],
        congnhan: ["Lịch làm việc", "Báo cáo sự cố"]
    };

    const linkMap = {
        "Xem công việc": "index.php?page=xem-cong-viec",
        "Theo dõi tiến độ": "index.php?page=ghi-nhan-tp",
        "Báo cáo tổng hợp": "index.php?page=bao-cao-tong-hop",
        "Yêu cầu cung cấp NVL": "index.php?page=tao-yeu-cau-nvl",
        "Lịch làm việc": "index.php?page=lichlamviec",
        "Báo cáo sự cố": "index.php?page=baocaosuco",
        "Xem công việc": "index.php?page=xemcongviec",
        "Thêm nhân viên": "index.php?page=themnhanvien",
        "Xóa nhân viên": "index.php?page=xoanhanvien&id",
        "Xem nhân viên": "index.php?page=xemnhanvien",
        "Thống kê kho nguyên vật liệu": "index.php?page=thongke-khonvl",
        "Xuất kho thành phẩm": "index.php?page=xuatthanhpham",
        "Thông tin cá nhân": "index.php?page=thong-tin-ca-nhan",
        "Phê duyệt các yêu cầu sản xuất": "index.php?page=phe-duyet-cac-yeu-cau",
        "Xuất nguyên vật liệu": "index.php?page=xuat-kho-nvl",
        "Tạo yêu cầu nhập nguyên vật liệu": "index.php?page=tao-yeu-cau-nhap-kho",
        "Nhập kho nguyên vật liệu": "index.php?page=nhap-kho-nvl",
        "Yêu cầu kiểm tra chất lượng": "index.php?page=tao-yeu-cau-kiem-tra-chat-luong",
        "Phê duyệt kế hoạch sản xuất": "index.php?page=phe-duyet-ke-hoach-sx",
        "Sửa nhân viên": "index.php?page=suanhanvien",
        "Lập kế hoạch sản xuất": "index.php?page=lap-khsx",
        "Thống kê kho thành phẩm": "index.php?page=thongke",
        "Báo cáo chất lượng": "index.php?page=baocao-chatluong",
        "Tạo đơn hàng sản xuất": "index.php?page=tao-don-hang-san-xuat",
        "Cập nhật thành phẩm": "index.php?page=cap-nhat-thanh-pham",
        "Nhập kho thành phẩm": "index.php?page=nhap-kho-thanh-pham"

    };

    function populateSidebar(sectionKey) {
        if (!sidebarList) return;
        const chucnang = danhMucData[sectionKey] || [];
        if (chucnang.length === 0) {
            sidebarList.innerHTML = `<li class="sidebar-alert">Chưa có chức năng nào trong mục này</li>`;
        } else {
            sidebarList.innerHTML = chucnang
                .map(item => {
                    const url = linkMap[item] || "#";
                    return `<li><a href="${url}">${item}</a></li>`;
                })
                .join("");
        }
        localStorage.setItem("lastSelectedSection", sectionKey);
    }

    // --- Kiểm tra trạng thái đăng nhập ---
    const currentPage = window.location.search;
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    const isHomePage = currentPage === "" || currentPage === "?page=home";
// --- Auto load sidebar khi ở trang giới thiệu ---
const pageParams = new URLSearchParams(window.location.search);
const pageName = pageParams.get("page");

const introToSection = {
    "gioi-thieu-tong-quan": "tongquan",
    "gioi-thieu-nhan-su": "nhansu",
    "gioi-thieu-san-xuat": "sanxuat",
    "gioi-thieu-kho-nvl": "khoNVL",
    "gioi-thieu-xuong": "xuong",
    "gioi-thieu-kiem-tra-chat-luong": "qc",
    "gioi-thieu-kho-thanh-pham": "khoTP",
    "gioi-thieu-cong-nhan": "congnhan"
};

if (isLoggedIn && introToSection[pageName]) {
    const sectionKey = introToSection[pageName];
    populateSidebar(sectionKey);
}

    // --- Hiển thị sidebar mặc định ---
    if (!isLoggedIn) {
        localStorage.removeItem("lastSelectedSection");
        localStorage.removeItem("lastSelectedCategory");
        sidebarList.innerHTML = `<li class="sidebar-alert">⚠️ Hãy đăng nhập trước để sử dụng các chức năng</li>`;
    } else {
        // ✅ LUÔN LUÔN hiển thị sidebar với tất cả chức năng được phân nhóm
        loadAllMenuItemsByRoleGrouped();
    }
    
    // --- Function load tất cả menu items theo role (không nhóm) ---
    function loadAllMenuItemsByRole() {
        if (!sidebarList) return;
        
        // Lấy trang hiện tại từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 'home';
        
        // Fetch menu items từ backend theo role
        fetch('index.php?page=get-menu-by-role')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.menuItems) {
                    let html = '';
                    // Chỉ hiển thị các chức năng, KHÔNG CẦN category headers
                    for (const [categoryName, items] of Object.entries(data.menuItems)) {
                        items.forEach(item => {
                            const isActive = item.link === currentPage ? 'active' : '';
                            html += `<li><a href="index.php?page=${item.link}" class="menu-item-btn ${isActive}" data-page="${item.link}">
                                ${item.text}
                            </a></li>`;
                        });
                    }
                    sidebarList.innerHTML = html || `<li class="sidebar-alert">Không có chức năng nào</li>`;
                    
                    // Thêm event listener cho các menu items
                    setupMenuItemClickHandlers();
                } else {
                    sidebarList.innerHTML = `<li class="sidebar-alert">Không thể tải menu</li>`;
                }
            })
            .catch(error => {
                console.error('Error loading menu:', error);
                sidebarList.innerHTML = `<li class="sidebar-alert">Lỗi tải menu</li>`;
            });
    }
    
    // --- Function load tất cả menu items theo role (có nhóm category) ---
    function loadAllMenuItemsByRoleGrouped() {
        if (!sidebarList) return;
        
        // Lấy trang hiện tại từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 'home';
        
        // Fetch menu items từ backend theo role
        fetch('index.php?page=get-menu-by-role')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.menuItems) {
                    let html = '';
                    // ✅ Hiển thị các chức năng THEO NHÓM với category headers
                    for (const [categoryName, items] of Object.entries(data.menuItems)) {
                        if (items && items.length > 0) {
                            // Thêm header cho category
                            html += `<li class="sidebar-category-header">${categoryName}</li>`;
                            
                            // Thêm các items trong category
                            items.forEach(item => {
                                const isActive = item.link === currentPage ? 'active' : '';
                                html += `<li><a href="index.php?page=${item.link}" class="menu-item-btn ${isActive}" data-page="${item.link}">
                                    ${item.text}
                                </a></li>`;
                            });
                        }
                    }
                    sidebarList.innerHTML = html || `<li class="sidebar-alert">Không có chức năng nào</li>`;
                    
                    // Thêm event listener cho các menu items
                    setupMenuItemClickHandlers();
                } else {
                    sidebarList.innerHTML = `<li class="sidebar-alert">Không thể tải menu</li>`;
                }
            })
            .catch(error => {
                console.error('Error loading menu:', error);
                sidebarList.innerHTML = `<li class="sidebar-alert">Lỗi tải menu</li>`;
            });
    }
    
    // --- Function setup click handlers cho menu items ---
    function setupMenuItemClickHandlers() {
        const menuItems = document.querySelectorAll('.menu-item-btn');
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Lưu trang vào localStorage để active state persist sau redirect
                const pageLink = this.getAttribute('data-page');
                if (pageLink) {
                    localStorage.setItem('lastActivePage', pageLink);
                }
                
                // Remove active từ tất cả items
                menuItems.forEach(btn => btn.classList.remove('active'));
                
                // Add active vào item được click
                this.classList.add('active');
            });
        });
    }
    
    // --- Function update active state based on current URL ---
    function updateActiveMenuItem() {
        if (!sidebarList) return;
        
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 'home';
        
        // Remove active từ tất cả
        const menuItems = document.querySelectorAll('.menu-item-btn');
        menuItems.forEach(item => {
            const pageLink = item.getAttribute('data-page');
            if (pageLink === currentPage) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }
    
    // --- Function load sidebar theo category cụ thể ---
    function loadSidebarByCategory(categoryKey, categoryName) {
        if (!sidebarList) return;
        
        // Lưu category vào localStorage
        localStorage.setItem('lastSelectedCategory', categoryKey);
        localStorage.setItem('lastSelectedCategoryName', categoryName);
        
        // Lấy trang hiện tại
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 'home';
        
        // Fetch menu items từ backend
        fetch('index.php?page=get-menu-by-role')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.menuItems) {
                    // Filter chỉ lấy items của category được chọn
                    const categoryItems = data.menuItems[categoryName];
                    
                    if (categoryItems && categoryItems.length > 0) {
                        let html = '';
                        categoryItems.forEach(item => {
                            const isActive = item.link === currentPage ? 'active' : '';
                            html += `<li><a href="index.php?page=${item.link}" class="menu-item-btn ${isActive}" data-page="${item.link}">
                                ${item.text}
                            </a></li>`;
                        });
                        sidebarList.innerHTML = html;
                        
                        // Setup click handlers
                        setupMenuItemClickHandlers();
                    } else {
                        sidebarList.innerHTML = `<li class="sidebar-alert">Không có chức năng nào trong mục này</li>`;
                    }
                } else {
                    sidebarList.innerHTML = `<li class="sidebar-alert">Không thể tải menu</li>`;
                }
            })
            .catch(error => {
                console.error('Error loading category menu:', error);
                sidebarList.innerHTML = `<li class="sidebar-alert">Lỗi tải menu</li>`;
            });
    }

    // --- Dropdown ---
    if (btnMenu && dropdown) {
        btnMenu.addEventListener("click", () => {
            if (!isLoggedIn) {
                alert("Vui lòng đăng nhập để sử dụng danh mục chức năng!");
                return;
            }
            dropdown.classList.toggle("show");
        });

       dropdown.querySelectorAll("button[data-section]").forEach(btn => {
    btn.addEventListener("click", async () => {
        if (!isLoggedIn) {
            alert("Vui lòng đăng nhập để chọn chức năng!");
            return;
        }
        const section = btn.dataset.section;
        
        // Đóng dropdown
        dropdown.classList.remove("show");
        
        // Mapping section sang category key cho API
        const categoryMap = {
            tongquan: "tong-quan",
            nhansu: "nhan-su",
            sanxuat: "san-xuat",
            khoNVL: "kho-nvl",
            xuong: "xuong",
            qc: "kiem-tra-chat-luong",
            khoTP: "kho-thanh-pham",
            congnhan: "cong-nhan"
        };
        
        // Mapping tên category để hiển thị
        const categoryNames = {
            tongquan: "Tổng quan & Báo cáo",
            nhansu: "Quản lý Nhân sự",
            sanxuat: "Quản lý Sản xuất",
            khoNVL: "Kho Nguyên vật liệu",
            xuong: "Xưởng sản xuất",
            qc: "Kiểm tra chất lượng",
            khoTP: "Kho Thành phẩm",
            congnhan: "Công việc & Báo cáo"
        };
        
        const category = categoryMap[section];
        
        // ✅ Kiểm tra quyền và load sidebar với chức năng của category
        try {
            const response = await fetch(`index.php?page=check-permission&category=${category}`);
            const result = await response.json();
            
            if (result.hasPermission) {
                // ✅ Có quyền → Load sidebar với chức năng của category này
                loadSidebarByCategory(section, categoryNames[section]);
                
                // Redirect đến trang giới thiệu
                const introPages = {
                    tongquan: "gioi-thieu-tong-quan",
                    nhansu: "gioi-thieu-nhan-su",
                    sanxuat: "gioi-thieu-san-xuat",
                    khoNVL: "gioi-thieu-kho-nvl",
                    xuong: "gioi-thieu-xuong",
                    qc: "gioi-thieu-kiem-tra-chat-luong",
                    khoTP: "gioi-thieu-kho-thanh-pham",
                    congnhan: "gioi-thieu-cong-nhan"
                };
                const page = introPages[section] || "home";
                window.location.href = `index.php?page=${page}`;
            } else {
                // ❌ Không có quyền → Hiển thị modal đẹp
                showPermissionModal(result.categoryName, result.userRole);
            }
        } catch (error) {
            console.error("Lỗi kiểm tra quyền:", error);
            alert("Có lỗi xảy ra. Vui lòng thử lại!");
        }
    });
});

        document.addEventListener("click", e => {
            if (dropdown.classList.contains("show") &&
                !btnMenu.contains(e.target) &&
                !dropdown.contains(e.target)) {
                dropdown.classList.remove("show");
            }
        });
    }
});

// ✅ Function hiển thị modal không có quyền truy cập
function showPermissionModal(categoryName, userRole) {
    // Xóa modal cũ nếu có
    const oldModal = document.getElementById('permissionModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Tạo modal HTML
    const modalHTML = `
        <div id="permissionModal" class="permission-modal-overlay">
            <div class="permission-modal-content">
                <button class="modal-close-btn" onclick="closePermissionModal()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-icon-container">
                    <i class="fas fa-lock modal-lock-icon"></i>
                </div>
                <h2 class="modal-title">TRUY CẬP BỊ TỪ CHỐI</h2>
                <div class="modal-message">
                    <p>Bạn không có quyền truy cập danh mục</p>
                    <p class="category-name"><strong>${categoryName}</strong></p>
                 
                </div>
                <div class="modal-actions">
                    <button class="modal-btn modal-btn-primary" onclick="window.location.href='index.php?page=home'">
                        <i class="fas fa-home"></i> Về trang chủ
                    </button>
                </div>
            </div>
        </div>
    `;

    // Thêm modal vào body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Animation fade in
    setTimeout(() => {
        document.getElementById('permissionModal').classList.add('show');
    }, 10);
}

// ✅ Function đóng modal
function closePermissionModal() {
    const modal = document.getElementById('permissionModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

// ✅ Đóng modal khi click vào overlay
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('permission-modal-overlay')) {
        closePermissionModal();
    }
});

// ✅ Update active menu item khi trang load hoặc sau navigation
window.addEventListener('load', function() {
    // Đợi một chút để sidebar load xong
    setTimeout(() => {
        const sidebarList = document.getElementById("sidebarList");
        if (sidebarList) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = urlParams.get('page') || 'home';
            
            const menuItems = document.querySelectorAll('.menu-item-btn');
            menuItems.forEach(item => {
                const pageLink = item.getAttribute('data-page');
                if (pageLink === currentPage) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }
    }, 500);
});

// ✅ Update active state khi navigate back/forward
window.addEventListener('pageshow', function() {
    setTimeout(() => {
        const sidebarList = document.getElementById("sidebarList");
        if (sidebarList) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = urlParams.get('page') || 'home';
            
            const menuItems = document.querySelectorAll('.menu-item-btn');
            menuItems.forEach(item => {
                const pageLink = item.getAttribute('data-page');
                if (pageLink === currentPage) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }
    }, 300);
});