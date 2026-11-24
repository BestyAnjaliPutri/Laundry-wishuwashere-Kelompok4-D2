<?php
/**
 * RAILWAY DEPLOYMENT BOOTSTRAP
 * 
 * File ini membaca environment variables dari Railway
 * dan menulis ke .env CodeIgniter secara dinamis.
 * 
 * Railway menyediakan variabel DB via environment:
 *   MYSQLHOST, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD, MYSQLPORT
 */

// Baca env Railway → tulis ke $_ENV agar CI4 bisa baca
if (getenv('RAILWAY_ENVIRONMENT') || getenv('MYSQLHOST')) {
    $dbHost     = getenv('MYSQLHOST')     ?: getenv('DB_HOST')     ?: 'localhost';
    $dbName     = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')     ?: 'db_laundry';
    $dbUser     = getenv('MYSQLUSER')     ?: getenv('DB_USER')     ?: 'root';
    $dbPass     = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS')     ?: '';
    $dbPort     = getenv('MYSQLPORT')     ?: getenv('DB_PORT')     ?: '3306';
    $appUrl     = getenv('APP_URL')       ?: 'http://localhost:8080/';

    $envContent = <<<ENV
CI_ENVIRONMENT = production

app.baseURL = '{$appUrl}'

database.default.hostname = {$dbHost}
database.default.database = {$dbName}
database.default.username = {$dbUser}
database.default.password = {$dbPass}
database.default.DBDriver = MySQLi
database.default.port = {$dbPort}
ENV;

    file_put_contents(__DIR__ . '/.env', $envContent);
}

// Lanjutkan ke index.php CI4 normal
chdir(__DIR__ . '/public');
require __DIR__ . '/public/index.php';
