#!/bin/bash
# Railway Build Script untuk CodeIgniter 4
# Dijalankan saat deploy

echo "=== Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Creating writable directories ==="
mkdir -p writable/logs writable/cache writable/session writable/uploads

echo "=== Setting permissions ==="
chmod -R 777 writable/

echo "=== Build complete ==="
