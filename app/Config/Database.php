<?php

/*
 | KONFIGURASI DATABASE UNTUK RAILWAY DEPLOYMENT
 | File: app/Config/Database.php (override bagian default)
 |
 | CodeIgniter 4 membaca environment variables dari .env
 | Railway otomatis menyediakan:
 |   MYSQLHOST, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD, MYSQLPORT
 */

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    /**
     * Default group
     */
    public string $defaultGroup = 'default';

    /**
     * Default database connection.
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => '', // akan diisi dari env
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => false,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
    ];

    /**
     * Test database (untuk testing CI4)
     */
    public array $tests = [
        'DSN'          => '',
        'hostname'     => '127.0.0.1',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'db_laundry_test',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => 'test_',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
    ];

    public function __construct()
    {
        parent::__construct();

        // Jika berjalan di Vercel, otomatis gunakan TiDB Cloud default
        $isVercel = getenv('VERCEL') || isset($_SERVER['VERCEL']) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false);

        if ($isVercel) {
            $this->default['hostname'] = getenv('MYSQLHOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
            $this->default['database'] = getenv('MYSQLDATABASE') ?: 'db_laundry';
            $this->default['username'] = getenv('MYSQLUSER') ?: '45S7bPdvjuyqytw.root';
            $this->default['password'] = getenv('MYSQLPASSWORD') ?: 'ILv3ndVtFmLLvqS2';
            $this->default['port']     = (int)(getenv('MYSQLPORT') ?: 4000);
            $this->default['encrypt']  = ['ssl_verify' => false];
            $this->default['DBDebug']  = false;
        } else {
            // Konfigurasi Lokal XAMPP / Railway
            $this->default['hostname'] = getenv('MYSQLHOST')     ?: getenv('DB_HOST')     ?: env('database.default.hostname', 'localhost');
            $this->default['database'] = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')     ?: env('database.default.database', 'db_laundry');
            $this->default['username'] = getenv('MYSQLUSER')     ?: getenv('DB_USER')     ?: env('database.default.username', 'root');
            $this->default['password'] = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS')     ?: env('database.default.password', '');
            $this->default['port']     = (int)(getenv('MYSQLPORT') ?: getenv('DB_PORT')   ?: env('database.default.port', 3306));
            $this->default['DBDebug']  = (ENVIRONMENT !== 'production');

            // Dukungan SSL otomatis jika terhubung ke cloud
            if (getenv('MYSQLSSL') || strpos((string)$this->default['hostname'], 'tidbcloud.com') !== false || strpos((string)$this->default['hostname'], 'aivencloud.com') !== false) {
                $this->default['encrypt'] = [
                    'ssl_verify' => false
                ];
            }
        }
    }
}
