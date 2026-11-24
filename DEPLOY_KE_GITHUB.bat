@echo off
chcp 65001 >nul
echo ========================================================
echo   PUSH PERBAIKAN VERCEL BUILD KE GITHUB
echo   wishuwashere. Laundry Kelompok 4
echo ========================================================

cd /d C:\xampp\htdocs\laundry

echo [1/4] Mengatur identitas Git...
git config user.name "BestyAnjaliPutri"
git config user.email "bestyanjali@gmail.com"

echo [2/4] Menambahkan semua file termasuk vendor...
git add .
git add -f vendor

echo [3/4] Melakukan commit...
git commit -m "Fix Vercel build: remove buildCommand and include vendor directory"

echo [4/4] Mengirim (Push) ke GitHub main branch...
set GCM_INTERACTIVE=never
git push origin main

echo.
echo ========================================================
echo   BERHASIL DIPUSH KE GITHUB!
echo   Vercel akan otomatis build ulang (sekitar 15 detik).
echo ========================================================
pause
