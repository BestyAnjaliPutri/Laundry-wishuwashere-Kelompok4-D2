@echo off
chcp 65001 >nul
echo ========================================================
echo   PUSH PERBAIKAN LOGIN KELOMPOK 4 KE VERCEL
echo   wishuwashere. Laundry Kelompok 4
echo ========================================================

cd /d C:\xampp\htdocs\laundry

echo [1/3] Menambahkan semua file...
git add .
git add -f vendor

echo [2/3] Melakukan commit...
git commit -m "Fix: Add /seed-data-november endpoint to seed 50 transaksi + jurnal November 2026 ke TiDB Cloud"

echo [3/3] Mengirim (Push) ke GitHub main branch...
git push origin main

echo.
echo ========================================================
echo   BERHASIL DIPUSH KE GITHUB!
echo   Vercel akan otomatis build ulang dalam 15 detik.
echo ========================================================
pause
