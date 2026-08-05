-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 03:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_jember`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_about`
--

CREATE TABLE `tbl_about` (
  `id` int(11) NOT NULL,
  `nama_instansi` varchar(200) DEFAULT 'Pemerintah Kabupaten Jember',
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'logo.png',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_about`
--

INSERT INTO `tbl_about` (`id`, `nama_instansi`, `alamat`, `telepon`, `email`, `website`, `logo`, `updated_at`) VALUES
(1, 'Pemerintah Kabupaten Jember', 'Jl. Sultan Agung No. 2, Jember', '(0331) 331234', 'info@jember.go.id', 'www.jember.go.id', 'logo.png', '2026-08-05 04:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit_log`
--

CREATE TABLE `tbl_audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `tabel` varchar(50) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `data_lama` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_lama`)),
  `data_baru` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_baru`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_audit_log`
--

INSERT INTO `tbl_audit_log` (`id`, `user_id`, `aksi`, `tabel`, `record_id`, `data_lama`, `data_baru`, `ip_address`, `timestamp`) VALUES
(1, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 04:55:57'),
(2, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 05:35:23'),
(3, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 10:57:11'),
(4, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 10:59:15'),
(5, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:13:52'),
(6, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:18:23'),
(7, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:20:39'),
(8, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:25:09'),
(9, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:26:56'),
(10, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:26:59'),
(11, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:00'),
(12, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:00'),
(13, 1, 'SYNC', 'tbl_bps_data', NULL, NULL, NULL, '::1', '2026-08-05 11:27:01'),
(14, 1, 'SYNC', 'tbl_bps_data', NULL, NULL, NULL, '::1', '2026-08-05 11:27:09'),
(15, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:14'),
(16, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:15'),
(17, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:15'),
(18, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:16'),
(19, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:16'),
(20, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:16'),
(21, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:17'),
(22, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:17'),
(23, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:27:17'),
(24, 1, 'UPDATE', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:33:56'),
(25, 1, 'UPDATE', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:37:05'),
(26, 1, 'UPDATE', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:38:28'),
(27, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:44:16'),
(28, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:47:25'),
(29, 1, 'UPDATE', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:48:59'),
(30, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 11:49:28'),
(31, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 11:57:04'),
(32, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 12:21:17'),
(33, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 12:21:18'),
(34, 1, 'SYNC', 'tbl_bps_data', NULL, NULL, NULL, '::1', '2026-08-05 12:21:23'),
(35, 1, 'CLASSIFY', 'tbl_hasil_klasifikasi', NULL, NULL, '{\"tahun\":\"2026\",\"jumlah\":25}', '::1', '2026-08-05 12:28:35'),
(36, 1, 'SYNC', 'tbl_siak_data', NULL, NULL, '{\"sumber\":\"SIAK\",\"jumlah\":3}', '::1', '2026-08-05 12:30:34'),
(37, 1, 'DELETE', 'tbl_rekam_medis', 1, NULL, NULL, '::1', '2026-08-05 12:39:23'),
(38, 1, 'DELETE', 'tbl_rekam_medis', 2, NULL, NULL, '::1', '2026-08-05 12:39:27'),
(39, 1, 'DELETE', 'tbl_rekam_medis', 3, NULL, NULL, '::1', '2026-08-05 12:39:30'),
(40, 1, 'DELETE', 'tbl_rumah_sakit', 1, NULL, NULL, '::1', '2026-08-05 12:40:10'),
(41, 1, 'DELETE', 'tbl_rumah_sakit', 1, NULL, NULL, '::1', '2026-08-05 12:40:13'),
(42, 1, 'DELETE', 'tbl_stok_obat', 10, NULL, NULL, '::1', '2026-08-05 12:40:29'),
(43, 1, 'DELETE', 'tbl_stok_obat', 15, NULL, NULL, '::1', '2026-08-05 12:40:32'),
(44, 1, 'DELETE', 'tbl_stok_obat', 1, NULL, NULL, '::1', '2026-08-05 12:40:35'),
(45, 1, 'DELETE', 'tbl_stok_obat', 9, NULL, NULL, '::1', '2026-08-05 12:40:39'),
(46, 1, 'DELETE', 'tbl_stok_obat', 6, NULL, NULL, '::1', '2026-08-05 12:40:42'),
(47, 1, 'DELETE', 'tbl_rumah_sakit', 1, NULL, NULL, '::1', '2026-08-05 12:41:27'),
(48, 1, 'DELETE', 'tbl_rumah_sakit', 1, NULL, NULL, '::1', '2026-08-05 12:42:31'),
(49, 1, 'DELETE', 'tbl_puskesmas', 6, NULL, NULL, '::1', '2026-08-05 12:42:36'),
(50, 1, 'DELETE', 'tbl_puskesmas', 5, NULL, NULL, '::1', '2026-08-05 12:44:21'),
(51, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 13:40:33'),
(52, 1, 'LOGIN', 'tbl_users', 1, NULL, NULL, '::1', '2026-08-05 13:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bantuan_kesehatan`
--

CREATE TABLE `tbl_bantuan_kesehatan` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `jenis_bantuan` varchar(100) NOT NULL,
  `nominal` decimal(15,2) DEFAULT 0.00,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak','Dibayar') DEFAULT 'Diajukan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bantuan_kesehatan`
--

INSERT INTO `tbl_bantuan_kesehatan` (`id`, `warga_id`, `jenis_bantuan`, `nominal`, `tanggal`, `keterangan`, `status`, `created_at`) VALUES
(1, 1, 'Bantuan Biaya Rumah Sakit', 2500000.00, '2026-01-12', 'Bantuan biaya rawat inap DBD', 'Dibayar', '2026-08-05 12:39:18'),
(2, 3, 'Bantuan Obat Kronis', 500000.00, '2026-01-20', 'Bantuan pembelian obat ISPA', 'Dibayar', '2026-08-05 12:39:18'),
(3, 6, 'Bantuan Kontrol Rutin', 300000.00, '2026-02-05', 'Bantuan transportasi kontrol hipertensi', 'Disetujui', '2026-08-05 12:39:18'),
(4, 8, 'Bantuan Obat Diabetes', 750000.00, '2026-02-15', 'Bantuan pembelian Metformin 3 bulan', 'Dibayar', '2026-08-05 12:39:18'),
(5, 11, 'Bantuan Biaya RS', 1500000.00, '2026-02-20', 'Bantuan biaya perawatan maag', 'Diajukan', '2026-08-05 12:39:18'),
(6, 16, 'Bantuan Alat Kesehatan', 400000.00, '2026-03-05', 'Bantuan pembelian inhaler', 'Disetujui', '2026-08-05 12:39:18'),
(7, 21, 'Bantuan Biaya RS', 3000000.00, '2026-03-12', 'Bantuan biaya rawat inap DBD', 'Dibayar', '2026-08-05 12:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bps_data`
--

CREATE TABLE `tbl_bps_data` (
  `id` int(11) NOT NULL,
  `kode_wilayah` varchar(20) NOT NULL,
  `nama_wilayah` varchar(100) DEFAULT NULL,
  `data_statistik` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_statistik`)),
  `tahun` int(11) DEFAULT NULL,
  `sumber` varchar(100) DEFAULT 'BPS',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bps_data`
--

INSERT INTO `tbl_bps_data` (`id`, `kode_wilayah`, `nama_wilayah`, `data_statistik`, `tahun`, `sumber`, `created_at`) VALUES
(1, '35.09.01', 'Kecamatan Ajung', '{\"jumlah_penduduk\":45000,\"luas\":25.5,\"pendidikan\":\"78%\",\"kesehatan\":\"85%\"}', 2026, 'BPS', '2026-08-05 11:27:01'),
(2, '35.09.02', 'Kecamatan Ambulu', '{\"jumlah_penduduk\":52000,\"luas\":30.2,\"pendidikan\":\"75%\",\"kesehatan\":\"82%\"}', 2026, 'BPS', '2026-08-05 11:27:01'),
(3, '35.09.01', 'Kecamatan Ajung', '{\"jumlah_penduduk\":45000,\"luas\":25.5,\"pendidikan\":\"78%\",\"kesehatan\":\"85%\"}', 2026, 'BPS', '2026-08-05 11:27:09'),
(4, '35.09.02', 'Kecamatan Ambulu', '{\"jumlah_penduduk\":52000,\"luas\":30.2,\"pendidikan\":\"75%\",\"kesehatan\":\"82%\"}', 2026, 'BPS', '2026-08-05 11:27:09'),
(5, '35.09.01', 'Kecamatan Ajung', '{\"jumlah_penduduk\":45000,\"luas\":25.5,\"pendidikan\":\"78%\",\"kesehatan\":\"85%\"}', 2026, 'BPS', '2026-08-05 12:21:23'),
(6, '35.09.02', 'Kecamatan Ambulu', '{\"jumlah_penduduk\":52000,\"luas\":30.2,\"pendidikan\":\"75%\",\"kesehatan\":\"82%\"}', 2026, 'BPS', '2026-08-05 12:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_desa`
--

CREATE TABLE `tbl_desa` (
  `id` int(11) NOT NULL,
  `kecamatan_id` int(11) NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `kode_desa` varchar(10) NOT NULL,
  `jumlah_rt` int(11) DEFAULT 0,
  `jumlah_rw` int(11) DEFAULT 0,
  `koordinat_lat` decimal(10,6) DEFAULT NULL,
  `koordinat_lng` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_desa`
--

INSERT INTO `tbl_desa` (`id`, `kecamatan_id`, `nama_desa`, `kode_desa`, `jumlah_rt`, `jumlah_rw`, `koordinat_lat`, `koordinat_lng`, `created_at`) VALUES
(1, 1, 'Ajung', '001', 20, 5, NULL, NULL, '2026-08-05 04:55:07'),
(2, 1, 'Bakalan', '002', 15, 4, NULL, NULL, '2026-08-05 04:55:07'),
(3, 1, 'Bintoro', '003', 18, 5, NULL, NULL, '2026-08-05 04:55:07'),
(4, 1, 'Cangkring', '004', 12, 3, NULL, NULL, '2026-08-05 04:55:07'),
(5, 1, 'Gambiran', '005', 16, 4, NULL, NULL, '2026-08-05 04:55:07'),
(6, 1, 'Glagahwero', '006', 14, 4, NULL, NULL, '2026-08-05 04:55:07'),
(7, 1, 'Kampung Bagor', '007', 10, 3, NULL, NULL, '2026-08-05 04:55:07'),
(8, 1, 'Krai', '008', 22, 6, NULL, NULL, '2026-08-05 04:55:07'),
(9, 1, 'Panduman', '009', 11, 3, NULL, NULL, '2026-08-05 04:55:07'),
(10, 1, 'Suci', '010', 17, 5, NULL, NULL, '2026-08-05 04:55:07'),
(11, 2, 'Ambulu', '011', 18, 5, NULL, NULL, '2026-08-05 04:55:08'),
(12, 2, 'Andongsari', '012', 14, 4, NULL, NULL, '2026-08-05 04:55:08'),
(13, 2, 'Arjasa', '013', 16, 4, NULL, NULL, '2026-08-05 04:55:08'),
(14, 2, 'Badean', '014', 12, 3, NULL, NULL, '2026-08-05 04:55:08'),
(15, 2, 'Karangsono', '015', 20, 5, NULL, NULL, '2026-08-05 04:55:08'),
(16, 2, 'Sranak', '016', 10, 3, NULL, NULL, '2026-08-05 04:55:08'),
(17, 2, 'Sumbersari', '017', 15, 4, NULL, NULL, '2026-08-05 04:55:08'),
(18, 2, 'Tanjungrejo', '018', 13, 4, NULL, NULL, '2026-08-05 04:55:08'),
(19, 4, 'Bangsalsari', '027', 20, 5, NULL, NULL, '2026-08-05 04:55:08'),
(20, 4, 'Banjarsari', '028', 14, 4, NULL, NULL, '2026-08-05 04:55:08'),
(21, 4, 'Curahmalang', '029', 16, 4, NULL, NULL, '2026-08-05 04:55:08'),
(22, 4, 'Gondosari', '030', 12, 3, NULL, NULL, '2026-08-05 04:55:08'),
(23, 4, 'Karangbayat', '031', 18, 5, NULL, NULL, '2026-08-05 04:55:08'),
(24, 4, 'Ombul', '032', 15, 4, NULL, NULL, '2026-08-05 04:55:08'),
(25, 4, 'Paleteang', '033', 11, 3, NULL, NULL, '2026-08-05 04:55:08'),
(26, 4, 'Sukorejo', '034', 13, 4, NULL, NULL, '2026-08-05 04:55:08'),
(27, 4, 'Tegalwangi', '035', 17, 5, NULL, NULL, '2026-08-05 04:55:08'),
(28, 4, 'Tugusari', '036', 19, 5, NULL, NULL, '2026-08-05 04:55:08'),
(29, 1, 'Ajung', '010001', 15, 5, -8.155300, 113.645500, '2026-08-05 12:13:57'),
(30, 1, 'Bandungsari', '010002', 12, 4, -8.160000, 113.650000, '2026-08-05 12:13:57'),
(31, 2, 'Ambulu', '020001', 18, 6, -8.345300, 113.605500, '2026-08-05 12:13:57'),
(32, 2, 'Tanjungrejo', '020002', 10, 3, -8.350000, 113.610000, '2026-08-05 12:13:57'),
(33, 4, 'Bangsalsari', '040001', 20, 7, -8.195300, 113.705500, '2026-08-05 12:13:57'),
(34, 4, 'Tegalgede', '040002', 14, 5, -8.200000, 113.710000, '2026-08-05 12:13:57'),
(35, 10, 'Kaliwates', '100001', 25, 8, -8.165300, 113.665500, '2026-08-05 12:13:57'),
(36, 10, 'Sempusari', '100002', 16, 5, -8.170000, 113.670000, '2026-08-05 12:13:57'),
(37, 16, 'Patrang', '160001', 22, 7, -8.135300, 113.655500, '2026-08-05 12:13:57'),
(38, 16, 'Sumberwuni', '160002', 11, 4, -8.140000, 113.660000, '2026-08-05 12:13:57'),
(39, 25, 'Arjasa', '250001', 12, 4, -8.180000, 113.720000, '2026-08-05 12:23:56'),
(40, 25, 'Biting', '250002', 10, 3, -8.185000, 113.725000, '2026-08-05 12:23:56'),
(41, 26, 'Jelbuk', '260001', 14, 5, -8.120000, 113.680000, '2026-08-05 12:23:56'),
(42, 26, 'Panduman', '260002', 11, 4, -8.125000, 113.685000, '2026-08-05 12:23:56'),
(43, 27, 'Sumber Baru', '270001', 16, 5, -8.250000, 113.580000, '2026-08-05 12:23:56'),
(44, 27, 'Tempursari', '270002', 13, 4, -8.255000, 113.585000, '2026-08-05 12:23:56'),
(45, 28, 'Wuluhan', '280001', 15, 5, -8.280000, 113.560000, '2026-08-05 12:23:56'),
(46, 28, 'Dukuhdempok', '280002', 12, 4, -8.285000, 113.565000, '2026-08-05 12:23:56'),
(53, 35, 'Panti', '300001', 14, 5, -8.150000, 113.700000, '2026-08-05 12:27:58'),
(54, 35, 'Serambi', '300002', 11, 4, -8.155000, 113.705000, '2026-08-05 12:27:58'),
(55, 36, 'Sumberjambe', '310001', 13, 4, -8.100000, 113.750000, '2026-08-05 12:27:58'),
(56, 36, 'Plerean', '310002', 10, 3, -8.105000, 113.755000, '2026-08-05 12:27:58'),
(57, 37, 'Umbulsari', '320001', 15, 5, -8.230000, 113.550000, '2026-08-05 12:27:58'),
(58, 37, 'Tanjungsari', '320002', 12, 4, -8.235000, 113.555000, '2026-08-05 12:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_desa_data`
--

CREATE TABLE `tbl_desa_data` (
  `id` int(11) NOT NULL,
  `desa_id` int(11) NOT NULL,
  `data_warga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_warga`)),
  `data_infrastruktur` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_infrastruktur`)),
  `last_sync` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dokumen`
--

CREATE TABLE `tbl_dokumen` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `jenis_dokumen` enum('KTP','KK','Akta Kelahiran','Akta Kematian','Surat Pindah','Surat Keterangan') NOT NULL,
  `nomor_dokumen` varchar(50) DEFAULT NULL,
  `tanggal_terbit` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Non-aktif','Proses') DEFAULT 'Proses',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dokumen`
--

INSERT INTO `tbl_dokumen` (`id`, `warga_id`, `jenis_dokumen`, `nomor_dokumen`, `tanggal_terbit`, `file_path`, `status`, `created_at`) VALUES
(1, 1, 'KTP', '3509010101010001', '2020-01-15', 'ktp_budi.pdf', 'Aktif', '2026-08-05 12:16:11'),
(2, 1, 'KK', '35090101010100001', '2020-01-15', 'kk_budi.pdf', 'Aktif', '2026-08-05 12:16:11'),
(3, 3, 'KTP', '3509010101010003', '2021-03-20', 'ktp_andi.pdf', 'Aktif', '2026-08-05 12:16:11'),
(4, 5, 'KTP', '3509010101010005', '2019-05-10', 'ktp_rudi.pdf', 'Aktif', '2026-08-05 12:16:11'),
(5, 6, 'KTP', '3509020202020001', '2018-07-25', 'ktp_joko.pdf', 'Aktif', '2026-08-05 12:16:11'),
(6, 8, 'KTP', '3509020202020003', '2022-01-10', 'ktp_ahmad.pdf', 'Aktif', '2026-08-05 12:16:11'),
(7, 11, 'KTP', '3509040404040001', '2019-11-05', 'ktp_sugeng.pdf', 'Aktif', '2026-08-05 12:16:11'),
(8, 16, 'KTP', '3509101010100001', '2020-09-15', 'ktp_eko.pdf', 'Aktif', '2026-08-05 12:16:11'),
(9, 21, 'KTP', '3509161616160001', '2021-06-20', 'ktp_tri.pdf', 'Aktif', '2026-08-05 12:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hasil_klasifikasi`
--

CREATE TABLE `tbl_hasil_klasifikasi` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `skor` decimal(10,4) NOT NULL,
  `kategori` enum('Sangat Miskin','Miskin','Rentan','Layak Hidup','Sejahtera') NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) DEFAULT NULL,
  `metode` varchar(50) DEFAULT 'TOPSIS',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hasil_klasifikasi`
--

INSERT INTO `tbl_hasil_klasifikasi` (`id`, `warga_id`, `skor`, `kategori`, `tahun`, `bulan`, `metode`, `created_at`) VALUES
(26, 1, 61.0000, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(27, 2, 100.0000, 'Sejahtera', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(28, 3, 74.3200, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(29, 4, 61.0000, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(30, 5, 52.4100, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(31, 6, 64.8000, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(32, 7, 64.8100, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(33, 8, 100.0000, 'Sejahtera', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(34, 9, 56.8600, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(35, 10, 45.6100, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(36, 11, 63.3200, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(37, 12, 100.0000, 'Sejahtera', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(38, 13, 71.2900, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(39, 14, 55.1500, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(40, 15, 56.2800, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(41, 16, 25.8200, 'Miskin', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(42, 17, 17.1000, 'Sangat Miskin', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(43, 18, 60.4300, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(44, 19, 56.2800, 'Rentan', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(45, 20, 38.2400, 'Miskin', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(46, 21, 62.5600, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(47, 22, 100.0000, 'Sejahtera', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(48, 23, 79.1000, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(49, 24, 62.7800, 'Layak Hidup', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35'),
(50, 25, 35.0600, 'Miskin', 2026, 8, 'TOPSIS', '2026-08-05 12:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kecamatan`
--

CREATE TABLE `tbl_kecamatan` (
  `id` int(11) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL,
  `kode_kecamatan` varchar(10) NOT NULL,
  `luas` decimal(10,2) DEFAULT NULL,
  `jumlah_desa` int(11) DEFAULT 0,
  `koordinat_lat` decimal(10,6) DEFAULT NULL,
  `koordinat_lng` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kecamatan`
--

INSERT INTO `tbl_kecamatan` (`id`, `nama_kecamatan`, `kode_kecamatan`, `luas`, `jumlah_desa`, `koordinat_lat`, `koordinat_lng`, `created_at`) VALUES
(1, 'Ajung', '01', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(2, 'Ambulu', '02', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(4, 'Bangsalsari', '04', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(5, 'Balung', '05', NULL, 9, NULL, NULL, '2026-08-05 04:54:50'),
(6, 'Gumuk Mas', '06', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(7, 'Jenggawah', '07', NULL, 9, NULL, NULL, '2026-08-05 04:54:50'),
(8, 'Jombang', '08', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(9, 'Kalisat', '09', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(10, 'Kaliwates', '10', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(11, 'Kencong', '11', NULL, 7, NULL, NULL, '2026-08-05 04:54:50'),
(12, 'Ledokombo', '12', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(13, 'Mayang', '13', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(14, 'Mumbulsari', '14', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(15, 'Pakusari', '15', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(16, 'Patrang', '16', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(17, 'Puger', '17', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(18, 'Rambipuji', '18', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(19, 'Semboro', '19', NULL, 7, NULL, NULL, '2026-08-05 04:54:50'),
(20, 'Sukorambi', '20', NULL, 7, NULL, NULL, '2026-08-05 04:54:50'),
(21, 'Sukowono', '21', NULL, 9, NULL, NULL, '2026-08-05 04:54:50'),
(22, 'Sumber Sari', '22', NULL, 10, NULL, NULL, '2026-08-05 04:54:50'),
(23, 'Tanggul', '23', NULL, 9, NULL, NULL, '2026-08-05 04:54:50'),
(24, 'Tempurejo', '24', NULL, 8, NULL, NULL, '2026-08-05 04:54:50'),
(25, 'Arjasa', '25', NULL, 6, NULL, NULL, '2026-08-05 12:23:36'),
(26, 'Jelbuk', '26', NULL, 6, NULL, NULL, '2026-08-05 12:23:36'),
(27, 'Sumber Baru', '27', NULL, 10, NULL, NULL, '2026-08-05 12:23:36'),
(28, 'Wuluhan', '28', NULL, 7, NULL, NULL, '2026-08-05 12:23:36'),
(29, 'Silo', '29', NULL, 8, NULL, NULL, '2026-08-05 12:23:36'),
(35, 'Panti', '30', NULL, 7, NULL, NULL, '2026-08-05 12:27:25'),
(36, 'Sumberjambe', '31', NULL, 7, NULL, NULL, '2026-08-05 12:27:25'),
(37, 'Umbulsari', '32', NULL, 8, NULL, NULL, '2026-08-05 12:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keluarga`
--

CREATE TABLE `tbl_keluarga` (
  `id` int(11) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `kepala_keluarga_id` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `desa_id` int(11) DEFAULT NULL,
  `jumlah_anggota` int(11) DEFAULT 0,
  `status_aktif` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_keluarga`
--

INSERT INTO `tbl_keluarga` (`id`, `no_kk`, `kepala_keluarga_id`, `alamat`, `rt`, `rw`, `desa_id`, `jumlah_anggota`, `status_aktif`, `created_at`) VALUES
(21, '3509010101010001', 1, 'Jl. Ahmad Yani No. 10', '001', '001', 1, 4, 1, '2026-08-05 12:16:57'),
(22, '3509010101010002', 3, 'Jl. Merdeka No. 5', '002', '001', 1, 3, 1, '2026-08-05 12:16:57'),
(23, '3509010101010003', 5, 'Jl. Pahlawan No. 8', '003', '002', 1, 5, 1, '2026-08-05 12:16:57'),
(24, '3509020202020001', 6, 'Jl. Sudirman No. 12', '001', '001', 3, 4, 1, '2026-08-05 12:16:57'),
(25, '3509020202020002', 9, 'Jl. Gatot Subroto No. 3', '002', '001', 3, 3, 1, '2026-08-05 12:16:57'),
(26, '3509040404040001', 11, 'Jl. Mawar No. 4', '001', '001', 5, 4, 1, '2026-08-05 12:16:57'),
(27, '3509040404040002', 14, 'Jl. Kenanga No. 1', '001', '002', 5, 5, 1, '2026-08-05 12:16:57'),
(28, '3509101010100001', 16, 'Jl. Sultan Agung No. 20', '001', '001', 7, 4, 1, '2026-08-05 12:16:57'),
(29, '3509101010100002', 19, 'Jl. Hayam Wuruk No. 15', '003', '002', 7, 3, 1, '2026-08-05 12:16:57'),
(30, '3509161616160001', 21, 'Jl. Pemuda No. 11', '001', '001', 9, 4, 1, '2026-08-05 12:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keluarga_anggota`
--

CREATE TABLE `tbl_keluarga_anggota` (
  `id` int(11) NOT NULL,
  `keluarga_id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `hubungan` enum('Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Mertua','Lainnya') DEFAULT 'Lainnya',
  `status_dalam_kk` enum('Ada','Meninggal','Pindah','Hilang') DEFAULT 'Ada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_keluarga_anggota`
--

INSERT INTO `tbl_keluarga_anggota` (`id`, `keluarga_id`, `warga_id`, `hubungan`, `status_dalam_kk`) VALUES
(37, 21, 1, 'Kepala Keluarga', 'Ada'),
(38, 21, 2, 'Istri', 'Ada'),
(39, 22, 3, 'Kepala Keluarga', 'Ada'),
(40, 22, 4, 'Istri', 'Ada'),
(41, 23, 5, 'Kepala Keluarga', 'Ada'),
(42, 24, 6, 'Kepala Keluarga', 'Ada'),
(43, 24, 7, 'Istri', 'Ada'),
(44, 25, 8, 'Kepala Keluarga', 'Ada'),
(45, 25, 9, 'Istri', 'Ada'),
(46, 26, 11, 'Kepala Keluarga', 'Ada'),
(47, 26, 12, 'Istri', 'Ada'),
(48, 27, 15, 'Kepala Keluarga', 'Ada'),
(49, 27, 14, 'Istri', 'Ada'),
(50, 28, 16, 'Kepala Keluarga', 'Ada'),
(51, 28, 17, 'Istri', 'Ada'),
(52, 29, 20, 'Kepala Keluarga', 'Ada'),
(53, 30, 21, 'Kepala Keluarga', 'Ada'),
(54, 30, 22, 'Istri', 'Ada');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kriteria_kemiskinan`
--

CREATE TABLE `tbl_kriteria_kemiskinan` (
  `id` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `tipe` enum('Benefit','Cost') DEFAULT 'Benefit',
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kriteria_kemiskinan`
--

INSERT INTO `tbl_kriteria_kemiskinan` (`id`, `nama_kriteria`, `bobot`, `tipe`, `deskripsi`, `created_at`) VALUES
(1, 'Penghasilan', 0.30, 'Cost', 'Penghasilan per bulan (semakin rendah = semakin miskin)', '2026-08-05 04:54:50'),
(2, 'Jumlah Tanggungan', 0.20, 'Cost', 'Jumlah tanggungan keluarga', '2026-08-05 04:54:50'),
(3, 'Status Pekerjaan', 0.20, 'Cost', 'Stabilitas pekerjaan (semakin tidak stabil = semakin miskin)', '2026-08-05 04:54:50'),
(4, 'Kondisi Rumah', 0.15, 'Cost', 'Kualitas kondisi rumah', '2026-08-05 04:54:50'),
(5, 'Akses Pendidikan', 0.10, 'Benefit', 'Tingkat akses pendidikan', '2026-08-05 04:54:50'),
(6, 'Akses Kesehatan', 0.05, 'Benefit', 'Tingkat akses layanan kesehatan', '2026-08-05 04:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai_kriteria`
--

CREATE TABLE `tbl_nilai_kriteria` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `nilai` decimal(10,4) NOT NULL,
  `tahun` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penerima_bantuan`
--

CREATE TABLE `tbl_penerima_bantuan` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `pengajuan_id` int(11) DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `nominal` decimal(15,2) DEFAULT 0.00,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('Diterima','Belum','Batal') DEFAULT 'Belum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_penerima_bantuan`
--

INSERT INTO `tbl_penerima_bantuan` (`id`, `program_id`, `warga_id`, `pengajuan_id`, `tanggal_terima`, `nominal`, `bukti_transfer`, `status`, `created_at`) VALUES
(1, 1, 1, 1, '2026-02-01', 500000.00, 'bukti_budi_001.pdf', 'Diterima', '2026-08-05 12:15:45'),
(2, 1, 3, 2, '2026-02-01', 500000.00, 'bukti_andi_001.pdf', 'Diterima', '2026-08-05 12:15:45'),
(3, 1, 6, 3, '2026-02-01', 500000.00, 'bukti_joko_001.pdf', 'Diterima', '2026-08-05 12:15:45'),
(4, 2, 21, 6, '2026-02-15', 150000.00, 'bukti_tri_001.pdf', 'Diterima', '2026-08-05 12:15:45'),
(5, 6, 16, 9, '2026-03-20', 2000000.00, 'bukti_eko_001.pdf', 'Diterima', '2026-08-05 12:15:45'),
(6, 1, 11, 5, '2026-03-01', 500000.00, NULL, 'Belum', '2026-08-05 12:15:45'),
(7, 2, 20, 10, '2026-03-15', 150000.00, NULL, 'Batal', '2026-08-05 12:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengajuan_bantuan`
--

CREATE TABLE `tbl_pengajuan_bantuan` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `berkas` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Diajukan','Verifikasi','Disetujui','Ditolak','Selesai') DEFAULT 'Diajukan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengajuan_bantuan`
--

INSERT INTO `tbl_pengajuan_bantuan` (`id`, `warga_id`, `program_id`, `tanggal_pengajuan`, `berkas`, `keterangan`, `status`, `created_at`) VALUES
(1, 1, 1, '2026-01-15', 'berkas_budi.pdf', 'Pengajuan BLT untuk keluarga Budi Santoso', 'Disetujui', '2026-08-05 12:15:36'),
(2, 3, 1, '2026-01-16', 'berkas_andi.pdf', 'Pengajuan BLT untuk keluarga Andi Prasetyo', 'Disetujui', '2026-08-05 12:15:36'),
(3, 6, 1, '2026-01-17', 'berkas_joko.pdf', 'Pengajuan BLT untuk keluarga Joko Widodo', 'Disetujui', '2026-08-05 12:15:36'),
(4, 8, 3, '2026-02-01', 'berkas_ahmad.pdf', 'Bantuan pendidikan untuk Ahmad Fauzi', 'Verifikasi', '2026-08-05 12:15:36'),
(5, 11, 1, '2026-02-05', 'berkas_sugeng.pdf', 'Pengajuan BLT untuk keluarga Sugeng Riyadi', 'Diajukan', '2026-08-05 12:15:36'),
(6, 21, 2, '2026-02-10', 'berkas_tri.pdf', 'Pengajuan beras untuk keluarga Tri Handoko', 'Disetujui', '2026-08-05 12:15:36'),
(7, 1, 4, '2026-03-01', 'berkas_budi_lansia.pdf', 'Bantuan kesehatan untuk orang tua Budi', 'Diajukan', '2026-08-05 12:15:36'),
(8, 5, 5, '2026-03-05', 'berkas_rudi_rumah.png', 'Bedah rumah untuk Rudi Hartono', 'Verifikasi', '2026-08-05 12:15:36'),
(9, 16, 6, '2026-03-10', 'berkas_eko_usaha.pdf', 'Bantuan modal usaha untuk Eko', 'Disetujui', '2026-08-05 12:15:36'),
(10, 20, 2, '2026-03-12', 'berkas_bambang.pdf', 'Bantuan beras untuk Bambang', 'Ditolak', '2026-08-05 12:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengaturan`
--

CREATE TABLE `tbl_pengaturan` (
  `id` int(11) NOT NULL,
  `nama_pengaturan` varchar(100) NOT NULL,
  `nilai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(50) DEFAULT 'umum',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengaturan`
--

INSERT INTO `tbl_pengaturan` (`id`, `nama_pengaturan`, `nilai`, `deskripsi`, `kategori`, `updated_at`) VALUES
(1, 'nama_instansi', 'Pemerintah Kabupaten Jember', 'Nama instansi', 'umum', '2026-08-05 04:54:50'),
(2, 'alamat', 'Jl. Sultan Agung No. 2, Jember', 'Alamat instansi', 'umum', '2026-08-05 04:54:50'),
(3, 'telepon', '(0331) 331234', 'Nomor telepon', 'umum', '2026-08-05 04:54:50'),
(4, 'email', 'info@jember.go.id', 'Email instansi', 'umum', '2026-08-05 04:54:50'),
(5, 'tahun_aktif', '2026', 'Tahun aktif sistem', 'sistem', '2026-08-05 04:54:50'),
(6, 'metode_spk', 'TOPSIS', 'Metode SPK yang digunakan', 'SPK', '2026-08-05 12:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_program_bantuan`
--

CREATE TABLE `tbl_program_bantuan` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `kategori` enum('BLT','Beras','Pendidikan','Kesehatan','Perumahan','Usaha','Lainnya') NOT NULL,
  `anggaran` decimal(15,2) DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `status` enum('Aktif','Non-aktif','Selesai') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_program_bantuan`
--

INSERT INTO `tbl_program_bantuan` (`id`, `nama_program`, `kategori`, `anggaran`, `deskripsi`, `tahun`, `status`, `created_at`) VALUES
(1, 'BLT DD Desa Ajung', 'BLT', 500000000.00, 'Bantuan Langsung Tunai dari Dana Desa untuk warga miskin', 2026, 'Aktif', '2026-08-05 12:15:26'),
(2, 'Beras Sejahtera', 'Beras', 300000000.00, 'Program beras untuk keluarga kurang mampu', 2026, 'Aktif', '2026-08-05 12:15:26'),
(3, 'Bantuan Pendidikan Anak', 'Pendidikan', 200000000.00, 'Bantuan biaya pendidikan untuk anak dari keluarga tidak mampu', 2026, 'Aktif', '2026-08-05 12:15:26'),
(4, 'Bantuan Kesehatan Lansia', 'Kesehatan', 150000000.00, 'Bantuan biaya kesehatan untuk lansia', 2026, 'Aktif', '2026-08-05 12:15:26'),
(5, 'Bedah Rumah', 'Perumahan', 400000000.00, 'Program perbaikan rumah tidak layak huni', 2026, 'Aktif', '2026-08-05 12:15:26'),
(6, 'Bantuan Modal Usaha', 'Usaha', 250000000.00, 'Bantuan modal usaha untuk UMKM', 2026, 'Aktif', '2026-08-05 12:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_puskesmas`
--

CREATE TABLE `tbl_puskesmas` (
  `id` int(11) NOT NULL,
  `nama_puskesmas` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `desa_id` int(11) DEFAULT NULL,
  `jadwal_praktek` text DEFAULT NULL,
  `status` enum('Aktif','Non-aktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_puskesmas`
--

INSERT INTO `tbl_puskesmas` (`id`, `nama_puskesmas`, `alamat`, `telepon`, `desa_id`, `jadwal_praktek`, `status`, `created_at`) VALUES
(1, 'Puskesmas Ajung', 'Jl. Raya Ajung No. 10', '(0331) 491234', 1, 'Senin-Sabtu 08:00-14:00', 'Aktif', '2026-08-05 12:38:31'),
(2, 'Puskesmas Ambulu', 'Jl. Sudirman No. 15, Ambulu', '(0331) 492345', 3, 'Senin-Sabtu 08:00-14:00', 'Aktif', '2026-08-05 12:38:31'),
(3, 'Puskesmas Bangsalsari', 'Jl. Raya Bangsalsari No. 5', '(0331) 493456', 5, 'Senin-Sabtu 08:00-14:00', 'Aktif', '2026-08-05 12:38:31'),
(4, 'Puskesmas Kaliwates', 'Jl. Sultan Agung No. 40', '(0331) 494567', 7, 'Senin-Sabtu 07:30-13:30', 'Aktif', '2026-08-05 12:38:31'),
(5, 'Puskesmas Patrang', 'Jl. Pemuda No. 20, Patrang', '(0331) 495678', 9, 'Senin-Sabtu 08:00-14:00', 'Non-aktif', '2026-08-05 12:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rekam_medis`
--

CREATE TABLE `tbl_rekam_medis` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `nomor_rm` varchar(20) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `diagnosa` text DEFAULT NULL,
  `gejala` text DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `obat_diberikan` text DEFAULT NULL,
  `nama_dokter` varchar(100) DEFAULT NULL,
  `rumah_sakit_id` int(11) DEFAULT NULL,
  `status` enum('Rawat Jalan','Rawat Inap','Selesai','Meninggal') DEFAULT 'Rawat Jalan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rekam_medis`
--

INSERT INTO `tbl_rekam_medis` (`id`, `warga_id`, `nomor_rm`, `tanggal`, `diagnosa`, `gejala`, `tindakan`, `obat_diberikan`, `nama_dokter`, `rumah_sakit_id`, `status`, `created_at`) VALUES
(4, 8, 'RM-2026-004', '2026-02-10', 'Diabetes Melitus Type 2', 'Sering haus, sering kencing, luka lambat sembuh', 'Rawat Jalan', 'Metformin 500mg', 'dr. Dewi Lestari, Sp.PD', 1, 'Selesai', '2026-08-05 12:38:58'),
(5, 11, 'RM-2026-005', '2026-02-15', 'Maag Akut', 'Nyeri perut bagian atas, mual, muntah', 'Rawat Jalan', 'Omeprazole 20mg,Sucralfate', 'dr. Rudi Hartono, Sp.PD', 2, 'Selesai', '2026-08-05 12:38:58'),
(6, 16, 'RM-2026-006', '2026-03-01', 'Asma Bronkial', 'Sesak napas, mengi, batuk malam hari', 'Rawat Jalan', 'Salbutamol inhaler,Budesonide', 'dr. Eko Prasetyo, Sp.P', 1, 'Selesai', '2026-08-05 12:38:58'),
(7, 21, 'RM-2026-007', '2026-03-10', 'DBD', 'Demam tinggi 4 hari, trombosit rendah', 'Rawat Inap', 'Platelet,Paracetamol', 'dr. Ahmad Fauzi, Sp.PD', 1, 'Selesai', '2026-08-05 12:38:58'),
(8, 5, 'RM-2026-008', '2026-03-15', 'Kolesterol Tinggi', 'Sakit kepala, lelah, kesemutan', 'Rawat Jalan', 'Atorvastatin 20mg', 'dr. Maya Sari, Sp.PD', 3, 'Selesai', '2026-08-05 12:38:58'),
(9, 15, 'RM-2026-009', '2026-03-20', 'Gastritis', 'Nyeri perut, begah, mual', 'Rawat Jalan', 'Pantoprazole 40mg', 'dr. Siti Rahayu, Sp.P', 2, 'Selesai', '2026-08-05 12:38:58'),
(10, 20, 'RM-2026-010', '2026-03-25', 'Sakit Punggung', 'Nyeri punggung bawah, kaku', 'Rawat Jalan', 'Ibuprofen 400mg,Muscle relaxant', 'dr. Heri Susanto, Sp.OT', 1, 'Selesai', '2026-08-05 12:38:58'),
(11, 1, 'RM-2026-001', '2026-01-10', 'Demam Berdarah', 'Demam tinggi, nyeri otot, mual', 'Rawat Inap + Infus', 'Paracetamol 500mg, ORS', 'dr. Ahmad Fauzi, Sp.PD', 1, 'Selesai', '2026-08-05 12:40:01'),
(12, 3, 'RM-2026-002', '2026-01-15', 'ISPA', 'Batuk pilek, sesak napas ringan', 'Rawat Jalan', 'Amoxicillin 500mg, Vitamin C', 'dr. Siti Rahayu, Sp.P', 1, 'Selesai', '2026-08-05 12:40:01'),
(13, 6, 'RM-2026-003', '2026-02-01', 'Hipertensi', 'Sakit kepala, pusing, tekanan darah tinggi', 'Rawat Jalan', 'Amlodipine 5mg, Losartan 50mg', 'dr. Budi Santoso, Sp.JP', 2, 'Selesai', '2026-08-05 12:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `level_akses` int(11) DEFAULT 3,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`id`, `nama_role`, `deskripsi`, `level_akses`, `created_at`) VALUES
(1, 'Admin', 'Administrator sistem - Akses Penuh', 1, '2026-08-05 04:54:50'),
(2, 'Operator', 'Operator data - Akses Edit', 2, '2026-08-05 04:54:50'),
(3, 'Viewer', 'Pengamat - Akses Lihat Saja', 3, '2026-08-05 04:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rumah_sakit`
--

CREATE TABLE `tbl_rumah_sakit` (
  `id` int(11) NOT NULL,
  `nama_rs` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tipe` enum('RSUD','RS Swasta','RS Khusus') DEFAULT 'RSUD',
  `telepon` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT 0,
  `status` enum('Aktif','Non-aktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rumah_sakit`
--

INSERT INTO `tbl_rumah_sakit` (`id`, `nama_rs`, `alamat`, `tipe`, `telepon`, `latitude`, `longitude`, `kapasitas`, `status`, `created_at`) VALUES
(1, 'RSUD dr. H. Soedirman', 'Jl. dr. Soebandar No. 2, Kebonsari', 'RSUD', '(0331) 488128', -8.168000, 113.665000, 300, 'Non-aktif', '2026-08-05 12:38:31'),
(2, 'RS.BP. Ketenagakerjaan', 'Jl. Diponegoro No. 18, Kaliwates', 'RS Swasta', '(0331) 488333', -8.165000, 113.670000, 150, 'Aktif', '2026-08-05 12:38:31'),
(3, 'RSIA Permata Bunda', 'Jl. Sultan Agung No. 30, Kaliwates', 'RS Swasta', '(0331) 488789', -8.167000, 113.672000, 80, 'Aktif', '2026-08-05 12:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siak_data`
--

CREATE TABLE `tbl_siak_data` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_json`)),
  `terakhir_sinkron` datetime DEFAULT NULL,
  `status` enum('Valid','Invalid','Pending') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_siak_data`
--

INSERT INTO `tbl_siak_data` (`id`, `nik`, `nama`, `data_json`, `terakhir_sinkron`, `status`, `created_at`) VALUES
(1, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 17:59:15', 'Valid', '2026-08-05 10:59:15'),
(2, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 17:59:15', 'Valid', '2026-08-05 10:59:15'),
(3, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 17:59:15', 'Valid', '2026-08-05 10:59:15'),
(4, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:26:56', 'Valid', '2026-08-05 11:26:56'),
(5, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:26:56', 'Valid', '2026-08-05 11:26:56'),
(6, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:26:56', 'Valid', '2026-08-05 11:26:56'),
(7, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:26:59', 'Valid', '2026-08-05 11:26:59'),
(8, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:26:59', 'Valid', '2026-08-05 11:26:59'),
(9, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:26:59', 'Valid', '2026-08-05 11:26:59'),
(10, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(11, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(12, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(13, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(14, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(15, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:00', 'Valid', '2026-08-05 11:27:00'),
(16, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:13', 'Valid', '2026-08-05 11:27:13'),
(17, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:14', 'Valid', '2026-08-05 11:27:14'),
(18, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:14', 'Valid', '2026-08-05 11:27:14'),
(19, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(20, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(21, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(22, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(23, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(24, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:15', 'Valid', '2026-08-05 11:27:15'),
(25, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(26, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(27, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(28, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(29, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(30, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(31, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(32, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(33, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:16', 'Valid', '2026-08-05 11:27:16'),
(34, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(35, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(36, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(37, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(38, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(39, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(40, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(41, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(42, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:27:17', 'Valid', '2026-08-05 11:27:17'),
(43, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:57:04', 'Valid', '2026-08-05 11:57:04'),
(44, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 18:57:04', 'Valid', '2026-08-05 11:57:04'),
(45, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 18:57:04', 'Valid', '2026-08-05 11:57:04'),
(46, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:21:17', 'Valid', '2026-08-05 12:21:17'),
(47, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 19:21:17', 'Valid', '2026-08-05 12:21:17'),
(48, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:21:17', 'Valid', '2026-08-05 12:21:17'),
(49, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:21:18', 'Valid', '2026-08-05 12:21:18'),
(50, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 19:21:18', 'Valid', '2026-08-05 12:21:18'),
(51, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:21:18', 'Valid', '2026-08-05 12:21:18'),
(52, '3509010101010001', 'Budi Santoso', '{\"alamat\":\"Jl. Merdeka No. 1\",\"ttl\":\"1985-01-01\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:30:34', 'Valid', '2026-08-05 12:30:34'),
(53, '3509010101010002', 'Siti Rahayu', '{\"alamat\":\"Jl. Sudirman No. 5\",\"ttl\":\"1990-05-15\",\"jk\":\"Perempuan\"}', '2026-08-05 19:30:34', 'Valid', '2026-08-05 12:30:34'),
(54, '3509010101010003', 'Ahmad Hidayat', '{\"alamat\":\"Jl. Pahlawan No. 10\",\"ttl\":\"1988-03-20\",\"jk\":\"Laki-laki\"}', '2026-08-05 19:30:34', 'Valid', '2026-08-05 12:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stok_obat`
--

CREATE TABLE `tbl_stok_obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(20) DEFAULT 'pcs',
  `harga` decimal(15,2) DEFAULT 0.00,
  `tanggal_kadaluarsa` date DEFAULT NULL,
  `rumah_sakit_id` int(11) DEFAULT NULL,
  `status` enum('Tersedia','Habis','Kadaluarsa') DEFAULT 'Tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stok_obat`
--

INSERT INTO `tbl_stok_obat` (`id`, `nama_obat`, `kategori`, `stok`, `satuan`, `harga`, `tanggal_kadaluarsa`, `rumah_sakit_id`, `status`, `created_at`) VALUES
(2, 'Amoxicillin 500mg', 'Generik', 300, 'kaplet', 1200.00, '2027-06-30', 1, 'Tersedia', '2026-08-05 12:39:18'),
(3, 'Amlodipine 5mg', 'Generik', 200, 'tablet', 800.00, '2027-09-30', 1, 'Tersedia', '2026-08-05 12:39:18'),
(4, 'Metformin 500mg', 'Generik', 250, 'tablet', 600.00, '2027-08-31', 1, 'Tersedia', '2026-08-05 12:39:18'),
(5, 'Omeprazole 20mg', 'Generik', 180, 'kaplet', 900.00, '2027-07-31', 2, 'Tersedia', '2026-08-05 12:39:18'),
(7, 'Atorvastatin 20mg', 'Generik', 150, 'tablet', 1500.00, '2027-10-31', 3, 'Tersedia', '2026-08-05 12:39:18'),
(8, 'Losartan 50mg', 'Generik', 200, 'tablet', 1000.00, '2027-11-30', 2, 'Tersedia', '2026-08-05 12:39:18'),
(11, 'Ibuprofen 400mg', 'Generik', 350, 'tablet', 700.00, '2027-08-31', 1, 'Tersedia', '2026-08-05 12:39:18'),
(12, 'Pantoprazole 40mg', 'Generik', 120, 'tablet', 2000.00, '2027-06-30', 2, 'Tersedia', '2026-08-05 12:39:18'),
(13, 'Budesonide Inhaler', 'Generik', 40, 'botol', 85000.00, '2027-03-31', 1, 'Tersedia', '2026-08-05 12:39:18'),
(14, 'Platelet Concentrate', 'Blood Product', 20, 'kantong', 1500000.00, '2026-12-31', 1, 'Tersedia', '2026-08-05 12:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sync_log`
--

CREATE TABLE `tbl_sync_log` (
  `id` int(11) NOT NULL,
  `sumber` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `jumlah_data` int(11) DEFAULT 0,
  `status` enum('Berhasil','Gagal','Sebagian') DEFAULT 'Berhasil',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sync_log`
--

INSERT INTO `tbl_sync_log` (`id`, `sumber`, `tanggal`, `jumlah_data`, `status`, `keterangan`, `created_at`) VALUES
(1, 'SIAK', '2026-08-05 17:59:15', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 10:59:15'),
(2, 'SIAK', '2026-08-05 18:26:56', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:26:56'),
(3, 'SIAK', '2026-08-05 18:26:59', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:26:59'),
(4, 'SIAK', '2026-08-05 18:27:00', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:00'),
(5, 'SIAK', '2026-08-05 18:27:00', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:00'),
(6, 'BPS', '2026-08-05 18:27:01', 2, 'Berhasil', 'Sinkronisasi dari BPS', '2026-08-05 11:27:01'),
(7, 'BPS', '2026-08-05 18:27:09', 2, 'Berhasil', 'Sinkronisasi dari BPS', '2026-08-05 11:27:09'),
(8, 'SIAK', '2026-08-05 18:27:14', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:14'),
(9, 'SIAK', '2026-08-05 18:27:15', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:15'),
(10, 'SIAK', '2026-08-05 18:27:15', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:15'),
(11, 'SIAK', '2026-08-05 18:27:16', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:16'),
(12, 'SIAK', '2026-08-05 18:27:16', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:16'),
(13, 'SIAK', '2026-08-05 18:27:16', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:16'),
(14, 'SIAK', '2026-08-05 18:27:17', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:17'),
(15, 'SIAK', '2026-08-05 18:27:17', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:17'),
(16, 'SIAK', '2026-08-05 18:27:17', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:27:17'),
(17, 'SIAK', '2026-08-05 18:57:04', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 11:57:04'),
(18, 'SIK', '2026-01-15 08:30:00', 150, 'Berhasil', 'Sinkronisasi data SIK ke SIAK', '2026-08-05 12:16:11'),
(19, 'BPS', '2026-01-20 09:00:00', 50, 'Berhasil', 'Sinkronisasi data BPS Desa', '2026-08-05 12:16:11'),
(20, 'SIK', '2026-02-15 08:45:00', 200, 'Berhasil', 'Sinkronisasi data SIK bulanan', '2026-08-05 12:16:11'),
(21, 'BPS', '2026-02-20 10:15:00', 75, 'Sebagian', 'Sinkronisasi BPS - sebagian data gagal', '2026-08-05 12:16:11'),
(22, 'SIK', '2026-03-15 08:30:00', 180, 'Berhasil', 'Sinkronisasi data SIK Maret', '2026-08-05 12:16:11'),
(23, 'DESA', '2026-03-20 14:00:00', 30, 'Berhasil', 'Sinkronisasi data desa terbaru', '2026-08-05 12:16:11'),
(24, 'SIAK', '2026-08-05 19:21:17', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 12:21:17'),
(25, 'SIAK', '2026-08-05 19:21:18', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 12:21:18'),
(26, 'BPS', '2026-08-05 19:21:23', 2, 'Berhasil', 'Sinkronisasi dari BPS', '2026-08-05 12:21:23'),
(27, 'SIAK', '2026-08-05 19:30:34', 3, 'Berhasil', 'Sinkronisasi dari SIAK', '2026-08-05 12:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role_id` int(11) DEFAULT 3,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `status` tinyint(4) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password_hash`, `nama_lengkap`, `email`, `no_hp`, `role_id`, `foto`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$F/sYSj0r2ohXx8U1GPcXE.gW44mZGo5SSNlPtubnhhwUAZ1KerQb.', 'Administrator', 'admin@jember.go.id', NULL, 1, '6a7320b420012.png', 1, '2026-08-05 20:53:18', '2026-08-05 04:54:50', '2026-08-05 13:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vaksinasi`
--

CREATE TABLE `tbl_vaksinasi` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `jenis_vaksin` varchar(50) NOT NULL,
  `tanggal_vaksinasi` date NOT NULL,
  `dosis` int(11) DEFAULT 1,
  `petugas` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `reaksi` varchar(255) DEFAULT NULL,
  `status` enum('Terjadwal','Selesai','Batal','Tunda') DEFAULT 'Terjadwal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vaksinasi`
--

INSERT INTO `tbl_vaksinasi` (`id`, `warga_id`, `jenis_vaksin`, `tanggal_vaksinasi`, `dosis`, `petugas`, `lokasi`, `reaksi`, `status`, `created_at`) VALUES
(1, 1, 'COVID-19 (Sinovac)', '2026-01-05', 1, 'Nurse Sari', 'Puskesmas Ajung', 'Nyeri di tempat suntikan', 'Selesai', '2026-08-05 12:38:58'),
(2, 1, 'COVID-19 (Sinovac)', '2026-01-26', 2, 'Nurse Sari', 'Puskesmas Ajung', 'Demam ringan 1 hari', 'Selesai', '2026-08-05 12:38:58'),
(3, 3, 'COVID-19 (Sinovac)', '2026-01-08', 1, 'Nurse Rina', 'Puskesmas Ambulu', 'Tidak ada', 'Selesai', '2026-08-05 12:38:58'),
(4, 3, 'COVID-19 (Sinovac)', '2026-01-29', 2, 'Nurse Rina', 'Puskesmas Ambulu', 'Nyeri ringan', 'Selesai', '2026-08-05 12:38:58'),
(5, 6, 'COVID-19 (AstraZeneca)', '2026-02-01', 1, 'Nurse Dewi', 'Puskesmas Bangsalsari', 'Demam 2 hari', 'Selesai', '2026-08-05 12:38:58'),
(6, 8, 'COVID-19 (Moderna)', '2026-02-05', 1, 'Nurse Eka', 'Puskesmas Kaliwates', 'Nyeri otot ringan', 'Selesai', '2026-08-05 12:38:58'),
(7, 11, 'COVID-19 (Sinovac)', '2026-02-10', 1, 'Nurse Sari', 'Puskesmas Ajung', 'Tidak ada', 'Selesai', '2026-08-05 12:38:58'),
(8, 16, 'Influenza', '2026-03-01', 1, 'Nurse Rina', 'Puskesmas Ambulu', 'Tidak ada', 'Selesai', '2026-08-05 12:38:58'),
(9, 21, 'COVID-19 (Sinovac)', '2026-03-05', 1, 'Nurse Dewi', 'Puskesmas Bangsalsari', 'Demam ringan', 'Selesai', '2026-08-05 12:38:58'),
(10, 25, 'Tetanus', '2026-03-10', 1, 'Nurse Eka', 'Puskesmas Kaliwates', 'Tidak ada', 'Terjadwal', '2026-08-05 12:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_warga`
--

CREATE TABLE `tbl_warga` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `golongan_darah` enum('A','B','AB','O','-') DEFAULT '-',
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') DEFAULT 'Islam',
  `status_kawin` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT 'Belum Kawin',
  `pekerjaan` varchar(100) DEFAULT NULL,
  `penghasilan` decimal(15,2) DEFAULT 0.00,
  `alamat_lengkap` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `desa_id` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `status_aktif` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_warga`
--

INSERT INTO `tbl_warga` (`id`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `golongan_darah`, `agama`, `status_kawin`, `pekerjaan`, `penghasilan`, `alamat_lengkap`, `rt`, `rw`, `desa_id`, `foto`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '3509010101010001', 'Budi Santoso', 'Jember', '1985-03-15', 'Laki-laki', 'O', 'Islam', 'Kawin', 'Petani', 2500000.00, 'Jl. Ahmad Yani No. 10', '001', '001', 1, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(2, '3509010101010002', 'Siti Rahayu', 'Jember', '1987-07-20', 'Perempuan', 'A', 'Islam', 'Kawin', 'Ibu Rumah Tangga', 0.00, 'Jl. Ahmad Yani No. 10', '001', '001', 1, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(3, '3509010101010003', 'Andi Prasetyo', 'Jember', '1990-11-05', 'Laki-laki', 'B', 'Islam', 'Belum Kawin', 'Buruh Harian', 1800000.00, 'Jl. Merdeka No. 5', '002', '001', 1, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(4, '3509010101010004', 'Dewi Lestari', 'Jember', '1992-04-12', 'Perempuan', 'AB', 'Islam', 'Kawin', 'Pedagang', 3000000.00, 'Jl. Merdeka No. 5', '002', '001', 1, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(5, '3509010101010005', 'Rudi Hartono', 'Jember', '1980-08-25', 'Laki-laki', 'O', 'Islam', 'Kawin', 'Wiraswasta', 5000000.00, 'Jl. Pahlawan No. 8', '003', '002', 1, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(6, '3509020202020001', 'Joko Widodo', 'Jember', '1978-06-21', 'Laki-laki', 'A', 'Islam', 'Kawin', 'Petani', 2000000.00, 'Jl. Sudirman No. 12', '001', '001', 3, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(7, '3509020202020002', 'Putri Ayu', 'Jember', '1982-09-10', 'Perempuan', 'B', 'Islam', 'Kawin', 'Guru SD', 3500000.00, 'Jl. Sudirman No. 12', '001', '001', 3, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(8, '3509020202020003', 'Ahmad Fauzi', 'Jember', '1995-02-14', 'Laki-laki', 'O', 'Islam', 'Belum Kawin', 'Mahasiswa', 0.00, 'Jl. Gatot Subroto No. 3', '002', '001', 3, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(9, '3509020202020004', 'Rina Wati', 'Jember', '1988-12-01', 'Perempuan', 'AB', 'Islam', 'Kawin', 'Perawat', 4000000.00, 'Jl. Gatot Subroto No. 3', '002', '001', 3, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(10, '3509020202020005', 'Hendra Gunawan', 'Jember', '1975-05-30', 'Laki-laki', 'A', 'Islam', 'Kawin', 'Wiraswasta', 6000000.00, 'Jl. Diponegoro No. 7', '003', '002', 3, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(11, '3509040404040001', 'Sugeng Riyadi', 'Jember', '1983-01-18', 'Laki-laki', 'O', 'Islam', 'Kawin', 'Petani', 2200000.00, 'Jl. Mawar No. 4', '001', '001', 5, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(12, '3509040404040002', 'Lestari Sari', 'Jember', '1986-07-22', 'Perempuan', 'B', 'Islam', 'Kawin', 'Ibu Rumah Tangga', 0.00, 'Jl. Mawar No. 4', '001', '001', 5, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(13, '3509040404040003', 'Dwi Purwanto', 'Jember', '1991-10-08', 'Laki-laki', 'A', 'Islam', 'Belum Kawin', 'Buruh Pabrik', 2800000.00, 'Jl. Melati No. 9', '002', '001', 5, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(14, '3509040404040004', 'Anisa Rahmawati', 'Jember', '1993-03-25', 'Perempuan', 'AB', 'Islam', 'Kawin', 'Pedagang', 3200000.00, 'Jl. Melati No. 9', '002', '001', 5, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(15, '3509040404040005', 'Wahyu Nugroho', 'Jember', '1979-11-12', 'Laki-laki', 'O', 'Islam', 'Kawin', 'Kepala Desa', 4500000.00, 'Jl. Kenanga No. 1', '001', '002', 5, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(16, '3509101010100001', 'Eko Prasetyo', 'Jember', '1984-04-17', 'Laki-laki', 'B', 'Islam', 'Kawin', 'PNS', 5500000.00, 'Jl. Sultan Agung No. 20', '001', '001', 7, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(17, '3509101010100002', 'Maya Sari', 'Jember', '1987-08-28', 'Perempuan', 'O', 'Islam', 'Kawin', 'Dokter', 8000000.00, 'Jl. Sultan Agung No. 20', '001', '001', 7, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(18, '3509101010100003', 'Firmansyah', 'Jember', '1992-01-05', 'Laki-laki', 'A', 'Islam', 'Belum Kawin', 'Karyawan Swasta', 4000000.00, 'Jl. Ahmad Dahlan No. 6', '002', '001', 7, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(19, '3509101010100004', 'Sri Wahyuni', 'Jember', '1989-06-15', 'Perempuan', 'AB', 'Islam', 'Kawin', 'Guru SMA', 4500000.00, 'Jl. Ahmad Dahlan No. 6', '002', '001', 7, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(20, '3509101010100005', 'Bambang Setiawan', 'Jember', '1976-09-20', 'Laki-laki', 'O', 'Islam', 'Kawin', 'Wiraswasta', 7500000.00, 'Jl. Hayam Wuruk No. 15', '003', '002', 7, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(21, '3509161616160001', 'Tri Handoko', 'Jember', '1981-02-28', 'Laki-laki', 'A', 'Islam', 'Kawin', 'Petani', 2300000.00, 'Jl. Pemuda No. 11', '001', '001', 9, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(22, '3509161616160002', 'Wati Susilawati', 'Jember', '1985-05-10', 'Perempuan', 'B', 'Islam', 'Kawin', 'Ibu Rumah Tangga', 0.00, 'Jl. Pemuda No. 11', '001', '001', 9, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(23, '3509161616160003', 'Agus Setiawan', 'Jember', '1993-08-15', 'Laki-laki', 'O', 'Islam', 'Belum Kawin', 'Ojek Online', 2000000.00, 'Jl. Veteran No. 3', '002', '001', 9, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(24, '3509161616160004', 'Dian Permata', 'Jember', '1990-11-22', 'Perempuan', 'AB', 'Islam', 'Kawin', 'Pedagang', 2800000.00, 'Jl. Veteran No. 3', '002', '001', 9, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57'),
(25, '3509161616160005', 'Heri Susanto', 'Jember', '1977-12-05', 'Laki-laki', 'A', 'Islam', 'Kawin', 'PNS', 5000000.00, 'Jl. Gajah Mada No. 8', '003', '002', 9, 'default.jpg', 1, '2026-08-05 12:13:57', '2026-08-05 12:13:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_about`
--
ALTER TABLE `tbl_about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_audit_log`
--
ALTER TABLE `tbl_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_bantuan_kesehatan`
--
ALTER TABLE `tbl_bantuan_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `tbl_bps_data`
--
ALTER TABLE `tbl_bps_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_desa` (`kode_desa`),
  ADD KEY `kecamatan_id` (`kecamatan_id`);

--
-- Indexes for table `tbl_desa_data`
--
ALTER TABLE `tbl_desa_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desa_id` (`desa_id`);

--
-- Indexes for table `tbl_dokumen`
--
ALTER TABLE `tbl_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `tbl_hasil_klasifikasi`
--
ALTER TABLE `tbl_hasil_klasifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `tbl_kecamatan`
--
ALTER TABLE `tbl_kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kecamatan` (`kode_kecamatan`);

--
-- Indexes for table `tbl_keluarga`
--
ALTER TABLE `tbl_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_kk` (`no_kk`),
  ADD KEY `kepala_keluarga_id` (`kepala_keluarga_id`),
  ADD KEY `desa_id` (`desa_id`);

--
-- Indexes for table `tbl_keluarga_anggota`
--
ALTER TABLE `tbl_keluarga_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keluarga_id` (`keluarga_id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `tbl_kriteria_kemiskinan`
--
ALTER TABLE `tbl_kriteria_kemiskinan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_nilai_kriteria`
--
ALTER TABLE `tbl_nilai_kriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_warga_kriteria` (`warga_id`,`kriteria_id`,`tahun`),
  ADD KEY `kriteria_id` (`kriteria_id`);

--
-- Indexes for table `tbl_penerima_bantuan`
--
ALTER TABLE `tbl_penerima_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `warga_id` (`warga_id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indexes for table `tbl_pengajuan_bantuan`
--
ALTER TABLE `tbl_pengajuan_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `tbl_pengaturan`
--
ALTER TABLE `tbl_pengaturan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_pengaturan` (`nama_pengaturan`);

--
-- Indexes for table `tbl_program_bantuan`
--
ALTER TABLE `tbl_program_bantuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_puskesmas`
--
ALTER TABLE `tbl_puskesmas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desa_id` (`desa_id`);

--
-- Indexes for table `tbl_rekam_medis`
--
ALTER TABLE `tbl_rekam_medis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_rm` (`nomor_rm`),
  ADD KEY `warga_id` (`warga_id`),
  ADD KEY `rumah_sakit_id` (`rumah_sakit_id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rumah_sakit`
--
ALTER TABLE `tbl_rumah_sakit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_siak_data`
--
ALTER TABLE `tbl_siak_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_stok_obat`
--
ALTER TABLE `tbl_stok_obat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_sakit_id` (`rumah_sakit_id`);

--
-- Indexes for table `tbl_sync_log`
--
ALTER TABLE `tbl_sync_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `tbl_vaksinasi`
--
ALTER TABLE `tbl_vaksinasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `tbl_warga`
--
ALTER TABLE `tbl_warga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `desa_id` (`desa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_about`
--
ALTER TABLE `tbl_about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_audit_log`
--
ALTER TABLE `tbl_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tbl_bantuan_kesehatan`
--
ALTER TABLE `tbl_bantuan_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_bps_data`
--
ALTER TABLE `tbl_bps_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tbl_desa_data`
--
ALTER TABLE `tbl_desa_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_dokumen`
--
ALTER TABLE `tbl_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_hasil_klasifikasi`
--
ALTER TABLE `tbl_hasil_klasifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_kecamatan`
--
ALTER TABLE `tbl_kecamatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_keluarga`
--
ALTER TABLE `tbl_keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_keluarga_anggota`
--
ALTER TABLE `tbl_keluarga_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_kriteria_kemiskinan`
--
ALTER TABLE `tbl_kriteria_kemiskinan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_nilai_kriteria`
--
ALTER TABLE `tbl_nilai_kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_penerima_bantuan`
--
ALTER TABLE `tbl_penerima_bantuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_pengajuan_bantuan`
--
ALTER TABLE `tbl_pengajuan_bantuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_pengaturan`
--
ALTER TABLE `tbl_pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_program_bantuan`
--
ALTER TABLE `tbl_program_bantuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_puskesmas`
--
ALTER TABLE `tbl_puskesmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_rekam_medis`
--
ALTER TABLE `tbl_rekam_medis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_rumah_sakit`
--
ALTER TABLE `tbl_rumah_sakit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_siak_data`
--
ALTER TABLE `tbl_siak_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_stok_obat`
--
ALTER TABLE `tbl_stok_obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_sync_log`
--
ALTER TABLE `tbl_sync_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_vaksinasi`
--
ALTER TABLE `tbl_vaksinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_warga`
--
ALTER TABLE `tbl_warga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_audit_log`
--
ALTER TABLE `tbl_audit_log`
  ADD CONSTRAINT `tbl_audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`);

--
-- Constraints for table `tbl_bantuan_kesehatan`
--
ALTER TABLE `tbl_bantuan_kesehatan`
  ADD CONSTRAINT `tbl_bantuan_kesehatan_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`);

--
-- Constraints for table `tbl_desa`
--
ALTER TABLE `tbl_desa`
  ADD CONSTRAINT `tbl_desa_ibfk_1` FOREIGN KEY (`kecamatan_id`) REFERENCES `tbl_kecamatan` (`id`);

--
-- Constraints for table `tbl_desa_data`
--
ALTER TABLE `tbl_desa_data`
  ADD CONSTRAINT `tbl_desa_data_ibfk_1` FOREIGN KEY (`desa_id`) REFERENCES `tbl_desa` (`id`);

--
-- Constraints for table `tbl_dokumen`
--
ALTER TABLE `tbl_dokumen`
  ADD CONSTRAINT `tbl_dokumen_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`);

--
-- Constraints for table `tbl_hasil_klasifikasi`
--
ALTER TABLE `tbl_hasil_klasifikasi`
  ADD CONSTRAINT `tbl_hasil_klasifikasi_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`);

--
-- Constraints for table `tbl_keluarga`
--
ALTER TABLE `tbl_keluarga`
  ADD CONSTRAINT `tbl_keluarga_ibfk_1` FOREIGN KEY (`kepala_keluarga_id`) REFERENCES `tbl_warga` (`id`),
  ADD CONSTRAINT `tbl_keluarga_ibfk_2` FOREIGN KEY (`desa_id`) REFERENCES `tbl_desa` (`id`);

--
-- Constraints for table `tbl_keluarga_anggota`
--
ALTER TABLE `tbl_keluarga_anggota`
  ADD CONSTRAINT `tbl_keluarga_anggota_ibfk_1` FOREIGN KEY (`keluarga_id`) REFERENCES `tbl_keluarga` (`id`),
  ADD CONSTRAINT `tbl_keluarga_anggota_ibfk_2` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`);

--
-- Constraints for table `tbl_nilai_kriteria`
--
ALTER TABLE `tbl_nilai_kriteria`
  ADD CONSTRAINT `tbl_nilai_kriteria_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`),
  ADD CONSTRAINT `tbl_nilai_kriteria_ibfk_2` FOREIGN KEY (`kriteria_id`) REFERENCES `tbl_kriteria_kemiskinan` (`id`);

--
-- Constraints for table `tbl_penerima_bantuan`
--
ALTER TABLE `tbl_penerima_bantuan`
  ADD CONSTRAINT `tbl_penerima_bantuan_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `tbl_program_bantuan` (`id`),
  ADD CONSTRAINT `tbl_penerima_bantuan_ibfk_2` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`),
  ADD CONSTRAINT `tbl_penerima_bantuan_ibfk_3` FOREIGN KEY (`pengajuan_id`) REFERENCES `tbl_pengajuan_bantuan` (`id`);

--
-- Constraints for table `tbl_pengajuan_bantuan`
--
ALTER TABLE `tbl_pengajuan_bantuan`
  ADD CONSTRAINT `tbl_pengajuan_bantuan_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`),
  ADD CONSTRAINT `tbl_pengajuan_bantuan_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `tbl_program_bantuan` (`id`);

--
-- Constraints for table `tbl_puskesmas`
--
ALTER TABLE `tbl_puskesmas`
  ADD CONSTRAINT `tbl_puskesmas_ibfk_1` FOREIGN KEY (`desa_id`) REFERENCES `tbl_desa` (`id`);

--
-- Constraints for table `tbl_rekam_medis`
--
ALTER TABLE `tbl_rekam_medis`
  ADD CONSTRAINT `tbl_rekam_medis_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`),
  ADD CONSTRAINT `tbl_rekam_medis_ibfk_2` FOREIGN KEY (`rumah_sakit_id`) REFERENCES `tbl_rumah_sakit` (`id`);

--
-- Constraints for table `tbl_stok_obat`
--
ALTER TABLE `tbl_stok_obat`
  ADD CONSTRAINT `tbl_stok_obat_ibfk_1` FOREIGN KEY (`rumah_sakit_id`) REFERENCES `tbl_rumah_sakit` (`id`);

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `tbl_roles` (`id`);

--
-- Constraints for table `tbl_vaksinasi`
--
ALTER TABLE `tbl_vaksinasi`
  ADD CONSTRAINT `tbl_vaksinasi_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `tbl_warga` (`id`);

--
-- Constraints for table `tbl_warga`
--
ALTER TABLE `tbl_warga`
  ADD CONSTRAINT `tbl_warga_ibfk_1` FOREIGN KEY (`desa_id`) REFERENCES `tbl_desa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
