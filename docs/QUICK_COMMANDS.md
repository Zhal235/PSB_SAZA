# ⚡ Quick Command Reference

## View Commands

```bash
# Lihat semua permission (22 permissions)
php artisan permission:list

# Lihat permission untuk role tertentu
php artisan role:show-permissions admin
php artisan role:show-permissions petugas_pendaftaran
php artisan role:show-permissions calon_santri

# Lihat semua role dan permissions
php artisan role:show-permissions

# Lihat user dan role mereka
php artisan user:list
```

## Management Commands

```bash
# TAMBAH permission ke role
php artisan role:manage add {role} {permission}
php artisan role:manage add petugas_pendaftaran delete-calon-santri
php artisan role:manage add admin export-calon-santri

# HAPUS permission dari role
php artisan role:manage remove {role} {permission}
php artisan role:manage remove petugas_pendaftaran view-dokumen

# LIST permission untuk role
php artisan role:manage list petugas_keuangan
php artisan role:manage list calon_santri

# RESET semua permission role (HATI-HATI!)
php artisan role:manage reset admin
```

## Setup Commands

```bash
# Setup ulang default permissions
php artisan role:setup-permissions
```

---

## Common Scenarios

### Scenario 1: Petugas Pendaftaran butuh bisa export calon santri
```bash
php artisan role:manage add petugas_pendaftaran export-calon-santri
php artisan role:show-permissions petugas_pendaftaran
```

### Scenario 2: Petugas Pendaftaran butuh bisa delete calon santri
```bash
php artisan role:manage add petugas_pendaftaran delete-calon-santri
php artisan role:show-permissions petugas_pendaftaran
```

### Scenario 3: Cabut akses verify-dokumen dari petugas_pendaftaran
```bash
php artisan role:manage remove petugas_pendaftaran verify-dokumen
php artisan role:show-permissions petugas_pendaftaran
```

### Scenario 4: Buat user baru dengan role petugas_keuangan
```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES ('Nama Petugas', 'email@example.com', 'hashed_password', 'petugas_keuangan', NOW(), NOW());
```

### Scenario 5: Ubah role user dari calon_santri ke santri
```sql
UPDATE users SET role = 'santri' WHERE id = 2;
```

---

## URL Akses Berdasarkan Role

| Role | URL Dashboard | Routes Prefix |
|------|---------------|----------------|
| admin | /admin/dashboard | /admin/ |
| calon_santri | /santri/dashboard | /santri/ |
| santri | /santri/dashboard | /santri/ |
| petugas_pendaftaran | /petugas/dashboard | /petugas/ |
| petugas_keuangan | /petugas/dashboard | /petugas/ |

---

## Permissions by Category

### Dashboard (1)
- view-dashboard

### Calon Santri (5)
- view-calon-santri
- create-calon-santri
- edit-calon-santri
- delete-calon-santri
- export-calon-santri

### Pembayaran (3)
- view-pembayaran
- create-pembayaran
- verify-pembayaran

### Dokumen (3)
- view-dokumen
- upload-dokumen
- verify-dokumen

### Settings (4)
- view-bank-settings
- edit-bank-settings
- view-pembayaran-items
- manage-pembayaran-items

### Financial (2)
- view-financial-records
- create-financial-records

### Users (4)
- view-users
- create-users
- edit-users
- delete-users

---

## Tips

1. **Admin** secara otomatis punya SEMUA permissions
2. Permission bersifat **instant**, tidak perlu restart
3. Gunakan `php artisan role:show-permissions` untuk verify perubahan
4. Backup database sebelum bulk changes
5. Test dengan akun user sebelum apply ke production

---

## Emergency Commands

```bash
# Jika permission berantakan, reset ke default
php artisan role:setup-permissions

# Clear cache jika ada issue
php artisan cache:clear

# Refresh database (DESTRUCTIVE!)
php artisan migrate:refresh --seed
```