@echo off
chcp 65001 >nul
echo ========================================================
echo   PUSH KODE WISHUWASHERE LAUNDRY KE GITHUB & VERCEL
echo   Repository: BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2
echo ========================================================

cd /d C:\xampp\htdocs\laundry

echo [1/3] Menambahkan semua perubahan file...
git add .
git add -f vendor

echo [2/3] Melakukan commit...
git commit -m "Fix: Point Vercel to wishuwasherelaundry cluster with auto failover"

echo [3/3] Mengirim (Push) ke GitHub main branch...
set GCM_INTERACTIVE=never
git push origin main

echo.
echo ========================================================
echo   BERHASIL DIPUSH KE GITHUB!
echo   Vercel akan otomatis build ulang (sekitar 15 detik).
echo ========================================================
pause
