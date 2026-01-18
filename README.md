# PSB SAZA - Sistem Penerimaan Santri Baru

Aplikasi web untuk mengelola penerimaan santri baru dengan fitur admin panel dan santri portal.

## 🚀 Stack Teknologi

- **Backend:** Laravel 12
- **Frontend:** Blade Template + Tailwind CSS
- **Database:** SQLite (development), MySQL (production)
- **PHP Version:** 8.2+

## 📋 Fitur yang Sudah Diimplementasikan

### ✅ Authentication System
- [x] Login form dengan validasi email & password
- [x] Role-based access control (Admin & Calon Santri)
- [x] Logout functionality
- [x] Session management
- [x] Password hashing dengan bcrypt

### ✅ Admin Dashboard
- [x] Admin login dengan role 'admin'
- [x] Dashboard overview dengan stats placeholder
- [x] Sidebar navigation
- [x] Logout button

### ✅ Santri Dashboard
- [x] Calon santri login dengan role 'calon_santri'
- [x] Status pendaftaran display
- [x] Dashboard overview
- [x] Logout button

## 🔧 Setup & Instalasi

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (untuk build CSS/JS jika diperlukan)

### Installation Steps

```bash
# Clone repository
git clone <repo-url>
cd PSB_SAZA

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database dengan test users
php artisan db:seed

# Run development server
php artisan serve
```

Server akan berjalan di `http://127.0.0.1:8000`

## 👤 Test Credentials

### Admin User
```
Email: admin@psb-saza.local
Password: password123
```

### Calon Santri
```
Email: ahmad@example.com
Password: password123

Email: siti@example.com
Password: password123
```

## 📁 Struktur Project

```
PSB_SAZA/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── UserSeeder.php
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   └── dashboard.blade.php
│       └── santri/
│           └── dashboard.blade.php
├── routes/
│   └── web.php
└── bootstrap/
    └── app.php
```

## 🔐 Security Features

- ✅ CSRF Protection (token di setiap form)
- ✅ Password Hashing (bcrypt)
- ✅ Role-Based Access Control (Middleware)
- ✅ Session Management
- ✅ Email Validation

## 📝 Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/login` | Show login form |
| POST | `/login` | Handle login |
| POST | `/logout` | Handle logout |
| GET | `/admin/dashboard` | Admin dashboard (admin only) |
| GET | `/santri/dashboard` | Santri dashboard (santri only) |

## 🎯 Fitur yang Akan Dikembangkan

### Phase 2: Calon Santri Registration
- [ ] Halaman daftar santri baru
- [ ] Form data santri (nama, alamat, asal sekolah, dll)
- [ ] Upload dokumen (scan ijazah, akte lahir, foto, dll)
- [ ] Email verification
- [ ] Password reset functionality

### Phase 3: Admin Management
- [ ] CRUD pendaftar
- [ ] Status management
- [ ] Verifikasi dokumen
- [ ] Export data to Excel/PDF
- [ ] Dashboard analytics
- [ ] Admin user management

### Phase 4: Advanced Features
- [ ] Jadwal tes & pengumuman
- [ ] Payment gateway integration
- [ ] SMS/Email notifications
- [ ] Report generation
- [ ] Audit logs

## 🛠️ Development Commands

```bash
# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration migration_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# Create new middleware
php artisan make:middleware MiddlewareName

# Tinker shell (untuk testing)
php artisan tinker

# Seed database
php artisan db:seed

# Fresh migration + seed
php artisan migrate:fresh --seed
```

## 📱 UI Preview

### Login Page
- Clean, modern design dengan Tailwind CSS
- Gradient background
- Email & password input fields
- "Remember me" checkbox
- Error message display

### Admin Dashboard
- Indigo color scheme
- Sidebar navigation
- Stats cards (Total Pendaftar, Verifikasi, Lolos, Ditolak)
- Recent activity section
- User info di top bar

### Santri Dashboard
- Blue color scheme
- Sidebar navigation
- Status pendaftaran display
- Jadwal tes & dokumen status
- Pengumuman terbaru
- User info di top bar

## 🐛 Troubleshooting

### Port 8000 sudah digunakan
```bash
php artisan serve --port=8001
```

### Database error
```bash
php artisan migrate:fresh --seed
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Laravel Blade Templates](https://laravel.com/docs/blade)

## 📄 License

© 2026 PSB SAZA - Semua Hak Dilindungi

## 👨‍💻 Author

Dikembangkan dengan ❤️ untuk PSB SAZA
