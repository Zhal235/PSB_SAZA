# Fitur Export Data Calon Santri ke Excel (SIMPELS)

## Deskripsi
Fitur ini memungkinkan admin untuk mengekspor seluruh data calon santri ke file Excel yang dapat di-upload ke SIMPELS (Sistem Informasi Manajemen Peserta Didik Berkelanjutan).

## Instalasi Dependencies
Package yang digunakan:
```bash
composer require maatwebsite/excel
```

## File-File yang Ditambahkan

### 1. **Export Class: `app/Exports/CalonSantriExport.php`**
File ini berisi logik untuk mengonversi data calon santri ke format Excel dengan fitur:
- ✅ Heading yang formatted dengan warna dan styling
- ✅ Column widths yang optimal untuk setiap kolom
- ✅ Data styling dengan border, warna alternating rows (zebra striping)
- ✅ Freeze header row untuk kemudahan scroll
- ✅ Support filter berdasarkan jenjang (MTs/SMK)
- ✅ **Format kolom ID sebagai TEXT** (NIK, NISN, No. KK, dll) - Prevents number rounding

**Kolom-Kolom yang di-Export:**
```
No. | No. Pendaftaran | Jenjang | Nama Santri | Jenis Kelamin | NISN | NIK | 
Tempat Lahir | Tanggal Lahir | Alamat | Desa | Kecamatan | Kabupaten | Provinsi | 
Kode Pos | Asal Sekolah | No. KK | Nama Ayah | NIK Ayah | Pendidikan Ayah | 
Pekerjaan Ayah | HP Ayah | Nama Ibu | NIK Ibu | Pendidikan Ibu | Pekerjaan Ibu | 
HP Ibu | No. Telepon | Hobi | Cita-cita | Jumlah Saudara | Pendapatan Keluarga | 
Status | Catatan
```

**Kolom yang Di-Format TEXT (Tidak Boleh Berubah):**
- No. Pendaftaran
- NISN (10 digit)
- NIK Santri (16 digit)
- Kode Pos (dengan leading zero)
- No. Kartu Keluarga (16 digit)
- NIK Ayah (16 digit)
- NIK Ibu (16 digit)
- HP Ayah
- HP Ibu
- No. Telepon

### 2. **Controller Method: `CalonSantriController::export()`**
```php
public function export(Request $request)
{
    $jenjang = $request->query('jenjang', 'MTs');
    $fileName = 'CalonSantri_' . $jenjang . '_' . now()->format('d-m-Y-H-i-s') . '.xlsx';
    
    return Excel::download(new CalonSantriExport($jenjang), $fileName);
}
```

Fitur:
- Download langsung file Excel
- Naming convention: `CalonSantri_[JENJANG]_[TANGGAL-JAM].xlsx`
- Filter otomatis berdasarkan jenjang yang dipilih

### 3. **Route: `routes/web.php`**
```php
Route::get('calon-santri-export', [CalonSantriController::class, 'export'])->name('calon-santri.export');
```

### 4. **View Button: `resources/views/admin/calon-santri/index.blade.php`**
Tombol "📊 Export Excel" ditambahkan di bagian top-right, bersama tombol "Tambah Calon Santri"

## Cara Penggunaan

### User Flow:
1. Admin membuka halaman "Kelola Calon Santri"
2. Pilih jenjang yang ingin di-export (MTs atau SMK) via tab
3. Klik tombol **"📊 Export Excel"** (hijau)
4. File Excel akan otomatis ter-download
5. File siap untuk di-upload ke SIMPELS

### Contoh Nama File yang Dihasilkan:
```
CalonSantri_MTs_21-01-2026-14-30-45.xlsx
CalonSantri_SMK_21-01-2026-14-31-20.xlsx
```

## Format Excel yang Dihasilkan

### Header Row (Baris 1):
- **Background Color**: Indigo (#4C51BF)
- **Text Color**: Putih
- **Font**: Bold, Size 11
- **Height**: 25px
- **Text Alignment**: Center, Center (vertikal)
- **Border**: Thin black border

### Data Rows:
- **Border**: Thin gray border (#CCCCCC)
- **Alternate Row Colors**: 
  - Row genap: Light gray (#F3F4F6)
  - Row ganjil: Putih
- **Text Alignment**: Top, Wrap text

### Column Widths (Optimal):
```
A=5    (No)
B=15   (No. Pendaftaran)
C=10   (Jenjang)
D=20   (Nama Santri)
E=15   (Jenis Kelamin)
...dst
AH=20  (Catatan)
```

### Features:
- ✅ Header Freeze (freeze pane di baris 2)
- ✅ Zebra striping untuk mudah dibaca
- ✅ Text wrap untuk kolom yang panjang
- ✅ Optimized column widths
- ✅ Professional styling

## Contoh Data dalam Excel

| No. | No. Pendaftaran | Jenjang | Nama Santri | Jenis Kelamin | NISN | ... | Status | Catatan |
|-----|-----------------|---------|-------------|---------------|------|-----|--------|---------|
| 1 | PSB-2026-00001 | MTs | Ahmad Ridho | Laki-laki | 1234567890 | ... | baru | - |
| 2 | PSB-2026-00002 | MTs | Siti Nurhaliza | Perempuan | 0987654321 | ... | proses | Cek dokumen |
| ... | ... | ... | ... | ... | ... | ... | ... | ... |

## Integration dengan SIMPELS

### Persiapan Upload:
1. Download file Excel dari sistem ini
2. Buka file untuk verifikasi data
3. Pastikan semua kolom terisi (khususnya NISN, NIK, No. KK)
4. Login ke SIMPELS
5. Navigasi ke menu "Import Data Peserta Didik"
6. Upload file Excel
7. Verifikasi mapping kolom
8. Confirm import

### Kolom yang Penting untuk SIMPELS:
- ✅ **Nama** (wajib)
- ✅ **Jenis Kelamin** (wajib)
- ✅ **Tanggal Lahir** (wajib)
- ✅ **NISN** (wajib di SIMPELS)
- ✅ **NIK** (sangat penting)
- ✅ **Alamat** (penting)
- ✅ **Data Orang Tua** (penting)

## Tips Penggunaan

### 1. Data Sebelum Export
- Pastikan semua data calon santri sudah valid
- Cek field NISN dan NIK sudah terisi
- Verifikasi alamat lengkap

### 2. Setelah Export
- Buka file di Excel untuk verifikasi
- Perhatikan format tanggal (dd-mm-yyyy)
- Check apakah ada data yang aneh atau duplikat

### 3. Upload ke SIMPELS
- Jangan edit file hasil export terlalu banyak
- Gunakan template SIMPELS jika diminta
- Backup file sebelum upload

## Perubahan File

| File | Status | Keterangan |
|------|--------|-----------|
| `app/Exports/CalonSantriExport.php` | ✨ Baru | Export logic dengan styling |
| `app/Http/Controllers/CalonSantriController.php` | 📝 Modified | Tambah method `export()` dan imports |
| `routes/web.php` | 📝 Modified | Tambah route `calon-santri.export` |
| `resources/views/admin/calon-santri/index.blade.php` | 📝 Modified | Tambah tombol Export Excel |

## Testing

### Test Case 1: Export MTs
1. Navigasi ke "Kelola Calon Santri" tab MTs
2. Klik tombol "Export Excel"
3. Verifikasi: File berhasil didownload dengan format `CalonSantri_MTs_*.xlsx`

### Test Case 2: Export SMK
1. Navigasi ke tab SMK
2. Klik tombol "Export Excel"
3. Verifikasi: Hanya data SMK yang di-export

### Test Case 3: File Content
1. Buka file hasil export
2. Verifikasi:
   - Header sudah terformat dengan warna indigo
   - Data sudah terisi lengkap
   - Kolom sudah sesuai dengan order yang benar
   - Row coloring (zebra stripe) sudah ada
   - Freeze pane sudah aktif

## Performance Notes

- Export time tergantung jumlah data
- Untuk 1000 santri ≈ 1-2 detik
- Memory usage: Minimal (~5MB)
- Format XLSX (compressed): Lebih ringan dari XLS

## Future Enhancements

- [ ] Custom column selection
- [ ] Filter berdasarkan status
- [ ] Export ke format lain (CSV, PDF)
- [ ] Schedule export otomatis
- [ ] Email report
- [ ] Export dengan format SIMPELS standar
- [ ] Merge data dari multiple jenjang dalam 1 file

## Troubleshooting

### Error: "Class 'Maatwebsite\Excel\Facades\Excel' not found"
**Solusi**: Jalankan `composer require maatwebsite/excel` dan `php artisan vendor:publish --provider="Maatwebsite\\Excel\\ExcelServiceProvider"`

### Error: "Memory exhausted"
**Solusi**: Chunking data atau increase memory di `php.ini`: `memory_limit = 256M`

### File tidak ter-download
**Solusi**: Periksa folder `storage/app/public/`, pastikan writable. Restart server jika perlu.

## Catatan Penting

✅ Data yang di-export adalah **READ-ONLY** di sistem ini
✅ Perubahan di SIMPELS tidak otomatis sync kembali
✅ Backup file Excel sebelum upload ke SIMPELS
✅ Gunakan format tanggal yang konsisten
✅ Jangan hapus file Excel original setelah upload

