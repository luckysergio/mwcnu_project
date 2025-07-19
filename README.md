# 🕌 MWCNU Karang Tengah - Monitoring Program Kerja

Aplikasi web untuk memonitor program kerja organisasi **Majelis Wakil Cabang Nahdlatul Ulama (MWCNU) Karang Tengah**. Sistem ini mendukung pendataan anggota, pengajuan dan monitoring program kerja oleh ranting-ranting NU.

---

## 🔐 Halaman Login

Form login digunakan oleh semua pengguna sistem: admin, tanfidiyah ranting, dan anggota biasa.

![Login](public/tampilan/login.png)

---

## 🏠 Home Admin

Dashboard untuk admin MWCNU yang memiliki akses penuh terhadap seluruh data dan fitur sistem.

**Fitur:**
- Statistik jumlah anggota dan program kerja
- Manajemen data:
  - Anggota NU
  - Bidang, Jenis Kegiatan, Tujuan, Sasaran
  - Ranting dan Jabatan
  - Proker (Program Kerja)
- Monitoring laporan dan dokumentasi kegiatan

![Home Admin](public/tampilan/home admin.png)

---

## 🏘️ Home Tanfidiyah Ranting

Halaman utama untuk pengurus ranting NU (tanfidiyah).

**Fitur:**
- Ajukan program kerja ranting
- Lihat jadwal kegiatan MWCNU
- Upload dokumentasi & laporan kegiatan
- Riwayat kegiatan ranting

![Home Tanfidiyah Ranting](public/tampilan/home tanfidiyah ranting.png)

---

## 👤 Home Anggota

Tampilan untuk anggota umum dengan akses terbatas.

**Fitur:**
- Melihat informasi kegiatan NU
- Akses dokumentasi publik kegiatan
- Melihat profil & histori aktivitas pribadi

![Home Anggota](public/tampilan/home anggota.png)

---

## ⚙️ Teknologi yang Digunakan

- Laravel 12
- Tailwind CSS + Vite
- Bootstrap (SB Admin)
- MySQL
- SweetAlert2

