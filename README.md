# 📚 SIAKAD - Sistem Informasi Akademik Sederhana

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat&logo=bootstrap)

---

## 📖 Deskripsi

**SIAKAD** (Sistem Informasi Akademik) adalah aplikasi web berbasis Laravel yang dikembangkan untuk memenuhi Tugas Besar Mata Kuliah Web II. Aplikasi ini mensimulasikan pengelolaan data akademik di perguruan tinggi secara sederhana namun terstruktur.

Sistem ini dirancang untuk mengelola:
- Data Dosen
- Data Mahasiswa
- Data Mata Kuliah
- Jadwal Perkuliahan
- Kartu Rencana Studi (KRS)

Aplikasi menerapkan **Role-Based Access Control (RBAC)** dengan 2 role utama: **Admin** dan **Mahasiswa**. Admin memiliki hak akses penuh untuk mengelola seluruh data, sedangkan Mahasiswa hanya dapat mengelola KRS dan melihat jadwal.

---

## ✨ Fitur Utama

### 🔐 Authentication & Authorization
- Login & Logout menggunakan Laravel Auth
- 2 Role pengguna: Admin dan Mahasiswa
- Middleware untuk proteksi akses berdasarkan role

### 👨‍💼 Manajemen Data (CRUD) - Admin
| Modul | Fitur |
|-------|-------|
| **Dosen** | Tambah, Edit, Hapus, Lihat, Cari, Filter, Export PDF |
| **Mahasiswa** | Tambah, Edit, Hapus, Lihat, Cari, Filter |
| **Mata Kuliah** | Tambah, Edit, Hapus, Lihat, Cari, Filter, Export PDF |
| **Jadwal** | Tambah, Edit, Hapus, Lihat, Cari, Filter, Validasi Konflik Jadwal |
| **KRS** | Tambah, Edit, Hapus, Lihat, Filter |

### 🎓 Fitur Mahasiswa
- Dashboard Akademik
- Pengisian KRS (Ambil Mata Kuliah)
- Pembatalan KRS (Drop Mata Kuliah)
- Lihat Daftar KRS yang Diambil
- Lihat Jadwal Hari Ini
- Export KRS ke PDF (Bonus)

### 📊 Dashboard
- **Admin Dashboard**: Statistik data akademik, grafik, data terbaru
- **Mahasiswa Dashboard**: Profil, statistik KRS, jadwal hari ini

### 🔍 Fitur Pencarian & Filter (Bonus)
- Pencarian data dosen, mahasiswa, mata kuliah, jadwal
- Filter berdasarkan berbagai kriteria
- Sorting data

### 📄 Export PDF (Bonus)
- Export daftar dosen ke PDF
- Export daftar mata kuliah ke PDF
- Export KRS mahasiswa ke PDF

### 👤 Manajemen Profil
- Ubah data profil
- Ubah password
- Logout

---


### Relasi Tabel:
- **Jadwal** terhubung ke **Dosen** dan **Mata Kuliah** (One to Many)
- **KRS** terhubung ke **Mahasiswa** dan **Jadwal** (Many to Many)
- **Mahasiswa** terhubung ke **User** (One to One)

---

## 🛠️ Teknologi yang Digunakan

### Backend
| Teknologi | Keterangan |
|-----------|------------|
| **Laravel 12.x** | Framework PHP untuk pengembangan aplikasi |
| **PHP 8.2+** | Bahasa pemrograman backend |
| **Eloquent ORM** | ORM Laravel untuk interaksi database |
| **Laravel Auth** | Sistem autentikasi bawaan Laravel |
| **Laravel Validation** | Validasi form input |

### Frontend
| Teknologi | Keterangan |
|-----------|------------|
| **Blade Template** | Template engine Laravel |
| **Bootstrap 5** | Framework CSS untuk UI/UX |
| **Font Awesome 6** | Library icon |
| **Chart.js** | Library untuk grafik (Bonus) |
| **JavaScript** | Interaktivitas frontend |

### Database
| Teknologi | Keterangan |
|-----------|------------|
| **MySQL 8.0** | Database management system |

### Library & Package
| Package | Kegunaan |
|---------|----------|
| **laravel/breeze** | Authentication scaffolding |
| **barryvdh/laravel-dompdf** | Export PDF (Bonus) |
| **maatwebsite/excel** | Export Excel (Bonus) |

---

## 🔐 Role & Hak Akses

### 👨‍💼 Admin
| Fitur | Akses |
|-------|-------|
| Dashboard Admin | ✅ |
| Manajemen Dosen (CRUD) | ✅ |
| Manajemen Mahasiswa (CRUD) | ✅ |
| Manajemen Mata Kuliah (CRUD) | ✅ |
| Manajemen Jadwal (CRUD) | ✅ |
| Manajemen KRS (CRUD) | ✅ |
| Export PDF | ✅ |
| Pencarian & Filter | ✅ |
| Profil | ✅ |

### 🎓 Mahasiswa
| Fitur | Akses |
|-------|-------|
| Dashboard Mahasiswa | ✅ |
| Lihat KRS Saya | ✅ |
| Ambil KRS | ✅ |
| Drop/Batal KRS | ✅ |
| Lihat Jadwal Hari Ini | ✅ |
| Export KRS PDF | ✅ |
| Profil | ✅ |

---
