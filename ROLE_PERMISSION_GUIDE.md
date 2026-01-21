# 📋 Sistem Role & Permission Management

## Overview

Sistem role dan permission yang fleksibel untuk mengelola akses pengguna berdasarkan role mereka. 

## Role yang Tersedia

1. **admin** - Administrator penuh dengan akses ke semua fitur
2. **calon_santri** - Calon santri yang baru daftar
3. **santri** - Santri yang sudah diterima
4. **petugas_pendaftaran** - Staff pendaftaran
5. **petugas_keuangan** - Staff keuangan

## Permission Categories

### 📊 Dashboard
- `view-dashboard` - View Dashboard

### 👥 Calon Santri
- `view-calon-santri` - View Calon Santri List
- `create-calon-santri` - Create Calon Santri
- `edit-calon-santri` - Edit Calon Santri
- `delete-calon-santri` - Delete Calon Santri
- `export-calon-santri` - Export Calon Santri

### 💰 Pembayaran
- `view-pembayaran` - View Pembayaran
- `create-pembayaran` - Create Pembayaran
- `verify-pembayaran` - Verify Pembayaran

### 📄 Dokumen
- `view-dokumen` - View Dokumen
- `upload-dokumen` - Upload Dokumen
- `verify-dokumen` - Verify Dokumen

### 🏦 Settings
- `view-bank-settings` - View Bank Settings
- `edit-bank-settings` - Edit Bank Settings
- `view-pembayaran-items` - View Pembayaran Items
- `manage-pembayaran-items` - Manage Pembayaran Items

### 📊 Financial
- `view-financial-records` - View Financial Records
- `create-financial-records` - Create Financial Records

### 👤 User Management
- `view-users` - View Users
- `create-users` - Create Users
- `edit-users` - Edit Users
- `delete-users` - Delete Users

## Command Usage

### 1. Lihat semua permission yang tersedia
```bash
php artisan permission:list
```

### 2. Lihat permission untuk suatu role
```bash
php artisan role:show-permissions admin
php artisan role:show-permissions petugas_pendaftaran
```

### 3. Lihat semua role dan permissions mereka
```bash
php artisan role:show-permissions
```

### 4. Tambah permission ke role
```bash
php artisan role:manage add petugas_pendaftaran create-calon-santri
php artisan role:manage add admin export-calon-santri
```

### 5. Hapus permission dari role
```bash
php artisan role:manage remove petugas_pendaftaran create-calon-santri
```

### 6. List permission yang dimiliki role
```bash
php artisan role:manage list petugas_keuangan
```

### 7. Reset semua permission role (hati-hati!)
```bash
php artisan role:manage reset admin
```

### 8. Setup ulang semua default permissions
```bash
php artisan role:setup-permissions
```

## Default Role Permissions

### Admin
- ✓ Semua permission

### Calon Santri & Santri
- ✓ view-dashboard
- ✓ view-pembayaran
- ✓ upload-dokumen

### Petugas Pendaftaran
- ✓ view-dashboard
- ✓ view-calon-santri
- ✓ create-calon-santri
- ✓ edit-calon-santri
- ✓ view-dokumen
- ✓ verify-dokumen
- ✓ upload-dokumen

### Petugas Keuangan
- ✓ view-dashboard
- ✓ view-pembayaran
- ✓ create-pembayaran
- ✓ verify-pembayaran
- ✓ view-financial-records
- ✓ create-financial-records

## Database Schema

### Tabel: permissions
```
- id (integer, primary key)
- name (string, unique) - e.g., 'view-dashboard'
- description (string, nullable)
- category (string) - e.g., 'dashboard', 'calon-santri'
- created_at (timestamp)
- updated_at (timestamp)
```

### Tabel: role_permissions
```
- id (integer, primary key)
- role (string) - admin, calon_santri, santri, petugas_pendaftaran, petugas_keuangan
- permission_id (foreign key -> permissions.id)
- created_at (timestamp)
- updated_at (timestamp)
- unique(role, permission_id)
```

## Integrasi di Code

### Di Controller
```php
// Check if user has permission
if (auth()->user()->hasPermission('view-calon-santri')) {
    // Show calon santri list
}
```

### Di Middleware
Middleware `role` sudah diupdate untuk support multiple roles:
```php
Route::middleware(['auth', 'role:petugas_pendaftaran,petugas_keuangan'])->group(function() {
    // Routes here
});
```

## Custom Permission

Untuk menambah permission baru:

1. Buka file `database/seeders/PermissionSeeder.php`
2. Tambah permission ke array `$permissions`
3. Run: `php artisan db:seed --class=PermissionSeeder`
4. Assign ke role: `php artisan role:manage add {role} {permission}`

## Catatan Penting

- Permission bersifat **additive** - user bisa punya banyak permission sesuai role
- Role **tidak bisa diubah** dari CLI untuk user yang sudah ada - ubah di database table `users` column `role`
- Jika ingin reset semua ke default: `php artisan role:setup-permissions`
