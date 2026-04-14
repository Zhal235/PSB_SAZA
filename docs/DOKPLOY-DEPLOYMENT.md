# Panduan Deploy PSB SAZA ke Dokploy

## Prasyarat
- Server dengan Dokploy sudah terinstall ([panduan install Dokploy](https://docs.dokploy.com/get-started/installation))
- Repository GitHub: `https://github.com/Zhal235/PSB_SAZA`
- Domain yang sudah mengarah ke IP server

---

## Langkah 1: Push Perubahan ke GitHub

Sebelum deploy, push semua perubahan konfigurasi Docker ke repo:

```bash
cd /path/to/PSB_SAZA
git add Dockerfile docker-compose.yml docker-entrypoint.sh .dockerignore
git commit -m "chore: update Docker configuration for Dokploy production deployment"
git push origin main
```

---

## Langkah 2: Buat Project di Dokploy

1. Buka dashboard Dokploy: `https://your-server-ip:3000`
2. Klik **"Create Project"**
3. Beri nama: `PSB SAZA`
4. Klik **"Create"**

---

## Langkah 3: Tambah Service (Docker Compose)

1. Di dalam project, klik **"Create Service"**
2. Pilih tipe: **"Docker Compose"**
3. Beri nama: `psb-saza`

---

## Langkah 4: Hubungkan GitHub Repository

1. Di tab **"General"** service, pilih **"Git"** sebagai source
2. Klik **"Configure"** → hubungkan akun GitHub
3. Pilih repository: `Zhal235/PSB_SAZA`
4. Branch: `main`
5. Compose file path: `docker-compose.yml`

---

## Langkah 5: Set Environment Variables

Di tab **"Environment"**, tambahkan variabel berikut:

```env
# Aplikasi
APP_NAME=PSB SAZA
APP_ENV=production
APP_KEY=                    # ← Diisi setelah deploy pertama (lihat catatan)
APP_DEBUG=false
APP_URL=https://psb.yourdomain.com

# Database
DB_DATABASE=psb_saza
DB_USERNAME=psb_user
DB_PASSWORD=GantiPasswordIni123!
DB_ROOT_PASSWORD=RootPasswordIni456!

# Log
LOG_LEVEL=warning
```

> **Catatan tentang APP_KEY:**
> - Saat deploy pertama, biarkan `APP_KEY` kosong.
> - Setelah container berjalan, cek logs container `psb-app` — akan ada baris:
>   `[WARN] Generated APP_KEY: base64:xxxxx...`
> - Copy nilai tersebut, paste ke environment variable `APP_KEY` di Dokploy.
> - Lakukan **Redeploy** agar key tersimpan permanen.

---

## Langkah 6: Konfigurasi Domain

1. Di tab **"Domains"**, klik **"Add Domain"**
2. Masukkan domain: `psb.yourdomain.com`
3. Port: `80`
4. Aktifkan **"HTTPS / SSL"** (Dokploy akan auto-generate via Let's Encrypt)
5. Klik **"Save"**

---

## Langkah 7: Deploy

1. Klik tombol **"Deploy"** (tab General)
2. Pantau progress di tab **"Deployments"**
3. Tunggu hingga status **"Running"**

Build akan memakan waktu 3-5 menit karena:
- Download PHP dependencies (Composer)
- Download & build Node.js/Vite assets
- Build Docker image

---

## Verifikasi Setelah Deploy

### Cek Status Container
Di Dokploy → Service → tab **"Containers"**
- `psb-app` → harus **Running**
- `psb-db` → harus **Running**

### Cek Logs
Di tab **"Logs"**, pastikan tidak ada error. Output normal:
```
=== PSB SAZA Docker Entrypoint ===
[INFO] Waiting for MySQL at psb-db:3306...
[INFO] Running database migrations...
[INFO] Caching configuration for production...
=== Startup complete, launching services ===
```

### Test Login
Buka `https://psb.yourdomain.com`

Credentials default:
- Admin: `admin@psb-saza.local` / `password123`
- Santri: `ahmad@example.com` / `password123`

> **PENTING:** Ganti password default setelah login pertama!

---

## Troubleshooting

### Container restart terus
Cek logs → kemungkinan `APP_KEY` kosong atau DB belum siap.

### Error "SQLSTATE"
Pastikan `DB_PASSWORD` di env vars sama antara `psb-app` dan `psb-db`.

### Error "Vite manifest not found"
Frontend belum di-build. Pastikan `node_modules` tidak ter-copy ke image (cek `.dockerignore`).

### Regenerate APP_KEY
```bash
# Di terminal Dokploy atau exec ke container:
docker exec psb-app php artisan key:generate --show
```
Copy hasilnya → update env var `APP_KEY` → Redeploy.

### Reset Database (hati-hati!)
```bash
docker exec psb-app php artisan migrate:fresh --seed --force
```

---

## Update / Redeploy

Setiap push ke branch `main` bisa trigger redeploy:
1. Otomatis: Set **"Auto Deploy"** di Dokploy (webhook GitHub)
2. Manual: Klik tombol **"Deploy"** di dashboard Dokploy

---

## Backup Database

```bash
# Dari server host:
docker exec psb-db sh -c 'mysqldump -u psb_user -p"$MYSQL_PASSWORD" psb_saza' > backup_$(date +%Y%m%d).sql
```
