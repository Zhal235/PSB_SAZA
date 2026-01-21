# 🔧 FIX: Dynamic Routes berdasarkan Role

**Tanggal:** 21 Januari 2026
**Status:** ✅ COMPLETE

## Masalah
Ketika login dengan role petugas, klik link di sidebar masih redirect ke `/admin/...` bukan `/petugas/...`

**Contoh:**
- Klick menu "Kelola Pendaftar" → `/admin/calon-santri` (❌ seharusnya `/petugas/calon-santri`)

## Root Cause
Route names di sidebar hardcoded dengan `admin.` prefix, tidak mempertimbangkan role user.

## Solusi ✅

### 1. Buat Helper Functions (`app/Helpers/RouteHelper.php`)
```php
function getRoutePrefix(): string
{
    $user = auth()->user();
    return match($user->role) {
        'petugas_pendaftaran', 'petugas_keuangan' => 'petugas',
        default => 'admin'
    };
}
```

### 2. Update Routes (`routes/web.php`)
Routes sekarang share controller yang sama tapi dengan prefix berbeda:
```php
// Admin routes (tetap seperti semula)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(...)

// Petugas routes (share controller, beda route name)
Route::middleware(['auth', 'role:petugas_pendaftaran,petugas_keuangan'])
    ->prefix('petugas')->name('petugas.')->group(...)
```

### 3. Update Sidebar (`resources/views/components/sidebar-admin.blade.php`)
Gunakan helper function untuk dynamic route prefix:
```blade
<!-- BEFORE -->
<a href="{{ route('admin.calon-santri.index') }}">...</a>

<!-- AFTER -->
<a href="{{ route(getRoutePrefix() . '.calon-santri.index') }}">...</a>
```

### 4. Register Helpers (`composer.json`)
```json
"autoload": {
    "files": ["app/Helpers/RouteHelper.php"]
}
```

Run: `composer dump-autoload`

## Hasil ✅

**Sebelum Fix:**
- Petugas klick "Kelola Pendaftar" → `/admin/calon-santri` ❌

**Sesudah Fix:**
- Petugas klick "Kelola Pendaftar" → `/petugas/calon-santri` ✅
- Admin klick "Kelola Pendaftar" → `/admin/calon-santri` ✅

## Keuntungan
1. ✅ **No duplicate views** - Share view dari admin, cukup dengan dynamic routing
2. ✅ **No duplicate controllers** - Share controller, tinggal atur routing
3. ✅ **Maintainable** - Edit satu tempat, auto apply ke semua role
4. ✅ **Permission-aware** - Menu dinamis berdasarkan permission user

## Files Modified
- `routes/web.php` - Simplified petugas routes
- `composer.json` - Added helper files autoload
- `app/Helpers/RouteHelper.php` - New helper functions
- `resources/views/components/sidebar-admin.blade.php` - Dynamic routes
- `app/Providers/AppServiceProvider.php` - Removed unnecessary blade macro

## Testing
```bash
# Login sebagai petugas
Email: soleh@saza.sch.id
Password: password123

# Klik menu → seharusnya stay di /petugas/...
# Klik "Kelola Pendaftar" → /petugas/calon-santri ✅
```

---

**Status: ✅ READY TO USE**