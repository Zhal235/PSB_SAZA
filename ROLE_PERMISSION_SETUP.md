# 🔐 Role & Permission Management System - COMPLETE

## 📋 Overview

Sistem manajemen role dan permission yang lengkap dan fleksibel untuk PSB SAZA.

**Fitur:**
- ✅ 5 Role yang sudah didefinisikan
- ✅ 22 Permission untuk berbagai fitur
- ✅ Management commands untuk add/remove permission
- ✅ Flexible routing berdasarkan role
- ✅ User model dengan permission check methods

---

## 🎯 Role & Default Permissions

### 1. **ADMIN** (22 permissions - ALL)
**Dashboard:** http://localhost:8000/admin/dashboard
- Akses penuh ke semua fitur
- Dapat mengelola users, calon santri, pembayaran, dokumen, settings

### 2. **CALON_SANTRI** (3 permissions)
**Dashboard:** http://localhost:8000/santri/dashboard
- `view-dashboard`
- `view-pembayaran`
- `upload-dokumen`

**Users:**
- Ahmad Ridho (ahmad@example.com)
- Siti Nurhaliza (siti@example.com)
- Muhammad Rizal (081234567895@santri.local)
- BABAY (081234567894@santri.local)

### 3. **SANTRI** (3 permissions - SAMA DENGAN CALON_SANTRI)
**Dashboard:** http://localhost:8000/santri/dashboard
- `view-dashboard`
- `view-pembayaran`
- `upload-dokumen`

**Users:**
- GERI NURDIANSYAH (geri.nurdiansyah@psb-saza.local)

### 4. **PETUGAS_PENDAFTARAN** (8 permissions)
**Dashboard:** http://localhost:8000/petugas/dashboard
- `view-dashboard`
- `view-calon-santri`
- `create-calon-santri`
- `edit-calon-santri`
- `view-dokumen`
- `upload-dokumen`
- `verify-dokumen`
- (bisa ditambah: export-calon-santri)

**Users:**
- Ahmad Saleh Nurjaman (soleh@saza.sch.id)

### 5. **PETUGAS_KEUANGAN** (6 permissions)
**Dashboard:** http://localhost:8000/petugas/dashboard
- `view-dashboard`
- `view-pembayaran`
- `create-pembayaran`
- `verify-pembayaran`
- `view-financial-records`
- `create-financial-records`

**Belum ada user dengan role ini, bisa dibuat nanti**

---

## 🛠️ Command Reference

### 📖 View Permissions

```bash
# List semua permission yang tersedia (22 permissions)
php artisan permission:list

# Show permissions untuk role tertentu
php artisan role:show-permissions admin
php artisan role:show-permissions petugas_pendaftaran
php artisan role:show-permissions calon_santri

# Show semua role dan permissions mereka
php artisan role:show-permissions
```

### ➕ Add Permission ke Role

```bash
# Tambah export-calon-santri ke petugas_pendaftaran
php artisan role:manage add petugas_pendaftaran export-calon-santri

# Tambah delete-calon-santri ke petugas_pendaftaran
php artisan role:manage add petugas_pendaftaran delete-calon-santri

# Tambah verify-pembayaran ke petugas_pendaftaran
php artisan role:manage add petugas_pendaftaran verify-pembayaran
```

### ➖ Remove Permission dari Role

```bash
# Hapus view-dokumen dari petugas_pendaftaran
php artisan role:manage remove petugas_pendaftaran view-dokumen

# Hapus semua akses pembayaran dari calon_santri (JANGAN LAKUKAN!)
php artisan role:manage remove calon_santri view-pembayaran
```

### 📋 List Permission Role

```bash
# Show semua permission yang dimiliki petugas_pendaftaran
php artisan role:manage list petugas_pendaftaran

# Show semua permission untuk calon_santri
php artisan role:manage list calon_santri
```

### 🔄 Reset & Setup

```bash
# Setup ulang default permissions untuk semua role
php artisan role:setup-permissions

# Reset semua permission untuk suatu role (JANGAN LAKUKAN!)
php artisan role:manage reset petugas_keuangan
```

---

## 👤 List User & Role

Gunakan command untuk lihat semua user:
```bash
php artisan user:list
```

Current Users:
| ID | Name | Email | Role |
|----|------|-------|------|
| 1 | Administrator | admin@psb-saza.local | admin |
| 2 | Ahmad Ridho | ahmad@example.com | calon_santri |
| 3 | Siti Nurhaliza | siti@example.com | calon_santri |
| 4 | Muhammad Rizal | 081234567895@santri.local | calon_santri |
| 5 | GERI NURDIANSYAH | geri.nurdiansyah@psb-saza.local | santri |
| 6 | BABAY | 081234567894@santri.local | calon_santri |
| 7 | Ahmad Saleh Nurjaman | soleh@saza.sch.id | petugas_pendaftaran |

---

## 📝 Available Permissions (22 Total)

### Dashboard (1)
- `view-dashboard`

### Calon Santri (5)
- `view-calon-santri`
- `create-calon-santri`
- `edit-calon-santri`
- `delete-calon-santri`
- `export-calon-santri`

### Pembayaran (3)
- `view-pembayaran`
- `create-pembayaran`
- `verify-pembayaran`

### Dokumen (3)
- `view-dokumen`
- `upload-dokumen`
- `verify-dokumen`

### Settings/Bank (4)
- `view-bank-settings`
- `edit-bank-settings`
- `view-pembayaran-items`
- `manage-pembayaran-items`

### Financial (2)
- `view-financial-records`
- `create-financial-records`

### Users (4)
- `view-users`
- `create-users`
- `edit-users`
- `delete-users`

---

## 💻 Usage di Code

### Check Permission di Controller

```php
namespace App\Http\Controllers;

class CalonSantriController extends Controller
{
    public function index()
    {
        // Check permission
        if (!auth()->user()->hasPermission('view-calon-santri')) {
            abort(403, 'Unauthorized');
        }
        
        // Show list
    }
    
    public function create()
    {
        if (!auth()->user()->hasPermission('create-calon-santri')) {
            abort(403, 'Unauthorized');
        }
        
        return view('calon-santri.create');
    }
}
```

### Check Multiple Permissions

```php
// Check if user has ANY of these permissions
if (auth()->user()->hasAnyPermission(['edit-calon-santri', 'delete-calon-santri'])) {
    // Show delete button
}
```

### Check di Blade View

```blade
@if (auth()->user()->hasPermission('create-calon-santri'))
    <a href="/admin/calon-santri/create" class="btn btn-primary">
        Tambah Calon Santri
    </a>
@endif

@if (auth()->user()->hasPermission('export-calon-santri'))
    <a href="/admin/calon-santri-export" class="btn btn-secondary">
        Export
    </a>
@endif
```

### Check di Middleware

```php
// Route protection
Route::middleware(['auth', 'permission:view-calon-santri'])->group(function() {
    // Routes here
});
```

---

## 🚀 Practical Examples

### 1. Tambah Permission ke Role Baru

Misalnya, saya ingin petugas_pendaftaran juga bisa delete calon santri:

```bash
php artisan role:manage add petugas_pendaftaran delete-calon-santri
```

Verify:
```bash
php artisan role:show-permissions petugas_pendaftaran
```

### 2. Buat Role Baru (ADVANCED)

Misalnya, role `verifikator_dokumen`:

1. Update enum di migration:
```php
$table->enum('role', [..., 'verifikator_dokumen'])
```

2. Update SetupRolePermissions:
```php
$this->assignRolePermissions('verifikator_dokumen', Permission::whereIn('name', [
    'view-dashboard',
    'view-dokumen',
    'verify-dokumen',
])->pluck('id')->toArray());
```

3. Jalankan:
```bash
php artisan migrate --refresh
php artisan role:setup-permissions
```

### 3. Tambah Permission Baru

Misalnya, permission baru `print-calon-santri`:

1. Edit `database/seeders/PermissionSeeder.php`:
```php
['name' => 'print-calon-santri', 'description' => 'Print Calon Santri', 'category' => 'calon-santri'],
```

2. Jalankan seeder:
```bash
php artisan db:seed --class=PermissionSeeder
```

3. Assign ke role:
```bash
php artisan role:manage add admin print-calon-santri
php artisan role:manage add petugas_pendaftaran print-calon-santri
```

---

## 🔍 Troubleshooting

### Error 403 Forbidden saat login dengan role tertentu

**Solusi:**
1. Pastikan role user sudah benar: `php artisan user:list`
2. Lihat routes untuk role tersebut: `php artisan route:list --name=petugas`
3. Check permission: `php artisan role:show-permissions {role}`

### Permission tidak ter-apply

**Solusi:**
1. Jangan lupa refresh page
2. Clear cache: `php artisan cache:clear`
3. Verify permission setup: `php artisan role:show-permissions`

### Ingin reset semua ke default

```bash
php artisan role:setup-permissions
```

---

## 📚 Dokumentasi Lengkap

Lihat file-file dokumentasi:
- `ROLE_PERMISSION_GUIDE.md` - Panduan lengkap
- `ROLE_PERMISSION_SUMMARY.md` - Ringkasan
- File ini untuk quick reference

---

## ✅ Checklist

- ✅ Database migrations dibuat dan dijalankan
- ✅ Permission model dibuat
- ✅ 22 permissions sudah di-seed ke database
- ✅ Role permissions sudah di-setup
- ✅ Commands untuk management sudah berfungsi
- ✅ User model ditambah methods untuk permission check
- ✅ Routes sudah update dengan petugas routes
- ✅ Middleware CheckRole sudah support multiple roles

---

**Status:** ✅ READY FOR PRODUCTION
**Last Updated:** 21 Jan 2026