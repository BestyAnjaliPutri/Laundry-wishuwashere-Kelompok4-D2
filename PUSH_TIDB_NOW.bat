@echo off
chcp 65001 >nul
echo ============================================
echo   PUSH KODE KE GITHUB (TiDB + Vercel Fix)
echo ============================================

cd /d C:\xampp\htdocs\laundry

echo [1/4] Setting git config...
git config user.email "besty@laundry.com"
git config user.name "BestyLaundry"

echo [2/4] Menambahkan semua file yang berubah...
git add app/Config/Routes.php
git add app/Config/Database.php
git add app/Config/App.php
git add vercel.json
git add api/index.php
git add .gitignore

echo [3/4] Commit...
git commit -m "Fix: TiDB Cloud SSL config, auto-detect baseURL, setup-tidb route"

echo [4/4] Push ke GitHub...
set GCM_INTERACTIVE=never
git push origin main

echo.
echo ============================================
echo   SELESAI! Cek GitHub dan tunggu Vercel deploy
echo ============================================
pause
