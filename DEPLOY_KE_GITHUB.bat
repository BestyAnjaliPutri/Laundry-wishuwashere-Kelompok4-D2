@echo off
chcp 65001 >nul
title wishuwashere. Laundry — Deploy ke GitHub

echo.
echo ============================================================
echo   wishuwashere. LAUNDRY — SETUP GITHUB
echo ============================================================
echo.

:: Pindah ke folder proyek
cd /d C:\xampp\htdocs\laundry

echo [1/6] Cek instalasi Git...
git --version
IF ERRORLEVEL 1 (
    echo.
    echo [ERROR] Git belum terinstall!
    echo Download di: https://git-scm.com/download/win
    echo Setelah install Git, jalankan script ini lagi.
    pause
    exit /b
)

echo.
echo [2/6] Masukkan informasi GitHub kamu:
echo.
set /p GIT_NAME="   Nama kamu (contoh: Wardiansyach): "
set /p GIT_EMAIL="   Email GitHub kamu: "
set /p REPO_URL="   URL repo GitHub (contoh: https://github.com/NAMA/laundry-wishuwashere.git): "

echo.
echo [3/6] Konfigurasi Git...
git config user.name "%GIT_NAME%"
git config user.email "%GIT_EMAIL%"

echo.
echo [4/6] Inisialisasi Git repository...
IF EXIST ".git" (
    echo     [INFO] Repo sudah ada, skip init.
) ELSE (
    git init
    echo     [OK] Git diinisialisasi.
)

echo.
echo [5/6] Menambahkan semua file dan commit...
git add .
git status --short
git commit -m "Initial commit: wishuwashere. Laundry SIA CI4 - Kelompok 4"

echo.
echo [6/6] Push ke GitHub...
git remote remove origin 2>nul
git remote add origin %REPO_URL%
git branch -M main
git push -u origin main

echo.
echo ============================================================
echo   SELESAI! Cek repo kamu di GitHub.
echo   Sekarang lanjut ke Railway: https://railway.app
echo ============================================================
echo.
pause
