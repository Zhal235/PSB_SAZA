# RBAC (Role Based Access Control) - Dokumentasi Profesional

## Overview
Sistem RBAC profesional yang telah diimplementasikan menggunakan Laravel Authorization pattern dengan:
- **Roles**: Admin, Petugas Pendaftaran, Petugas Keuangan, Calon Santri, Santri
- **Permissions**: Granular permissions untuk setiap aksi
- **Middleware**: CheckRole dan CheckPermission untuk validasi akses

---

## Struktur Database

### Tabel Roles
```sql
- id: Integer Primary Key
- name: String (unique) - admin, petugas_pendaftaran, petugas_keuangan, calon_santri, santri
- display_name: String - Nama display untuk UI
- description: Text - Deskripsi role
- timestamps
```

### Tabel Permissions
```sql
- id: Integer Primary Key
- name: String (unique) - view_dashboard, create_user, edit_user, dll
- display_name: String - Display name
- category: String - Dashboard, Calon Santri Management, Pembayaran, dll
- description: Text
- timestamps
```

### Tabel Role_Permission (Pivot)
```sql
- role_id (FK) -> roles.id
- permission_id (FK) -> permissions.id
```

### Users Table Update
```sql
- role_id (FK) -> roles.id (nullable, replaces 'role' string column)
```

---

## Model Relationships

### User Model
```php
// Relationships
$user->role()           // Get user's role: Role
$user->permissions()    // Get all permissions through role: Collection

// Methods
$user->hasPermission('view_dashboard')           // bool
$user->hasAnyPermission(['view_dashboard', 'create_user'])    // bool
$user->hasAllPermissions(['view_dashboard', 'create_user'])   // bool
$user->hasRole('admin')                          // bool
$user->hasAnyRole(['admin', 'petugas_pendaftaran'])           // bool
```

### Role Model
```php
// Relationships
$role->permissions()    // Get all permissions for role: BelongsToMany
$role->users()         // Get all users with this role: HasMany

// Methods
$role->hasPermission('view_dashboard')          // bool
$role->hasAnyPermission(['view_dashboard', ...]) // bool
$role->hasAllPermissions(['view_dashboard', ...])// bool
```

### Permission Model
```php
// Relationships
$permission->roles()   // Get all roles with this permission: BelongsToMany
```

---

## Usage Examples

### 1. Check Permission di Controller
```php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has permission
        if (!auth()->user()->hasPermission('view_dashboard')) {
            abort(403, 'Unauthorized');
        }
        
        return view('dashboard');
    }
}
```

### 2. Check Role di Controller
```php
public function show(CalonSantri $santri)
{
    if (!auth()->user()->hasAnyRole(['admin', 'petugas_pendaftaran'])) {
        abort(403);
    }
    
    return view('calon-santri.show', compact('santri'));
}
```

### 3. Menggunakan Middleware di Route
```php
// Check Role
Route::middleware(['auth', 'role:admin,petugas_pendaftaran'])->group(function () {
    Route::get('/calon-santri', [CalonSantriController::class, 'index']);
});

// Check Permission
Route::middleware(['auth', 'permission:view_calon_santri,edit_calon_santri'])->group(function () {
    Route::put('/calon-santri/{santri}', [CalonSantriController::class, 'update']);
});
```

### 4. Di Blade Template
```blade
@can('view_dashboard')
    <a href="/dashboard">Dashboard</a>
@endcan

@if(auth()->user()->hasRole('admin'))
    <a href="/admin/users">Kelola User</a>
@endif

@if(auth()->user()->hasPermission('create_calon_santri'))
    <button class="btn-create">Tambah Santri</button>
@endif
```

---

## Daftar Roles

| Role | Deskripsi | Use Case |
|------|-----------|----------|
| **admin** | Administrator penuh akses | Pengelola sistem lengkap |
| **petugas_pendaftaran** | Kelola pendaftaran & dokumen | Handle registrasi santri baru |
| **petugas_keuangan** | Kelola pembayaran & keuangan | Monitoring pembayaran |
| **calon_santri** | Calon santri dalam proses daftar | Pendaftar baru |
| **santri** | Santri yang sudah diterima | Alumni/current santri |

---

## Daftar Permissions

### Dashboard
- `view_dashboard`

### Calon Santri Management
- `view_calon_santri`
- `create_calon_santri`
- `edit_calon_santri`
- `delete_calon_santri`
- `export_calon_santri`

### Pembayaran Management
- `view_pembayaran`
- `create_pembayaran`
- `edit_pembayaran`
- `delete_pembayaran`
- `verify_pembayaran`

### Pembayaran Items Management
- `view_pembayaran_items`
- `create_pembayaran_items`
- `edit_pembayaran_items`
- `delete_pembayaran_items`

### Dokumen Verification
- `view_dokumen`
- `verify_dokumen`
- `manage_hardcopy`

### Bank Settings
- `view_bank_settings`
- `edit_bank_settings`

### Financial Records
- `view_financial_records`
- `create_financial_records`
- `edit_financial_records`
- `delete_financial_records`

### User Management
- `view_users`
- `create_users`
- `edit_users`
- `delete_users`
- `manage_user_roles`

### Reports
- `view_reports`
- `export_reports`

---

## Middleware

### CheckRole Middleware
```php
// File: app/Http/Middleware/CheckRole.php
// Validasi bahwa user memiliki salah satu dari roles yang diberikan
middleware(['auth', 'role:admin,petugas_pendaftaran'])
```

### CheckPermission Middleware
```php
// File: app/Http/Middleware/CheckPermission.php
// Validasi bahwa user memiliki salah satu dari permissions yang diberikan
middleware(['auth', 'permission:create_calon_santri,edit_calon_santri'])
```

---

## Best Practices

### 1. Assign Role ke User
```php
$user = User::find(1);
$role = Role::where('name', 'admin')->first();
$user->role_id = $role->id;
$user->save();
```

### 2. Assign Permission ke Role
```php
$role = Role::where('name', 'petugas_pendaftaran')->first();
$permissions = Permission::whereIn('name', [
    'view_calon_santri',
    'create_calon_santri',
    'edit_calon_santri'
])->get();

$role->permissions()->sync($permissions->pluck('id'));
```

### 3. Revoke Permission dari Role
```php
$role->permissions()->detach($permissionId);
```

### 4. Clear Single Permission
```php
$role->permissions()->detach(
    Permission::where('name', 'delete_calon_santri')->first()->id
);
```

### 5. Bulk Update Permissions
```php
$newPermissions = ['view_dashboard', 'view_pembayaran'];
$permissionIds = Permission::whereIn('name', $newPermissions)->pluck('id');
$role->permissions()->sync($permissionIds);
```

---

## Admin Privilege

Admin role memiliki privilege khusus:
- **Akses penuh** ke semua permissions
- Tidak perlu explicit permission assignment
- Method `hasPermission()` otomatis return true untuk admin

---

## Migration Reference

### Create RBAC Tables
```
2026_05_15_000003_create_rbac_tables.php
```

### Update File Path Nullable
```
2026_05_15_000002_make_file_path_nullable_in_dokumens.php
```

### Migrate Users Role to Role_ID
```
2026_05_15_000004_migrate_users_role_to_role_id.php
```

---

## Seeding

### RolePermissionSeeder
Run untuk initialize roles dan permissions:
```bash
php artisan db:seed --class=RolePermissionSeeder
```

Seeder ini akan:
1. Create 5 roles (admin, petugas_pendaftaran, petugas_keuangan, calon_santri, santri)
2. Create 30+ permissions dengan kategori
3. Assign permissions ke setiap role sesuai responsibility

---

## Future Enhancements

1. **Permission Caching**: Cache role permissions untuk performa optimal
2. **Audit Logging**: Log semua permission changes
3. **Dynamic Permissions**: Admin bisa create permission dari UI
4. **Role Templates**: Pre-made role templates untuk quick setup
5. **Permission Groups**: Group permissions untuk bulk assignment

---

## Support & Troubleshooting

### User tidak bisa akses resource padahal sudah login
1. Check apakah user punya `role_id` yang valid
2. Verifikasi role memiliki required permissions
3. Debug dengan: `dd(auth()->user()->role, auth()->user()->permissions())`

### Permission tidak working
1. Clear application cache: `php artisan cache:clear`
2. Verify permission ada di database
3. Check role sudah di-sync dengan permission

### Reset RBAC System
```bash
# Full reset
php artisan migrate:fresh
php artisan db:seed --class=RolePermissionSeeder
```

---

Generated: 2026-05-15
