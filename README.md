# Selulerku

## Deskripsi Proyek

Selulerku adalah aplikasi web yang dirancang untuk manajemen toko seluler, mencakup pengelolaan produk, kategori produk, pelanggan, pesanan, riwayat servis, dan pengguna. Aplikasi ini bertujuan untuk menyederhanakan operasional harian toko seluler, mulai dari pencatatan penjualan hingga pelacakan servis.

## Fitur Utama

-   **Manajemen Produk:** Tambah, edit, hapus, dan lihat detail produk seluler.
-   **Manajemen Kategori Produk:** Organisasi produk berdasarkan kategori.
-   **Manajemen Pelanggan:** Catat dan kelola data pelanggan.
-   **Manajemen Pesanan:** Buat dan lacak pesanan pelanggan.
-   **Riwayat Servis:** Catat dan pantau riwayat servis perangkat pelanggan.
-   **Manajemen Pengguna:** Kelola akun pengguna dengan peran yang berbeda (misalnya, admin, kasir).
-   **Autentikasi Pengguna:** Sistem login dan logout yang aman.

## Teknologi yang Digunakan

-   **Backend:** PHP (Laravel Framework)
-   **Frontend:** HTML, CSS (Tailwind CSS), JavaScript (Vanilla JS)
-   **Database:** MySQL (atau database relasional lainnya yang didukung Laravel)
-   **Package Manager:** Composer (PHP), npm/Yarn (JavaScript)

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini di lingkungan lokal:

1.  **Clone Repositori:**

    ```bash
    git clone https://github.com/dianudi/selulerku.git
    cd selulerku
    ```

2.  **Instal Dependensi Composer:**

    ```bash
    composer install
    ```

3.  **Konfigurasi Environment:**

    -   Buat file `.env` dari `.env.example`:
        ```bash
        cp .env.example .env
        ```
    -   Edit file `.env` dan sesuaikan konfigurasi database (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    -   Generate application key:
        ```bash
        php artisan key:generate
        ```

4.  **Migrasi Database dan Seeding:**

    -   Jalankan migrasi database:
        ```bash
        php artisan migrate
        ```
    -   (Opsional) Jalankan seeder untuk data dummy:
        ```bash
        php artisan db:seed
        ```

5.  **Instal Dependensi NPM/Yarn dan Kompilasi Aset Frontend:**

    ```bash
    npm install # atau yarn install
    npm run dev # atau npm run build untuk produksi
    ```

6.  **Jalankan Server Pengembangan:**
    ```bash
    php artisan serve
    ```

Aplikasi akan tersedia di `http://127.0.0.1:8000`.

## Penggunaan

Setelah beres instalasi, akses aplikasi melalui browser.

-   **Login:** Gunakan kredensial "**admin@example.net**" dan kata sandi "**password**".
-   Navigasi melalui menu untuk mengelola produk, pelanggan, pesanan, dll.

## Kontribusi

Kami menyambut kontribusi! Jika ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1.  Fork repositori ini.
2.  Buat branch baru (`git checkout -b feature/nama-fitur`).
3.  Lakukan commit.
4.  Commit perubahan (`git commit -m 'Tambahkan fitur baru'`).
5.  Push ke branch (`git push origin feature/nama-fitur`).
6.  Buat Pull Request.

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.
