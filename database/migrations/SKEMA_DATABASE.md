# Skema Database Siedu — Mapping ke PRD

## Daftar Tabel & Referensi PRD

| Tabel | Fungsi | Referensi PRD |
|---|---|---|
| `users` | Akun terpusat (admin/guru/siswa) | F1, Ketentuan Penggunaan #3 |
| `siswa_profiles` | Data siswa + data Wali (nempel, bukan akun terpisah) | F11, Hak Akses #2 |
| `mata_pelajaran` | Program/mapel yang ditawarkan | F9 |
| `periode_pendaftaran` | Tahun ajaran | F10, Business Rule #8 |
| `kelas` | Penawaran kelas per periode (kuota, jadwal, guru) | F10, Business Rule #6 |
| `pendaftaran` | Entitas inti — satu siswa + satu kelas + satu periode | F4, F5, F7, Business Rule #8 |
| `pembayaran` | Tagihan DP/cicilan/lunas, tokenized ke gateway | F6, Business Rule #7 |
| `presensi` | Kehadiran per pertemuan | F12 |
| `nilai_progres` | Progres belajar (kualitatif/kuantitatif) | F13, Validasi Data #3 |
| `pengajuan_pembatalan` | Klaim refund kasus khusus dari siswa | F14 |

## Keputusan desain penting (supaya konsisten dengan PRD)

1. **`kategori_usia_saat_daftar` dan `jenjang_saat_daftar` di-snapshot di tabel `pendaftaran`**, bukan hanya dibaca dari `siswa_profiles`. Ini karena Validasi Data #1 & #5: nilainya dihitung ulang tiap pendaftaran baru, tapi TETAP selama satu pendaftaran berjalan meski siswa ulang tahun di tengah periode. Kalau cuma baca dari profil, data historis akan berubah retroaktif — salah.

2. **`wali_approved_at` ada di tabel `pendaftaran`**, bukan tabel Wali terpisah. Sesuai Hak Akses #2: tidak ada login/akun Wali sendiri, jadi tidak perlu tabel `wali` sebagai entitas independen — cukup kolom di pendaftaran + data kontak Wali di `siswa_profiles`.

3. **Status pendaftaran dipecah eksplisit per skenario pembatalan** (`dibatalkan_timeout`, `dibatalkan_tunggakan`, `dibatalkan_siswa`, `dibatalkan_pelanggaran`, `dibatalkan_kuota`) — bukan cuma `dibatalkan` generik. Ini penting karena tiap skenario punya aturan refund BERBEDA (Business Rule #2, #4, #5, #7, F14). Kalau statusnya digeneralisir, logika refund di kode nanti jadi ambigu — harus tahu pembatalan mana yang sedang terjadi.

4. **Pembatalan otomatis (timeout/tunggakan) tidak lewat tabel `pengajuan_pembatalan`** — itu cukup update `status` di `pendaftaran` langsung lewat scheduled job, karena tidak ada pengajuan manual dari siswa. Tabel `pengajuan_pembatalan` khusus untuk kasus F14 yang butuh keputusan admin (klaim refund di luar aturan default).

5. **`kuota_terisi` di tabel `kelas` di-increment saat pendaftaran diajukan** (bukan saat status jadi "terdaftar"), sesuai Business Rule #1: kuota dikunci begitu diajukan supaya slot terakhir tidak direbut dua orang sekaligus. Butuh row-level locking (`lockForUpdate()`) di service layer saat increment, sesuai Non-Functional Requirement Performance.

## Yang SENGAJA tidak dibuat foreign-key constraint di level migration

- Validasi jadwal bentrok guru (Business Rule #6) dan jadwal bentrok siswa (Business Rule #9) — tidak bisa jadi constraint SQL biasa karena butuh perbandingan rentang waktu antar baris. Ini harus divalidasi di service/repository layer sebelum insert.
- Validasi kuota minimum saat periode ditutup (Business Rule #10) — dicek lewat scheduled job/command, bukan constraint DB.

## Langkah selanjutnya yang disarankan

1. Jalankan migration ini di project Laravel Anda: copy folder `database/migrations/` ke project, lalu `php artisan migrate`.
2. Buat Model Eloquent untuk tiap tabel (saya bisa bantu sekaligus relasinya kalau mau).
3. Buat Seeder untuk akun Admin (Ketentuan Penggunaan #3: admin dibuat lewat seeder, bukan form publik).
