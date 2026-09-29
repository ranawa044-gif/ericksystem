# LUMINARA Design System & UI Guide

Dokumen ini menjelaskan pedoman desain antarmuka (UI) dan pengalaman pengguna (UX) yang diterapkan di seluruh aplikasi **ERICK**, dengan mengadopsi bahasa desain **LUMINARA (2026 Style)**.

## 1. Filosofi Desain "Antigravity"
Gaya "Antigravity" diciptakan untuk melepaskan pengguna dari kesan aplikasi akademik yang kaku, kuno, dan membosankan. Konsep utamanya meliputi:
- **Elemen Mengambang (Floating UI):** Penggunaan *drop-shadow* dan translasi/transformasi *hover* yang lembut sehingga kartu atau tombol terasa ringan (tidak terikat gravitasi).
- **Glassmorphism:** Efek kaca (*frosted glass*) pada panel-panel yang menonjol (seperti halaman Login) melalui penggunaan `backdrop-filter: blur()`.
- **Clean & Spacious:** Menghindari elemen yang terlalu padat, memperbanyak *white-space* (ruang bernapas) antar komponen.

## 2. Palet Warna (Color Palette)

Aplikasi dibangun tanpa bergantung penuh pada utilitas framework (Tailwind/Bootstrap) untuk warna *custom*, melainkan menggunakan CSS Variables Vanilla di root file `style.css`.

| Variabel | Warna (Hex) | Kegunaan Utama |
|---|---|---|
| `--primary` | `#06b6d4` (Cyan) | Aksen utama, *glow effect*, *active state*. |
| `--secondary`| `#14b8a6` (Tosca) | Warna gradien sekunder untuk transisi yang dinamis. |
| `--accent` | `#0f172a` (Deep Navy) | *Dark accent* untuk Sidebar navigasi dan background halaman login. |
| `--text-main`| `#f8fafc` (Slate) | Teks utama pada latar gelap. |
| `--ink` | `#172026` | Warna tinta standar untuk teks pada mode terang. |
| `--page` | `#f8fafc` | Latar belakang (*background*) mode terang. |

## 3. Komponen Utama

### A. Sidebar (Menubar Kiri)
Sidebar menggunakan kombinasi warna `var(--accent)` dengan transisi linear gradien ke arah warna cyan. 
- **Nav-items:** Diubah menjadi bentuk *Pill* yang melayang. Ketika dalam mode aktif (`.active`), item navigasi memancarkan efek bersinar (Glow) berkat integrasi Box-Shadow transparan berwarna Cyan.

### B. Halaman Login
Halaman depan (Login) merupakan representasi terkuat dari gaya Antigravity.
- **Latar Belakang:** Deep Navy pekat.
- **CSS Orbs:** Tiga bola berwarna Cyan, Tosca, dan Biru (`.orb`) yang dikaburkan (`filter: blur(80px)`) dan memiliki animasi bergeser perlahan secara konstan untuk kesan dinamis futuristik.
- **Input Field:** Input gelap tembus pandang dengan batas cahaya (*glowing border*) dan ikon "mata" untuk *show/hide* password yang ter-align sempurna (presisi) dengan kotak teks.

### C. Konsultasi / Pesan Grid
Sebelumnya menggunakan list vertikal (*timeline*), kini telah menggunakan **CSS Grid System** (`grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))`). Hal ini mencegah pengguna (*scrolling fatigue*) ketika ada terlalu banyak pesan konsultasi. Masing-masing kotak (card) pesan dibekali batas vertikal mandiri (`overflow-y: auto`) untuk mengakomodasi tulisan yang panjang.

### D. Paginasi Custom
Karena ERICK tidak menggunakan vendor paginasi bawaan yang standar, sistem ini merancang UI paginasi sebaris (*inline*). Paginasi dimuat dalam `nav.pagination` yang bersih dengan warna aktif Cyan yang konsisten dengan *brand identity*.
