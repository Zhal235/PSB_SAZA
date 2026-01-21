# Fitur Kaitkan Akun Calon Santri ke Nomor HP Orang Tua

## Deskripsi
Fitur ini memungkinkan admin untuk otomatis membuat dan kaitkan akun calon santri ke salah satu nomor HP ayah atau ibu dengan password default **12345678**.

## Cara Kerja

### 1. Saat Admin Menginput Data Calon Santri (Create)
- Admin mengisi form "Tambah Calon Santri Baru"
- Di bagian **"🔗 Kaitkan Akun (Nomor HP Orang Tua)"**, admin bisa memilih:
  - **📱 Nomor HP Ayah** - Akun terkait ke nomor HP ayah
  - **📱 Nomor HP Ibu** - Akun terkait ke nomor HP ibu
  - **❌ Tidak Dikaitkan** - Otomatis ke HP Ayah jika ada, fallback ke HP Ibu

### 2. User Baru Dibuat Otomatis
Sistem akan secara otomatis:
- Membuat user baru dengan:
  - **Nama**: Sesuai nama santri
  - **Phone**: Nomor HP yang dipilih (ayah/ibu)
  - **Email**: `nama.santri@psb-saza.local`
  - **Password**: `12345678` (hashed)
  - **Role**: `calon_santri`
  - **Jenjang**: Sesuai jenjang yang dipilih

### 3. Relasi Database
- `calon_santris` table memiliki 2 field baru:
  - `phone_type` (enum: 'ayah', 'ibu') - Mencatat HP siapa yang dipakai
  - `user_id` (foreign key) - Referensi ke tabel users

## Database Schema

```sql
ALTER TABLE calon_santris ADD COLUMN phone_type ENUM('ayah', 'ibu') NULL AFTER no_telp;
ALTER TABLE calon_santris ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER phone_type;
ALTER TABLE calon_santris ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
```

## Perubahan File

### 1. **Model: `app/Models/CalonSantri.php`**
   - Tambah field ke `$fillable`: `'phone_type'`, `'user_id'`
   - Update relasi `user()`: Dari `belongsTo(User::class, 'no_telp', 'phone')` menjadi `belongsTo(User::class)`

### 2. **Model: `app/Models/User.php`**
   - Tidak perlu perubahan, sudah support relasi

### 3. **Controller: `app/Http/Controllers/CalonSantriController.php`**
   - **Method `store()`**: 
     - Tambah validasi untuk `phone_type`
     - Logic untuk menentukan nomor HP mana yang akan dipakai
     - Membuat user otomatis dengan `Hash::make('12345678')`
     - Assign `user_id` ke calon santri
   
   - **Method `update()`**:
     - Tambah validasi untuk `phone_type`
     - Support untuk mengubah phone_type (misal dari ayah ke ibu)
     - Update atau buat user baru jika nomor HP berubah

### 4. **View: `resources/views/admin/calon-santri/create.blade.php`**
   - Tambah bagian **"🔗 Kaitkan Akun (Nomor HP Orang Tua)"** 
   - Radio button untuk memilih: Ayah / Ibu / Tidak Dikaitkan

### 5. **View: `resources/views/admin/calon-santri/edit.blade.php`**
   - Tambah bagian **"🔗 Kaitkan Akun (Nomor HP Orang Tua)"**
   - Tampilkan status akun yang sudah terkait
   - Radio button untuk mengubah phone_type

### 6. **Migration: `database/migrations/2026_01_21_063516_add_phone_type_to_calon_santris_table.php`**
   - Tambah field `phone_type` dan `user_id` ke tabel `calon_santris`

## Flow Logic di Controller

### Create Flow:
```
1. Validasi input form (termasuk phone_type)
2. Generate nomor pendaftaran otomatis
3. Tentukan nomor HP mana yang akan dipakai:
   - Jika phone_type === 'ayah' dan hp_ayah ada → gunakan hp_ayah
   - Jika phone_type === 'ibu' dan hp_ibu ada → gunakan hp_ibu
   - Jika tidak dipilih:
     - Jika hp_ayah ada → gunakan hp_ayah, set phone_type='ayah'
     - Jika hp_ayah tidak ada tapi hp_ibu ada → gunakan hp_ibu, set phone_type='ibu'
4. Create/Update user dengan nomor HP tersebut:
   - updateOrCreate(['phone' => $phoneToUse], [...])
   - Password: Hash::make('12345678')
5. Assign user_id ke calon santri
6. Create pembayaran record
7. Redirect dengan success message berisi nomor HP yang dikaitkan
```

### Update Flow:
```
1. Validasi input form (termasuk phone_type baru)
2. Tentukan nomor HP mana yang akan dipakai (logic sama seperti create)
3. Update/Create user dengan phone_type baru
4. Update calon santri dengan data baru
5. Redirect dengan success message
```

## Contoh Usage

### Scenario 1: Admin Input Calon Santri dengan Memilih HP Ayah
```
- Nama: Ahmad Ridho
- HP Ayah: 08123456789
- HP Ibu: 08987654321
- Phone Type Dipilih: Ayah

Result:
- User dibuat dengan phone=08123456789, password=12345678
- calon_santri.phone_type = 'ayah'
- calon_santri.user_id = ID user yang baru dibuat
- Success message: "Akun sudah dibuat dengan HP: 08123456789 dan password default: 12345678"
```

### Scenario 2: Admin Ubah Akun dari HP Ayah ke HP Ibu
```
- Existing: phone_type='ayah', user.phone=08123456789
- Updated: phone_type='ibu', hp_ibu=08987654321

Result:
- User dengan phone=08987654321 dibuat/diupdate
- calon_santri.phone_type = 'ibu'
- calon_santri.user_id = ID user baru
- Success message: "Data calon santri berhasil diperbarui!"
```

## Testing

### Test Case 1: Create dengan HP Ayah
1. Buka form "Tambah Calon Santri"
2. Isi semua field termasuk HP Ayah
3. Pilih "Nomor HP Ayah" pada bagian kaitkan akun
4. Submit form
5. Verifikasi: User dibuat, phone=HP Ayah, password=12345678

### Test Case 2: Edit dan Ubah ke HP Ibu
1. Buka form edit calon santri
2. Ubah phone_type ke "Nomor HP Ibu"
3. Submit form
4. Verifikasi: User baru dibuat dengan HP Ibu, relasi ter-update

### Test Case 3: Create Tanpa Memilih (Auto-default)
1. Isi form dengan HP Ayah saja
2. Tidak memilih phone_type (default kosong)
3. Submit form
4. Verifikasi: Otomatis terkait ke HP Ayah

## Security Notes

- Password default `12345678` harus di-hash menggunakan `Hash::make()`
- User disarankan untuk mengganti password saat pertama kali login
- Email otomatis di-generate dari nama santri, pastikan tidak ada duplikasi

## Future Enhancements

- [ ] Notification email ke orang tua dengan informasi login
- [ ] Force password change pada login pertama
- [ ] SMS notification dengan credentials
- [ ] Audit log untuk tracking siapa yang membuat/edit akun

