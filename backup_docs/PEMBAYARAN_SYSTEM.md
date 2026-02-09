# 💰 Sistem Pembayaran PSB SAZA - Dokumentasi Lengkap

## 📋 Overview

Sistem pembayaran komprehensif untuk PSB (Pendaftaran Santri Baru) SAZA dengan fitur:
- ✅ Management item pembayaran (wajib/optional, cicilan)
- ✅ Tracking pembayaran per santri
- ✅ History pembayaran lengkap
- ✅ Invoice profesional (print & PDF)
- ✅ Auto-generate pembayaran saat santri didaftar

---

## 📊 Database Schema

### 1. `pembayaran_items` - Master Item Pembayaran
```
- id (PK)
- nama (string) - Contoh: Pendaftaran, SPP, Seragam
- deskripsi (text)
- nominal (decimal) - Harga item
- is_required (boolean) - Wajib atau optional
- can_cicil (boolean) - Bisa dicicil atau tidak
- cicil_month (int) - Max bulan cicilan (1-12)
- status (enum: active/inactive)
- timestamps
```

### 2. `pembayarans` - Data Pembayaran per Santri
```
- id (PK)
- calon_santri_id (FK)
- status (enum: belum_bayar/cicilan/lunas)
- total_amount (decimal) - Total yang harus dibayar
- paid_amount (decimal) - Total yang sudah dibayar
- remaining_amount (decimal) - Sisa tagihan
- due_date (date) - Jatuh tempo
- timestamps
```

### 3. `pembayaran_records` - Riwayat Pembayaran
```
- id (PK)
- pembayaran_id (FK)
- payment_method (enum: cash/transfer/check)
- amount (decimal) - Jumlah pembayaran
- paid_at (datetime) - Waktu pembayaran
- notes (text) - Catatan pembayaran
- receipt_number (string) - Nomor kwitansi
- timestamps
```

---

## 🔧 Models & Relationships

### CalonSantri Model
```php
public function pembayaran()
{
    return $this->hasOne(Pembayaran::class);
}
```

### Pembayaran Model
```php
public function calonSantri()
{
    return $this->belongsTo(CalonSantri::class);
}

public function records()
{
    return $this->hasMany(PembayaranRecord::class);
}

public function updateStatus()
{
    // Auto update status based on paid amount
}
```

### PembayaranRecord Model
```php
public function pembayaran()
{
    return $this->belongsTo(Pembayaran::class);
}
```

---

## 🛣️ API Routes

### Admin Routes
```
// Item Pembayaran Management
GET    /admin/pembayaran-items              - List semua item
POST   /admin/pembayaran-items              - Create item baru
GET    /admin/pembayaran-items/create       - Form create
GET    /admin/pembayaran-items/{item}/edit  - Form edit
PUT    /admin/pembayaran-items/{item}       - Update item
DELETE /admin/pembayaran-items/{item}       - Delete item

// Pembayaran Management
GET    /admin/pembayaran                    - List pembayaran santri
GET    /admin/pembayaran/{pembayaran}       - Detail pembayaran
POST   /admin/pembayaran/{pembayaran}/payment - Input pembayaran
GET    /admin/pembayaran/{pembayaran}/invoice    - View invoice
GET    /admin/pembayaran/{pembayaran}/invoice-pdf - Download PDF
```

---

## 🎯 Workflow Penggunaan

### 1️⃣ Setup Awal - Create Item Pembayaran

Admin masuk ke menu **💳 Item Pembayaran** → **➕ Tambah Item**

**Contoh Item:**
- Pendaftaran: Rp 500.000 (Wajib, Tidak Cicil)
- Seragam: Rp 450.000 (Wajib, Cicil 3 bulan)
- SPP: Rp 400.000 (Wajib, Tidak Cicil)
- Asuransi: Rp 250.000 (Optional, Tidak Cicil)

**Field yang diisi:**
```
Nama Item      : Seragam Sekolah
Deskripsi      : Paket seragam (3 set)
Nominal        : 450000
Tipe           : Wajib ✓
Bisa Cicil     : ✓ (3 bulan)
```

### 2️⃣ Auto Create Pembayaran

Saat admin create calon santri baru, sistem automatically:
- ✅ Buat record di table `pembayarans`
- ✅ Calculate total dari semua item yang `is_required = true`
- ✅ Set status `belum_bayar`
- ✅ Set jatuh tempo 14 hari dari sekarang

**Contoh auto-generated:**
```
Calon Santri: Ahmad Rozi
Total Amount: 1.300.000 (500k + 450k + 350k)
Paid Amount:  0
Status:       Belum Bayar
```

### 3️⃣ Track Pembayaran

Admin buka menu **💰 Kelola Pembayaran** → Lihat list santri

**Tampilan list:**
```
No. Pendaftaran | Nama           | Total     | Sudah Bayar | Sisa  | Status
PSB-2026-00001  | Ahmad Rozi     | 1.3M      | 0           | 1.3M  | ❌ Belum
PSB-2026-00002  | Siti Aminah    | 1.3M      | 1.3M        | 0     | ✅ Lunas
```

### 4️⃣ Input Pembayaran

Admin klik santri → **👁️ Detail** → **💵 Input Pembayaran**

**Form input:**
```
Jumlah Pembayaran   : 500000 (Rp)
Metode Pembayaran   : Transfer Bank
Tanggal Pembayaran  : 18/01/2026
Nomor Kwitansi      : KWS-2026-001
Catatan             : Transfer dari Ayah (BCA)
```

**System akan:**
- ✅ Create record di `pembayaran_records`
- ✅ Update `paid_amount` (tambah 500k)
- ✅ Update `remaining_amount` (kurang 500k)
- ✅ Auto update `status` (jika belum lunas → cicilan)

### 5️⃣ View & Print Invoice

Admin klik santri → **📄 Lihat Invoice** (tab baru)

**Invoice berisi:**
- Data santri lengkap
- Total tagihan
- Riwayat pembayaran
- Sisa tagihan
- Instruksi pembayaran
- Print / Simpan PDF

---

## 📌 Fitur Detail

### Item Pembayaran
- **Wajib vs Optional**: Admin bisa tentukan apakah item wajib untuk semua santri
- **Cicilan**: Item cicil bisa dibayar bertahap (1-12 bulan)
- **Status**: Item bisa diaktif/nonaktif tanpa delete

### Pembayaran Tracking
- **Auto Status**: System otomatis update status (belum_bayar → cicilan → lunas)
- **Jatuh Tempo**: Deadline pembayaran default 14 hari
- **History Lengkap**: Semua transaksi tercatat

### Invoice
- **Professional Design**: Format standar invoice dengan logo
- **Print-Friendly**: Optimal untuk print ke kertas A4
- **PDF Export**: Bisa download sebagai PDF
- **Data Lengkap**: Nomor invoice, tanggal, detail santri, history

---

## 💻 Controllers

### PembayaranItemController
```php
- index()      : Lihat semua item
- create()     : Form create item
- store()      : Simpan item baru
- edit()       : Form edit item
- update()     : Update item
- destroy()    : Hapus item
```

### PembayaranController
```php
- index()              : List pembayaran santri
- show()               : Detail pembayaran 1 santri
- storePayment()       : Input pembayaran baru
- invoice()            : View invoice (HTML)
- invoicePdf()         : Download invoice (PDF)
```

---

## 🗂️ Views

```
resources/views/admin/pembayaran/
├── items/
│   ├── index.blade.php      - List item pembayaran
│   ├── create.blade.php     - Form create item
│   └── edit.blade.php       - Form edit item
├── index.blade.php          - List pembayaran santri
├── show.blade.php           - Detail pembayaran + input
└── invoice.blade.php        - Invoice template
```

---

## 🔄 Observer - Auto Create Pembayaran

**File**: `app/Observers/CalonSantriObserver.php`

```php
public function created(CalonSantri $calonSantri): void
{
    // Saat santri baru dibuat:
    // 1. Hitung total item yang required
    // 2. Create pembayaran record
    // 3. Set due date 14 hari ke depan
}

public function deleted(CalonSantri $calonSantri): void
{
    // Saat santri dihapus:
    // Delete related pembayaran records
}
```

---

## 📊 Seed Data

**File**: `database/seeders/PembayaranItemSeeder.php`

Pre-populated dengan 7 item contoh:
1. Biaya Pendaftaran: 500k (Wajib, Tidak Cicil)
2. Formulir & Tes: 200k (Wajib, Tidak Cicil)
3. Seragam: 450k (Wajib, Cicil 3 bulan)
4. Perlengkapan: 300k (Wajib, Cicil 3 bulan)
5. SPP Bulan 1: 400k (Wajib, Tidak Cicil)
6. Asuransi: 250k (Optional, Tidak Cicil)
7. Kegiatan: 200k (Optional, Cicil 6 bulan)

**Total Default:** 1.850.000 (kalau ambil wajib semua)

Run seeder:
```bash
php artisan db:seed --class=PembayaranItemSeeder
```

---

## 🎨 UI/UX Design

### Admin Dashboard Sidebar
```
📊 Dashboard
👥 Kelola Pendaftar
📋 Verifikasi Dokumen
💳 Item Pembayaran ← NEW
💰 Kelola Pembayaran ← NEW
```

### Item Pembayaran Page
- Tabel list item dengan badge (Wajib/Optional, Cicil status)
- Tombol Edit & Hapus setiap item
- Filter by status

### Pembayaran Page
- List santri dengan status pembayaran
- Progress bar untuk paid/remaining
- Quick actions: Detail, Invoice

### Detail Pembayaran Page
- Summary cards: Total | Sudah Bayar | Sisa | Status
- Form input pembayaran
- Table riwayat pembayaran
- Link ke invoice

---

## 📝 Contoh Skenario

### Scenario 1: Santri Bayar Cicilan
```
1. Admin input item pembayaran
2. Santri daftar (auto create pembayaran Rp 1.3M)
3. Santri bayar Rp 500k (tunai)
   → Status jadi "Cicilan", remaining Rp 800k
4. Santri bayar Rp 300k (transfer)
   → Remaining Rp 500k
5. Santri bayar Rp 500k (transfer)
   → Remaining 0, Status "Lunas"
```

### Scenario 2: Optional Items
```
Admin setup items:
- Seragam: Rp 450k (Wajib)
- Asuransi: Rp 250k (Optional)

Santri A: Ambil seragam → Total 450k
Santri B: Ambil seragam + asuransi → Total 700k
```

---

## ✨ Kelebihan Sistem Ini

✅ **Otomatis** - Auto create pembayaran saat santri didaftar
✅ **Fleksibel** - Bisa setup item apapun, wajib/optional, cicil/tunai
✅ **Transparent** - Santri bisa lihat riwayat pembayaran mereka
✅ **Professional** - Invoice formal & bisa print/PDF
✅ **Reliable** - History lengkap, audit trail jelas
✅ **Simple** - UI intuitif, mudah digunakan admin

---

## 🔐 Security

- ✅ Access hanya untuk admin (role-based)
- ✅ Soft delete untuk data history
- ✅ Validation di controller & model
- ✅ Audit trail via records table

---

## 🚀 Fitur Implementasi Lanjutan (Future)

- [ ] Payment gateway integration (Midtrans, iPaymu)
- [ ] Reminder email untuk pembayaran belum lunas
- [ ] Dashboard analytics & reports
- [ ] Excel export pembayaran
- [ ] Dunning/late payment penalties
- [ ] Mobile app untuk santri track pembayaran

---

**Created**: 18 Januari 2026
**Version**: 1.0
**Status**: ✅ Production Ready
