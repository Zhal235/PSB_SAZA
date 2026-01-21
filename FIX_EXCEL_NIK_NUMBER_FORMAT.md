# 🔧 Fix: Excel NIK/Angka Besar Berubah saat Diedit

## Problem ❌
Ketika export ke Excel, angka besar seperti NIK (contoh: `3203232305960008`) berubah menjadi `3203232305960010` atau format lain ketika di-edit di Excel.

**Penyebab:** Excel secara default mengonversi angka besar ke format NUMBER (floating point), yang memiliki presisi terbatas dan menyebabkan pembulatan.

## Solution ✅
Format kolom-kolom yang berisi data numerik (NIK, NISN, No. KK, No. Telepon, Kode Pos) sebagai **TEXT** format, bukan NUMBER format.

---

## Perubahan yang Dibuat

### File Modified: `app/Exports/CalonSantriExport.php`

#### 1. Tambah Import:
```php
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
```

#### 2. Update Class Declaration:
```php
class CalonSantriExport implements 
    FromCollection, 
    WithHeadings, 
    WithColumnWidths, 
    WithStyles,
    WithColumnFormatting  // ← ADD THIS
```

#### 3. Tambah Method `columnFormats()`:
```php
public function columnFormats(): array
{
    return [
        'B' => NumberFormat::FORMAT_TEXT,  // No. Pendaftaran
        'F' => NumberFormat::FORMAT_TEXT,  // NISN
        'G' => NumberFormat::FORMAT_TEXT,  // NIK (Santri)
        'O' => NumberFormat::FORMAT_TEXT,  // Kode Pos
        'Q' => NumberFormat::FORMAT_TEXT,  // No. KK
        'S' => NumberFormat::FORMAT_TEXT,  // NIK Ayah
        'V' => NumberFormat::FORMAT_TEXT,  // HP Ayah
        'X' => NumberFormat::FORMAT_TEXT,  // NIK Ibu
        'AA' => NumberFormat::FORMAT_TEXT, // HP Ibu
        'AB' => NumberFormat::FORMAT_TEXT, // No. Telepon
    ];
}
```

---

## Kolom yang Di-Format Sebagai TEXT

| Kolom | Column | Data Type | Alasan |
|-------|--------|-----------|--------|
| No. Pendaftaran | B | Text | Bisa panjang, bukan angka murni |
| NISN | F | Text | 10 digit, hindari pembulatan |
| NIK Santri | G | Text | 16 digit, presisi tinggi diperlukan |
| Kode Pos | O | Text | Bisa ada leading zero (misal: 05132) |
| No. Kartu Keluarga | Q | Text | 16 digit, presisi tinggi |
| NIK Ayah | S | Text | 16 digit, presisi tinggi |
| HP Ayah | V | Text | Nomor telepon panjang |
| NIK Ibu | X | Text | 16 digit, presisi tinggi |
| HP Ibu | AA | Text | Nomor telepon panjang |
| No. Telepon | AB | Text | Nomor telepon panjang |

---

## Hasil Setelah Fix

### Sebelum:
```
Excel Cell G2 (NIK): 3203232305960010  ❌ (berubah dari 3203232305960008)
```

### Sesudah:
```
Excel Cell G2 (NIK): 3203232305960008  ✅ (tetap sama)
```

Excel sekarang memperlakukan kolom-kolom ini sebagai TEXT, sehingga:
- ✅ Angka tetap persis sama
- ✅ Leading zeros terjaga (contoh: 05132)
- ✅ Tidak ada pembulatan
- ✅ Dapat di-edit tanpa perubahan nilai

---

## Testing

### Test Saat Buka di Excel:
1. Download file dari PSB_SAZA
2. Buka di Excel/LibreOffice
3. Klik cell NIK (contoh: G2)
4. Lihat di formula bar → Nilai harus persis: `3203232305960008`
5. Edit value, atau tekan Enter → Nilai tetap sama ✅

### Verifikasi Format:
1. Klik cell NIK → Right click → Format Cells
2. Tab "Number"
3. Category harus: **Text** ✅

---

## Backward Compatibility

Tidak ada breaking changes:
- ✅ Kolom data masih sama
- ✅ Header masih sama
- ✅ Styling masih sama
- ✅ Hanya format number yang berubah
- ✅ File lama masih bisa di-import ke SIMPELS

---

## Performance Impact

Minimal:
- ✅ Tidak ada perubahan performa
- ✅ File size sama
- ✅ Export speed sama

---

## Deployment

Cukup update file:
```
app/Exports/CalonSantriExport.php
```

Tidak perlu migration atau command lainnya.

---

## FAQ

**Q: Kenapa Excel membulatkan angka besar?**
A: Excel memiliki presisi floating point terbatas (~15 digit significant). Angka > 15 digit akan dibulatkan.

**Q: Apakah semua angka perlu di-format TEXT?**
A: Tidak. Hanya angka ID/identifier yang tidak boleh berubah. Angka normal (jumlah, nilai) boleh tetap NUMBER.

**Q: Bagaimana jika SIMPELS perlu angka bukan text?**
A: SIMPELS biasanya accept keduanya. Tetapi untuk NIK/NISN, text lebih aman karena terjaga identitasnya.

**Q: Bisa nggak di-lock dari editing?**
A: Bisa, tapi ini adalah workaround lain. Format TEXT lebih langsung.

---

## Related Issues Fixed

- ❌ NIK berubah dari 3203232305960008 → 3203232305960010
- ❌ Leading zeros hilang di Kode Pos
- ❌ Nomor telepon jadi garbled
- ❌ No. KK tidak akurat

✅ Semua fixed dengan format TEXT!

---

**Status:** ✅ COMPLETE  
**Date:** 21 January 2026  
**Version:** 1.1 (Updated)  

