# 🦾 sethmacy.hub | Neo-Cyberpunk E-Commerce Terminal

![PHP](https://img.shields.io/badge/PHP-7.4+-8892bf.svg?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![MVC](https://img.shields.io/badge/Architecture-MVC-orange?style=for-the-badge)
![UI/UX](https://img.shields.io/badge/UI/UX-Futuristic-blueviolet?style=for-the-badge)


```text
┌──────────────────────────────────────────────────────────┐
│ ID CARD: AUTHORIZED DEVELOPER                            │
├──────────────────────────────────────────────────────────┤
│ NAME : SURYA PUTRA DARMA JAYA                            │
│ NIM  : 312410405                                         │
│ NODE : SETHMACY.HUB_MAINFRAME                            │
└──────────────────────────────────────────────────────────┘
```


**sethmacy.hub** adalah platform e-commerce futuristik bertema Cyberpunk. Proyek ini menggabungkan estetika terminal tingkat tinggi dengan fungsionalitas manajemen data modern (CRUD), sistem otentikasi berbasis peran (Role-based Access), dan pengalaman audiovisual yang imersif.

---

## 🛠️ Analisis Arsitektur Kode

Aplikasi ini dibangun menggunakan pola **MVC (Model-View-Controller)** yang dimodifikasi untuk efisiensi tinggi pada PHP Native.

### 1. Single Entry Point (`index.php`)
Seluruh permintaan (request) dari browser dikelola oleh satu file utama. 
* **Logika Kerja**: Router menangkap parameter `?url=` melalui metode `$_GET`. 
* **Keamanan**: Sebelum memuat tampilan (view), router melakukan pengecekan session (`$_SESSION['role']`) untuk mencegah akses ilegal ke halaman admin atau katalog user.

### 2. Controller System (`controllers/`)
Bertindak sebagai perantara antara User dan Database.
* **AuthController**: Mengelola enkripsi login, verifikasi kredensial di database, dan penghancuran session saat logout.
* **TransController**: Mengelola pengambilan data produk, pencarian barang, dan logika tampilan stok.

### 3. Integrated Audio Engine (JavaScript Synth)
Berbeda dengan website biasa, proyek ini menggunakan **Web Audio API**.
* **Analisis Kode**: Suara tidak berasal dari file MP3 eksternal, melainkan diproduksi secara real-time lewat sinyal osilator (`audioCtx.createOscillator`). Ini membuat interaksi tombol sangat responsif tanpa delay loading file suara.

---

## 🚀 Fitur Utama & Penjelasan Teknis

### 🌑 Antarmuka Imersif (Frontend)
* **Holographic Glassmorphism**: Menggunakan `backdrop-filter: blur()` dan transparansi tinggi untuk menciptakan efek kaca di masa depan.
* **3D Interactive Cards**: Implementasi `Vanilla-tilt.js` yang memanipulasi transformasi CSS 3D secara dinamis berdasarkan posisi kursor.
* **Dynamic Particles**: Latar belakang hidup berbasis `Particles.js` untuk memperkuat nuansa sci-fi.

### 👤 Pengalaman Pengguna (User Side)
* **Sequential Login Splash**: Sistem login tidak langsung pindah halaman, melainkan menjalankan sequence animasi status (Authorizing -> Decrypting -> Syncing) untuk membangun atmosfir.
* **Blockchain-Simulated Checkout**: Visualisasi progres pengiriman yang mensimulasikan verifikasi ledger blockchain hingga pengiriman unit oleh drone.
* **Digital Receipt**: Struk belanja otomatis dengan desain gerigi thermal yang unik dan simulasi kode QR.

### 🛠️ Pusat Kendali Admin (CRUD System)
Dashboard Admin dirancang sebagai "Command Center" dengan fitur lengkap:
* **Create (Uplink)**: Menambahkan unit hardware baru ke database melalui uplink visual URL.
* **Read (Monitor)**: Tabel inventaris real-time yang memantau status sistem dan ketersediaan barang.
* **Update (Reconfigure)**: Fitur edit tercanggih menggunakan **Bootstrap Modals**. Menggunakan JavaScript untuk mem-parsing data produk ke form edit tanpa reload halaman.
* **Delete (Erasure)**: Penghapusan permanen record data dari mainframe database.

---

## 📁 Struktur Direktori

```text
uas_project/
├── config/             # Database Connection (PDO Logic)
├── controllers/        # Business Logic (Brain of the app)
├── views/              # Interface (The Face of the app)
│   ├── admin/          # Admin Control Center (CRUD UI)
│   ├── user/           # User Catalog & Checkout System
│   └── login.php       # Entrance Authentication Gate
├── index.php           # Central Router (Entry Point)
└── sql/                # Database Schema & Initial Data
