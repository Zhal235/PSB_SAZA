# 🔧 FIX: Dynamic Route Prefixes untuk Dokumen

**Error:** `Route [dokumen.create] not defined` saat petugas akses verifikasi-dokumen
**Status:** ✅ **FIXED**

## Masalah
Routes dokumen tidak konsisten - view & controller reference `dokumen.create`, `dokumen.store`, `dokumen.destroy` tanpa prefix, tapi routes sudah di-organize dengan prefix `admin.` dan `petugas.`.

## Root Cause
Ketika admin dokumen routes di-move ke admin group dan di-rename ke `admin.dokumen.*`, petugas routes juga di-create sebagai `petugas.dokumen.*`. Tapi view & controller masih reference old route names tanpa prefix.

## Solusi

### 1. Update Views - Gunakan Dynamic Route Prefix
```blade
<!-- BEFORE: Hardcoded route name -->
<form action="{{ route('dokumen.store', $calonSantri) }}">

<!-- AFTER: Dynamic prefix based on user role -->
<form action="{{ route(getRoutePrefix() . '.dokumen.store', $calonSantri) }}">
```

**Files updated:**
- `resources/views/dokumen/upload-content.blade.php` - 4 routes
- `resources/views/admin/dokumen/create.blade.php` - 4 routes
- `resources/views/santri/dokumen-upload.blade.php` - 1 route
- `resources/views/admin/verifikasi-dokumen/index.blade.php` - 1 route

### 2. Update Controller - Dynamic Redirect
```php
// BEFORE
return redirect()->route('dokumen.create', $calonSantri);

// AFTER
return redirect()->route(getRoutePrefix() . '.dokumen.create', $calonSantri);
```

**File updated:**
- `app/Http/Controllers/CalonSantriController.php` - 2 redirects

### 3. Helper Function Already Available
`getRoutePrefix()` di `app/Helpers/RouteHelper.php` returns:
- `'admin'` for admin users
- `'petugas'` for petugas users
- `'santri'` for santri users

## Routes Now Consistent

**Admin:**
- `admin.dokumen.index` - Verifikasi dokumen list
- `admin.dokumen.create` - Upload form
- `admin.dokumen.store` - Save upload
- `admin.dokumen.destroy` - Delete dokumen

**Petugas:**
- `petugas.dokumen.index` - Verifikasi dokumen list
- `petugas.dokumen.create` - Upload form
- `petugas.dokumen.store` - Save upload
- `petugas.dokumen.destroy` - Delete dokumen

**Santri:**
- `santri.dokumen-upload` - Upload form
- `santri.dokumen-store` - Save upload

## Result
✅ Petugas dapat akses `/petugas/verifikasi-dokumen` tanpa error
✅ Klik tombol upload/delete menggunakan route yang benar
✅ Semua route references sekarang konsisten dengan prefix pattern