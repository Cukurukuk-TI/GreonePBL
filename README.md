# 🚀 Project Web Development – Team C1 TRPL

Selamat datang di repository resmi project web kami!  
Project ini dikembangkan oleh Tim C1 dari Program Studi Teknologi Rekayasa Perangkat Lunak (TRPL) dengan fokus pada pengembangan website berbasis Laravel.

---

## 🧩 Deskripsi Singkat

Website ini dirancang untuk menyediakan layanan penjualan produk secara online dengan antarmuka yang user-friendly untuk pengguna dan sistem manajemen backend yang efisien bagi admin. Proyek ini mencakup fitur-fitur utama seperti registrasi, login, dashboard admin, integrasi pembayaran Midtrans, dan lainnya.

---

## ✅ Status Fitur (Final)

| Fitur                   | Status     | Keterangan                                     |
| ----------------------- | ---------- | ---------------------------------------------- |
| 🎨 Penyesuaian UI Web   | ✅ SELESAI | Tampilan user bersih, responsif, dan intuitif  |
| 🛠️ Penyesuaian UI Admin | ✅ SELESAI | Panel admin telah lengkap dan user-friendly    |
| 🧠 Logic Web            | ✅ SELESAI | Fitur login, registrasi, proteksi halaman      |
| 📝 Penyesuaian Backlog  | ✅ SELESAI | Semua item backlog sudah direalisasikan        |
| 💳 API Midtrans         | ✅ SELESAI | Pembayaran menggunakan Midtrans aktif & stabil |

---

## ⚙️ Persiapan & Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Cukurukuk-TI/GreonePBL.git
```

### 2. Konfigurasi `.env`

-   Salin file `.env.example` menjadi `.env`
-   Atur konfigurasi berikut sesuai database lokal kamu:

```env
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan Perintah Laravel

```bash
php artisan migrate
php artisan db:seed
rm public/storage
php artisan storage:link
php artisan serve
```

---

## 🧠 Teknologi yang Digunakan

-   **Laravel 10+**
-   **PHP 8+**
-   **MySQL/MariaDB**
-   **Midtrans Payment Gateway**
-   **Blade Templating**
-   **Bootstrap / CSS Custom**

---

## 👨‍💻 Tim Pengembang

-   👤 versace – Developer
-   👤 drenzzz – Developer
-   👤 diqshei – UI/UX Designer
-   👤 iwd – TW
-   👤 ivere – TW

## 🙌 Honourable mention:

-   🤖 **ChatGPT (OpenAI)** – main
-   🔮 **Gemini (Google)** – sec
-   💡 **Claude (Anthropic)** – backburner
-   🧠 **DeepSeek** – backburner too

---

## 📌 Lisensi

Project ini bersifat **Open Source** untuk keperluan edukasi dan pembelajaran.  
Silakan digunakan dengan tetap mencantumkan kredit kepada tim pengembang.
