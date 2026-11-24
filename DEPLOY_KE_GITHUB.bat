@echo off
chcp 65001 >nul
title wishuwashere. Laundry — Push ke GitHub BestyAnjaliPutri

echo.
echo ============================================================
echo   wishuwashere. LAUNDRY — PUSH KE GITHUB
echo   Target: BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2
echo ============================================================
echo.

cd /d C:\xampp\htdocs\laundry

echo [1/5] Memeriksa Git...
git --version
IF ERRORLEVEL 1 (
    echo [ERROR] Git belum terpasang di komputer.
    pause
    exit /b
)

echo.
echo [2/5] Mengatur identitas Git...
git config user.name "BestyAnjaliPutri"
git config user.email "bestyanjali@gmail.com"
git config --add safe.directory C:/xampp/htdocs/laundry

echo.
echo [3/5] Menginisialisasi Git Repository...
IF NOT EXIST ".git" (
    git init
    git branch -M main
)
git remote remove origin 2>nul
git remote add origin https://github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2.git

echo.
echo [4/5] Menyiapkan seluruh file proyek...
git add .
git commit -m "Fix railway.toml syntax quote strings for Railway deployment" 2>nul

echo.
echo [5/5] Mengunggah (Push) ke GitHub...
echo (Jika muncul jendela browser, silakan klik 'Sign in with browser' / 'Authorize')
echo.
git push -u origin main

echo.
echo ============================================================
echo   SELESAI! Silakan refresh halaman GitHub Anda di browser!
echo ============================================================
echo.
pause
