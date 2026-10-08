# Rackly: Sistem Reservasi Meja Biliar Online Terintegrasi Payment Gateway (DP)

### Deskripsi Masalah
Pengelolaan operasional *billiard pool/lounge* umumnya masih mengandalkan sistem *walk-in* atau reservasi manual melalui pesan instan (WhatsApp) dan buku catatan kasir. Metode konvensional ini menimbulkan sejumlah kendala:
* **Tingginya Angka Pembatalan Sepihak (*No-Show*):** Pelanggan memesan meja namun tidak hadir tanpa konsekuensi finansial, sehingga meja menganggur dan pengelola kehilangan potensi pendapatan.
* **Risiko *Double Booking*:** Potensi bentrok reservasi meja pada jam sibuk (*peak hours*) akibat keterlambatan admin memperbarui ketersediaan meja.
* **Verifikasi Pembayaran Manual yang Lambat:** Proses cek mutasi bank manual untuk uang muka (*down payment*) memperlambat konfirmasi pemesanan dan rawan kesalahan pencatatan.
* **Visibilitas Meja yang Rendah:** Pelanggan tidak dapat mengetahui ketersediaan meja secara *real-time* tanpa harus bertanya langsung ke pihak pengelola.

---

### Profil Target Pengguna
* **Pelanggan / Pemain Biliar:** Pemain perorangan atau komunitas yang ingin memesan meja spesifik pada tanggal dan jam tertentu tanpa risiko kehabisan meja saat tiba di lokasi.
* **Kasir / Operator Tempat Biliar:** Staf operasional yang memvalidasi kedatangan pelanggan (*check-in*), memantau status meja secara langsung, dan menyelesaikan pelunasan sisa tagihan.
* **Pemilik / Pengelola (*Owner/Manager*):** Pihak yang membutuhkan visibilitas riwayat transaksi, laporan utilisasi meja, dan ringkasan dana DP yang masuk.

---

### Manfaat Aplikasi
* **Mengurangi Risiko Kerugian Akibat *No-Show*:** Penerapan kewajiban pembayaran DP secara otomatis mengikat komitmen pelanggan sebelum slot dikunci.
* **Eliminasi Jadwal Bentrok (*Anti-Double Booking*):** Validasi otomatis di sisi *backend* memastikan tidak ada dua pemesanan yang bertabrakan pada meja dan rentang waktu yang sama.
* **Konfirmasi Reservasi Instan & Otomatis:** Integrasi *payment gateway* (QRIS/VA) memverifikasi pembayaran secara asinkron tanpa intervensi manual staf kasir.
* **Efisiensi Manajemen Meja:** Memudahkan kasir melacak status meja (tersedia, dipesan, sedang digunakan, atau kedaluwarsa) dalam satu antarmuka terpadu.

---

### Daftar Fitur Inti (MVP)

1. **Katalog & Ketersediaan Meja Interaktif:**
   * Tampilan daftar meja biliar beserta jenis/tipe meja, tarif sewa per jam, dan status ketersediaan berbasis filter tanggal serta rentang jam.
2. **Alur Pemesanan & Validasi Jadwal:**
   * Pemilihan meja, durasi sewa, dan perhitungan estimasi total sewa serta nominal uang muka (DP).
   * Validasi *overlap schedule* untuk mencegah konflik jadwal.
3. **Penguncian Slot Sementara (*Temporary Hold*):**
   * Meja berstatus terkunci sementara (*Pending Payment*) selama 10–15 menit saat transaksi tagihan dibuat agar tidak diambil pengguna lain.
4. **Integrasi Payment Gateway Otomatis (Midtrans Sandbox / QRIS & VA):**
   * Pembuatan token transaksi otomatis untuk menampilkan popup/antarmuka pembayaran langsung di website.
   * Mendukung pembayaran instan via QRIS Dinamis dan Virtual Account.
5. **Webhook Notifikasi & Transisi Status Otomatis:**
   * *Endpoint* khusus penerima notifikasi HTTP POST dari payment gateway untuk mengubah status transaksi menjadi *Confirmed* secara instan setelah pembayaran sukses.
   * Pembatalan otomatis (*Cancelled/Expired*) jika pembayaran melewati batas waktu, sehingga meja kembali berstatus tersedia.
6. **Dashboard Manajemen Kasir/Admin:**
   * Antarmuka pemantauan status pesanan masuk, pencarian kode pemesanan/QR saat pelanggan *check-in*, dan pencatatan pelunasan sisa sewa di kasir.

---

### Tech Stack & Specifications
- **Frontend:** Laravel Blade + Tailwind CSS + Alpine.js
- **Backend:** Laravel 11 (PHP)
- **Database:** MySQL
- **ORM:** Eloquent ORM
- **Payment Gateway:** Midtrans (Snap.js & Notification Webhook)
- **Local Tunneling:** Ngrok (for Webhook testing)
- **Environment:** Use `.env.example` for database configuration and Midtrans API Keys (Merchant ID, Client Key, Server Key).

### Main Entities

1. **User**
   * Id
   * Name
   * Email
   * Password
   * Role (Admin/Student)
   * CreatedAt

2. **BilliardTable / Venue**
   * Id
   * Name
   * Status (Available/Maintenance)
   * PricePerHour
   * CreatedAt

3. **Booking**
   * Id
   * UserId
   * TableId
   * StartTime
   * EndTime
   * TotalPrice
   * DownPayment (30%)
   * Status (`pending`, `paid`, `cancelled`)
   * CreatedAt

4. **Payment**
   * Id
   * BookingId
   * MidtransOrderId
   * GrossAmount
   * PaymentType
   * TransactionStatus (`pending`, `settlement`, `expire`, `cancel`)
   * CreatedAt
   * UpdatedAt

### Backend Features

1. **CRUD Venues & Equipment**
   * Create, list, detail, update, delete sports venues/tables (Admin only).

2. **Smart Booking System**
   * Validate booking schedule (Operational hours: 08:00 - 23:00).
   * Prevent overlapping schedules for the same table.
   * Allow sequential (estafet) bookings without time gaps.
   * Automatically calculate total price based on duration.
   * Automatically calculate 30% Down Payment (DP).

3. **Midtrans Payment Integration**
   * Generate Snap Token for seamless frontend checkout pop-up.
   * Generate unique `OrderId` for every transaction.

4. **Automated Webhook Synchronization**
   * Endpoint to receive HTTP notifications from Midtrans.
   * Secure the endpoint by disabling CSRF specifically for `/webhook/midtrans`.
   * Validate incoming requests using `Signature Key` (SHA-512 hash of OrderId, StatusCode, GrossAmount, and ServerKey).
   * Automatically update `transaction_status` in the `payments` table.
   * Automatically sync and update the `status` in the `bookings` table (`pending` -> `paid` or `cancelled`).

### Frontend Pages

1. **Home / Venue Dashboard**
   * List of available billiard tables and sports equipment.
   * Show pricing and basic information.

2. **Booking Page**
   * Form to select date, start time, and end time.
   * Real-time calculation display for Total Price and 30% DP.
   * Show error messages if the schedule is already booked or invalid.

3. **Checkout & Payment**
   * Order summary details.
   * Button `Bayar Sekarang` triggering Midtrans Snap pop-up.
   * Auto-redirect to the user dashboard upon successful payment simulation.

4. **User Dashboard / Transaction History**
   * Table showing user's booking history.
   * Show current payment status (Pending, Paid, Cancelled).

### Required API & Web Routes

- `GET /` (Landing Page)
- `GET /dashboard` (User Dashboard)
- `GET /bookings/create` (Show Booking Form)
- `POST /bookings` (Store Booking & Generate Invoice)
- `GET /checkout/{Id}` (Checkout Page)
- `POST /webhook/midtrans` (Midtrans Notification Handler)

### Project Structure

- Built as a monolithic Laravel application.
- Structure:

```text
Rackly/
  app/
    Http/
      Controllers/ (BookingController, etc.)
    Models/ (Booking, Payment, BilliardTable, User)
  bootstrap/
    app.php (CSRF exclusions)
  config/
    midtrans.php
  database/
    migrations/
  resources/
    views/ (Blade templates)
  routes/
    web.php (Application & Webhook routes)

---

### Kriteria Aplikasi Dinyatakan Berhasil
* Sistem berhasil menolak reservasi baru jika jam yang dipilih beririsan (*overlap*) dengan reservasi yang sudah berstatus *Confirmed* atau *Pending Payment* aktif.
* Token transaksi dan pop-up pembayaran berhasil dimunculkan dengan nominal DP yang tepat.
* Webhook dari payment gateway berhasil memverifikasi *signature/hash key* dan memperbarui status pesanan di basis data dari `PENDING_PAYMENT` menjadi `CONFIRMED` dalam hitungan detik setelah transaksi sukses di simulator.
* Sistem secara otomatis mengembalikan status meja menjadi tersedia jika waktu tunggu pembayaran (15 menit) habis tanpa adanya konfirmasi pembayaran dari gateway.