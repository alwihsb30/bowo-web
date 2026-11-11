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
