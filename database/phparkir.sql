-- ============================================
-- PHParkir - Parking Management System
-- Database Schema & Seed Data
-- ============================================

CREATE DATABASE IF NOT EXISTS phparkir
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE phparkir;

-- --------------------------------------------
-- Table: roles
-- --------------------------------------------
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: users
-- --------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_users_role (role_id),
    INDEX idx_users_username (username)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: kendaraan
-- --------------------------------------------
CREATE TABLE kendaraan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_kendaraan VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kendaraan_jenis (jenis_kendaraan)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: area_parkir
-- --------------------------------------------
CREATE TABLE area_parkir (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_area VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL DEFAULT 0,
    terisi INT NOT NULL DEFAULT 0,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_area_status (status)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: tarif_parkir
-- --------------------------------------------
CREATE TABLE tarif_parkir (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kendaraan_id INT NOT NULL,
    tarif_per_jam DECIMAL(10,2) NOT NULL DEFAULT 0,
    tarif_flat DECIMAL(10,2) NOT NULL DEFAULT 0,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kendaraan_id) REFERENCES kendaraan(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_tarif_kendaraan (kendaraan_id)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: transaksi
-- --------------------------------------------
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(50) NOT NULL UNIQUE,
    plat_nomor VARCHAR(20) NOT NULL,
    kendaraan_id INT NOT NULL,
    area_parkir_id INT NOT NULL,
    user_id INT NOT NULL,
    waktu_masuk DATETIME NOT NULL,
    waktu_keluar DATETIME DEFAULT NULL,
    status ENUM('masuk','keluar') DEFAULT 'masuk',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kendaraan_id) REFERENCES kendaraan(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (area_parkir_id) REFERENCES area_parkir(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_transaksi_kode (kode_transaksi),
    INDEX idx_transaksi_plat (plat_nomor),
    INDEX idx_transaksi_status (status),
    INDEX idx_transaksi_waktu (waktu_masuk, waktu_keluar)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: detail_transaksi
-- --------------------------------------------
CREATE TABLE detail_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    durasi_jam DECIMAL(10,2) NOT NULL DEFAULT 0,
    tarif_per_jam DECIMAL(10,2) NOT NULL DEFAULT 0,
    tarif_flat DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_biaya DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_detail_transaksi (transaksi_id)
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table: activity_logs
-- --------------------------------------------
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    detail TEXT,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_logs_user (user_id),
    INDEX idx_logs_created (created_at)
) ENGINE=InnoDB;

-- ============================================
-- SEED DATA
-- ============================================

-- Roles
INSERT INTO roles (nama_role) VALUES
('Admin'),
('Petugas'),
('Owner');

-- Default Admin User (password: admin123)
INSERT INTO users (nama, username, password, role_id, status) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'aktif'),
('Petugas Parkir', 'petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'aktif'),
('Owner Parkir', 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'aktif');

-- Jenis Kendaraan
INSERT INTO kendaraan (jenis_kendaraan, deskripsi) VALUES
('Motor', 'Kendaraan roda dua'),
('Mobil', 'Kendaraan roda empat'),
('Truk', 'Kendaraan besar / angkutan');

-- Area Parkir
INSERT INTO area_parkir (nama_area, kapasitas, terisi, status) VALUES
('Area A - Utama', 50, 0, 'aktif'),
('Area B - Belakang', 30, 0, 'aktif'),
('Area C - VIP', 20, 0, 'aktif');

-- Tarif Parkir
INSERT INTO tarif_parkir (kendaraan_id, tarif_per_jam, tarif_flat, deskripsi) VALUES
(1, 2000.00, 1000.00, 'Tarif parkir motor'),
(2, 5000.00, 3000.00, 'Tarif parkir mobil'),
(3, 10000.00, 5000.00, 'Tarif parkir truk');
