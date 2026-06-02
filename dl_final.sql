-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2025 at 06:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dl_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `baocaoloi`
--

CREATE TABLE `baocaoloi` (
  `maBaoCao` int(11) NOT NULL,
  `tenBaoCao` varchar(100) NOT NULL,
  `loaiLoi` varchar(100) DEFAULT NULL,
  `hinhAnh` varchar(255) DEFAULT '0',
  `thoiGian` date DEFAULT NULL,
  `moTa` varchar(255) DEFAULT NULL,
  `maThietBi` int(11) DEFAULT NULL,
  `maND` int(11) DEFAULT NULL,
  `trangThai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baocaoloi`
--

INSERT INTO `baocaoloi` (`maBaoCao`, `tenBaoCao`, `loaiLoi`, `hinhAnh`, `thoiGian`, `moTa`, `maThietBi`, `maND`, `trangThai`) VALUES
(1, 'Báo cáo sự cố - Máy ép nhiệt', 'phanmem', 'uploads/img/1766548943_PheDuyetCacYeuCau.jpg', '2025-12-24', 'fdgfdsghdfgshgdf', 7, 106, 0);

-- --------------------------------------------------------

--
-- Table structure for table `calamviec`
--

CREATE TABLE `calamviec` (
  `maCa` varchar(10) NOT NULL,
  `tenCa` varchar(10) NOT NULL,
  `gioBatDau` time NOT NULL,
  `gioKetThuc` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calamviec`
--

INSERT INTO `calamviec` (`maCa`, `tenCa`, `gioBatDau`, `gioKetThuc`) VALUES
('CA_CHIEU', '', '13:00:00', '17:30:00'),
('CA_SANG', '', '07:30:00', '11:30:00'),
('CA_TOI', '', '18:00:00', '22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `chitietkehoachsanxuat`
--

CREATE TABLE `chitietkehoachsanxuat` (
  `maCTKHSX` int(11) NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `maGNTP` int(11) DEFAULT NULL,
  `maXuong` int(11) NOT NULL,
  `maNVL` int(11) NOT NULL,
  `tenNVL` varchar(50) NOT NULL,
  `loaiNVL` varchar(50) NOT NULL,
  `soLuongNVL` int(11) NOT NULL,
  `ngayBatDau` date DEFAULT NULL,
  `ngayKetThuc` date DEFAULT NULL,
  `KPI` int(11) DEFAULT NULL,
  `soLuongThanhPham` int(11) DEFAULT NULL,
  `dinhMuc` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietkehoachsanxuat`
--

INSERT INTO `chitietkehoachsanxuat` (`maCTKHSX`, `maKHSX`, `maGNTP`, `maXuong`, `maNVL`, `tenNVL`, `loaiNVL`, `soLuongNVL`, `ngayBatDau`, `ngayKetThuc`, `KPI`, `soLuongThanhPham`, `dinhMuc`) VALUES
(1, 15, NULL, 1, 1, 'Vải cotton', '0', 2000, '2025-12-25', '2026-01-03', 100, 0, 2.00),
(2, 15, NULL, 2, 2, 'Nút áo', '0', 6000, '2025-12-26', '2026-01-04', 100, 0, 6.00),
(3, 15, NULL, 2, 3, 'Chỉ may đen', '0', 3000, '2025-12-26', '2026-01-04', 100, 0, 3.00),
(4, 16, NULL, 1, 1, 'Vải cotton', '0', 2000, '2025-12-25', '2026-01-03', 100, 0, 2.00),
(5, 16, NULL, 2, 3, 'Chỉ may đen', '0', 2000, '2025-12-26', '2026-01-04', 100, 0, 2.00),
(6, 16, NULL, 2, 2, 'Nút áo', '0', 6000, '2025-12-26', '2026-01-04', 100, 0, 6.00),
(7, 17, NULL, 1, 1, 'Vải cotton', '0', 1230, '2025-12-25', '2025-12-28', 400, 0, 2.00),
(8, 17, NULL, 2, 2, 'Nút áo', '0', 1230, '2025-12-26', '2025-12-29', 400, 0, 6.00),
(9, 18, NULL, 1, 1, 'Vải cotton', '0', 4000, '2025-12-25', '2026-01-03', 200, 0, 2.00),
(10, 18, NULL, 2, 2, 'Nút áo', '0', 12000, '2025-12-26', '2026-01-04', 200, 0, 6.00),
(11, 18, NULL, 2, 3, 'Chỉ may đen', '0', 6000, '2025-12-26', '2026-01-04', 200, 0, 3.00),
(12, 19, NULL, 1, 1, 'Vải cotton', '0', 6000, '2025-12-25', '2026-01-08', 200, 0, 2.00),
(13, 19, NULL, 2, 4, 'Nút áo', '0', 18000, '2025-12-26', '2026-01-09', 200, 0, 6.00),
(14, 19, NULL, 2, 3, 'Chỉ may đen', '0', 9000, '2025-12-26', '2026-01-09', 200, 0, 3.00),
(15, 20, NULL, 1, 1, 'Vải cotton loại 1', '0', 2000, '2025-12-25', '2025-12-29', 100, 0, 2.00),
(16, 20, NULL, 2, 3, 'Chỉ may đen', '0', 2000, '2025-12-26', '2025-12-30', 200, 0, 2.00),
(17, 20, NULL, 2, 4, 'Nút áo trắng', '0', 6000, '2025-12-26', '2025-12-30', 200, 0, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietphieuxuatnvl`
--

CREATE TABLE `chitietphieuxuatnvl` (
  `maCTPX` int(11) NOT NULL,
  `maNVL` int(11) NOT NULL,
  `tenNVL` varchar(100) DEFAULT NULL,
  `soLuong` int(11) NOT NULL,
  `maPhieu` int(11) NOT NULL,
  `maXuong` int(11) NOT NULL,
  `ghiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietphieuxuatnvl`
--

INSERT INTO `chitietphieuxuatnvl` (`maCTPX`, `maNVL`, `tenNVL`, `soLuong`, `maPhieu`, `maXuong`, `ghiChu`) VALUES
(1, 1, 'Vải cotton', 1230, 14, 1, ''),
(2, 2, 'Nút áo', 1230, 14, 2, ''),
(3, 1, 'Vải cotton', 4000, 15, 1, ''),
(4, 2, 'Nút áo', 12000, 15, 2, ''),
(5, 3, 'Chỉ may đen', 6000, 15, 2, ''),
(6, 1, 'Vải cotton loại 1', 6000, 16, 1, 'rghrhg'),
(7, 4, 'Nút áo trắng', 18000, 16, 2, ''),
(8, 3, 'Chỉ may đen', 9000, 16, 2, ''),
(9, 1, 'Vải cotton loại 1', 2000, 17, 1, ''),
(10, 3, 'Chỉ may đen', 2000, 17, 2, ''),
(11, 4, 'Nút áo trắng', 6000, 17, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `chitietphieuyeucaukiemtrachatluong`
--

CREATE TABLE `chitietphieuyeucaukiemtrachatluong` (
  `maCTPKT` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `tenSanPham` varchar(255) NOT NULL,
  `soLuong` int(11) NOT NULL DEFAULT 0,
  `soLuongDat` int(11) DEFAULT 0,
  `soLuongHong` int(11) DEFAULT 0,
  `ngayKiemTra` date DEFAULT NULL,
  `donViTinh` varchar(20) NOT NULL,
  `maYC` int(11) NOT NULL,
  `trangThaiSanPham` varchar(50) NOT NULL DEFAULT 'Ch? ki?m tra',
  `ghiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietphieuyeucaukiemtrachatluong`
--

INSERT INTO `chitietphieuyeucaukiemtrachatluong` (`maCTPKT`, `maSanPham`, `tenSanPham`, `soLuong`, `soLuongDat`, `soLuongHong`, `ngayKiemTra`, `donViTinh`, `maYC`, `trangThaiSanPham`, `ghiChu`) VALUES
(1, 1, 'Áo sơ mi trắng', 1000, 999, 1, '2025-12-24', 'Cái', 33, 'Đã kiểm tra', ''),
(2, 5, 'Áo sơ mi đỏ', 6000, 6000, 0, NULL, 'Cái', 34, 'Đã kiểm tra', ''),
(3, 2, 'Áo sơ mi xanh dương', 2000, 1999, 1, '2025-12-24', 'Cái', 35, 'Đã kiểm tra', ''),
(4, 12, 'Áo hoa mai', 3000, 2998, 2, '2025-12-24', 'Cái', 36, 'Đã kiểm tra', ''),
(5, 7, 'Áo sơ mi đen', 1000, 999, 1, '2025-12-25', 'Cái', 37, 'Đã kiểm tra', 'hh');

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_lichlamviec`
--

CREATE TABLE `chitiet_lichlamviec` (
  `maLichLam` int(11) NOT NULL,
  `maND` int(10) NOT NULL,
  `ngayLam` date NOT NULL,
  `maCa` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `maXuong` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitiet_lichlamviec`
--

INSERT INTO `chitiet_lichlamviec` (`maLichLam`, `maND`, `ngayLam`, `maCa`, `maXuong`) VALUES
(1, 106, '2025-12-22', 'CA_SANG', '1'),
(2, 106, '2025-12-23', 'CA_CHIEU', '1'),
(3, 106, '2025-12-24', 'CA_SANG', '1'),
(4, 106, '2025-12-29', 'CA_CHIEU', '1'),
(5, 106, '2025-11-16', 'CA_SANG', '1'),
(6, 1, '2025-11-16', 'CA_CHIEU', '1'),
(7, 1, '2025-11-16', 'CA_TOI', '1'),
(8, 1, '2025-11-17', 'CA_SANG', '1'),
(9, 1, '2025-11-17', 'CA_CHIEU', '1'),
(10, 1, '2025-11-18', 'CA_SANG', '1'),
(11, 1, '2025-11-18', 'CA_CHIEU', '1'),
(12, 1, '2025-11-19', 'CA_SANG', '1'),
(13, 1, '2025-11-19', 'CA_CHIEU', '1'),
(14, 1, '2025-11-19', 'CA_TOI', '1'),
(15, 1, '2025-12-07', 'CA_SANG', '1'),
(16, 1, '2025-12-10', 'CA_CHIEU', '1'),
(17, 1, '2025-12-17', 'CA_SANG', '1');

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_nhapkhotp`
--

CREATE TABLE `chitiet_nhapkhotp` (
  `maCTNKTP` int(11) NOT NULL,
  `maPhieu` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `tenSanPham` varchar(255) NOT NULL,
  `soLuong` int(11) NOT NULL DEFAULT 0,
  `hanhDong` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiet_nhapkhotp`
--

INSERT INTO `chitiet_nhapkhotp` (`maCTNKTP`, `maPhieu`, `maSanPham`, `tenSanPham`, `soLuong`, `hanhDong`) VALUES
(1, 13, 1, 'Áo sơ mi trắng', 999, 'Nhập kho thành phẩm sau khi kiểm tra chất lượng'),
(2, 14, 2, 'Áo sơ mi xanh dương', 1999, 'Nhập kho thành phẩm sau khi kiểm tra chất lượng'),
(3, 15, 12, 'Áo hoa mai', 2998, 'Nhập kho thành phẩm sau khi kiểm tra chất lượng'),
(4, 16, 7, 'Áo sơ mi đen', 999, 'Nhập kho thành phẩm sau khi kiểm tra chất lượng'),
(5, 17, 5, 'Áo sơ mi đỏ', 6000, 'Nhập kho thành phẩm sau khi kiểm tra chất lượng');

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_phieuyeucaucapnvl`
--

CREATE TABLE `chitiet_phieuyeucaucapnvl` (
  `maCTPhieuYCCC` int(11) NOT NULL,
  `tenNVL` varchar(100) NOT NULL,
  `nhaCungCap` varchar(100) DEFAULT NULL,
  `soLuong` int(11) NOT NULL,
  `donViTinh` varchar(20) DEFAULT NULL,
  `maYCCC` int(11) NOT NULL,
  `maNVL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiet_phieuyeucaucapnvl`
--

INSERT INTO `chitiet_phieuyeucaucapnvl` (`maCTPhieuYCCC`, `tenNVL`, `nhaCungCap`, `soLuong`, `donViTinh`, `maYCCC`, `maNVL`) VALUES
(4, 'Vải cotton', NULL, 1230, 'Tấm', 35, 1),
(5, 'Nút áo', NULL, 1230, 'Cái', 35, 2),
(6, 'Vải cotton', NULL, 4000, 'Tấm', 36, 1),
(7, 'Nút áo', NULL, 12000, 'Cái', 36, 2),
(8, 'Chỉ may đen', NULL, 6000, 'Cuộn', 36, 3),
(9, 'Vải cotton loại 1', NULL, 6000, 'Tấm', 37, 1),
(10, 'Nút áo trắng', NULL, 18000, 'Cái', 37, 4),
(11, 'Chỉ may đen', NULL, 9000, 'Cuộn', 37, 3),
(12, 'Vải cotton loại 1', NULL, 2000, 'm', 38, 1),
(13, 'Chỉ may đen', NULL, 2000, 'Cuộn', 38, 3),
(14, 'Nút áo trắng', NULL, 6000, 'Cái', 38, 4);

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_phieuyeucaunhapkhonvl`
--

CREATE TABLE `chitiet_phieuyeucaunhapkhonvl` (
  `maChiTiet_YCNK` int(11) NOT NULL,
  `maYCNK` int(11) NOT NULL,
  `maNVL` int(11) NOT NULL,
  `tenNVL` varchar(255) DEFAULT NULL,
  `soLuong` int(11) DEFAULT NULL,
  `donViTinh` varchar(50) DEFAULT NULL,
  `nhaCungCap` varchar(200) NOT NULL,
  `soLuongTonKho` int(11) NOT NULL,
  `soLuongCanNhap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiet_phieuyeucaunhapkhonvl`
--

INSERT INTO `chitiet_phieuyeucaunhapkhonvl` (`maChiTiet_YCNK`, `maYCNK`, `maNVL`, `tenNVL`, `soLuong`, `donViTinh`, `nhaCungCap`, `soLuongTonKho`, `soLuongCanNhap`) VALUES
(1, 8, 1, 'Vải cotton', 829, 'Tấm', 'Công ty Vải Việt Nam', 11710, 2000),
(2, 8, 2, 'Nút áo', 2854, 'Cái', 'Công ty Phụ liệu May Mặc', 31463, 6000),
(3, 8, 3, 'Chỉ may đen', 1896, 'Cuộn', 'Công ty Sợi Quốc Tế', 11041, 3000),
(4, 9, 1, 'Vải cotton', 2869, 'Tấm', 'Công ty Vải Việt Nam', 11309, 4000),
(5, 9, 2, 'Nút áo', 8691, 'Cái', 'Công ty Phụ liệu May Mặc', 33087, 12000),
(6, 9, 3, 'Chỉ may đen', 4706, 'Cuộn', 'Công ty Sợi Quốc Tế', 12937, 6000),
(7, 10, 1, 'Vải cotton', 5900, 'Tấm', 'Công ty Vải Việt Nam', 1000, 6000),
(8, 10, 4, 'Nút áo', 17980, 'Cái', 'Công ty Phụ liệu May Mặc', 200, 18000),
(9, 10, 3, 'Chỉ may đen', 8600, 'Cuộn', 'Công ty Sợi Quốc Tế', 4000, 9000),
(10, 11, 1, 'Vải cotton loại 1', 1910, 'm', 'Công ty Vải Việt Nam', 900, 2000),
(11, 11, 3, 'Chỉ may đen', 1640, 'Cuộn', 'Công ty Sợi Quốc Tế', 3600, 2000),
(12, 11, 4, 'Nút áo trắng', 5982, 'Cái', 'Công ty Phụ liệu May Mặc', 180, 6000);

-- --------------------------------------------------------

--
-- Table structure for table `congviec`
--

CREATE TABLE `congviec` (
  `maCongViec` int(11) NOT NULL,
  `tieuDe` varchar(100) NOT NULL,
  `moTa` varchar(200) DEFAULT NULL,
  `trangThai` varchar(30) NOT NULL DEFAULT 'Đang thực hiện',
  `ngayHetHan` date NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `maXuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donhangsanxuat`
--

CREATE TABLE `donhangsanxuat` (
  `maDonHang` int(11) NOT NULL,
  `tenDonHang` varchar(100) NOT NULL,
  `tenSanPham` varchar(200) NOT NULL,
  `soLuongSanXuat` int(11) NOT NULL,
  `donVi` varchar(50) DEFAULT NULL,
  `diaChiNhan` varchar(100) NOT NULL,
  `trangThai` varchar(50) NOT NULL,
  `ngayGiao` date NOT NULL,
  `maSanPham` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donhangsanxuat`
--

INSERT INTO `donhangsanxuat` (`maDonHang`, `tenDonHang`, `tenSanPham`, `soLuongSanXuat`, `donVi`, `diaChiNhan`, `trangThai`, `ngayGiao`, `maSanPham`) VALUES
(1, ' DHSX1', 'Áo sơ mi hoa cúc', 2000, 'Cai', 'Nguyen Oanh', 'Chờ xuất kho', '2025-10-31', 1),
(2, 'DHSX2', 'Áo sơ mi xanh dương', 1000, 'Cái', 'ABC', 'Chờ xuất kho', '2025-10-31', 2),
(3, 'DHSX4', 'Áo sơ mi tay ngắn', 3500, 'Cái', 'Nguyễn Văn Bảo', 'Chờ xuất kho', '2025-11-28', 3),
(6, 'DHSX6', 'Áo sơ mi tay ngắn', 200, 'Cái', 'iuowqiueoq', 'Chờ xuất kho', '2025-12-11', 3),
(7, 'DHSX7', 'Áo sơ mi tím', 10000, 'Cái', '123', 'Đang thực hiện', '2025-12-18', 8),
(8, 'DHSX8', 'Áo sơ mi tay ngắn', 1200, 'Cái', '1234', 'Chờ xuất kho', '2025-12-24', 3),
(9, 'DHSX9', 'Áo sơ mi tay dài custom hoa cúc', 1230, 'Cái', '123 Phường 12 Thành phố HKT', 'Đang thực hiện', '2025-12-31', 10),
(10, 'DHSX10', 'Áo mới cà mau', 1111, 'Cái', '11111', 'Đang thực hiện', '2025-12-17', 11),
(11, 'DHSX11', 'Áo sơ mi trắng', 3000, 'Cái', '58 Quang Trung, Gò Vấp', 'Chờ xuất kho', '2026-01-10', 1),
(12, 'DHSX12', 'Áo sơ mi tay ngắn', 5000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đang thực hiện', '2026-01-28', 3),
(13, 'DHSX13', 'Áo sơ mi đỏ', 6000, 'Cái', '58 Quang Trung, Gò Vấp', 'Chờ xuất kho', '2026-01-10', 5),
(14, 'DHSX14', 'Áo sơ mi trắng', 1000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đã xuất kho', '2026-02-12', 1),
(15, 'DHSX15', 'Áo sơ mi xám', 1000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đang thực hiện', '2026-02-05', 9),
(16, 'DHSX16', 'Áo sơ mi xanh dương', 2000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đã xuất kho', '2026-01-08', 2),
(17, 'DHSX17', 'Áo hoa mai', 3000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đã xuất kho', '2026-01-23', 12),
(18, 'DHSX18', 'Áo sơ mi đen', 1000, 'Cái', '58 Quang Trung, Gò Vấp', 'Đã xuất kho', '2026-01-10', 7);

-- --------------------------------------------------------

--
-- Table structure for table `ghinhanthanhphamtheongay`
--

CREATE TABLE `ghinhanthanhphamtheongay` (
  `maGhiNhan` int(11) NOT NULL,
  `maNhanVien` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `soLuongSPHoanThanh` int(11) NOT NULL,
  `ngayLam` date NOT NULL,
  `maKHSX` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ghinhanthanhphamtheongay`
--

INSERT INTO `ghinhanthanhphamtheongay` (`maGhiNhan`, `maNhanVien`, `maSanPham`, `soLuongSPHoanThanh`, `ngayLam`, `maKHSX`) VALUES
(2, 1, 1, 20, '2025-10-28', 1),
(3, 1, 1, 30, '2025-10-29', 1),
(4, 1, 1, 25, '2025-10-31', 1),
(5, 1, 1, 40, '2025-11-03', 1),
(6, 15, 1, 25, '2025-11-04', 1),
(7, 7, 1, 35, '2025-10-30', 1),
(8, 8, 1, 20, '2025-12-05', 1),
(9, 7, 1, 15, '2025-12-05', 1),
(32, 7, 5, 500, '2025-12-16', 14),
(33, 7, 5, 5500, '2025-12-16', 14),
(35, 8, 3, 500, '2025-12-16', 13),
(36, 7, 3, 4500, '2025-12-16', 13),
(37, 6, 3, 500, '2025-12-16', 13),
(38, 7, 10, 1230, '2025-12-23', 17),
(39, 7, 1, 1000, '2025-12-23', 15),
(40, 7, 2, 200, '2025-12-24', 18),
(41, 6, 2, 1800, '2025-12-24', 18),
(42, 17, 12, 500, '2025-12-24', 19),
(43, 7, 12, 500, '2025-12-24', 19),
(44, 6, 12, 2500, '2025-12-24', 19),
(45, 8, 7, 500, '2025-12-24', 20),
(46, 17, 7, 500, '2025-12-24', 20),
(47, 6, 7, 500, '2025-12-24', 20),
(48, 7, 7, 499, '2025-12-24', 20),
(49, 107, 7, 1, '2025-12-24', 20);

-- --------------------------------------------------------

--
-- Table structure for table `kehoachsanxuat`
--

CREATE TABLE `kehoachsanxuat` (
  `maKHSX` int(11) NOT NULL,
  `tenKHSX` varchar(100) NOT NULL,
  `maDonHang` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `thoiGianBatDau` date NOT NULL,
  `thoiGianKetThuc` date NOT NULL,
  `trangThai` varchar(50) NOT NULL DEFAULT 'Chờ duyệt',
  `maND` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kehoachsanxuat`
--

INSERT INTO `kehoachsanxuat` (`maKHSX`, `tenKHSX`, `maDonHang`, `maSanPham`, `thoiGianBatDau`, `thoiGianKetThuc`, `trangThai`, `maND`) VALUES
(1, 'KHSX1', 1, 1, '2025-10-01', '2025-10-31', 'Từ chối', 1),
(2, 'KHSX2', 2, 2, '2025-10-09', '2025-10-31', 'Từ chối', 1),
(3, 'KHSX3', 3, 3, '2025-10-01', '2025-11-06', 'Từ chối', 1),
(6, 'KHSX cho ĐH 6', 6, 3, '2025-12-01', '2025-12-11', 'Từ chối', 1),
(7, 'KHSX cho ĐH 6', 6, 3, '2025-12-01', '2025-12-11', 'Hoàn thành', 1),
(8, 'KHSX cho ĐH 6', 6, 3, '2025-12-01', '2025-12-11', 'Hết hạn', 1),
(9, 'KHSX cho ĐH 6', 6, 3, '2025-12-01', '2025-12-11', 'Hết hạn', 1),
(10, 'KHSX cho ĐH 6', 6, 3, '2025-12-01', '2025-12-11', 'Hết hạn', 1),
(11, 'KHSX cho ĐH 11', 11, 0, '2025-12-17', '2026-01-10', 'Hết hạn', 1),
(12, 'KHSX cho ĐH 11', 11, 0, '2025-12-18', '2026-01-28', 'Hết hạn', 1),
(13, 'KHSX cho ĐH 12', 12, 0, '2025-12-16', '2026-01-28', 'Hết hạn', 1),
(14, 'KHSX cho ĐH 13', 13, 0, '2025-12-16', '2026-01-10', 'Hết hạn', 1),
(15, 'KHSX cho ĐH 14', 14, 0, '2025-12-24', '2026-02-12', 'Hết hạn', 1),
(16, 'KHSX cho ĐH 15', 15, 0, '2025-12-24', '2026-02-05', 'Hết hạn', 1),
(17, 'KHSX cho ĐH 9', 9, 0, '2025-12-24', '2025-12-31', 'Hoàn thành', 102),
(18, 'KHSX cho ĐH 16', 16, 0, '2025-12-24', '2026-01-08', 'Hoàn thành', 100),
(19, 'KHSX cho ĐH 17', 17, 0, '2025-12-24', '2026-01-23', 'Hoàn thành', 102),
(20, 'KHSX cho ĐH 18', 18, 0, '2025-12-24', '2026-01-10', 'Hoàn thành', 102);

-- --------------------------------------------------------

--
-- Table structure for table `kho`
--

CREATE TABLE `kho` (
  `maKho` int(11) NOT NULL,
  `tenKho` varchar(100) NOT NULL,
  `diaChi` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kho`
--

INSERT INTO `kho` (`maKho`, `tenKho`, `diaChi`) VALUES
(1, 'Kho Nguyên Vật Liệu', NULL),
(2, 'Kho Thành Phẩm', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lichsupheduyet`
--

CREATE TABLE `lichsupheduyet` (
  `id` int(11) NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `hanhDong` varchar(50) NOT NULL,
  `ghiChu` text DEFAULT NULL,
  `nguoiThucHien` varchar(100) DEFAULT NULL,
  `thoiGian` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lichsupheduyet`
--

INSERT INTO `lichsupheduyet` (`id`, `maKHSX`, `hanhDong`, `ghiChu`, `nguoiThucHien`, `thoiGian`) VALUES
(1, 1, 'Đã duyệt', '', 'TranKienQuoc', '2025-11-08 14:13:17'),
(2, 2, 'Từ chối', '', 'TranKienQuoc', '2025-11-08 14:13:42'),
(3, 1, 'Đã duyệt', '', 'TranKienQuoc', '2025-11-14 09:49:26'),
(4, 10, 'Đã duyệt', '', 'TranKienQuoc', '2025-12-16 11:10:26'),
(5, 6, 'Đã duyệt', '', 'TranKienQuoc', '2025-12-16 13:43:03'),
(6, 13, 'Đã duyệt', '', 'TranKienQuoc', '2025-12-16 20:37:02'),
(7, 14, 'Đã duyệt', '', 'TranKienQuoc', '2025-12-16 23:52:17'),
(8, 15, 'Đã duyệt', '', 'Lê Văn C', '2025-12-24 02:16:18'),
(9, 17, 'Đã duyệt', '', 'Nguyễn Văn A', '2025-12-24 02:45:58'),
(10, 18, 'Đã duyệt', '', 'Nguyễn Văn A', '2025-12-24 09:10:24'),
(11, 19, 'Đã duyệt', '', 'Nguyễn Văn A', '2025-12-24 10:31:32'),
(12, 20, 'Đã duyệt', '', 'Nguyễn Văn A', '2025-12-24 23:46:02'),
(13, 1, 'Từ chối', 'dgzdgsd', 'Nguyễn Văn A', '2025-12-25 00:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `maND` int(11) NOT NULL,
  `hoTen` varchar(100) NOT NULL,
  `gioiTinh` varchar(20) NOT NULL,
  `ngaySinh` date DEFAULT NULL,
  `chucVu` varchar(50) DEFAULT NULL,
  `phongBan` varchar(100) NOT NULL,
  `soDienThoai` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `diaChi` varchar(100) DEFAULT NULL,
  `maTK` int(11) NOT NULL,
  `trangThai` tinyint(1) DEFAULT 1,
  `hinhAnh` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`maND`, `hoTen`, `gioiTinh`, `ngaySinh`, `chucVu`, `phongBan`, `soDienThoai`, `email`, `diaChi`, `maTK`, `trangThai`, `hinhAnh`) VALUES
(1, 'TranKienQuoc', 'Nam', '2004-10-12', 'Giám đốc', '', '0346512104', 'trankienquoc@gmail.com', '54/12 Quang Trung, Gò Vấp', 1, 1, 'avatar1.png'),
(6, 'Nguyễn Văn B', 'Nam', '2002-01-12', 'Trưởng phòng', 'Xưởng may', '0901234567', 'an.nguyen@company.com', '123 Võ Văn Tần, Q.3, TP.HCM', 2, 1, ''),
(7, 'Trần Thị Bình', 'Nữ', '2001-04-20', 'Nhân viên', 'Xưởng may', '0987654321', 'binh.tran@company.com', '456 Lê Lợi, Q.1, TP.HCM', 3, 1, ''),
(8, 'Lê Minh Cường', 'Nam', '1994-07-10', 'Kỹ thuật viên', 'Xưởng cắt', '0912345678', 'cuong.le@company.com', '789 Nguyễn Trãi, Q.5, TP.HCM', 4, 1, ''),
(15, 'Mai Van Vu', 'Nam', NULL, 'Nhân viên xưởng Cắt', 'Xưởng cắt', '12222222222', '1232221@gmail.com', '581 Nguyen Oanh', 10, 0, ''),
(17, 'Vũ Mai', '', '1990-03-19', 'Nhân viên xưởng Cắt', 'Xưởng cắt', '1900232323', 'maivu@gmail.com', 'Chu Văn An, Phường 26', 16, 1, ''),
(18, 'Nguyễn Văn C', '', NULL, 'Nhân viên xưởng Cắt', '', '0345456734', 'trankienuoc@gmail.com', '54 Nguyễn Trãi, Hồ Chí Minh', 17, 0, ''),
(100, 'Nguyễn Văn A', 'Nam', '1980-01-15', 'Giám đốc', 'Ban Giám Đốc', '0901234567', 'giamdoc@company.com', '123 Nguyễn Huệ, Q.1, TP.HCM', 100, 1, 'avatar1.png'),
(101, 'Trần Thị B', 'Nữ', '1985-03-20', 'Quản lý nhân sự', 'Phòng Nhân Sự', '0902345678', 'qlnhansu@company.com', '456 Lê Lợi, Q.1, TP.HCM', 101, 1, ''),
(102, 'Lê Văn C', 'Nam', '1982-05-10', 'Quản lý sản xuất', 'Phòng Sản Xuất', '0903456789', 'qlsanxuat@company.com', '789 Hai Bà Trưng, Q.3, TP.HCM', 102, 1, 'avatar1.png'),
(103, 'Phạm Thị D', 'Nữ', '1988-07-25', 'Quản lý kho NVL', 'Kho Nguyên Vật Liệu', '0904567890', 'qlkhonvl@company.com', '321 Võ Văn Tần, Q.3, TP.HCM', 103, 1, ''),
(104, 'Hoàng Văn E', 'Nam', '1990-09-12', 'Nhân viên QC', 'Bộ Phận Kiểm Tra Chất Lượng', '0905678901', 'nhanvienqc@company.com', '654 Nguyễn Trãi, Q.5, TP.HCM', 104, 1, ''),
(105, 'Vũ Thị F', 'Nữ', '1987-11-30', 'Quản lý kho TP', 'Kho Thành Phẩm', '0906789012', 'qlkhotp@company.com', '987 Cách Mạng Tháng 8, Q.10, TP.HCM', 105, 1, ''),
(106, 'Đỗ Văn G', 'Nam', '1992-02-18', 'Công nhân', 'Xưởng Cắt', '0907890123', 'congnhancat@company.com', '135 Lý Thường Kiệt, Gò Vấp, TP.HCM', 106, 1, ''),
(107, 'Ngô Thị H', 'Nữ', '1993-04-22', 'Công nhân', 'Xưởng May', '0908901234', 'congnhanmay@company.com', '246 Phan Văn Trị, Bình Thạnh, TP.HCM', 107, 1, ''),
(200, 'Trần Văn Đức', 'Nam', '1988-07-20', 'Quản lý xưởng', 'Xưởng sản xuất', '0909876543', 'tranvanduc.qlx2024@company.com', '456 Đường Lê Lợi, Quận 1, TP.HCM', 200, 1, ''),
(201, 'Nguyên Thị P', 'Nữ', '2004-06-23', 'Công nhân', 'Xưởng cắt', '0345675124', 'dsfdsfgds@gmail.com', 'Gò Vấp', 201, 0, ''),
(202, 'Nguyễn Văn K', 'Nam', '1997-12-03', 'Công nhân', 'xưởng May', '0345675129', 'tranthik@gmail.com', '58 Nguyen Oanh', 202, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `nhapkhotp`
--

CREATE TABLE `nhapkhotp` (
  `maPhieu` int(11) NOT NULL,
  `maYC` int(11) NOT NULL,
  `ngayKiemTra` date NOT NULL,
  `nguoiLap` varchar(100) NOT NULL,
  `hanhDong` varchar(255) NOT NULL,
  `ngayNK` date NOT NULL,
  `maND` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhapkhotp`
--

INSERT INTO `nhapkhotp` (`maPhieu`, `maYC`, `ngayKiemTra`, `nguoiLap`, `hanhDong`, `ngayNK`, `maND`) VALUES
(13, 33, '2025-12-24', 'Vũ Thị F', 'Nhập kho thành phẩm sau khi kiểm tra chất lượng', '0000-00-00', NULL),
(14, 35, '2025-12-24', 'Nguyễn Văn A', 'Nhập kho thành phẩm sau khi kiểm tra chất lượng', '0000-00-00', NULL),
(15, 36, '2025-12-24', 'Vũ Thị F', 'Nhập kho thành phẩm sau khi kiểm tra chất lượng', '0000-00-00', NULL),
(16, 37, '2025-12-25', 'Vũ Thị F', 'Nhập kho thành phẩm sau khi kiểm tra chất lượng', '0000-00-00', NULL),
(17, 34, '2025-12-24', 'Nguyễn Văn A', 'Nhập kho thành phẩm sau khi kiểm tra chất lượng', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nvl`
--

CREATE TABLE `nvl` (
  `maNVL` int(11) NOT NULL,
  `tenNVL` varchar(100) NOT NULL,
  `loaiNVL` varchar(50) DEFAULT NULL,
  `soLuongTonKho` int(11) NOT NULL DEFAULT 0,
  `donViTinh` varchar(20) DEFAULT NULL,
  `moTa` varchar(255) DEFAULT NULL,
  `maKho` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nvl`
--

INSERT INTO `nvl` (`maNVL`, `tenNVL`, `loaiNVL`, `soLuongTonKho`, `donViTinh`, `moTa`, `maKho`) VALUES
(1, 'Vải cotton loại 1', 'Vải', 810, 'm', '2m x 3m', NULL),
(2, 'Nút áo đen', 'Phụ kiện', 3000, 'Cái', 'Nút áo trắng', NULL),
(3, 'Chỉ may đen', 'Chỉ', 3240, 'Cuộn', 'Chỉ may màu trắng', 1),
(4, 'Nút áo trắng', 'Phụ kiện', 162, 'Cái', 'Nút áo màu đen', 1),
(5, 'Vải cotton loại 2', 'Vải', 300, 'm', 'Vải cotton trắng', 1),
(6, 'Chỉ may trắng', 'Chỉ', 3000, 'Cuộn', 'Chỉ may màu trắng, mỏng', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phieunhapnvl`
--

CREATE TABLE `phieunhapnvl` (
  `maPNVL` int(11) NOT NULL,
  `tenPNVL` varchar(100) NOT NULL,
  `nguoiLap` varchar(100) NOT NULL,
  `nhaCungCap` varchar(500) NOT NULL,
  `ngayNhap` date NOT NULL,
  `maYCNK` int(11) NOT NULL,
  `maNVL` int(11) DEFAULT NULL,
  `soLuongNhap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieunhapnvl`
--

INSERT INTO `phieunhapnvl` (`maPNVL`, `tenPNVL`, `nguoiLap`, `nhaCungCap`, `ngayNhap`, `maYCNK`, `maNVL`, `soLuongNhap`) VALUES
(15, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Vải Việt Nam', '2025-12-23', 8, 1, 829),
(16, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Phụ liệu May Mặc', '2025-12-23', 8, 2, 2854),
(17, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Sợi Quốc Tế', '2025-12-23', 8, 3, 1896),
(18, 'Phiếu nhập nguyên vật liệu', 'Nguyễn Văn A', 'Công ty Vải Việt Nam', '2025-12-24', 9, 1, 2869),
(19, 'Phiếu nhập nguyên vật liệu', 'Nguyễn Văn A', 'Công ty Phụ liệu May Mặc', '2025-12-24', 9, 2, 8691),
(20, 'Phiếu nhập nguyên vật liệu', 'Nguyễn Văn A', 'Công ty Sợi Quốc Tế', '2025-12-24', 9, 3, 4706),
(21, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Vải Việt Nam', '2025-12-24', 10, 1, 5900),
(22, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Phụ liệu May Mặc', '2025-12-24', 10, 4, 17980),
(23, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Sợi Quốc Tế', '2025-12-24', 10, 3, 8600),
(24, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Vải Việt Nam', '2025-12-24', 11, 1, 1910),
(25, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Sợi Quốc Tế', '2025-12-24', 11, 3, 1640),
(26, 'Phiếu nhập nguyên vật liệu', 'Phạm Thị D', 'Công ty Phụ liệu May Mặc', '2025-12-24', 11, 4, 5982);

-- --------------------------------------------------------

--
-- Table structure for table `phieuxuatnvl`
--

CREATE TABLE `phieuxuatnvl` (
  `maPhieu` int(11) NOT NULL,
  `tenPhieu` varchar(100) NOT NULL,
  `tenNguoiLap` varchar(100) DEFAULT NULL,
  `ngayLap` date NOT NULL,
  `maND` int(11) NOT NULL,
  `maYCCC` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuxuatnvl`
--

INSERT INTO `phieuxuatnvl` (`maPhieu`, `tenPhieu`, `tenNguoiLap`, `ngayLap`, `maND`, `maYCCC`) VALUES
(13, 'Xuất NVL KHSX cho ĐH 12', 'TranKienQuoc', '2025-12-16', 1, 31),
(14, 'Xuất NVL KHSX cho ĐH 9', 'Phạm Thị D', '2025-12-23', 103, 35),
(15, 'Xuất NVL KHSX cho ĐH 16', 'Nguyễn Văn A', '2025-12-24', 100, 36),
(16, 'Xuất NVL KHSX cho ĐH 17', 'Phạm Thị D', '2025-12-24', 103, 37),
(17, 'Xuất NVL KHSX cho ĐH 18', 'Phạm Thị D', '2025-12-24', 103, 38);

-- --------------------------------------------------------

--
-- Table structure for table `phieuxuatthanhpham`
--

CREATE TABLE `phieuxuatthanhpham` (
  `maPhieuXuat` int(11) NOT NULL,
  `maDonHang` int(11) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `soLuongXuat` int(11) NOT NULL,
  `ngayXuat` date NOT NULL,
  `ghiChu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuxuatthanhpham`
--

INSERT INTO `phieuxuatthanhpham` (`maPhieuXuat`, `maDonHang`, `maSanPham`, `soLuongXuat`, `ngayXuat`, `ghiChu`) VALUES
(1, 1, 1, 5, '2025-10-30', ''),
(2, 2, 2, 200, '2025-11-03', ''),
(3, 12, 3, 5000, '0000-00-00', ''),
(4, 14, 1, 1000, '0000-00-00', ''),
(5, 16, 2, 2000, '0000-00-00', ''),
(6, 17, 12, 3000, '0000-00-00', ''),
(7, 18, 7, 1000, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `phieuyeucaucungcapnvl`
--

CREATE TABLE `phieuyeucaucungcapnvl` (
  `maYCCC` int(11) NOT NULL,
  `ngayLap` date NOT NULL,
  `trangThai` varchar(50) NOT NULL DEFAULT 'Chờ duyệt',
  `tenNguoiLap` varchar(100) DEFAULT NULL,
  `maND` int(11) NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `tenPhieu` varchar(100) NOT NULL,
  `ghiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuyeucaucungcapnvl`
--

INSERT INTO `phieuyeucaucungcapnvl` (`maYCCC`, `ngayLap`, `trangThai`, `tenNguoiLap`, `maND`, `maKHSX`, `tenPhieu`, `ghiChu`) VALUES
(30, '2025-11-21', 'Từ chối', 'TranKienQuoc', 1, 1, 'Yêu cầu NVL cho KHSX1', ''),
(31, '2025-12-16', 'Đang xuất NVL', 'TranKienQuoc', 1, 13, 'Yêu cầu NVL cho KHSX cho ĐH 12', ''),
(32, '2025-12-21', 'Từ chối', 'TranKienQuoc', 1, 2, 'Yêu cầu NVL cho KHSX2', 'hhjhjjhi'),
(33, '2025-12-23', 'Từ chối', 'TranKienQuoc', 1, 10, 'Yêu cầu NVL cho KHSX cho ĐH 6', ''),
(35, '2025-12-23', 'Đang xuất NVL', 'Trần Văn Đức', 200, 17, 'Yêu cầu NVL cho KHSX cho ĐH 9', ''),
(36, '2025-12-24', 'Đang xuất NVL', 'Nguyễn Văn A', 100, 18, 'Yêu cầu NVL cho KHSX cho ĐH 16', ''),
(37, '2025-12-24', 'Đang xuất NVL', 'Trần Văn Đức', 200, 19, 'Yêu cầu NVL cho KHSX cho ĐH 17', ''),
(38, '2025-12-24', 'Đang xuất NVL', 'Trần Văn Đức', 200, 20, 'Yêu cầu NVL cho KHSX cho ĐH 18', '');

-- --------------------------------------------------------

--
-- Table structure for table `phieuyeucaukiemtrachatluong`
--

CREATE TABLE `phieuyeucaukiemtrachatluong` (
  `maYC` int(11) NOT NULL,
  `tenPhieu` varchar(255) NOT NULL,
  `maSanPham` int(11) NOT NULL,
  `trangThai` varchar(100) NOT NULL DEFAULT 'Chờ duyệt',
  `ngayLap` date NOT NULL,
  `tenNguoiLap` varchar(100) NOT NULL,
  `maND` int(11) NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `thoiHanHoanThanh` date DEFAULT NULL,
  `ghiChu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuyeucaukiemtrachatluong`
--

INSERT INTO `phieuyeucaukiemtrachatluong` (`maYC`, `tenPhieu`, `maSanPham`, `trangThai`, `ngayLap`, `tenNguoiLap`, `maND`, `maKHSX`, `thoiHanHoanThanh`, `ghiChu`) VALUES
(33, 'Phiếu KTCL - KHSX cho ĐH 14', 1, 'Đã nhập kho', '0000-00-00', 'Trần Văn Đức', 200, 15, '2026-02-15', ''),
(34, 'Phiếu KTCL - KHSX cho ĐH 13', 5, 'Đã nhập kho', '0000-00-00', 'Trần Văn Đức', 200, 14, '2026-01-13', ''),
(35, 'Phiếu KTCL - KHSX cho ĐH 16', 2, 'Đã nhập kho', '0000-00-00', 'Nguyễn Văn A', 100, 18, '2026-01-11', ''),
(36, 'Phiếu KTCL - KHSX cho ĐH 17', 12, 'Từ chối', '2025-12-16', 'Trần Văn Đức', 200, 19, '2026-01-26', 'fjfjt'),
(37, 'Phiếu KTCL - KHSX cho ĐH 18', 7, 'Đã nhập kho', '2025-12-24', 'Trần Văn Đức', 200, 20, '2026-01-13', '');

-- --------------------------------------------------------

--
-- Table structure for table `phieuyeucaunhapkhonvl`
--

CREATE TABLE `phieuyeucaunhapkhonvl` (
  `maYCNK` int(11) NOT NULL,
  `tenPhieu` varchar(200) NOT NULL,
  `ngayLap` date NOT NULL,
  `trangThai` varchar(50) NOT NULL DEFAULT 'Chờ duyệt',
  `tenNguoiLap` varchar(100) NOT NULL,
  `maKHSX` int(11) NOT NULL,
  `maND` int(11) NOT NULL,
  `ghiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuyeucaunhapkhonvl`
--

INSERT INTO `phieuyeucaunhapkhonvl` (`maYCNK`, `tenPhieu`, `ngayLap`, `trangThai`, `tenNguoiLap`, `maKHSX`, `maND`, `ghiChu`) VALUES
(4, 'Phiếu yêu cầu nhập kho NVL - KHSX 2', '2025-12-16', 'Đã nhập kho', 'Admin', 2, 1, NULL),
(5, 'Phiếu yêu cầu nhập kho NVL - KHSX 10', '2025-12-16', 'Từ chối', 'Admin', 10, 1, 'fdfd'),
(6, 'Phiếu yêu cầu nhập kho NVL - KHSX 3', '2025-12-16', 'Đã nhập kho', 'Admin', 3, 1, NULL),
(7, 'Phiếu yêu cầu nhập kho NVL - KHSX 13', '2025-12-16', 'Đã nhập kho', 'Admin', 13, 1, NULL),
(8, 'Phiếu yêu cầu nhập kho NVL - KHSX 15', '2025-12-23', 'Đã nhập kho', 'Admin', 15, 1, NULL),
(9, 'Phiếu yêu cầu nhập kho NVL - KHSX 18', '2025-12-24', 'Đã nhập kho', 'Admin', 18, 1, NULL),
(10, 'Phiếu yêu cầu nhập kho NVL - KHSX 19', '2025-12-24', 'Đã nhập kho', 'Phạm Thị D', 19, 103, NULL),
(11, 'Phiếu yêu cầu nhập kho NVL - KHSX 20', '2025-12-24', 'Đã nhập kho', 'Phạm Thị D', 20, 103, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `maSanPham` int(11) NOT NULL,
  `tenSanPham` varchar(100) NOT NULL,
  `loaiSanPham` varchar(50) DEFAULT NULL,
  `soLuongTon` int(11) NOT NULL DEFAULT 0,
  `donVi` varchar(20) DEFAULT NULL,
  `moTa` varchar(255) DEFAULT NULL,
  `trangThaiSanPham` tinyint(1) DEFAULT NULL,
  `maKho` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`maSanPham`, `tenSanPham`, `loaiSanPham`, `soLuongTon`, `donVi`, `moTa`, `trangThaiSanPham`, `maKho`) VALUES
(1, 'Áo sơ mi trắng', 'Áo', 4004, 'Cái', 'Áo sơ mi trắng có thêu hoa cúc', 1, NULL),
(2, 'Áo sơ mi xanh dương', 'Áo', 2414, 'Cái', 'Áo tay dài, mỏng, thoáng mát', 0, 1),
(3, 'Áo sơ mi tay ngắn', 'Áo', 12550, 'Cái', 'Tay ngắn, màu trắng', NULL, 2),
(4, 'Áo sơ mi 1', 'Áo', 300, 'Cái', NULL, NULL, NULL),
(5, 'Áo sơ mi đỏ', 'Áo', 6010, 'Cái', 'Áo sơ mi màu đỏ', 1, NULL),
(6, 'Áo sơ mi vàng', 'Áo', 20, 'Cái', 'Áo sơ mi màu vàng', 1, NULL),
(7, 'Áo sơ mi đen', 'Áo', 49, 'Cái', 'Áo sơ mi màu đen', 1, NULL),
(8, 'Áo sơ mi tím', 'Áo', 125, 'Cái', 'Áo sơ mi màu tím', 1, NULL),
(9, 'Áo sơ mi xám', 'Áo', 20, 'Cái', 'Áo sơ mi màu xám', 1, NULL),
(10, 'Áo sơ mi tay dài custom hoa cúc', NULL, 10, 'Cái', NULL, NULL, NULL),
(11, 'Áo mới cà mau', NULL, 1, 'Cái', NULL, NULL, NULL),
(12, 'Áo hoa mai', NULL, 0, 'Cái', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `maTK` int(11) NOT NULL,
  `tenDangNhap` varchar(50) NOT NULL,
  `matKhau` varchar(100) NOT NULL,
  `trangThai` varchar(20) DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`maTK`, `tenDangNhap`, `matKhau`, `trangThai`) VALUES
(1, 'trankienquoc', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(2, 'nguyenvanan', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(3, 'tranthibinh', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(4, 'leminhcuong', '0ec6e061798a1a86d9afdbdb2475529d', 'Hoạt động'),
(5, 'phamthidung', '827ccb0eea8a706c4c34a16891f84e7b', 'không hoạt động'),
(6, '122232', '$2y$10$XQb4fwZIMx2LZ5hPjaMjh.8e1G7c/SvHYzfgPL559j8LQQWx/ABeq', 'không hoạt động'),
(7, 'trankienquoc122102004', '$2y$10$f0WVQWevy0D4S6T8kITg0eq/9VdNaRExfvhiVlbW6UDLHE9nbA.BC', 'không hoạt động'),
(8, 'quannguyen2002619', '$2y$10$aZ9Sxi5dp0pjzEnUprxtm.0kVrpRxKgxAbUlmyNr9mioxPZNmB.4.', 'không hoạt động'),
(9, '123333', '$2y$10$xLj2YZBkxMHwuFjxzH0vmutPse/sKDiZ2kOk.fYZ0zgbIahFoMt66', 'không hoạt động'),
(10, '1232221', '$2y$10$.QyQpjQWp3shgDbFMxqjn.zbzs7gAHhf/L10kS5jBO36Wuh.bQCa.', 'không hoạt động'),
(11, 'abc', '$2y$10$/d9jbV7WT4yYuJhyFhv6BONaMEfqkxqHN1RBnKsM600HwwrJ7Z.oO', 'Hoạt động'),
(12, 'QKT', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(13, 'duy', '1', 'Hoạt động'),
(15, 'trang', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(16, 'maivu', '$2y$10$tfkm.PX0OiDg44Zi4xIdv.rYTZ8r55WZ6ajaHf.vrh0FqZqNkQRoi', 'Hoạt động'),
(17, 'trankienuoc', '$2y$10$70U1s26.Nx6NoffcJvG3.OwQfbTNTI5FVRo2bHKYKFKOfAHk2NQYW', 'không hoạt động'),
(100, 'giamdoc', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(101, 'qlnhansu', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(102, 'qlsanxuat', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(103, 'qlkhonvl', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(104, 'nhanvienqc', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(105, 'qlkhotp', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(106, 'congnhancat', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(107, 'congnhanmay', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(200, 'qlxuong', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động'),
(201, 'dsfdsfgds', '827ccb0eea8a706c4c34a16891f84e7b', 'không hoạt động'),
(202, 'tranthik', '827ccb0eea8a706c4c34a16891f84e7b', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `thietbi`
--

CREATE TABLE `thietbi` (
  `maThietBi` int(11) NOT NULL,
  `tenThietBi` varchar(100) NOT NULL,
  `viTri` varchar(100) DEFAULT NULL,
  `trangThai` varchar(50) DEFAULT NULL,
  `maXuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thietbi`
--

INSERT INTO `thietbi` (`maThietBi`, `tenThietBi`, `viTri`, `trangThai`, `maXuong`) VALUES
(7, 'Máy ép nhiệt', '2', 'Đang hoạt động', 1),
(8, 'Máy cắt vải', '2', 'Đang hoạt động', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xuong`
--

CREATE TABLE `xuong` (
  `maXuong` int(11) NOT NULL,
  `tenXuong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xuong`
--

INSERT INTO `xuong` (`maXuong`, `tenXuong`) VALUES
(1, 'Xưởng cắt'),
(2, 'Xưởng may');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baocaoloi`
--
ALTER TABLE `baocaoloi`
  ADD PRIMARY KEY (`maBaoCao`),
  ADD KEY `FK_BCLOI_THIETBI` (`maThietBi`),
  ADD KEY `FK_BCLOI_NGUOIDUNG` (`maND`);

--
-- Indexes for table `calamviec`
--
ALTER TABLE `calamviec`
  ADD PRIMARY KEY (`maCa`);

--
-- Indexes for table `chitietkehoachsanxuat`
--
ALTER TABLE `chitietkehoachsanxuat`
  ADD PRIMARY KEY (`maCTKHSX`),
  ADD KEY `FK_CTKHSX_KHSX` (`maKHSX`),
  ADD KEY `FK_CTKHSX_GNTP` (`maGNTP`),
  ADD KEY `FK_CTKHSX_XUONG` (`maXuong`),
  ADD KEY `FK_CTKHSX_NVL` (`maNVL`);

--
-- Indexes for table `chitietphieuxuatnvl`
--
ALTER TABLE `chitietphieuxuatnvl`
  ADD PRIMARY KEY (`maCTPX`),
  ADD KEY `FK_ChiTietPhieuXuatNVL_NVL` (`maNVL`),
  ADD KEY `FK_ChiTietPhieuXuatNVL_Xuong` (`maXuong`),
  ADD KEY `FK_ChiTietPhieuXuatNVL_Phieu` (`maPhieu`);

--
-- Indexes for table `chitietphieuyeucaukiemtrachatluong`
--
ALTER TABLE `chitietphieuyeucaukiemtrachatluong`
  ADD PRIMARY KEY (`maCTPKT`),
  ADD KEY `maYC` (`maYC`);

--
-- Indexes for table `chitiet_lichlamviec`
--
ALTER TABLE `chitiet_lichlamviec`
  ADD PRIMARY KEY (`maLichLam`),
  ADD KEY `FK_LICH_NGUOIDUNG_NEW` (`maND`),
  ADD KEY `fk_chitiet_lichlamviec_maca` (`maCa`);

--
-- Indexes for table `chitiet_nhapkhotp`
--
ALTER TABLE `chitiet_nhapkhotp`
  ADD PRIMARY KEY (`maCTNKTP`),
  ADD KEY `maPhieu` (`maPhieu`),
  ADD KEY `maSanPham` (`maSanPham`);

--
-- Indexes for table `chitiet_phieuyeucaucapnvl`
--
ALTER TABLE `chitiet_phieuyeucaucapnvl`
  ADD PRIMARY KEY (`maCTPhieuYCCC`),
  ADD KEY `FK_ChiTiet_Phieu` (`maYCCC`),
  ADD KEY `FK_ChiTiet_NVL` (`maNVL`);

--
-- Indexes for table `chitiet_phieuyeucaunhapkhonvl`
--
ALTER TABLE `chitiet_phieuyeucaunhapkhonvl`
  ADD PRIMARY KEY (`maChiTiet_YCNK`),
  ADD KEY `maYCNK` (`maYCNK`),
  ADD KEY `maNVL` (`maNVL`);

--
-- Indexes for table `congviec`
--
ALTER TABLE `congviec`
  ADD PRIMARY KEY (`maCongViec`),
  ADD KEY `fk_kehoach_congviec` (`maKHSX`),
  ADD KEY `fk_congviec_xuong` (`maXuong`);

--
-- Indexes for table `donhangsanxuat`
--
ALTER TABLE `donhangsanxuat`
  ADD PRIMARY KEY (`maDonHang`),
  ADD KEY `FK_DONHANG_SANPHAM` (`maSanPham`);

--
-- Indexes for table `ghinhanthanhphamtheongay`
--
ALTER TABLE `ghinhanthanhphamtheongay`
  ADD PRIMARY KEY (`maGhiNhan`),
  ADD KEY `FK_GNTP_NGUOIDUNG` (`maNhanVien`),
  ADD KEY `FK_GNTP_SANPHAM` (`maSanPham`),
  ADD KEY `fk_ghinhanthanhphamtheongay_khsx` (`maKHSX`);

--
-- Indexes for table `kehoachsanxuat`
--
ALTER TABLE `kehoachsanxuat`
  ADD PRIMARY KEY (`maKHSX`),
  ADD KEY `FK_KHSX_NGUOIDUNG` (`maND`),
  ADD KEY `FK_KeHoachSanXuat_DonHangSanXuat` (`maDonHang`);

--
-- Indexes for table `kho`
--
ALTER TABLE `kho`
  ADD PRIMARY KEY (`maKho`);

--
-- Indexes for table `lichsupheduyet`
--
ALTER TABLE `lichsupheduyet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maKHSX` (`maKHSX`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maND`),
  ADD KEY `FK_NGUOIDUNG_TAIKHOAN` (`maTK`);

--
-- Indexes for table `nhapkhotp`
--
ALTER TABLE `nhapkhotp`
  ADD PRIMARY KEY (`maPhieu`),
  ADD KEY `maYC` (`maYC`),
  ADD KEY `FK_NHAPKHOTP_NGUOIDUNG_NEW` (`maND`);

--
-- Indexes for table `nvl`
--
ALTER TABLE `nvl`
  ADD PRIMARY KEY (`maNVL`),
  ADD KEY `fk_nvl_kho` (`maKho`);

--
-- Indexes for table `phieunhapnvl`
--
ALTER TABLE `phieunhapnvl`
  ADD PRIMARY KEY (`maPNVL`),
  ADD UNIQUE KEY `phieuyeucaunhapkhonvl` (`maYCNK`,`maNVL`),
  ADD KEY `fk_phieunhapnvl_nvl` (`maNVL`);

--
-- Indexes for table `phieuxuatnvl`
--
ALTER TABLE `phieuxuatnvl`
  ADD PRIMARY KEY (`maPhieu`),
  ADD KEY `FK_PhieuXuatNVL_NguoiDung` (`maND`),
  ADD KEY `fk_phieuxuat_yccc` (`maYCCC`);

--
-- Indexes for table `phieuxuatthanhpham`
--
ALTER TABLE `phieuxuatthanhpham`
  ADD PRIMARY KEY (`maPhieuXuat`),
  ADD KEY `maDonHang` (`maDonHang`),
  ADD KEY `maSanPham` (`maSanPham`);

--
-- Indexes for table `phieuyeucaucungcapnvl`
--
ALTER TABLE `phieuyeucaucungcapnvl`
  ADD PRIMARY KEY (`maYCCC`),
  ADD KEY `FK_PhieuYeuCauCungCapNVL_NguoiDung` (`maND`),
  ADD KEY `FK_PhieuYeuCauCungCapNVL_KHSX` (`maKHSX`);

--
-- Indexes for table `phieuyeucaukiemtrachatluong`
--
ALTER TABLE `phieuyeucaukiemtrachatluong`
  ADD PRIMARY KEY (`maYC`),
  ADD KEY `FK_PhieuYCKTCL_SanPham` (`maSanPham`),
  ADD KEY `FK_KTCL_ND` (`maND`),
  ADD KEY `FK_KTCL_KHSX` (`maKHSX`);

--
-- Indexes for table `phieuyeucaunhapkhonvl`
--
ALTER TABLE `phieuyeucaunhapkhonvl`
  ADD PRIMARY KEY (`maYCNK`),
  ADD KEY `FK_YCNK_KHSX` (`maKHSX`),
  ADD KEY `FK_YCNK_ND` (`maND`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`maSanPham`),
  ADD KEY `fk_sanpham_kho` (`maKho`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`maTK`),
  ADD UNIQUE KEY `tenDangNhap` (`tenDangNhap`);

--
-- Indexes for table `thietbi`
--
ALTER TABLE `thietbi`
  ADD PRIMARY KEY (`maThietBi`),
  ADD KEY `fk_thietbi_xuong` (`maXuong`);

--
-- Indexes for table `xuong`
--
ALTER TABLE `xuong`
  ADD PRIMARY KEY (`maXuong`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baocaoloi`
--
ALTER TABLE `baocaoloi`
  MODIFY `maBaoCao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chitietkehoachsanxuat`
--
ALTER TABLE `chitietkehoachsanxuat`
  MODIFY `maCTKHSX` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chitietphieuxuatnvl`
--
ALTER TABLE `chitietphieuxuatnvl`
  MODIFY `maCTPX` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `chitietphieuyeucaukiemtrachatluong`
--
ALTER TABLE `chitietphieuyeucaukiemtrachatluong`
  MODIFY `maCTPKT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chitiet_lichlamviec`
--
ALTER TABLE `chitiet_lichlamviec`
  MODIFY `maLichLam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `chitiet_nhapkhotp`
--
ALTER TABLE `chitiet_nhapkhotp`
  MODIFY `maCTNKTP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chitiet_phieuyeucaucapnvl`
--
ALTER TABLE `chitiet_phieuyeucaucapnvl`
  MODIFY `maCTPhieuYCCC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `chitiet_phieuyeucaunhapkhonvl`
--
ALTER TABLE `chitiet_phieuyeucaunhapkhonvl`
  MODIFY `maChiTiet_YCNK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `congviec`
--
ALTER TABLE `congviec`
  MODIFY `maCongViec` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donhangsanxuat`
--
ALTER TABLE `donhangsanxuat`
  MODIFY `maDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ghinhanthanhphamtheongay`
--
ALTER TABLE `ghinhanthanhphamtheongay`
  MODIFY `maGhiNhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `kehoachsanxuat`
--
ALTER TABLE `kehoachsanxuat`
  MODIFY `maKHSX` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `kho`
--
ALTER TABLE `kho`
  MODIFY `maKho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lichsupheduyet`
--
ALTER TABLE `lichsupheduyet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `maND` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `nhapkhotp`
--
ALTER TABLE `nhapkhotp`
  MODIFY `maPhieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `nvl`
--
ALTER TABLE `nvl`
  MODIFY `maNVL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `phieunhapnvl`
--
ALTER TABLE `phieunhapnvl`
  MODIFY `maPNVL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `phieuxuatnvl`
--
ALTER TABLE `phieuxuatnvl`
  MODIFY `maPhieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `phieuxuatthanhpham`
--
ALTER TABLE `phieuxuatthanhpham`
  MODIFY `maPhieuXuat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `phieuyeucaucungcapnvl`
--
ALTER TABLE `phieuyeucaucungcapnvl`
  MODIFY `maYCCC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `phieuyeucaukiemtrachatluong`
--
ALTER TABLE `phieuyeucaukiemtrachatluong`
  MODIFY `maYC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `phieuyeucaunhapkhonvl`
--
ALTER TABLE `phieuyeucaunhapkhonvl`
  MODIFY `maYCNK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `maSanPham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `maTK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `thietbi`
--
ALTER TABLE `thietbi`
  MODIFY `maThietBi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `xuong`
--
ALTER TABLE `xuong`
  MODIFY `maXuong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baocaoloi`
--
ALTER TABLE `baocaoloi`
  ADD CONSTRAINT `FK_BCL_ND_ID` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `FK_BCL_THIETBI_ID` FOREIGN KEY (`maThietBi`) REFERENCES `thietbi` (`maThietBi`);

--
-- Constraints for table `chitietkehoachsanxuat`
--
ALTER TABLE `chitietkehoachsanxuat`
  ADD CONSTRAINT `FK_CTKHSX_GNTP` FOREIGN KEY (`maGNTP`) REFERENCES `ghinhanthanhphamtheongay` (`maGhiNhan`),
  ADD CONSTRAINT `FK_CTKHSX_KHSX` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`),
  ADD CONSTRAINT `FK_CTKHSX_NVL` FOREIGN KEY (`maNVL`) REFERENCES `nvl` (`maNVL`),
  ADD CONSTRAINT `FK_CTKHSX_XUONG` FOREIGN KEY (`maXuong`) REFERENCES `xuong` (`maXuong`);

--
-- Constraints for table `chitietphieuxuatnvl`
--
ALTER TABLE `chitietphieuxuatnvl`
  ADD CONSTRAINT `FK_ChiTietPhieuXuatNVL_Phieu` FOREIGN KEY (`maPhieu`) REFERENCES `phieuxuatnvl` (`maPhieu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chitietphieuyeucaukiemtrachatluong`
--
ALTER TABLE `chitietphieuyeucaukiemtrachatluong`
  ADD CONSTRAINT `chitietphieuyeucaukiemtrachatluong_ibfk_1` FOREIGN KEY (`maYC`) REFERENCES `phieuyeucaukiemtrachatluong` (`maYC`) ON DELETE CASCADE;

--
-- Constraints for table `chitiet_lichlamviec`
--
ALTER TABLE `chitiet_lichlamviec`
  ADD CONSTRAINT `FK_LICH_NGUOIDUNG` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_LICH_NGUOIDUNG_NEW` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `fk_chitiet_lichlamviec_maca` FOREIGN KEY (`maCa`) REFERENCES `calamviec` (`maCa`);

--
-- Constraints for table `chitiet_nhapkhotp`
--
ALTER TABLE `chitiet_nhapkhotp`
  ADD CONSTRAINT `FK_CTNKTP_PHIEUNHAP` FOREIGN KEY (`maPhieu`) REFERENCES `nhapkhotp` (`maPhieu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chitiet_nhapkhotp_ibfk_1` FOREIGN KEY (`maPhieu`) REFERENCES `nhapkhotp` (`maPhieu`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitiet_nhapkhotp_ibfk_2` FOREIGN KEY (`maSanPham`) REFERENCES `san_pham` (`maSanPham`);

--
-- Constraints for table `chitiet_phieuyeucaucapnvl`
--
ALTER TABLE `chitiet_phieuyeucaucapnvl`
  ADD CONSTRAINT `FK_CT_NVL_ID` FOREIGN KEY (`maNVL`) REFERENCES `nvl` (`maNVL`),
  ADD CONSTRAINT `FK_CT_YCCC_ID` FOREIGN KEY (`maYCCC`) REFERENCES `phieuyeucaucungcapnvl` (`maYCCC`) ON DELETE CASCADE;

--
-- Constraints for table `chitiet_phieuyeucaunhapkhonvl`
--
ALTER TABLE `chitiet_phieuyeucaunhapkhonvl`
  ADD CONSTRAINT `FK_CT_YCNK_ID` FOREIGN KEY (`maYCNK`) REFERENCES `phieuyeucaunhapkhonvl` (`maYCNK`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CT_YCNK_NVL` FOREIGN KEY (`maNVL`) REFERENCES `nvl` (`maNVL`);

--
-- Constraints for table `congviec`
--
ALTER TABLE `congviec`
  ADD CONSTRAINT `fk_congviec_xuong` FOREIGN KEY (`maXuong`) REFERENCES `xuong` (`maXuong`),
  ADD CONSTRAINT `fk_kehoach_congviec` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`);

--
-- Constraints for table `donhangsanxuat`
--
ALTER TABLE `donhangsanxuat`
  ADD CONSTRAINT `FK_DONHANG_SANPHAM` FOREIGN KEY (`maSanPham`) REFERENCES `san_pham` (`maSanPham`);

--
-- Constraints for table `ghinhanthanhphamtheongay`
--
ALTER TABLE `ghinhanthanhphamtheongay`
  ADD CONSTRAINT `FK_GNTP_NGUOIDUNG` FOREIGN KEY (`maNhanVien`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `FK_GNTP_SANPHAM` FOREIGN KEY (`maSanPham`) REFERENCES `san_pham` (`maSanPham`),
  ADD CONSTRAINT `fk_ghinhanthanhphamtheongay_khsx` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`);

--
-- Constraints for table `kehoachsanxuat`
--
ALTER TABLE `kehoachsanxuat`
  ADD CONSTRAINT `FK_KHSX_NGUOIDUNG` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `FK_KeHoachSanXuat_DonHangSanXuat` FOREIGN KEY (`maDonHang`) REFERENCES `donhangsanxuat` (`maDonHang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lichsupheduyet`
--
ALTER TABLE `lichsupheduyet`
  ADD CONSTRAINT `lichsupheduyet_ibfk_1` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`);

--
-- Constraints for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `FK_NGUOIDUNG_TAIKHOAN` FOREIGN KEY (`maTK`) REFERENCES `taikhoan` (`maTK`);

--
-- Constraints for table `nhapkhotp`
--
ALTER TABLE `nhapkhotp`
  ADD CONSTRAINT `FK_NHAPKHOTP_NGUOIDUNG_NEW` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `nvl`
--
ALTER TABLE `nvl`
  ADD CONSTRAINT `fk_nvl_kho` FOREIGN KEY (`maKho`) REFERENCES `kho` (`maKho`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `phieunhapnvl`
--
ALTER TABLE `phieunhapnvl`
  ADD CONSTRAINT `fk_nhapkhonvl_phieuyeucaunhapkho` FOREIGN KEY (`maYCNK`) REFERENCES `phieuyeucaunhapkhonvl` (`maYCNK`);

--
-- Constraints for table `phieuxuatnvl`
--
ALTER TABLE `phieuxuatnvl`
  ADD CONSTRAINT `FK_PX_ND` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`),
  ADD CONSTRAINT `FK_PX_YCCC` FOREIGN KEY (`maYCCC`) REFERENCES `phieuyeucaucungcapnvl` (`maYCCC`);

--
-- Constraints for table `phieuxuatthanhpham`
--
ALTER TABLE `phieuxuatthanhpham`
  ADD CONSTRAINT `FK_PXTP_DONHANG` FOREIGN KEY (`maDonHang`) REFERENCES `donhangsanxuat` (`maDonHang`),
  ADD CONSTRAINT `FK_PXTP_SANPHAM` FOREIGN KEY (`maSanPham`) REFERENCES `san_pham` (`maSanPham`);

--
-- Constraints for table `phieuyeucaucungcapnvl`
--
ALTER TABLE `phieuyeucaucungcapnvl`
  ADD CONSTRAINT `FK_YCCC_KHSX` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`),
  ADD CONSTRAINT `FK_YCCC_ND` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Constraints for table `phieuyeucaukiemtrachatluong`
--
ALTER TABLE `phieuyeucaukiemtrachatluong`
  ADD CONSTRAINT `FK_KTCL_KHSX_NEW` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`),
  ADD CONSTRAINT `FK_KTCL_NGUOIDUNG` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);

--
-- Constraints for table `phieuyeucaunhapkhonvl`
--
ALTER TABLE `phieuyeucaunhapkhonvl`
  ADD CONSTRAINT `FK_YCNK_KHSX_ID` FOREIGN KEY (`maKHSX`) REFERENCES `kehoachsanxuat` (`maKHSX`),
  ADD CONSTRAINT `FK_YCNK_ND_ID` FOREIGN KEY (`maND`) REFERENCES `nguoidung` (`maND`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
