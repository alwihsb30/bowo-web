-- Skema Database dan Data Awal BOWO (Bank of Waste Online)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `bowo_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `bowo_db`;

DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `deposits`;
DROP TABLE IF EXISTS `waste_types`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- Tabel users (menyimpan data akun admin dan nasabah)
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'nasabah') NOT NULL DEFAULT 'nasabah',
  `balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `points` INT NOT NULL DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel waste_types (menyimpan jenis dan harga sampah per kg)
CREATE TABLE `waste_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `price_per_kg` DECIMAL(10,2) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel deposits (menyimpan data setoran sampah nasabah)
CREATE TABLE `deposits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `waste_type_id` INT UNSIGNED NOT NULL,
  `weight` DECIMAL(8,2) NOT NULL,
  `price_per_kg` DECIMAL(10,2) NOT NULL,
  `total_value` DECIMAL(12,2) NOT NULL,
  `points` INT NOT NULL,
  `deposit_date` DATE NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `photo_proof` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('menunggu', 'diverifikasi', 'ditolak') NOT NULL DEFAULT 'menunggu',
  `verified_by` INT UNSIGNED DEFAULT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_deposits_user_id` (`user_id`),
  KEY `idx_deposits_waste_type_id` (`waste_type_id`),
  KEY `idx_deposits_verified_by` (`verified_by`),
  CONSTRAINT `fk_deposits_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_deposits_waste_type` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_deposits_verifier` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel transactions (menyimpan riwayat mutasi saldo nasabah)
CREATE TABLE `transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `deposit_id` INT UNSIGNED DEFAULT NULL,
  `type` ENUM('setoran_masuk', 'penarikan') NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_transactions_user_id` (`user_id`),
  KEY `idx_transactions_deposit_id` (`deposit_id`),
  CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_deposit` FOREIGN KEY (`deposit_id`) REFERENCES `deposits` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data awal pengguna (admin: admin123, budi: password123, siti: password123)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `password`, `role`, `balance`, `points`, `status`) VALUES
(1, 'Administrator BOWO', 'admin', 'admin@bowo.id', '081234567890', 'Kantor Pusat BOWO', '$2y$10$WIxzlb0Mt5aOBFwIM0aNRuePY9GCDPnruF8RV4bO8V.yHje98jz9q', 'admin', 0.00, 0, 'active'),
(2, 'Budi Santoso', 'budi', 'budi@gmail.com', '081298765432', 'Jl. Merdeka No. 12', '$2y$10$sZbZEr5PeAjNIhVLflNnS.AFXTmZyZm8C4lr5CzinLXMrLosQwt32', 'nasabah', 37500.00, 145, 'active'),
(3, 'Siti Rahma', 'siti', 'siti@gmail.com', '085612345678', 'Jl. Mawar No. 5', '$2y$10$sZbZEr5PeAjNIhVLflNnS.AFXTmZyZm8C4lr5CzinLXMrLosQwt32', 'nasabah', 37000.00, 160, 'active');

-- Data awal jenis dan harga sampah
INSERT INTO `waste_types` (`id`, `name`, `category`, `price_per_kg`, `description`, `status`) VALUES
(1, 'Plastik PET (Botol Bening)', 'Plastik', 5000.00, 'Botol air mineral bekas bersih tanpa label', 'active'),
(2, 'Kertas Dupleks / Dus', 'Kardus', 2500.00, 'Kardus bekas kemasan, bersih dan kering', 'active'),
(3, 'Kertas HVS / Buku', 'Kertas', 3000.00, 'Kertas putih bekas cetak atau buku tulis', 'active'),
(4, 'Kaleng / Besi Tipis', 'Logam', 8000.00, 'Kaleng minuman atau wadah makanan logam', 'active');

-- Data awal pengajuan setoran sampah (multi-bulan)
INSERT INTO `deposits` (`id`, `user_id`, `waste_type_id`, `weight`, `price_per_kg`, `total_value`, `points`, `deposit_date`, `notes`, `status`, `verified_by`, `verified_at`) VALUES
(1, 2, 2, 5.00, 2500.00, 12500.00, 50, '2026-09-18', 'Kardus bersih diikat rapi', 'diverifikasi', 1, '2026-09-18 14:00:00'),
(2, 2, 1, 3.00, 5000.00, 15000.00, 30, '2026-10-20', 'Botol air mineral dipilah', 'diverifikasi', 1, '2026-10-20 14:15:00'),
(3, 2, 4, 2.50, 8000.00, 20000.00, 25, '2026-11-03', 'Kaleng minuman bekas', 'diverifikasi', 1, '2026-11-03 10:30:00'),
(4, 2, 2, 4.00, 2500.00, 10000.00, 40, '2026-11-10', 'Dus kemasan elektronik', 'diverifikasi', 1, '2026-11-10 16:20:00'),
(5, 2, 1, 2.00, 5000.00, 10000.00, 20, '2026-11-14', 'Setoran mingguan', 'menunggu', NULL, NULL),
(6, 3, 3, 5.00, 3000.00, 15000.00, 50, '2026-09-20', 'Kertas arsip kantor bersih', 'diverifikasi', 1, '2026-09-20 11:00:00'),
(7, 3, 2, 4.00, 2500.00, 10000.00, 40, '2026-10-25', 'Kardus bekas rumah tangga', 'diverifikasi', 1, '2026-10-25 11:00:00'),
(8, 3, 3, 4.00, 3000.00, 12000.00, 40, '2026-11-04', 'Buku tulis bekas', 'diverifikasi', 1, '2026-11-04 13:45:00'),
(9, 3, 1, 3.00, 5000.00, 15000.00, 30, '2026-11-11', 'Botol plastik bening', 'diverifikasi', 1, '2026-11-11 15:10:00'),
(10, 3, 4, 1.50, 8000.00, 12000.00, 15, '2026-11-14', 'Kaleng biskuit bekas', 'menunggu', NULL, NULL);

-- Data awal riwayat mutasi saldo nasabah
INSERT INTO `transactions` (`id`, `user_id`, `deposit_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 2, 1, 'setoran_masuk', 12500.00, 'Setoran terverifikasi: Kertas Dupleks / Dus (5.00 kg)', '2026-09-18 14:00:00'),
(2, 2, 2, 'setoran_masuk', 15000.00, 'Setoran terverifikasi: Plastik PET (Botol Bening) (3.00 kg)', '2026-10-20 14:15:00'),
(3, 2, 3, 'setoran_masuk', 20000.00, 'Setoran terverifikasi: Kaleng / Besi Tipis (2.50 kg)', '2026-11-03 10:30:00'),
(4, 2, NULL, 'penarikan', 20000.00, 'Penarikan tunai tabungan sampah oleh nasabah', '2026-11-05 11:00:00'),
(5, 2, 4, 'setoran_masuk', 10000.00, 'Setoran terverifikasi: Kertas Dupleks / Dus (4.00 kg)', '2026-11-10 16:20:00'),
(6, 3, 6, 'setoran_masuk', 15000.00, 'Setoran terverifikasi: Kertas HVS / Buku (5.00 kg)', '2026-09-20 11:00:00'),
(7, 3, 7, 'setoran_masuk', 10000.00, 'Setoran terverifikasi: Kertas Dupleks / Dus (4.00 kg)', '2026-10-25 11:00:00'),
(8, 3, 8, 'setoran_masuk', 12000.00, 'Setoran terverifikasi: Kertas HVS / Buku (4.00 kg)', '2026-11-04 13:45:00'),
(9, 3, NULL, 'penarikan', 15000.00, 'Penarikan saldo tabungan via transfer e-wallet', '2026-11-08 09:15:00'),
(10, 3, 9, 'setoran_masuk', 15000.00, 'Setoran terverifikasi: Plastik PET (Botol Bening) (3.00 kg)', '2026-11-11 15:10:00');
