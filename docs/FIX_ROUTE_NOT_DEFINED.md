# 🔧 FIX: RouteNotFoundException - admin.dokumen.index

**Error:** `Route [admin.dokumen.index] not defined`  
**Status:** ✅ **FIXED**

## Masalah
Admin routes untuk dokumen tidak konsisten:
- Route name `verifikasi-dokumen.index` (hardcoded di route)
- Sidebar reference `admin.dokumen.index` (tidak ada!)

## Solusi

### 1. Standardize Admin Routes
Moved dokumen routes dari terpisah ke dalam admin group dan rename:
```php
// BEFORE: Outside admin group
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/verifikasi-dokumen', [DokumenController::class, 'index'])
        ->name('verifikasi-dokumen.index');
});

// AFTER: Inside admin group dengan consistent naming
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/verifikasi-dokumen', [DokumenController::class, 'index'])
        ->name('dokumen.index'); // Jadi: admin.dokumen.index
});
```

### 2. Update Views
Updated verifikasi-dokumen views untuk gunakan route baru:
- `verifikasi-dokumen.index` → `admin.dokumen.index`

### 3. Update Sidebar
Sidebar now properly references dokumen routes:
```blade
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.dokumen.index') }}">...</a>
@else
    <a href="{{ route('petugas.dokumen.index') }}">...</a>
@endif
```

## Routes Consistency

**Admin Routes (all with `admin.` prefix):**
- `admin.dashboard`
- `admin.calon-santri.*`
- `admin.dokumen.*` ✅ (FIXED)
- `admin.pembayaran.*`
- `admin.bukti-pembayaran.*`
- `admin.bank-settings.*`
- `admin.pembayaran-items.*`
- `admin.financial-records.*`
- `admin.users.*`

**Petugas Routes (all with `petugas.` prefix):**
- `petugas.dashboard`
- `petugas.calon-santri.*`
- `petugas.dokumen.*`
- `petugas.pembayaran.*`

## Files Changed
- `routes/web.php` - Move dokumen routes inside admin group
- `resources/views/components/sidebar-admin.blade.php` - Update dokumen route reference
- `resources/views/admin/verifikasi-dokumen/index.blade.php` - Update all route names

## Result
✅ No more `Route [admin.dokumen.index] not defined` error
✅ Admin can access `/admin/calon-santri` properly
✅ All routes now consistent with prefix naming