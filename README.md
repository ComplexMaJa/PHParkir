# 🅿️ PHParkir - Sistem Manajemen Parkir

Aplikasi web manajemen parkir berbasis PHP native dengan arsitektur MVC sederhana.  
Dibuat untuk Ujian Kompetensi Keahlian (UKK) SMK Rekayasa Perangkat Lunak.

---

## 📋 Fitur

### 3 Role Pengguna
| Role | Akses |
|------|-------|
| **Admin** | CRUD User, Kendaraan, Area Parkir, Tarif Parkir, Activity Log |
| **Petugas** | Input kendaraan masuk/keluar, hitung biaya otomatis, cetak struk |
| **Owner** | Rekap transaksi, filter tanggal, ringkasan pendapatan |

### Fitur Utama
- ✅ Login & Logout dengan session
- ✅ Role-based authorization
- ✅ CRUD User (Admin)
- ✅ CRUD Kendaraan (Admin)
- ✅ CRUD Area Parkir (Admin)
- ✅ CRUD Tarif Parkir (Admin)
- ✅ Activity Log (Admin)
- ✅ Input kendaraan masuk (Petugas)
- ✅ Proses kendaraan keluar + hitung biaya otomatis (Petugas)
- ✅ Cetak struk parkir (Petugas)
- ✅ Rekap transaksi dengan filter tanggal (Owner)
- ✅ Ringkasan pendapatan harian & total (Owner)
- ✅ Pagination
- ✅ Responsive design (mobile-friendly)
- ✅ White + purple color theme

---

## 🚀 Instalasi & Setup

### Prasyarat
- XAMPP / Laragon / PHP 7.4+ dengan MySQL
- Web browser modern

### Langkah Instalasi

1. **Clone / download** repository ini ke folder web server:
   ```bash
   # XAMPP
   cd C:\xampp\htdocs
   git clone https://github.com/ComplexMaJa/PHParkir.git

   # Laragon
   cd C:\laragon\www
   git clone https://github.com/ComplexMaJa/PHParkir.git
   ```

2. **Import database:**
   - Buka phpMyAdmin (`http://localhost/phpmyadmin`)
   - Klik tab **Import**
   - Pilih file `database/phparkir.sql`
   - Klik **Go** / **Import**

3. **Konfigurasi database** (jika perlu):
   - Edit file `config/database.php`
   - Sesuaikan `DB_HOST`, `DB_USER`, `DB_PASS` jika berbeda

4. **Akses aplikasi:**
   ```
   http://localhost/PHParkir/
   ```

### Akun Default
| Username | Password | Role |
|----------|----------|------|
| `admin` | `password` | Admin |
| `petugas` | `password` | Petugas |
| `owner` | `password` | Owner |

---

## 📁 Struktur Proyek

```
PHParkir/
├── index.php              # Entry point / Router
├── config/
│   ├── app.php            # Konfigurasi aplikasi
│   └── database.php       # Konfigurasi database
├── helpers/
│   ├── auth.php           # Fungsi autentikasi
│   └── functions.php      # Helper functions
├── models/
│   ├── UserModel.php
│   ├── KendaraanModel.php
│   ├── AreaParkirModel.php
│   ├── TarifParkirModel.php
│   ├── TransaksiModel.php
│   └── ActivityLogModel.php
├── controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── UserController.php
│   ├── KendaraanController.php
│   ├── AreaParkirController.php
│   ├── TarifParkirController.php
│   ├── TransaksiController.php
│   ├── RekapController.php
│   └── ActivityLogController.php
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   └── footer.php
│   ├── auth/
│   │   └── login.php
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── activity_logs.php
│   │   ├── users/
│   │   ├── kendaraan/
│   │   ├── area_parkir/
│   │   └── tarif_parkir/
│   ├── petugas/
│   │   ├── dashboard.php
│   │   └── transaksi/
│   └── owner/
│       ├── dashboard.php
│       └── rekap.php
├── assets/
│   └── css/
│       └── style.css
├── database/
│   └── phparkir.sql
└── README.md
```

---

## 🔄 Flow Logic / Pseudocode

### Login Flow
```
1. User membuka halaman login
2. User memasukkan username dan password
3. Sistem memvalidasi input (tidak kosong)
4. Sistem mencari user di database berdasarkan username
5. Jika user ditemukan dan status aktif:
   a. Verifikasi password dengan password_verify()
   b. Jika cocok:
      - Simpan data user di session
      - Log aktivitas login
      - Redirect ke dashboard sesuai role
   c. Jika tidak cocok:
      - Tampilkan pesan error
6. Jika user tidak ditemukan:
   - Tampilkan pesan error
```

### Transaksi Parkir (Kendaraan Masuk)
```
1. Petugas memilih menu "Kendaraan Masuk"
2. Petugas mengisi:
   - Plat nomor kendaraan
   - Jenis kendaraan (dari dropdown)
   - Area parkir (dari dropdown, tampilkan sisa kapasitas)
3. Sistem memvalidasi input
4. Sistem mengecek kapasitas area parkir
5. Jika area penuh:
   - Tampilkan pesan error
6. Jika tersedia:
   - Generate kode transaksi unik (PRK-YYYYMMDD-XXXXX)
   - Simpan transaksi dengan waktu masuk = sekarang
   - Tambah counter terisi pada area parkir
   - Log aktivitas
   - Tampilkan pesan sukses
```

### Transaksi Parkir (Kendaraan Keluar)
```
1. Petugas memilih menu "Kendaraan Keluar"
2. Petugas mencari kendaraan berdasarkan plat nomor
3. Sistem mencari transaksi aktif (status = 'masuk')
4. Jika ditemukan:
   - Tampilkan data kendaraan dan durasi parkir
   - Petugas klik "Proses Keluar"
5. Sistem menghitung biaya:
   a. Waktu keluar = sekarang
   b. Durasi = waktu_keluar - waktu_masuk (dalam jam, dibulatkan ke atas)
   c. Minimum durasi = 1 jam
   d. Ambil tarif dari tabel tarif_parkir berdasarkan jenis kendaraan
   e. Total = tarif_flat + (tarif_per_jam × durasi_jam)
6. Simpan detail transaksi
7. Update status transaksi menjadi 'keluar'
8. Kurangi counter terisi pada area parkir
9. Log aktivitas
10. Redirect ke halaman struk
```

### Cetak Struk
```
1. Sistem menampilkan data transaksi lengkap:
   - Kode transaksi, plat nomor, jenis kendaraan
   - Area parkir, nama petugas
   - Waktu masuk & keluar
   - Durasi, tarif flat, tarif per jam
   - Total biaya
2. Petugas klik tombol "Cetak"
3. Browser membuka dialog print (window.print())
4. Elemen non-struk disembunyikan saat print (CSS @media print)
```

---

## 🐛 Known Bugs

- Belum ada CSRF token protection pada form
- Belum ada rate limiting pada login
- Filter transaksi di halaman petugas belum mempertahankan state saat pagination

---

## 🔮 Future Improvements

- Tambah CSRF token untuk keamanan form
- Implementasi "remember me" pada login
- Export rekap ke PDF/Excel
- Dashboard chart/grafik pendapatan
- Notifikasi area parkir hampir penuh
- API endpoint untuk integrasi mobile
- Pencarian global transaksi
- Multi-language support
