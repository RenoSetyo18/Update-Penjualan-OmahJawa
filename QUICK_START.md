# 🚀 QUICK START - Master Data Implementation

Berikut adalah panduan cepat untuk menjalankan sistem master data yang baru dibuat.

## ⚡ Instalasi (5 Menit)

### 1. Jalankan Migration

```bash
php artisan migrate
```

### 2. Jalankan Seeder (Optional, tapi Disarankan)

```bash
php artisan db:seed --class=JenisSeeder
```

## 🌐 Akses URL

Buka di browser (sesuaikan domain Anda):

| Fitur           | URL                                    |
| --------------- | -------------------------------------- |
| Master Karyawan | http://localhost/omahjawa1/karyawan    |
| Master Jenis    | http://localhost/omahjawa1/jenis       |
| Master Menu     | http://localhost/omahjawa1/master-menu |

**ATAU** dengan prefix `/master`:
| Fitur | URL |
|-------|-----|
| Master Karyawan | http://localhost/omahjawa1/master/karyawan |
| Master Jenis | http://localhost/omahjawa1/master/jenis |
| Master Menu | http://localhost/omahjawa1/master/menu |

## 📋 Workflow Rekomendasi

### 1. Tambah Jenis Menu Terlebih Dahulu

- Buka `/jenis`
- Klik "Tambah Jenis"
- Default sudah ada 4 jenis dari seeder (Makanan, Minuman, Cemilan, Dessert)
- Bisa tambah jenis baru lainnya

### 2. Tambah Menu

- Buka `/master-menu`
- Klik "Tambah Menu"
- Pilih jenis dari dropdown
- Isi nama, harga, deskripsi
- Simpan

### 3. Tambah Karyawan

- Buka `/karyawan`
- Klik "Tambah Karyawan"
- Isi nama, email, password, role
- Simpan

## 🎯 Fitur Utama

### Master Karyawan

- Tambah karyawan dengan role: Admin, Kasir, Pelayan, Super Admin
- Edit nama, email, role
- Hapus karyawan

### Master Jenis Menu

- Tambah kategori menu: Makanan, Minuman, Cemilan, dll
- Edit deskripsi jenis
- Hapus jenis (default: 4 jenis sudah ada)

### Master Menu

- Tambah menu dengan kategori
- Input harga dengan format Rupiah
- Edit dan hapus menu sesuai kebutuhan

## 📄 File-File Penting

| File                                                       | Tujuan                |
| ---------------------------------------------------------- | --------------------- |
| MASTER_DATA_GUIDE.md                                       | Dokumentasi lengkap   |
| IMPLEMENTATION_SUMMARY.md                                  | Ringkasan teknis      |
| QUICK_START.md                                             | Panduan ini           |
| resources/views/pages/master/NAVIGATION_TEMPLATE.blade.php | Template menu sidebar |

## ✅ Checklist Sebelum Mulai

- [ ] Jalankan `php artisan migrate`
- [ ] Jalankan `php artisan db:seed --class=JenisSeeder`
- [ ] Akses `/jenis` - lihat 4 jenis default
- [ ] Akses `/master-menu` - tambah satu menu
- [ ] Akses `/karyawan` - tambah satu karyawan
- [ ] Test edit dan hapus

## 🆘 Troubleshooting Cepat

| Problem             | Solusi                           |
| ------------------- | -------------------------------- |
| Migration error     | Run `php artisan migrate:fresh`  |
| Route not found     | Run `php artisan route:cache`    |
| Class not found     | Run `composer dump-autoload`     |
| Database tidak sync | Check `.env` database connection |

## 📞 Dukungan Lebih Lanjut

Lihat file:

- `MASTER_DATA_GUIDE.md` - Dokumentasi detail
- `IMPLEMENTATION_SUMMARY.md` - Info teknis lengkap

---

**Status**: ✅ SIAP DIGUNAKAN
**Setup Time**: ~5 menit
**Versi**: 1.0
