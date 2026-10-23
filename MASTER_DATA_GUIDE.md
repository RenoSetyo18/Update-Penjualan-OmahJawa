# Master Data Management - Panduan Instalasi & Penggunaan

Sistem master data telah dibuat dengan struktur lengkap untuk mengelola karyawan, jenis menu, dan menu. Berikut adalah panduan lengkap untuk mengimplementasikan fitur ini.

## 📋 Struktur Folder yang Dibuat

```
resources/views/pages/master/
├── karyawan/
│   ├── index.blade.php      (Daftar karyawan)
│   ├── create.blade.php     (Form tambah karyawan)
│   └── edit.blade.php       (Form edit karyawan)
├── jenis/
│   ├── index.blade.php      (Daftar jenis menu)
│   ├── create.blade.php     (Form tambah jenis)
│   └── edit.blade.php       (Form edit jenis)
└── menu/
    ├── index.blade.php      (Daftar menu)
    ├── create.blade.php     (Form tambah menu)
    └── edit.blade.php       (Form edit menu)
```

## 📁 File-File yang Dibuat

### Models

- `app/Models/Jenis.php` - Model untuk tabel jenis menu
- `app/Models/Menu.php` - Updated dengan relasi ke jenis
- `app/Models/User.php` - Updated dengan role field

### Controllers

- `app/Http/Controllers/KaryawanController.php` - CRUD karyawan
- `app/Http/Controllers/JenisController.php` - CRUD jenis menu
- `app/Http/Controllers/MasterMenuController.php` - CRUD menu

### Migrations

- `database/migrations/2025_02_27_000000_create_jenis_table.php` - Buat table jenis
- `database/migrations/2025_02_27_000001_add_role_to_users_table.php` - Tambah role ke users
- `database/migrations/2025_02_27_000002_add_jenis_and_deskripsi_to_menus_table.php` - Update menu table

### Routes (diupdate di routes/web.php)

- Master Karyawan: `/karyawan`, `/master/karyawan`
- Master Jenis: `/jenis`, `/master/jenis`
- Master Menu: `/master-menu`, `/master/menu`

## 🔧 Langkah Instalasi

### 1. Jalankan Migrations

Buka terminal di folder project dan jalankan:

```bash
php artisan migrate
```

Migrations akan membuat:

- Table `jenis` dengan kolom: id, nama_jenis, deskripsi, timestamps
- Menambah kolom `role` ke table `users` (default: 'kasir')
- Menambah kolom `jenis_id` dan `deskripsi` ke table `menus`

### 2. Seeder Data (Opsional)

Untuk menambahkan data contoh, buat seeder dengan perintah:

```bash
php artisan make:seeder JenisSeeder
```

Edit file `database/seeders/JenisSeeder.php`:

```php
<?php
namespace Database\Seeders;
use App\Models\Jenis;
use Illuminate\Database\Seeder;

class JenisSeeder extends Seeder
{
    public function run(): void
    {
        Jenis::create(['nama_jenis' => 'Makanan', 'deskripsi' => 'Makanan utama']);
        Jenis::create(['nama_jenis' => 'Minuman', 'deskripsi' => 'Minuman panas dan dingin']);
        Jenis::create(['nama_jenis' => 'Cemilan', 'deskripsi' => 'Camilan ringan']);
    }
}
```

Jalankan seeder:

```bash
php artisan db:seed --class=JenisSeeder
```

## 📝 Fitur-Fitur

### Master Karyawan

- **URL**: `/karyawan` atau `/master/karyawan`
- **Fitur**:
    - ✅ Tambah karyawan baru (input nama, email, password, role/hak akses)
    - ✅ Edit data karyawan
    - ✅ Hapus karyawan
    - ✅ Role/Hak Akses: Admin, Kasir, Pelayan, Super Admin

**Form Fields**:

- Nama Karyawan
- Email
- Password (dienkripsi otomatis)
- Role/Hak Akses (Admin, Kasir, Pelayan, Super Admin)

### Master Jenis Menu

- **URL**: `/jenis` atau `/master/jenis`
- **Fitur**:
    - ✅ Tambah jenis menu baru (Makanan, Minuman, Cemilan, dll)
    - ✅ Edit jenis menu
    - ✅ Hapus jenis menu
    - ✅ Relasi 1-to-many dengan menu

**Form Fields**:

- Nama Jenis (unique, validasi otomatis)
- Deskripsi (opsional)

### Master Menu

- **URL**: `/master-menu` atau `/master/menu`
- **Fitur**:
    - ✅ Tambah menu baru
    - ✅ Edit menu
    - ✅ Hapus menu
    - ✅ Kategori menu berdasarkan jenis
    - ✅ Harga menu terformats

**Form Fields**:

- Nama Menu
- Jenis Menu (dropdown dari master jenis)
- Harga
- Deskripsi (opsional)

## 🔑 Role/Hak Akses yang Tersedia

| Role    | Deskripsi                             |
| ------- | ------------------------------------- |
| Admin   | Akses penuh ke semua fitur            |
| Pelayan | Akses untuk input pesanan             |
| Kasir   | Akses untuk konfirmasi pembayaran     |
| Manajer | Akses untuk laporan dan manajemen     |

## 📊 Database Schema

### Table Jenis

```sql
CREATE TABLE jenis (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nama_jenis VARCHAR(255) UNIQUE NOT NULL,
  deskripsi TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Table Users (Updated)

```sql
ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'kasir' AFTER password;
```

### Table Menus (Updated)

```sql
ALTER TABLE menus ADD COLUMN jenis_id BIGINT FOREIGN KEY REFERENCES jenis(id) ON DELETE SET NULL;
ALTER TABLE menus ADD COLUMN deskripsi TEXT;
```

## 🔗 Relasi Database

```
Jenis (1) ─→ (Many) Menu
           ↓
         (through jenis_id)

User (Many) ←─ Role (Stored in users.role field)
```

## ✅ Validasi Form

### Master Karyawan

- Nama: Required, string, max 255
- Email: Required, email, unique
- Password: Required, min 6, confirm
- Role: Required, in (admin, super_admin, kasir, pelayan)

### Master Jenis

- Nama Jenis: Required, string, max 255, unique
- Deskripsi: Optional, string

### Master Menu

- Nama Menu: Required, string, max 255
- Jenis Menu: Required, exists in jenis table
- Harga: Required, numeric, min 0
- Deskripsi: Optional, string

## 🧪 Testing

### Akses Menu Navigasi

Tambahkan link di sidebar atau navbar:

```blade
<li class="nav-item">
    <a class="nav-link" href="{{ route('karyawan.index') }}">
        <i class="fas fa-users"></i> Master Karyawan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('jenis.index') }}">
        <i class="fas fa-list"></i> Master Jenis Menu
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('master-menu.index') }}">
        <i class="fas fa-fork-knife"></i> Master Menu
    </a>
</li>
```

## 🐛 Troubleshooting

### Error: Table not found

**Solusi**: Jalankan `php artisan migrate`

### Error: Route not found

**Solusi**: Pastikan routes sudah ditambahkan dan jalankan `php artisan route:cache`

### Error: Class not found

**Solusi**: Pastikan autoload benar dengan `composer dump-autoload`

### Validasi unique field error

**Solusi**: Periksa data duplikat di database atau tambahkan `not_deleted_at` untuk soft delete

## 📚 Workflow Lengkap

1. **Setup Awal**

    ```bash
    php artisan migrate
    php artisan db:seed --class=JenisSeeder
    ```

2. **Buat Jenis Menu Terlebih Dahulu**
    - Buka `/jenis`
    - Tambahkan: Makanan, Minuman, Cemilan

3. **Tambah Menu**
    - Buka `/master-menu`
    - Pilih jenis, input nama, harga, deskripsi

4. **Kelola Karyawan**
    - Buka `/karyawan`
    - Tambah karyawan dengan role sesuai kebutuhan

## 💡 Tips

- Selalu buat jenis menu terlebih dahulu sebelum menambah menu
- Role karyawan dapat diubah kapan saja
- Password akan otomatis di-enkripsi dengan bcrypt
- Format harga otomatis dengan Rp dan ribuan separator

---

**Status**: ✅ Siap Digunakan
**Terakhir Diupdate**: 2025-02-27
