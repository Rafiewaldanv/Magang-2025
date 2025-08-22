# psikologanda.com — Test Online Web

<p align="center">
  <strong>psikologanda.com</strong> — Sistem Ujian / Test Online berbasis web
</p>

---

## Ringkasan

**psikologanda.com** adalah aplikasi web Test Online berbasis **Laravel** yang dirancang untuk menyelenggarakan ujian dan asesmen psikologi secara daring: ringan, terintegrasi, dan mudah dipelihara.

**Tim Magang (Universitas Diponegoro)**

* **Farhan Nasrullah** — Frontend & Integrator (FE & BE)
* **Rafie Waldan Valerie** — Backend & Database

---

## Fitur Utama

* Otentikasi pengguna (siswa/admin)
* Manajemen paket soal dan bank soal (CRUD)
* Timer per paket soal + autosubmit saat waktu habis
* Penyimpanan jawaban sementara (sessionStorage / localStorage)
* Perhitungan skor otomatis (per soal & total)
* Dashboard admin untuk melihat hasil dan statistik

---

## Teknologi

* Backend: **Laravel (PHP)**
* Frontend: **Blade**, Vanilla **JavaScript**
* Database: **MySQL / MariaDB**
* Build: Composer, NPM (Vite / Laravel Mix)

---

## Instalasi & Jalankan (Lokal)

> Pastikan PHP, Composer, Node.js/NPM, dan MySQL sudah terpasang.

1. Clone repo

```bash
git clone --branch final --single-branch https://github.com/Rafiewaldanv/Magang-2025.git
cd Magang-2025
```

2. Install dependency

```bash
composer install
npm install
```

3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk menyesuaikan database, `APP_URL`, dan konfigurasi lain seperti mail.

4. Migrasi dan seeder (opsional)

```bash
php artisan migrate
php artisan db:seed   # jika tersedia seeder
```

5. Link storage & build assets

```bash
php artisan storage:link
npm run build   # atau npm run dev
```

6. Jalankan server

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` atau sesuai `APP_URL`.

---

## Struktur Penting

* `routes/web.php` — routes untuk halaman
* `app/Http/Controllers/` — controllers (pisahkan Admin / Test / Result)
* `resources/views/` — Blade templates
* `resources/js` & `resources/css` — assets frontend
* `database/migrations` & `database/seeders` — schema dan seed data

---

## Catatan Pengembangan (Frontend & UX penting)

* **Timer & Autosubmit**: Pastikan autosubmit men-set `localStorage.setItem('quizFinished_<packetId>', '1')` agar modal/status berubah setelah submit.
* **Sinkronisasi soal**: Selalu update `localStorage.quizCurrent_<packetId>` saat berpindah soal agar modal dan indikator nomor soal tersinkron.
* **Penyimpanan jawaban sementara**: Gunakan `sessionStorage` atau `localStorage` untuk menyimpan jawaban sementara dan hapus kunci terkait setelah submit final.
* **Unique IDs**: Hindari duplicate `id` pada elemen form soal — gunakan `question-{{ $question->id }}` atau format dinamis lain.

---

## Deployment (Singkat)

* Pastikan `APP_ENV=production` dan variabel `.env` untuk DB/Mail/URL sudah benar
* Jalankan migrasi di server: `php artisan migrate --force`
* Setup queue/cron jika fitur background digunakan
* Konfigurasi Nginx/Apache sesuai panduan Laravel

---

## Testing

* Jika ada test: `php artisan test`
* Lakukan manual testing untuk alur ujian (mulai tes, submit manual, autosubmit ketika waktu habis, perhitungan skor)

---

## Cara Berkontribusi

1. Fork repo → buat branch fitur: `feature/namafitur`
2. Buat PR dengan deskripsi perubahan dan langkah testing
3. Ikuti style guide (PSR-12 untuk PHP; konsistensi naming pada JS/Blade)

---

## Pelaporan Masalah

* Gunakan GitHub Issues: sertakan langkah reproduksi, versi aplikasi, screenshot/log jika perlu
* Untuk isu terkait scoring/data hasil, tandai sebagai `bug` dan beri prioritas tinggi

---

## Lisensi

Lisensi proyek ini: **MIT** 
All Right Reserved to PT Campus Digital Indonesia

---

## Kontak

* **Farhan Nasrullah** — Frontend & Integrator — `farhannasrullah3@gmail.com`
* **Rafie Waldan Valerie** — Backend & Database — `replace-with-email@example.com`

> Ganti kontak di atas dengan alamat email yang valid sebelum publish.

