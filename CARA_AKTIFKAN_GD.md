# Cara Mengaktifkan GD Extension di Laragon

Error "The PHP GD extension is required, but is not installed" terjadi karena GD extension belum diaktifkan di PHP yang digunakan Laragon.

## Solusi: Aktifkan GD Extension

### Step 1: Buka Laragon Menu

- Klik kanan pada icon Laragon di system tray (kanan bawah)
- Pilih "PHP" → "php.ini"

### Step 2: Edit php.ini

Di file php.ini yang terbuka, cari baris:

```
;extension=gd
```

Hapus tanda semicolon (;) di depannya sehingga menjadi:

```
extension=gd
```

### Step 3: Simpan dan Restart

- Simpan file (Ctrl+S)
- Tutup text editor
- Di Laragon menu, restart Apache atau stop/start Laragon

### Step 4: Verifikasi

- Buka terminal
- Jalankan: `php -m | grep gd` (Linux/Mac) atau `php -m | findstr gd` (Windows)
- Jika berhasil, akan muncul output "gd"

## Alternatif Cepat (Laragon UI)

1. Buka Laragon → Menu utama
2. Pilih "PHP" → "Extensions"
3. Cari dan centang "gd"
4. Restart services

Setelah GD extension diaktifkan, error akan hilang dan PDF bisa di-generate dengan logo!
