@echo off
chcp 65001 >nul
echo ========================================================
echo   PUSH PERBAIKAN VERCEL KE GITHUB
echo   wishuwashere. Laundry Kelompok 4
echo ========================================================

cd /d C:\xampp\htdocs\laundry

echo [1/3] Menambahkan semua perubahan file...
git add .
git add -f vendor

echo [2/3] Melakukan commit...
git commit -m "Fix Vercel: TiDB SSL cipher handshake, writable session temp dir, development env"

echo [3/3] Mengirim (Push) ke GitHub main branch...
set GCM_INTERACTIVE=never
git push origin main

echo.
echo ========================================================
echo   BERHASIL DIPUSH KE GITHUB!
echo   Vercel akan otomatis build ulang (sekitar 15 detik).
echo ========================================================
pause
