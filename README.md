# 🚀 CodeIgniter 4 Project

Project ini dibangun menggunakan **CodeIgniter 4** sebagai framework backend dan **Tailwind CSS + DaisyUI v4** untuk tampilan antarmuka. Project ini dibuat sebagai tugas akhir mata kuliah Pemrograman Berbasis Web 2

---

## 📌 Deskripsi Singkat

Website ini merupakan aplikasi berbasis web yang memiliki halaman publik (Home & About) serta halaman admin.
Halaman **About** menampilkan anggota kelompok dan teknologi yang digunakan dalam pengembangan web.

---

## 🛠️ Teknologi yang Digunakan

- PHP >= 8.1
- CodeIgniter 4
- MySQL / MariaDB
- Tailwind CSS
- DaisyUI v4
- Composer

---

## ⚙️ Cara Setup Project

### 1. Clone Repository

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

### 2. Install Dependency

```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment

Salin file env:

```bash
cp env .env
```

Edit `.env`:

```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 4. Generate App Key

```bash
php spark app:key:generate
```

### 5. Migrasi Database

```bash
php spark migrate
```

Seeder:

```bash
php spark db:seed UserSeeder
```

---

## ▶️ Menjalankan Aplikasi

```bash
php spark serve
```

Akses:

```
http://localhost:8080
```

## User Seeder

```bash
username: pemilik
password: pemilik123

username: kasir
password: kasir123
```

---

## 👥 Anggota Kelompok

- Fauzan Priatmana (24.240.0027)
- Muhammad Ichsan (24.240.0028)
- Nadhifatunnizza (24.240.0064)
- Imel Aimanda Bregawati (24.240.0080)

---
