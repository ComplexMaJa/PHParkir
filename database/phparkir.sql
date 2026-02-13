-- ============================================
-- PHParkir - Parking Management System
-- Database Schema & Seed Data
-- ============================================

CREATE DATABASE IF NOT EXISTS phparkir
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE phparkir;

-- --------------------------------------------
-- Table: tb_user
-- --------------------------------------------
CREATE TABLE tb_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','petugas','owner') NOT NULL,
    status_aktif TINYINT(1) NOT NULL DEFAULT 1,
    INDEX idx_user_username (username)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tb_kendaraan
-- --------------------------------------------
CREATE TABLE tb_kendaraan (
    id_kendaraan INT AUTO_INCREMENT PRIMARY KEY,
    plat_nomor VARCHAR(15) NOT NULL,
    jenis_kendaraan VARCHAR(20) NOT NULL,
    warna VARCHAR(20) NOT NULL,
    pemilik VARCHAR(100) NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES tb_user(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_kendaraan_plat (plat_nomor),
    INDEX idx_kendaraan_user (id_user)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tb_area_parkir
-- --------------------------------------------
CREATE TABLE tb_area_parkir (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nama_area VARCHAR(50) NOT NULL,
    kapasitas INT NOT NULL DEFAULT 0,
    terisi INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tb_tarif
-- --------------------------------------------
CREATE TABLE tb_tarif (
    id_tarif INT AUTO_INCREMENT PRIMARY KEY,
    jenis_kendaraan ENUM('motor','mobil','lainnya') NOT NULL,
    tarif_per_jam DECIMAL(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tb_transaksi
-- --------------------------------------------
CREATE TABLE tb_transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_kendaraan INT NOT NULL,
    waktu_masuk DATETIME NOT NULL,
    waktu_keluar DATETIME DEFAULT NULL,
    id_tarif INT NOT NULL,
    durasi_jam INT NOT NULL DEFAULT 0,
    biaya_total DECIMAL(10,0) NOT NULL DEFAULT 0,
    status ENUM('masuk','keluar') DEFAULT 'masuk',
    id_user INT NOT NULL,
    id_area INT NOT NULL,
    FOREIGN KEY (id_kendaraan) REFERENCES tb_kendaraan(id_kendaraan) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_tarif) REFERENCES tb_tarif(id_tarif) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_user) REFERENCES tb_user(id_user) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_area) REFERENCES tb_area_parkir(id_area) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_transaksi_status (status),
    INDEX idx_transaksi_waktu (waktu_masuk, waktu_keluar)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tb_log_aktivitas
-- --------------------------------------------
CREATE TABLE tb_log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT DEFAULT NULL,
    aktivitas VARCHAR(100) NOT NULL,
    waktu_aktivitas DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES tb_user(id_user) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_log_user (id_user),
    INDEX idx_log_waktu (waktu_aktivitas)
) ENGINE=InnoDB;

-- ============================================
-- SEED DATA
-- ============================================

-- Default Users (password: password)
INSERT INTO tb_user (nama_lengkap, username, password, role, status_aktif) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Petugas Parkir', 'petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', 1),
('Owner Parkir', 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 1);

-- Area Parkir
INSERT INTO tb_area_parkir (nama_area, kapasitas, terisi) VALUES
('Area A - Utama', 50, 0),
('Area B - Belakang', 30, 0),
('Area C - VIP', 20, 0);

-- Tarif
INSERT INTO tb_tarif (jenis_kendaraan, tarif_per_jam) VALUES
('motor', 2000),
('mobil', 5000),
('lainnya', 10000);
