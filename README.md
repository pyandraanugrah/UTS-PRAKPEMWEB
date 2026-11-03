
# Sistem Menu Kantin

Aplikasi berbasis web untuk mengelola data menu makanan dan minuman pada kantin kampus. Proyek ini dibuat sebagai bagian dari Ujian Tengah Semester (UTS) Praktikum Pemrograman Web dengan menggunakan Laravel dan MySQL.

## Deskripsi Proyek

Sistem Menu Kantin merupakan aplikasi sederhana yang digunakan untuk membantu proses pengelolaan data menu kantin. Aplikasi ini menyediakan fitur untuk menambahkan, menampilkan, mengubah, dan menghapus data menu.

Selain fitur CRUD, aplikasi juga memiliki fitur pencarian berdasarkan kode menu, nama menu, dan barcode, serta filter berdasarkan kategori menu.

## Teknologi yang Digunakan

- PHP
- Laravel
- MySQL
- PDO (PHP Data Objects)
- HTML
- Blade Template Engine
- Composer

## Fitur Aplikasi

1. **Menampilkan Menu (Read)**  
   Menampilkan seluruh data menu beserta informasi kategori, harga, barcode, dan status harga.

2. **Menambahkan Menu (Create)**  
   Menambahkan data menu baru melalui formulir yang tersedia.

3. **Mengubah Menu (Update)**  
   Memperbarui informasi menu yang sudah tersimpan.

4. **Menghapus Menu (Delete)**  
   Menghapus data menu berdasarkan ID.

5. **Pencarian Menu**  
   Mencari menu berdasarkan kode menu, nama menu, atau barcode.

6. **Filter Kategori**  
   Menampilkan menu berdasarkan kategori makanan atau minuman.

7. **Validasi Data**  
   Memastikan data yang dimasukkan sesuai dengan ketentuan sistem.

8. **Status Harga**  
   Menentukan status harga menu berdasarkan nilai harga yang dimasukkan.

## Struktur Database

Aplikasi menggunakan dua tabel utama, yaitu:

### 1. Tabel `kategori_menu`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED | Primary Key |
| nama_kategori | VARCHAR(100) | Nama kategori, UNIQUE |

### 2. Tabel `menu`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED | Primary Key |
| kode_menu | CHAR(10) | Kode menu, UNIQUE |
| nama_menu | VARCHAR(100) | Nama menu |
| barcode | VARCHAR(30) | Barcode, UNIQUE |
| harga | INT UNSIGNED | Harga menu |
| kategori_menu_id | BIGINT UNSIGNED | Foreign Key |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu perubahan |

### Relasi Tabel

Relasi database menggunakan konsep One-to-Many.

Satu kategori dapat memiliki banyak menu, sedangkan satu menu hanya memiliki satu kategori.

```text
kategori_menu
      |
      | 1
      |
      | N
     menu
```

Ketentuan database:
- Primary Key pada setiap tabel.
- Foreign Key pada `kategori_menu_id`.
- UNIQUE pada `nama_kategori`, `kode_menu`, dan `barcode`.
- CHECK harga antara 1.000 sampai 100.000.
- ON UPDATE CASCADE.
- ON DELETE RESTRICT.

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat sudah memiliki:

- PHP sesuai persyaratan versi Laravel yang digunakan.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM (jika diperlukan untuk pengelolaan aset).

## Cara Instalasi

### 1. Clone Repository

```bash
git clone URL_REPOSITORY
```

Masuk ke direktori proyek:

```bash
cd menu_kantin
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Windows CMD:

```cmd
copy .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buat database MySQL dengan nama:

```sql
CREATE DATABASE menu_kantin_2401020154;
```

Sesuaikan konfigurasi berikut pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menu_kantin_2401020154
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password berdasarkan konfigurasi MySQL masing-masing.

### 6. Migrasi Database

Jalankan perintah:

```bash
php artisan migrate
```

### 7. Menambahkan Data Awal

Jalankan seeder:

```bash
php artisan db:seed
```

Seeder digunakan untuk memasukkan data kategori dan menu awal ke dalam database.

### 8. Menjalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses:

```text
http://127.0.0.1:8000/menu
```

## Daftar Route

| Method | Route | Fungsi |
|---|---|---|
| GET | /menu | Menampilkan daftar menu |
| POST | /menu | Menambahkan menu |
| GET | /menu/{id}/edit | Menampilkan halaman edit |
| PUT | /menu/{id} | Memperbarui menu |
| DELETE | /menu/{id} | Menghapus menu |

## Keamanan dan Validasi

Beberapa mekanisme yang diterapkan dalam aplikasi:

- Prepared Statement menggunakan PDO untuk menjalankan query database.
- Validasi input pada sisi server.
- Sanitasi input menggunakan `trim()` dan `strip_tags()`.
- Perlindungan CSRF melalui Laravel.
- UNIQUE constraint untuk mencegah data duplikat.
- Error handling menggunakan `try-catch` dan `report()`.

## Dokumentasi

Dokumentasi tambahan proyek meliputi:

- File migration database.
- File seeder.
- Source code controller.
- File Blade.
- File SQL hasil export database.
- Laporan UTS Praktikum Pemrograman Web.

## Pengembang

**Nama:** [Nama Lengkap]  
**NIM:** [NIM]  
**Program Studi:** Teknik Informatika  
**Universitas:** Universitas Maritim Raja Ali Haji  

## Tujuan Proyek

Proyek ini dibuat untuk memenuhi tugas Ujian Tengah Semester Praktikum Pemrograman Web, sekaligus menerapkan materi pengembangan aplikasi web menggunakan Laravel, pengelolaan database MySQL, PDO, serta implementasi operasi CRUD.
