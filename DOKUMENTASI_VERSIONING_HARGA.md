# 📋 Dokumentasi: Sistem Versioning Harga Perlengkapan

## 🎯 Tujuan
Fitur ini memungkinkan Anda menaikkan/mengubah harga perlengkapan **efektif langsung**, dengan tetap menjaga harga lama untuk pendaftar yang sudah ada.

---

## ⚙️ Bagaimana Sistem Bekerja

### 1. **Saat Anda Mengubah Harga**
Misalnya, Anda ingin menaikkan harga "Buku/Kitab" dari **Rp 300.000** menjadi **Rp 350.000**:

```
Admin Panel → Manage Item Pembayaran → Edit Item "Buku/Kitab"
Ubah nominal dari 300000 menjadi 350000 → Klik Update
```

Sistem otomatis akan:
- ✅ Menyimpan harga lama (300000) ke field `nominal_old`
- ✅ Mencatat tanggal perubahan di field `effective_date` (hari ini)
- ✅ Mengubah harga baru menjadi 350000

### 2. **Saat Calon Santri Mendaftar**
Sistem **otomatis mengecek tanggal pendaftaran** dan memilih harga yang tepat:

| Kondisi | Harga yang Dikenakan | Alasan |
|---------|-------|--------|
| Mendaftar **SEBELUM** tanggal perubahan | Rp 300.000 (nominal_old) | Menggunakan harga lama |
| Mendaftar **PADA ATAU SETELAH** tanggal perubahan | Rp 350.000 (nominal baru) | Menggunakan harga baru |

### 3. **Contoh Skenario Praktis**

**Skenario:** Anda ingin menaikkan harga pada **24 Juni 2026**

```
24 Juni 2026 - Pukul 14:00 → Admin mengubah harga "Seragam Pesantren" 
                              dari Rp 152.500 menjadi Rp 160.000
                              
Pendaftar Lama (sebelum jam 14:00):
└─ Tetap Rp 152.500

Pendaftar Baru (jam 14:00 ke atas):
└─ Dikenakan Rp 160.000
```

---

## 📊 Struktur Database

Kolom baru yang ditambahkan ke tabel `pembayaran_items`:

```sql
- nominal_old    : Menyimpan harga sebelum perubahan (nullable)
- effective_date : Tanggal mulai berlaku harga baru (nullable)
```

### Contoh Data di Database:

| ID | Nama | Nominal (Baru) | Nominal_Old | Effective_Date |
|---|---|---|---|---|
| 5 | Buku/Kitab | 350000 | 300000 | 2026-06-24 |
| 6 | Seragam Pesantren | 160000 | 152500 | 2026-06-24 |
| 7 | Seragam Pesantren + Kerudung | 202500 | NULL | NULL |

---

## 🔧 Cara Menggunakan Fitur

### Langkah 1: Buka Management Harga
```
Dashboard Admin → Pembayaran → Manage Item Pembayaran
```

### Langkah 2: Pilih Item yang Ingin Diubah Harganya
- Cari item perlengkapan yang ingin diubah (misal: "Buku/Kitab")
- Klik tombol **✏️ Edit**

### Langkah 3: Ubah Harga
```
Kolom Nominal (Rp) * 
[300000] → [350000]  ← Ubah ke harga baru
```

### Langkah 4: Klik Update
```
Klik tombol ✅ Update
```

**Sistem akan otomatis:**
- Menyimpan 300000 ke `nominal_old`
- Mencatat tanggal hari ini ke `effective_date`
- Menampilkan notifikasi: "✅ Item pembayaran berhasil diperbarui! Harga baru efektif untuk pendaftar baru mulai hari ini."

---

## ❓ FAQ

### Q: Bagaimana jika saya ingin mengembalikan harga lama?
**A:** Ubah nominal ke harga lama lagi. Sistem akan mencatat perubahan baru dan menjadikannya `effective_date` yang baru.

### Q: Berapa lama proses berlaku?
**A:** **Langsung efektif!** Tidak ada delay. Setiap pendaftar baru setelah Anda klik Update akan mendapat harga baru.

### Q: Apa yang terjadi dengan pendaftar yang sudah masuk?
**A:** 
- ✅ **Harga mereka tetap** menggunakan harga saat mereka mendaftar
- ✅ Tidak ada perubahan retroaktif pada invoice mereka
- ✅ Mereka sudah terdaftar di sistem dengan harga lama

### Q: Bisa ganti harga untuk item spesifik saja?
**A:** **YA!** Anda bisa mengubah harga per-item. Misalnya:
- Seragam Pesantren: Naik dari 152.500 → 160.000 ✅
- Seragam Olahraga: Tetap 175.000 (tidak diubah) ✅

### Q: Bagaimana dengan item yang tidak pernah diubah harganya?
**A:** Kolom `nominal_old` dan `effective_date` akan **kosong (NULL)**. Semua pendaftar akan dikenakan harga nominal yang sama.

---

## 🚀 Contoh Lengkap

### Skenario: Musim Masuk Tahun 2026

**Hari ke-1: Pembukaan Pendaftaran (17 Juni 2026)**
```
Item "Buku/Kitab" → Harga: Rp 300.000
Pendaftar: Ahmad, Siti, Budi, Ani
└─ Semuanya dikenakan Rp 300.000 ✅
```

**Hari ke-8: Ternyata Stok Terbatas, Harga Naik (24 Juni 2026, jam 14:00)**
```
Admin mengubah harga di sistem:
Rp 300.000 → Rp 350.000

Catatan Sistem:
- nominal_old = 300000
- effective_date = 2026-06-24
```

**Setelah Perubahan (24 Juni 2026, jam 15:00 ke atas)**
```
Pendaftar Ahmad (17 Juni)  → Invoice Rp 300.000 ✅
Pendaftar Siti (20 Juni)   → Invoice Rp 300.000 ✅
Pendaftar Budi (24 Juni pagi) → Invoice Rp 300.000 ✅
Pendaftar Ani (24 Juni sore) → Invoice Rp 350.000 ✅ (harga baru)
Pendaftar Doni (25 Juni)   → Invoice Rp 350.000 ✅ (harga baru)
```

---

## 📝 Catatan Teknis

### File yang Diubah:
1. **Migration:** `2026_06_24_000001_add_price_versioning_to_pembayaran_items_table.php`
   - Menambah kolom `nominal_old` dan `effective_date`

2. **Model:** `PembayaranItem.php`
   - Tambah method `getPriceForDate($date)` untuk logic versioning harga
   - Tambah casting untuk tipe data baru

3. **Controller:** `CalonSantriController.php`
   - Update method `store()` dan `update()` untuk menggunakan `getPriceForDate()`

4. **Controller:** `PembayaranItemController.php`
   - Update method `update()` untuk otomatis set `nominal_old` dan `effective_date`

5. **Views:** Tampilan management item sudah update untuk menampilkan info versioning

### Jalankan Migration:
```bash
php artisan migrate
```

---

## ⚠️ Penting!

- **Backup Database:** Selalu backup database sebelum running migration!
- **Testing:** Test dengan beberapa pendaftar dummy sebelum production
- **Tracking:** Sistem otomatis mencatat semua perubahan harga dengan timestamp

---

Untuk pertanyaan lebih lanjut, hubungi tim teknis! 🙌
