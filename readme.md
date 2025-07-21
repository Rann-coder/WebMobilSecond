**WEBMOBILSECOND - Website Jual Beli Mobil Bekas**



**DESKRIPSI PROYEK**

MobilSecond.id adalah website pencarian mobil bekas yang memudahkan pengguna menemukan mobil sesuai kebutuhan. Dilengkapi fitur pencarian, filter, detail mobil lengkap, dan sistem like, pengguna bisa menjelajahi mobil-mobil bekas berkualitas dengan mudah. Website ini dibuat menggunakan PHP, Twig, dan MySQL, serta mendukung login user untuk pengalaman yang lebih personal.



**FITUR UTAMA**

Untuk Pengguna (User)



\* Melihat daftar mobil bekas lengkap dengan opsi berdasarkan tipe mobil, merek mobil dan fitur pencarian.

\* Melihat ketersediaan mobil pada masing-masing showroom.

\* Melihat detail spesifikasi setiap mobil.

\* Melakukan simulasi kredit.

\* Fitur "Suka" (Like) untuk menyimpan mobil favorit.

\* Fungsi perbandingan dua mobil secara berdampingan untuk membantu pengambilan keputusan.

\* Informasi dan rekomendasi layanan leasing terkait.

\* Filter mobil berdasarkan showroom, rentang harga, merek, model, dan kriteria lainnya.



Untuk Admin

\* Sistem Otentikasi: halaman login yang aman khusus untuk admin.

\* Dashboard Statistik: melihat ringkasan data penting seperti total mobil yang tersedia, jumlah showroom yang aktif, dan sedikit mengenai keuangan.

\* Manajemen Mobil: menambah dan mengedit mobil

\* Sistem Persetujuan Mobil: alur kerja untuk me-review dan menyetujui mobil baru sebelum ditampilkan ke publik.

\* Manajemen Showroom: mengelola daftar showroom beserta detail dan status operasionalnya.

\* Manajemen Staff: mengelola data staff pemasaran dan penempatannya di setiap showroom.

\* Manajemen Pengguna: Mengelola daftar pengguna yang terdaftar (admin dan customer).

\* Manajemen Leasing: Mengelola Leasing yang aktif dan menambah yang baru.

\* Riwayat Penjualan: melihat, memfilter, dan mencari seluruh Riwayat transaksi penjualan.





&nbsp; 

**TEKNOLOGI YANG DIGUNAKAN:**

Frontend:

\* HTML5 (untuk struktur halaman)

\* CSS3 (untuk styling)

\* JavaScript (untuk interaktivitas dinamis)



Backend:

\* PHP (untuk logika server-side dan database interaction)

\* Twig (sebagai template engine)



Database:

\* MySQL (untuk penyimpanan data)



Lain-lain:

\* Composer (untuk manajemen dependensi PHP)



**STRUKTUR PROYEK**

WEBMOBILSECOND/

├── api/                     # Direktori untuk API (Application Programming Interface)

│   ├── all-cars.php         # Endpoint untuk mengambil semua data mobil

│   ├── filter\_cars\_showroom.php # Endpoint untuk memfilter mobil berdasarkan showroom

│   ├── filter\_cars.php      # Endpoint untuk filter mobil umum (harga, kriteria lain)

│   └── get-leasing-rules.php # Endpoint untuk mengambil aturan/informasi leasing

├── css/                     # Direktori utama untuk file CSS

│   ├── css-admin/           # File CSS khusus untuk tampilan antarmuka admin

│   └── css-user/            # File CSS khusus untuk tampilan antarmuka pengguna

├── database/                # Direktori terkait database (misal: migrasi, seeder)

├── images/                  # Direktori untuk semua aset gambar (mobil, ikon, logo, dll.)

├── php-admin/               # File PHP yang menangani logika dan fungsionalitas admin

├── php-user/                # File PHP yang menangani logika dan fungsionalitas pengguna

├── script/                  # Direktori utama untuk file JavaScript

│   ├── script-admin/        # File JavaScript untuk fungsionalitas di sisi admin

│   └── script-user/         # File JavaScript untuk fungsionalitas di sisi pengguna

├── src/                     # Direktori untuk kode sumber (misal: kelas PHP, model)

├── templates-admin/         # Template HTML (atau Twig) untuk halaman-halaman admin

├── templates-user/          # Template HTML (atau Twig) untuk halaman-halaman pengguna

├── vendor/		     # Folder dependensi dari Composer

├── composer.json

├── composer.lock

└── README.md                # File dokumentasi proyek ini





**INSTALASI DAN KONFIGURASI**

Prasyarat

Web Server (PHP built-in web server)PHP

Versi minimal yang direkomendasikan: PHP 7.4 atau lebih tinggi.



MySQL Database Server

Digunakan untuk menyimpan data aplikasi. Anda dapat menggunakan DBngin sebagai alat bantu manajemen database lokal.



Browser Web Modern

Seperti Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari.



Composer



**Langkah-Langkah Instalasi**

**Kloning Repositori**

git clone https://github.com/Rann-coder/WebMobilSecond

cd WebMobilSecond



**Install Dependensi PHP**

composer install



**Konfigurasi Database**

Jalankan di cmd

\# Membuat database tabel-tabel dasar

mysql -u root -p  < database/migrations/0001_create_initial_tables.sql



\# Membuat tabel cars

mysql -u root -p web_mobil_second < database/migrations/0002_create_cars_table.sql



\# Membuat tabel penghubung (pivot)

mysql -u root -p web_mobil_second < database/migrations/0003_create_pivot_tables.sql



\# Mengisi tabel daftarBrands dan daftarTypes

mysql -u root -p web_mobil_second < database/seeds/0001_brands_and_types.sql



mysql -u root -p web_mobil_second < database/seeds/0002_sample_data.sql





**AKSES APLIKASI**

Buka powershell pada terminal VsCode (melalui PHP built-in server untuk pengembangan lokal) lalu ketik :



php -S 127.0.0.1:8080



Untuk user dapat dimulai explore dari --> http://localhost:8080/php-user/home.php

Untuk admin dapat dimulai explore dari --> http://localhost:8080/php-admin/admin-home.php



Jika ingin login sebagai user:

email --> bryancenbryan@gmail.com

pass --> admin01



Jika ingin login sebagai admin:

email --> randy@gmail.com

pass --> staff01


**OPSIONAL JIKA TERJADI MASALAH**

**Penyebab**:
Total ukuran file yang Anda upload melebihi batas yang diizinkan oleh konfigurasi PHP Anda. Akibatnya, PHP membatalkan request tersebut, dan semua data $_POST dan $_FILES menjadi kosong.

*Solusi:*
Anda perlu menaikkan batas upload di file php.ini Anda.

1.  **Temukan file php.ini:**
 jalankan
http://localhost:8080/php-admin/info.php
    Cari baris Loaded Configuration File: untuk melihat lokasi pasti dari file php.ini yang sedang digunakan.

2.  **Edit file php.ini:**
    Buka file tersebut dan ubah nilai berikut menjadi lebih besar (misalnya 64M):
    ini
    post_max_size = 64M
    upload_max_filesize = 64M
    

3.  *Restart Server PHP Anda:*
    Setelah menyimpan perubahan, hentikan server PHP Anda (dengan *Ctrl + C*) dan jalankan kembali:
    
    php -S localhost:8080
    