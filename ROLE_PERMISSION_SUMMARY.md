# 🔐 Sistem Role & Permission - Ringkasan

**Tanggal**: 21 Januari 2026

## ✅ Apa yang Sudah Disetup

### Database Tables
- ✅ `permissions` - Menyimpan semua permission yang tersedia
- ✅ `role_permissions` - Mapping antara role dan permissions

### Total Permissions: 22
Terbagi dalam 7 kategori:
- Dashboard (1)
- Calon Santri (5)
- Pembayaran (3)
- Dokumen (3)
- Settings/Bank (4)
- Financial (2)
- Users (4)

### Role yang Tersedia: 5
1. **admin** - 22 permissions (semua)
2. **calon_santri** - 3 permissions
3. **santri** - 3 permissions
4. **petugas_pendaftaran** - 7 permissions
5. **petugas_keuangan** - 6 permissions

---

## 📊 Role & Permissions Table

| Role | Dashboard | Calon Santri | Pembayaran | Dokumen | Settings | Financial | Users |
|------|-----------|--------------|-----------|---------|----------|-----------|-------|
| admin | ✓ | Create, Edit, Delete, Export, View | Create, Verify, View | Upload, Verify, View | View, Edit, Manage | Create, View | Create, Edit, Delete, View |
| calon_santri | ✓ | - | View | Upload | - | - | - |
| santri | ✓ | - | View | Upload | - | - | - |
| petugas_pendaftaran | ✓ | Create, Edit, View | - | Upload, Verify, View | - | - | - |
| petugas_keuangan | ✓ | - | Create, Verify, View | - | - | Create, View | - |

---

## 🛠️ Command yang Tersedia

### View Commands
```bash
# Lihat semua permission
php artisan permission:list

# Lihat permission untuk suatu role
php artisan role:show-permissions {role}

# Lihat semua role dan permissions mereka
php artisan role:show-permissions
```

### Management Commands
```bash
# Tambah permission ke role
php artisan role:manage add {role} {permission}

# Hapus permission dari role
php artisan role:manage remove {role} {permission}

# List permission yang dimiliki role
php artisan role:manage list {role}

# Reset semua permission role (hati-hati!)
php artisan role:manage reset {role}
```

### Setup Commands
```bash
# Setup ulang default permissions untuk semua role
php artisan role:setup-permissions
```

---

## 💡 Contoh Penggunaan

### Tambah permission ke petugas_pendaftaran
```bash
php artisan role:manage add petugas_pendaftaran export-calon-santri
```

### Hapus permission dari petugas_keuangan
```bash
php artisan role:manage remove petugas_keuangan view-pembayaran
```

### Lihat semua permission yang tersedia
```bash
php artisan permission:list
```

### Lihat permission untuk admin
```bash
php artisan role:show-permissions admin
```

---

## 🔍 Check Permission di Code

### Di Controller
```php
// Check single permission
if (auth()->user()->hasPermission('view-calon-santri')) {
    // Tampilkan calon santri
}

// Check multiple permissions
if (auth()->user()->hasAnyPermission(['create-calon-santri', 'edit-calon-santri'])) {
    // Tampilkan form
}
```

### Di View (Blade)
```blade
@if (auth()->user()->hasPermission('view-calon-santri'))
    <a href="/admin/calon-santri">Calon Santri</a>
@endif
```

---

## 🚀 Cara Menambah Role Baru

1. **Tambah role ke enum di migration**
   - Edit: `database/migrations/2026_01_21_142514_update_user_roles.php`
   - Tambahkan role di enum: `['admin', 'role_baru', ...]`

2. **Update SetupRolePermissions command**
   - Edit: `app/Console/Commands/SetupRolePermissions.php`
   - Tambahkan assignment untuk role baru

3. **Jalankan setup**
   ```bash
   php artisan migrate --refresh
   php artisan role:setup-permissions
   ```

---

## ⚠️ Catatan Penting

- **Admin** otomatis punya semua permission
- **Reset permission** akan menghapus SEMUA permission dari role - hati-hati!
- Perubahan permission **instant**, tidak perlu restart server
- Permission bersifat **role-based**, bukan per-user

---

## 📁 File yang Dibuat/Diubah

**Migrations:**
- `2026_01_21_144441_create_permissions_table.php`
- `2026_01_21_144443_create_role_permissions_table.php`

**Models:**
- `app/Models/Permission.php`
- `app/Models/User.php` (updated dengan hasPermission methods)

**Commands:**
- `app/Console/Commands/PermissionSeeder.php`
- `app/Console/Commands/SetupRolePermissions.php`
- `app/Console/Commands/ShowRolePermissions.php`
- `app/Console/Commands/ManageRolePermission.php`
- `app/Console/Commands/ListPermissions.php`

**Routes:**
- `routes/web.php` (updated dengan petugas routes)

**Middleware:**
- `app/Http/Middleware/CheckRole.php` (updated untuk support multiple roles)

---

## 📞 Support

Jika ada pertanyaan atau butuh menambah permission baru, gunakan command:
```bash
php artisan permission:list
php artisan role:show-permissions
```