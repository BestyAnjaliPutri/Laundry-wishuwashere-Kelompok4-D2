<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;

class Home extends BaseController
{
    public function index(): string
    {
        $transaksiModel = new TransaksiModel();
        $paketModel     = new PaketModel();
        $pelangganModel = new PelangganModel();

        // Ambil data transaksi terbaru dengan join
        $transaksi = $transaksiModel->getTransaksiWithDetail();

        // Hitung statistik aktual dari database
        $totalOrderHariIni = $transaksiModel->countAllResults();
        $sedangDiproses    = $transaksiModel->whereIn('status_cucian', ['Sedang Dicuci', 'Proses Setrika'])->countAllResults();
        $siapDiambil       = $transaksiModel->where('status_cucian', 'Siap Diambil')->countAllResults();

        // Hitung pendapatan dari order yang sudah Lunas
        $db = \Config\Database::connect();
        $pendapatanResult = $db->table('transaksi')
            ->selectSum('total_biaya')
            ->where('status_bayar', 'Lunas')
            ->get()->getRow();
        $pendapatanHariIni = $pendapatanResult->total_biaya ?? 0;

        $data = [
            'title'               => 'Dashboard Kasir - wishuwashere.',
            'menu'                => 'dashboard',
            'total_order'         => $totalOrderHariIni,
            'sedang_diproses'     => $sedangDiproses,
            'siap_diambil'        => $siapDiambil,
            'pendapatan_hari_ini' => $pendapatanHariIni,
            'transaksi'           => $transaksi,
            'paket'               => $paketModel->findAll(),
            'pelanggan'           => $pelangganModel->findAll(),
        ];

        return view('dashboard/index', $data);
    }

    public function setupTidb()
    {
        mysqli_report(MYSQLI_REPORT_OFF);

        $host = trim((string)$this->request->getVar('host'));
        $user = trim((string)$this->request->getVar('user'));
        $pass = trim((string)$this->request->getVar('pass'));
        $port = (int)($this->request->getVar('port') ?: 4000);

        if (empty($host)) {
            $host = 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com';
        }

        $defaultPasswords = ['mJGks9RYclPnbAKQ', 'ILv3ndVtFmLLvqS2'];
        $tryPasses = !empty($pass) ? [$pass] : $defaultPasswords;
        $tryUsers  = !empty($user) ? [$user] : ['3rDxFxgkognB5J2.root', '45S7bPdvjuyqytw.root'];
        $tryHosts  = !empty($host) ? [$host] : [
            'gateway01.ap-northeast-1.prod.aws.tidbcloud.com',
            'gateway01.ap-southeast-1.prod.aws.tidbcloud.com'
        ];

        $mysqli = null;
        $validPass = '';
        $validHost = '';
        $validUser = '';
        $lastErr = '';

        foreach ($tryHosts as $h) {
            foreach ($tryUsers as $u) {
                foreach ($tryPasses as $p) {
                    try {
                        $testConn = mysqli_init();
                        mysqli_ssl_set($testConn, NULL, NULL, NULL, NULL, NULL);
                        mysqli_options($testConn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
                        if (@mysqli_real_connect($testConn, $h, $u, $p, 'sys', $port, NULL, MYSQLI_CLIENT_SSL)) {
                            $mysqli = $testConn;
                            $validPass = $p;
                            $validHost = $h;
                            $validUser = $u;
                            break 3;
                        } else {
                            $lastErr = mysqli_connect_error();
                        }
                    } catch (\Throwable $e) {
                        $lastErr = $e->getMessage();
                    }
                }
            }
        }

        // JIKA GAGAL: Tampilkan form panduan input credential
        if (!$mysqli) {
            $currentErrHtml = $lastErr ? "<div style='background:#fef2f2;border:1px solid #f87171;color:#b91c1c;padding:12px;border-radius:8px;margin-bottom:16px;font-size:0.9rem;'><strong>Koneksi Gagal:</strong> " . htmlspecialchars($lastErr) . "<br><small>User / Password / Host tidak cocok dengan cluster aktif.</small></div>" : "";

            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Koneksi TiDB Cloud - wishuwashere.</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            </head>
            <body style='background:#f0f9ff;font-family:system-ui,-apple-system,sans-serif;'>
                <div class='container py-5' style='max-width:680px;'>
                    <div class='card shadow-sm border-0' style='border-radius:16px;'>
                        <div class='card-header text-white p-4' style='background:linear-gradient(135deg, #0284c7, #0369a1);border-radius:16px 16px 0 0;'>
                            <h4 class='fw-bold mb-1'>☁️ Setup Koneksi TiDB Cloud</h4>
                            <p class='mb-0 small opacity-75'>wishuwashere. Laundry Kelompok 4</p>
                        </div>
                        <div class='card-body p-4'>
                            {$currentErrHtml}

                            <div class='alert alert-info border-0 mb-4' style='background:#e0f2fe;color:#0369a1;border-radius:10px;'>
                                <div class='fw-bold mb-1'>📌 Cara ambil info dari TiDB Cloud:</div>
                                <ol class='ps-3 mb-0 small'>
                                    <li>Buka tab <strong>TiDB Cloud</strong> di browsermu</li>
                                    <li>Klik nama cluster: <strong>aabw-pink-cluster</strong></li>
                                    <li>Klik tombol <strong>Connect</strong> di kanan atas</li>
                                    <li>Pilih tab <strong>Parameters</strong>, lalu copy <strong>HOST</strong>, <strong>USERNAME</strong>, dan <strong>PASSWORD</strong> ke form di bawah:</li>
                                </ol>
                            </div>

                            <form action='" . base_url('setup-tidb') . "' method='post'>
                                <div class='mb-3'>
                                    <label class='form-label fw-bold small text-secondary'>HOST</label>
                                    <input type='text' name='host' class='form-control' value='" . htmlspecialchars($host) . "' placeholder='misal: gateway01.ap-southeast-1.prod.aws.tidbcloud.com' required>
                                </div>
                                <div class='row g-3 mb-3'>
                                    <div class='col-8'>
                                        <label class='form-label fw-bold small text-secondary'>USERNAME</label>
                                        <input type='text' name='user' class='form-control' value='" . htmlspecialchars($user ?: '3rDxFxgkognB5J2.root') . "' placeholder='misal: xxxx.root' required>
                                    </div>
                                    <div class='col-4'>
                                        <label class='form-label fw-bold small text-secondary'>PORT</label>
                                        <input type='number' name='port' class='form-control' value='{$port}' required>
                                    </div>
                                </div>
                                <div class='mb-4'>
                                    <label class='form-label fw-bold small text-secondary'>PASSWORD</label>
                                    <input type='text' name='pass' class='form-control' value='" . htmlspecialchars($pass ?: 'ILv3ndVtFmLLvqS2') . "' placeholder='Masukkan password TiDB' required>
                                    <div class='form-text'>Jika belum ada password, klik <em>Generate Password</em> di jendela Connect TiDB Cloud.</div>
                                </div>

                                <button type='submit' class='btn btn-primary w-100 py-2 fw-bold shadow-sm' style='background:#0284c7;border:none;border-radius:10px;'>
                                    🚀 Hubungkan &amp; Setup Database Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

            return $this->response->setBody($html);
        }

        // Buat database db_laundry jika belum ada
        mysqli_query($mysqli, "CREATE DATABASE IF NOT EXISTS db_laundry CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        mysqli_select_db($mysqli, "db_laundry");

        // Baca file database_railway.sql
        $sqlFile = FCPATH . '../database_railway.sql';
        if (!file_exists($sqlFile)) {
            $sqlFile = 'C:\\xampp\\htdocs\\laundry\\database_railway.sql';
        }

        if (!file_exists($sqlFile)) {
            return $this->response->setBody("<h3 style='color:red;'>File database_railway.sql tidak ditemukan</h3>");
        }

        $sql = file_get_contents($sqlFile);
        $sql = str_replace("\r\n", "\n", $sql);
        $queries = explode(";\n", $sql);
        $executed = 0;
        $errors = [];

        foreach ($queries as $q) {
            $q = trim($q);
            if (!empty($q) && substr($q, 0, 2) !== '--') {
                if (!mysqli_query($mysqli, $q)) {
                    $errors[] = mysqli_error($mysqli);
                } else {
                    $executed++;
                }
            }
        }

        mysqli_close($mysqli);

        // Update Database.php otomatis dengan credential yang valid!
        $dbConfigFile = APPPATH . 'Config/Database.php';
        if (file_exists($dbConfigFile)) {
            $content = file_get_contents($dbConfigFile);
            $content = preg_replace("/'gateway01\.[^']+'/", "'{$validHost}'", $content);
            $content = preg_replace("/'[^']+\.root'/", "'{$validUser}'", $content);
            $content = preg_replace("/\$this->default\['password'\] = getenv\('MYSQLPASSWORD'\) \?: '[^']*';/", "\$this->default['password'] = getenv('MYSQLPASSWORD') ?: '{$validPass}';", $content);
            file_put_contents($dbConfigFile, $content);
        }

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Sukses Setup TiDB - wishuwashere.</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body style='background:#f0fdf4;font-family:system-ui,-apple-system,sans-serif;'>
            <div class='container py-5' style='max-width:600px;'>
                <div class='card shadow-sm border-0' style='border-radius:16px;'>
                    <div class='card-header text-white p-4' style='background:linear-gradient(135deg, #16a34a, #15803d);border-radius:16px 16px 0 0;'>
                        <h4 class='fw-bold mb-1'>✅ Berhasil Terhubung ke TiDB Cloud!</h4>
                        <p class='mb-0 small opacity-75'>wishuwashere. Laundry</p>
                    </div>
                    <div class='card-body p-4'>
                        <div class='alert alert-success border-0 mb-3' style='background:#dcfce7;color:#166534;border-radius:10px;'>
                            Database <strong>db_laundry</strong> dan <strong>{$executed} query</strong> tabel & data user default berhasil diisi ke TiDB Cloud!
                        </div>
                        <ul class='list-group list-group-flush mb-4 small'>
                            <li class='list-group-item d-flex justify-content-between'><span>Host:</span><strong class='text-break'>{$validHost}</strong></li>
                            <li class='list-group-item d-flex justify-content-between'><span>User:</span><strong>{$validUser}</strong></li>
                            <li class='list-group-item d-flex justify-content-between'><span>Database:</span><strong>db_laundry</strong></li>
                            <li class='list-group-item d-flex justify-content-between'><span>Status:</span><span class='badge bg-success'>SSL Connected &amp; Ready</span></li>
                        </ul>
                        <div class='d-grid gap-2'>
                            <a href='" . base_url() . "' class='btn btn-success fw-bold py-2' style='border-radius:10px;'>← Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $this->response->setBody($html);
    }

    public function checkToken()
    {
        $token = trim((string)$this->request->getVar('token'));
        if (empty($token)) {
            return $this->response->setJSON(['status' => 'empty']);
        }

        // Test Vercel API
        $chV = curl_init('https://api.vercel.com/v2/user');
        curl_setopt($chV, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token", "User-Agent: CI4-Deployer"]);
        curl_setopt($chV, CURLOPT_RETURNTRANSFER, true);
        $resV = curl_exec($chV);
        $codeV = curl_getinfo($chV, CURLINFO_HTTP_CODE);
        curl_close($chV);

        // Test GitHub API
        $chG = curl_init('https://api.github.com/user');
        curl_setopt($chG, CURLOPT_HTTPHEADER, ["Authorization: token $token", "User-Agent: CI4-Deployer"]);
        curl_setopt($chG, CURLOPT_RETURNTRANSFER, true);
        $resG = curl_exec($chG);
        $codeG = curl_getinfo($chG, CURLINFO_HTTP_CODE);
        curl_close($chG);

        return $this->response->setJSON([
            'vercel' => ['code' => $codeV, 'res' => json_decode($resV, true)],
            'github' => ['code' => $codeG, 'res' => json_decode($resG, true)],
        ]);
    }

    public function deployVercelApi()
    {
        $token = trim((string)$this->request->getVar('token'));
        if (empty($token)) {
            return $this->response->setJSON(['error' => 'Token required']);
        }

        // 1. Cek atau Buat Project di Vercel via API
        $ch = curl_init('https://api.vercel.com/v9/projects');
        $payload = json_encode([
            'name' => 'laundry-wishuwashere-kelompok4',
            'framework' => null,
            'gitRepository' => [
                'type' => 'github',
                'repo' => 'BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2'
            ]
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "User-Agent: CI4-Deployer"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->response->setJSON([
            'http_code' => $code,
            'response'  => json_decode($res, true)
        ]);
    }

    /**
     * SEED DATA NOVEMBER 2026 - Langsung ke TiDB Cloud Vercel
     * Akses: /seed-data-november
     * Menyeed: pelanggan, transaksi, jurnal umum, jurnal penyesuaian, bagan akun lengkap
     */
    public function seedDataNovember()
    {
        set_time_limit(120);
        $db = \Config\Database::connect();
        $log = [];
        $errors = [];

        try {
            // ============================
            // 1. BAGAN AKUN LENGKAP
            // ============================
            $akunList = [
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Lancar',  'kode_akun' => '1101', 'nama_akun3' => 'Kas di Tangan / Bank',                'posisi' => 'Debit'],
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Lancar',  'kode_akun' => '1102', 'nama_akun3' => 'Piutang Usaha Pelanggan',              'posisi' => 'Debit'],
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Lancar',  'kode_akun' => '1103', 'nama_akun3' => 'Perlengkapan Deterjen & Parfum',       'posisi' => 'Debit'],
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Lancar',  'kode_akun' => '1104', 'nama_akun3' => 'Sewa Dibayar di Muka',                 'posisi' => 'Debit'],
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Tetap',   'kode_akun' => '1201', 'nama_akun3' => 'Peralatan Mesin Cuci & Dryer',         'posisi' => 'Debit'],
                ['nama_akun1' => 'Aset',       'nama_akun2' => 'Aset Tetap',   'kode_akun' => '1202', 'nama_akun3' => 'Akumulasi Penyusutan Peralatan',       'posisi' => 'Kredit'],
                ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2101', 'nama_akun3' => 'Utang Usaha / Operasional',      'posisi' => 'Kredit'],
                ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2102', 'nama_akun3' => 'Beban Terutang (Accrued Expense)','posisi' => 'Kredit'],
                ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2103', 'nama_akun3' => 'Pendapatan Diterima di Muka',    'posisi' => 'Kredit'],
                ['nama_akun1' => 'Ekuitas',    'nama_akun2' => 'Ekuitas Pemilik',   'kode_akun' => '3101', 'nama_akun3' => 'Modal Pemilik wishuwashere.',     'posisi' => 'Kredit'],
                ['nama_akun1' => 'Ekuitas',    'nama_akun2' => 'Ekuitas Pemilik',   'kode_akun' => '3102', 'nama_akun3' => 'Prive Pemilik wishuwashere.',     'posisi' => 'Debit'],
                ['nama_akun1' => 'Pendapatan', 'nama_akun2' => 'Pendapatan Jasa Laundry', 'kode_akun' => '4101', 'nama_akun3' => 'Pendapatan Jasa Laundry',  'posisi' => 'Kredit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5101', 'nama_akun3' => 'Beban Pemakaian Bahan Deterjen', 'posisi' => 'Debit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5102', 'nama_akun3' => 'Beban Listrik, Air & Gas',       'posisi' => 'Debit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5103', 'nama_akun3' => 'Beban Gaji Karyawan',             'posisi' => 'Debit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5104', 'nama_akun3' => 'Beban Perawatan Mesin / Servis',  'posisi' => 'Debit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Perlengkapan & Penyusutan',  'kode_akun' => '5105', 'nama_akun3' => 'Beban Penyusutan Peralatan Mesin','posisi' => 'Debit'],
                ['nama_akun1' => 'Beban',      'nama_akun2' => 'Beban Perlengkapan & Penyusutan',  'kode_akun' => '5106', 'nama_akun3' => 'Beban Perlengkapan & Operasional Lain', 'posisi' => 'Debit'],
            ];
            $akunSeeded = 0;
            foreach ($akunList as $a) {
                $existing = $db->table('akun')->where('kode_akun', $a['kode_akun'])->get()->getRow();
                if (!$existing) {
                    $db->table('akun')->insert($a);
                    $akunSeeded++;
                }
            }
            $log[] = "✅ Bagan Akun: {$akunSeeded} akun baru ditambahkan.";

            // ============================
            // 2. PAKET LAUNDRY
            // ============================
            $pakets = [
                ['id' => 1, 'nama_paket' => 'Cuci Komplit Reguler (2 Hari)', 'jenis' => 'kiloan', 'harga' => 7000,  'estimasi_hari' => 2],
                ['id' => 2, 'nama_paket' => 'Cuci Komplit Kilat (1 Hari)',   'jenis' => 'kiloan', 'harga' => 10000, 'estimasi_hari' => 1],
                ['id' => 3, 'nama_paket' => 'Cuci Komplit Express (6 Jam)',  'jenis' => 'kiloan', 'harga' => 15000, 'estimasi_hari' => 1],
                ['id' => 4, 'nama_paket' => 'Cuci Lipat Wangi (Tanpa Setrika)', 'jenis' => 'kiloan', 'harga' => 5000, 'estimasi_hari' => 2],
                ['id' => 5, 'nama_paket' => 'Setrika Uap Presisi Saja',     'jenis' => 'kiloan', 'harga' => 4500,  'estimasi_hari' => 1],
                ['id' => 6, 'nama_paket' => 'Bed Cover Single / Selimut Tebal', 'jenis' => 'satuan', 'harga' => 25000, 'estimasi_hari' => 3],
                ['id' => 7, 'nama_paket' => 'Bed Cover King Size / Sprei Jumbo', 'jenis' => 'satuan', 'harga' => 35000, 'estimasi_hari' => 3],
                ['id' => 8, 'nama_paket' => 'Jas / Blazer / Gaun Pesta Care', 'jenis' => 'satuan', 'harga' => 30000, 'estimasi_hari' => 2],
            ];
            $paketSeeded = 0;
            foreach ($pakets as $p) {
                $ex = $db->table('paket')->where('id', $p['id'])->get()->getRow();
                if (!$ex) {
                    $db->table('paket')->insert($p);
                    $paketSeeded++;
                }
            }
            $log[] = "✅ Paket Laundry: {$paketSeeded} paket baru ditambahkan.";

            // ============================
            // 3. PELANGGAN (20 orang)
            // ============================
            $pelangganList = [
                ['nama' => 'Ibu Siti Aminah',         'no_hp' => '081234567801', 'alamat' => 'Jl. Pajajaran No. 12, Bogor'],
                ['nama' => 'Bpk. Hendra Wijaya',      'no_hp' => '081234567802', 'alamat' => 'Perum Baranangsiang Indah Blok B4'],
                ['nama' => 'Kak Anisa Rahma',          'no_hp' => '081234567803', 'alamat' => 'Kost Putri Melati, Dramaga Bogor'],
                ['nama' => 'Mas Dimas Prasetyo',       'no_hp' => '081234567804', 'alamat' => 'Jl. Malabar Ujung No. 8'],
                ['nama' => 'Ibu Ratna Sari',           'no_hp' => '081234567805', 'alamat' => 'Komplek IPB Baranangsiang A-10'],
                ['nama' => 'Bpk. Bambang Sutrisno',   'no_hp' => '081234567806', 'alamat' => 'Jl. Pandu Raya No. 45'],
                ['nama' => 'Mbak Nadia Safitri',       'no_hp' => '081234567807', 'alamat' => 'Apartemen Bogor Icon Tower A'],
                ['nama' => 'Mas Rizky Ramadhan',       'no_hp' => '081234567808', 'alamat' => 'Jl. Kumbang No. 3, Bogor Tengah'],
                ['nama' => 'Ibu Dewi Sartika',         'no_hp' => '081234567809', 'alamat' => 'Perumahan Bantar Kemang Asri C-12'],
                ['nama' => 'Mas Aditya Pratama',       'no_hp' => '081234567810', 'alamat' => 'Kost Putra IPB Dramaga'],
                ['nama' => 'Ibu Lestari Handayani',    'no_hp' => '082234561011', 'alamat' => 'Jl. Paledang No. 3, Bogor Tengah'],
                ['nama' => 'Bpk. Yusuf Hidayat',      'no_hp' => '083334561012', 'alamat' => 'Komplek Bumi Mutiara Blok C No. 5'],
                ['nama' => 'Mbak Siska Amelia',        'no_hp' => '087733445566', 'alamat' => 'Kost Putri Mahasiswi IPB Dramaga'],
                ['nama' => 'Mas Fajar Ramadhan',       'no_hp' => '085611778899', 'alamat' => 'Jl. Pandu Raya No. 15, Bogor Utara'],
                ['nama' => 'Ibu Maya Indrawati',       'no_hp' => '081277332211', 'alamat' => 'Villa Duta Blok C No. 7, Bogor'],
                ['nama' => 'Bpk. Ir. Gunawan',         'no_hp' => '081388001122', 'alamat' => 'Perum Danau Bogor Raya Blok D'],
                ['nama' => 'Kak Fitri Handayani',      'no_hp' => '081299445577', 'alamat' => 'Jl. Sukasari III No. 22, Bogor'],
                ['nama' => 'Mas Rendi Firmansyah',     'no_hp' => '082298765432', 'alamat' => 'Kost Mahasiswa Darmaga, Gang Karet'],
                ['nama' => 'Ibu Suci Rahayu',          'no_hp' => '085898761234', 'alamat' => 'Jl. Pangrango No. 8, Bogor Tengah'],
                ['nama' => 'Bpk. Cahyo Nugroho',       'no_hp' => '081312349876', 'alamat' => 'Komplek Mutiara Bogor Blok F7'],
            ];
            $plgSeeded = 0;
            foreach ($pelangganList as $p) {
                $ex = $db->table('pelanggan')->where('no_hp', $p['no_hp'])->get()->getRow();
                if (!$ex) {
                    $p['created_at'] = date('Y-m-d H:i:s');
                    $p['updated_at'] = date('Y-m-d H:i:s');
                    $db->table('pelanggan')->insert($p);
                    $plgSeeded++;
                }
            }
            $allPelanggan = $db->table('pelanggan')->orderBy('id', 'ASC')->get()->getResultArray();
            $allPaket     = $db->table('paket')->orderBy('id', 'ASC')->get()->getResultArray();
            $log[] = "✅ Pelanggan: {$plgSeeded} pelanggan baru ditambahkan. Total: " . count($allPelanggan);

            // ============================
            // 4. TRANSAKSI KASIR NOVEMBER 2026 (50 transaksi)
            // ============================
            $dataset = [
                // Format: [plg_idx, pkt_idx, qty, bayar, metode, status, tgl, catatan]
                [0, 0, 4.5, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-01 08:30:00', 'Pewangi lavender'],
                [1, 6, 2.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-01 09:15:00', 'Bed cover king size'],
                [2, 1, 6.0, 'Lunas',       'Tunai',            'Siap Diambil',  '2026-11-02 10:00:00', 'Express jangan kusut'],
                [3, 7, 3.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-02 11:20:00', 'Jas kantor dokter'],
                [4, 0, 7.2, 'Belum Lunas', 'Tunai',            'Siap Diambil',  '2026-11-03 13:45:00', 'Bayar pas akhir bulan'],
                [5, 5, 2.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-03 14:10:00', 'Sepatu putih & running'],
                [6, 0, 5.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-04 08:50:00', 'Pakaian anak dipisah'],
                [7, 2, 8.5, 'Lunas',       'Tunai',            'Siap Diambil',  '2026-11-04 15:30:00', 'Lipat rapi'],
                [8, 6, 1.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-05 10:20:00', 'Selimut bulu lembut'],
                [9, 1, 4.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil',  '2026-11-05 11:00:00', 'Baju dinas'],
                [10, 0, 6.5, 'Belum Lunas','Transfer / QRIS', 'Siap Diambil',  '2026-11-06 10:40:00', 'Piutang langganan'],
                [11, 0, 5.8, 'Lunas',       'Tunai',           'Siap Diambil',  '2026-11-06 09:30:00', 'Baju harian'],
                [0, 1, 5.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-07 12:15:00', 'Langganan Bu Siti'],
                [1, 0, 9.2, 'Lunas',        'Tunai',           'Siap Diambil',  '2026-11-07 14:00:00', 'Pakaian keluarga besar'],
                [2, 7, 2.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-08 10:25:00', 'Blazer kerja'],
                [3, 6, 2.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-08 16:50:00', 'Bed cover 2 pcs'],
                [4, 1, 4.2, 'Lunas',        'Tunai',           'Siap Diambil',  '2026-11-09 08:40:00', 'Baju dinas pemda'],
                [5, 0, 6.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-10 11:10:00', 'Kemeja kuliah'],
                [6, 5, 3.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-10 15:45:00', 'Sepatu 3 pasang'],
                [7, 0, 7.0, 'Belum Lunas',  'Tunai',           'Siap Diambil',  '2026-11-11 09:20:00', 'Bayar awal bulan depan'],
                [8, 1, 5.5, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-11 13:30:00', 'Packing wangi sakura'],
                [9, 0, 4.8, 'Lunas',        'Tunai',           'Siap Diambil',  '2026-11-12 08:15:00', 'Selesai tepat waktu'],
                [10, 6, 1.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-12 14:00:00', 'Bed cover king'],
                [11, 0, 8.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-13 09:50:00', 'Kaos dan celana'],
                [0, 2, 6.2, 'Lunas',        'Tunai',           'Siap Diambil',  '2026-11-13 11:30:00', 'Cuci lipat'],
                [1, 1, 4.0, 'Belum Lunas',  'Transfer / QRIS', 'Siap Diambil', '2026-11-14 08:45:00', 'Cucian express kredit'],
                [2, 5, 1.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-14 10:15:00', 'Sepatu vans'],
                [3, 0, 5.2, 'Lunas',        'Tunai',           'Siap Diambil',  '2026-11-15 13:20:00', 'Sprei & sarung bantal'],
                [4, 7, 1.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-15 09:00:00', 'Jas resepsi'],
                [5, 0, 6.8, 'Belum Lunas',  'Tunai',           'Siap Diambil',  '2026-11-16 11:30:00', 'Bayar pas gajian'],
                [6, 1, 3.5, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-16 14:15:00', 'Express besok sore'],
                [7, 6, 1.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-17 09:00:00', 'Bed cover tebal'],
                [12, 0, 5.5, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-17 11:15:00', 'Pakaian kantor rapi'],
                [13, 1, 4.2, 'Lunas',       'Tunai',           'Siap Diambil',  '2026-11-18 09:40:00', 'Express 1 hari'],
                [14, 6, 2.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-18 14:20:00', 'Bed cover katun jepang'],
                [15, 7, 1.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-19 10:10:00', 'Jas resepsi pernikahan'],
                [16, 0, 6.0, 'Belum Lunas', 'Transfer / QRIS', 'Siap Diambil', '2026-11-19 13:50:00', 'Bayar pas gajian'],
                [17, 5, 2.0, 'Lunas',       'Tunai',           'Siap Diambil',  '2026-11-20 16:30:00', 'Sepatu futsal & sneakers'],
                [18, 0, 8.4, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-20 08:30:00', 'Sprei villa & sarung bantal'],
                [19, 1, 5.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-21 11:00:00', 'Baju dinas pemkot'],
                [12, 2, 7.0, 'Lunas',       'Tunai',           'Siap Diambil',  '2026-11-21 15:10:00', 'Cuci lipat hemat'],
                [13, 0, 4.6, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-22 10:20:00', 'Pewangi ocean fresh'],
                [14, 6, 1.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-22 13:40:00', 'Bed cover king'],
                [15, 1, 3.8, 'Belum Lunas', 'Tunai',           'Siap Diambil',  '2026-11-22 09:15:00', 'Express kilat'],
                [16, 7, 2.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-23 10:45:00', 'Jas wisuda IPB'],
                [17, 0, 5.2, 'Lunas',       'Tunai',           'Siap Diambil',  '2026-11-23 15:30:00', 'Pakaian harian kost'],
                [18, 5, 1.0, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-24 08:30:00', 'Sepatu running nike'],
                [19, 0, 6.5, 'Lunas',       'Transfer / QRIS', 'Siap Diambil', '2026-11-24 09:15:00', 'Cuci setrika reguler'],
                [0, 1, 4.5, 'Belum Lunas',  'Tunai',           'Siap Diambil',  '2026-11-24 10:00:00', 'Pelanggan langganan Bu Siti'],
                [1, 6, 1.0, 'Lunas',        'Transfer / QRIS', 'Siap Diambil', '2026-11-25 10:30:00', 'Bed cover anak motif kartun'],
            ];

            $transaksiSeeded = 0;
            $counter = 1001;
            foreach ($dataset as $row) {
                $plg = $allPelanggan[$row[0] % count($allPelanggan)];
                $pkt = $allPaket[$row[1] % count($allPaket)];
                $qty = (float) $row[2];
                $bayar = $row[3];
                $metode = $row[4];
                $status = $row[5];
                $tgl = $row[6];
                $catatan = $row[7];

                $total = (int) round($qty * $pkt['harga']);
                $kdTrans = 'KW-' . date('d', strtotime($tgl)) . str_pad($counter, 3, '0', STR_PAD_LEFT);

                // Pastikan kode unik
                while ($db->table('transaksi')->where('kode_transaksi', $kdTrans)->get()->getRow()) {
                    $counter++;
                    $kdTrans = 'KW-' . date('d', strtotime($tgl)) . str_pad($counter, 3, '0', STR_PAD_LEFT);
                }

                $estHari = $pkt['estimasi_hari'] ?? 2;
                $tglSelesai = date('Y-m-d H:i:s', strtotime($tgl . " +{$estHari} days"));

                $db->table('transaksi')->insert([
                    'kode_transaksi' => $kdTrans,
                    'pelanggan_id'   => $plg['id'],
                    'paket_id'       => $pkt['id'],
                    'berat_qty'      => $qty,
                    'total_biaya'    => $total,
                    'status_cucian'  => $status,
                    'status_bayar'   => $bayar,
                    'metode_bayar'   => $metode,
                    'catatan'        => $catatan,
                    'tgl_masuk'      => $tgl,
                    'tgl_selesai'    => $tglSelesai,
                    'created_at'     => $tgl,
                    'updated_at'     => $tgl,
                ]);

                // Jurnal Umum otomatis dari kasir
                $noJurnal = $kdTrans . '-J';
                $existingJurnal = $db->table('jurnal_umum')->where('no_bukti', $noJurnal)->get()->getRow();
                if (!$existingJurnal) {
                    if ($bayar === 'Lunas') {
                        $db->table('jurnal_umum')->insert([
                            'no_bukti'        => $noJurnal,
                            'tanggal'         => date('Y-m-d', strtotime($tgl)),
                            'tipe_transaksi'  => 'Penerimaan Pendapatan',
                            'keterangan'      => "Pendapatan Cucian {$pkt['nama_paket']} - {$plg['nama']} ({$kdTrans}) [{$metode}]",
                            'debit_kode_akun' => '1101',
                            'debit_nama_akun' => 'Kas di Tangan / Bank',
                            'kredit_kode_akun'=> '4101',
                            'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                            'nominal'         => $total,
                            'created_at'      => $tgl,
                            'updated_at'      => $tgl,
                        ]);
                    } else {
                        $db->table('jurnal_umum')->insert([
                            'no_bukti'        => $noJurnal,
                            'tanggal'         => date('Y-m-d', strtotime($tgl)),
                            'tipe_transaksi'  => 'Piutang Laundry',
                            'keterangan'      => "Piutang Cucian {$pkt['nama_paket']} - {$plg['nama']} ({$kdTrans})",
                            'debit_kode_akun' => '1102',
                            'debit_nama_akun' => 'Piutang Usaha Pelanggan',
                            'kredit_kode_akun'=> '4101',
                            'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                            'nominal'         => $total,
                            'created_at'      => $tgl,
                            'updated_at'      => $tgl,
                        ]);
                    }
                }

                $counter++;
                $transaksiSeeded++;
            }
            $log[] = "✅ Transaksi Kasir November 2026: {$transaksiSeeded} transaksi berhasil diseed.";

            // ============================
            // 5. JURNAL UMUM TAMBAHAN (Modal, Pembelian, Beban)
            // ============================
            $jurnalTambahan = [
                ['no_bukti' => 'JU-2611-001', 'tanggal' => '2026-11-01', 'tipe_transaksi' => 'Penyetoran Modal',        'keterangan' => 'Setoran Modal Awal Pendirian Usaha wishuwashere. Laundry', 'debit_kode_akun' => '1101', 'debit_nama_akun' => 'Kas di Tangan / Bank', 'kredit_kode_akun' => '3101', 'kredit_nama_akun' => 'Modal Pemilik wishuwashere.', 'nominal' => 50000000],
                ['no_bukti' => 'BKK-2611-001','tanggal' => '2026-11-03', 'tipe_transaksi' => 'Pembelian Perlengkapan', 'keterangan' => 'Pembelian Bahan Deterjen Matic & Softener Wangi 5 Jerigen (Tunai)', 'debit_kode_akun' => '1103', 'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 450000],
                ['no_bukti' => 'BKK-2611-002','tanggal' => '2026-11-07', 'tipe_transaksi' => 'Pembelian Perlengkapan', 'keterangan' => 'Pembelian Stok Plastik Packing Bening & Hanger Kawat Laundry (Tunai)', 'debit_kode_akun' => '1103', 'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 320000],
                ['no_bukti' => 'FB-2611-001', 'tanggal' => '2026-11-10', 'tipe_transaksi' => 'Pembelian Perlengkapan', 'keterangan' => 'Pembelian Stok Bahan Kimia, Alkali & Spotting Anti-Noda (Kredit CV Sumber Wangi)', 'debit_kode_akun' => '1103', 'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum', 'kredit_kode_akun' => '2101', 'kredit_nama_akun' => 'Utang Usaha / Operasional', 'nominal' => 1250000],
                ['no_bukti' => 'BKK-2611-003','tanggal' => '2026-11-14', 'tipe_transaksi' => 'Pembelian Peralatan',    'keterangan' => 'Pembelian Mesin Steamer Boiler Uap Listrik Tambahan (Tunai)', 'debit_kode_akun' => '1201', 'debit_nama_akun' => 'Peralatan Mesin Cuci & Dryer', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 2500000],
                ['no_bukti' => 'BKK-2611-004','tanggal' => '2026-11-18', 'tipe_transaksi' => 'Pembayaran Utang',       'keterangan' => 'Pembayaran Sebagian Utang Usaha Pembelian Bahan ke CV Sumber Wangi', 'debit_kode_akun' => '2101', 'debit_nama_akun' => 'Utang Usaha / Operasional', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 750000],
                ['no_bukti' => 'BKK-2611-005','tanggal' => '2026-11-21', 'tipe_transaksi' => 'Pengeluaran Beban',      'keterangan' => 'Pembayaran Jasa Servis Rutin & Penggantian Sparepart V-Belt Mesin Cuci', 'debit_kode_akun' => '5104', 'debit_nama_akun' => 'Beban Perawatan Mesin / Servis', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 350000],
                ['no_bukti' => 'BKK-2611-006','tanggal' => '2026-11-01', 'tipe_transaksi' => 'Sewa Dibayar di Muka',  'keterangan' => 'Pembayaran Sewa Tempat/Kios Usaha Laundry Dimuka 1 Tahun (Tunai)', 'debit_kode_akun' => '1104', 'debit_nama_akun' => 'Sewa Dibayar di Muka', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 1800000],
                ['no_bukti' => 'BKK-2611-007','tanggal' => '2026-11-16', 'tipe_transaksi' => 'Penarikan Prive',        'keterangan' => 'Penarikan Kas Prive Pemilik wishuwashere. untuk Keperluan Pribadi', 'debit_kode_akun' => '3102', 'debit_nama_akun' => 'Prive Pemilik wishuwashere.', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 850000],
                ['no_bukti' => 'BKK-2611-008','tanggal' => '2026-11-20', 'tipe_transaksi' => 'Pembayaran Beban Gaji',  'keterangan' => 'Pembayaran Gaji Karyawan Operasional Kasir & Penyetrika Paruh Waktu (Tunai)', 'debit_kode_akun' => '5103', 'debit_nama_akun' => 'Beban Gaji Karyawan', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 1250000],
                ['no_bukti' => 'BKK-2611-009','tanggal' => '2026-11-23', 'tipe_transaksi' => 'Pengeluaran Beban',      'keterangan' => 'Pembayaran Biaya Cetak Banner, Brosur & Iklan Promosi Laundry (Tunai)', 'debit_kode_akun' => '5106', 'debit_nama_akun' => 'Beban Perlengkapan & Operasional Lain', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 300000],
                ['no_bukti' => 'BKM-2611-002','tanggal' => '2026-11-08', 'tipe_transaksi' => 'Penerimaan Pendapatan',  'keterangan' => 'Penerimaan Pendapatan Jasa Laundry Borongan Seragam Hotel Salak Heritage', 'debit_kode_akun' => '1101', 'debit_nama_akun' => 'Kas di Tangan / Bank', 'kredit_kode_akun' => '4101', 'kredit_nama_akun' => 'Pendapatan Jasa Laundry', 'nominal' => 1450000],
                ['no_bukti' => 'BKM-2611-003','tanggal' => '2026-11-15', 'tipe_transaksi' => 'Penerimaan Pendapatan',  'keterangan' => 'Penerimaan Pendapatan Jasa Cuci Taplak Meja, Gordyn & Banner Wisuda IPB', 'debit_kode_akun' => '1101', 'debit_nama_akun' => 'Kas di Tangan / Bank', 'kredit_kode_akun' => '4101', 'kredit_nama_akun' => 'Pendapatan Jasa Laundry', 'nominal' => 2100000],
                ['no_bukti' => 'BKM-2611-004','tanggal' => '2026-11-19', 'tipe_transaksi' => 'Penerimaan Pendapatan',  'keterangan' => 'Penerimaan Uang Muka Paket Laundry Bulanan Kost Mahasiswa Baranangsiang', 'debit_kode_akun' => '1101', 'debit_nama_akun' => 'Kas di Tangan / Bank', 'kredit_kode_akun' => '2103', 'kredit_nama_akun' => 'Pendapatan Diterima di Muka', 'nominal' => 600000],
                ['no_bukti' => 'BKM-2611-005','tanggal' => '2026-11-22', 'tipe_transaksi' => 'Pelunasan Piutang',      'keterangan' => 'Pelunasan Piutang Usaha Cucian dari Pelanggan Kantor Notaris', 'debit_kode_akun' => '1101', 'debit_nama_akun' => 'Kas di Tangan / Bank', 'kredit_kode_akun' => '1102', 'kredit_nama_akun' => 'Piutang Usaha Pelanggan', 'nominal' => 380000],
                ['no_bukti' => 'FB-2611-002', 'tanggal' => '2026-11-05', 'tipe_transaksi' => 'Pendapatan Jasa',        'keterangan' => 'Pendapatan Jasa Laundry Seragam Kantor Notaris & Rekanan (Kredit / Tempo)', 'debit_kode_akun' => '1102', 'debit_nama_akun' => 'Piutang Usaha Pelanggan', 'kredit_kode_akun' => '4101', 'kredit_nama_akun' => 'Pendapatan Jasa Laundry', 'nominal' => 1200000],
                ['no_bukti' => 'BKK-2611-010','tanggal' => '2026-11-25', 'tipe_transaksi' => 'Pengeluaran Beban',      'keterangan' => 'Pembayaran Tagihan Listrik & Air Bulan November 2026', 'debit_kode_akun' => '5102', 'debit_nama_akun' => 'Beban Listrik, Air & Gas', 'kredit_kode_akun' => '1101', 'kredit_nama_akun' => 'Kas di Tangan / Bank', 'nominal' => 480000],
            ];
            $jurnalSeeded = 0;
            foreach ($jurnalTambahan as $j) {
                $ex = $db->table('jurnal_umum')->where('no_bukti', $j['no_bukti'])->get()->getRow();
                if (!$ex) {
                    $j['created_at'] = $j['tanggal'] . ' 00:00:00';
                    $j['updated_at'] = $j['tanggal'] . ' 00:00:00';
                    $db->table('jurnal_umum')->insert($j);
                    $jurnalSeeded++;
                }
            }
            $log[] = "✅ Jurnal Umum Tambahan: {$jurnalSeeded} entri baru ditambahkan.";

            // ============================
            // 6. JURNAL PENYESUAIAN AJE
            // ============================
            $penyesuaian = [
                ['no_bukti' => 'AJP-261130-001', 'tanggal' => '2026-11-30', 'keterangan' => 'Perlengkapan Deterjen Terpakai selama November 2026',         'debit_kode_akun' => '5101', 'debit_nama_akun' => 'Beban Pemakaian Bahan Deterjen',       'kredit_kode_akun' => '1103', 'kredit_nama_akun' => 'Perlengkapan Deterjen & Parfum',       'nominal' => 550000],
                ['no_bukti' => 'AJP-261130-002', 'tanggal' => '2026-11-30', 'keterangan' => 'Penyusutan Peralatan/Mesin Cuci Bulan November 2026',           'debit_kode_akun' => '5105', 'debit_nama_akun' => 'Beban Penyusutan Peralatan Mesin',    'kredit_kode_akun' => '1202', 'kredit_nama_akun' => 'Akumulasi Penyusutan Peralatan',    'nominal' => 350000],
                ['no_bukti' => 'AJP-261130-003', 'tanggal' => '2026-11-30', 'keterangan' => 'Beban Gaji Karyawan Terutang Akhir November 2026 (Accrued)',    'debit_kode_akun' => '5103', 'debit_nama_akun' => 'Beban Gaji Karyawan',                  'kredit_kode_akun' => '2102', 'kredit_nama_akun' => 'Beban Terutang (Accrued Expense)',  'nominal' => 500000],
                ['no_bukti' => 'AJP-261130-004', 'tanggal' => '2026-11-30', 'keterangan' => 'Beban Listrik/Air Terutang Akhir November 2026 (Accrued)',      'debit_kode_akun' => '5102', 'debit_nama_akun' => 'Beban Listrik, Air & Gas',             'kredit_kode_akun' => '2102', 'kredit_nama_akun' => 'Beban Terutang (Accrued Expense)',  'nominal' => 275000],
                ['no_bukti' => 'AJP-261130-005', 'tanggal' => '2026-11-30', 'keterangan' => 'Pengakuan Pendapatan Diterima di Muka yang Sudah Terealisasi',  'debit_kode_akun' => '2103', 'debit_nama_akun' => 'Pendapatan Diterima di Muka',         'kredit_kode_akun' => '4101', 'kredit_nama_akun' => 'Pendapatan Jasa Laundry',          'nominal' => 250000],
                ['no_bukti' => 'AJP-261130-006', 'tanggal' => '2026-11-30', 'keterangan' => 'Reklasifikasi Sewa Dibayar di Muka Bagian Yang Telah Jatuh Tempo','debit_kode_akun' => '5106', 'debit_nama_akun' => 'Beban Perlengkapan & Operasional Lain','kredit_kode_akun' => '1104', 'kredit_nama_akun' => 'Sewa Dibayar di Muka',           'nominal' => 150000],
            ];
            $ajeSeeded = 0;
            foreach ($penyesuaian as $aje) {
                $ex = $db->table('jurnal_penyesuaian')->where('no_bukti', $aje['no_bukti'])->get()->getRow();
                if (!$ex) {
                    $aje['created_at'] = $aje['tanggal'] . ' 00:00:00';
                    $aje['updated_at'] = $aje['tanggal'] . ' 00:00:00';
                    $db->table('jurnal_penyesuaian')->insert($aje);
                    $ajeSeeded++;
                }
            }
            $log[] = "✅ Jurnal Penyesuaian AJE: {$ajeSeeded} entri baru ditambahkan.";

        } catch (\Throwable $e) {
            $errors[] = "❌ ERROR: " . $e->getMessage();
        }

        // Hitung total setelah seeding
        $totalTransaksi = $db->table('transaksi')->countAllResults();
        $totalJurnal    = $db->table('jurnal_umum')->countAllResults();
        $totalAJE       = $db->table('jurnal_penyesuaian')->countAllResults();
        $totalPelanggan = $db->table('pelanggan')->countAllResults();

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Seed Data November 2026</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head><body style="background:#f0f9ff;font-family:system-ui;">
        <div class="container py-5" style="max-width:720px;">
        <div class="card shadow border-0" style="border-radius:16px;">
        <div class="card-header text-white p-4" style="background:linear-gradient(135deg,#0284c7,#0369a1);border-radius:16px 16px 0 0;">
        <h4 class="fw-bold mb-1">🌸 Seed Data November 2026 - wishuwashere. Laundry</h4>
        <p class="mb-0 small opacity-80">Kelompok 4 D2 IPB</p></div>
        <div class="card-body p-4">';

        foreach ($log as $l) {
            $html .= "<div class='alert alert-success border-0 mb-2 py-2' style='border-radius:8px;'>{$l}</div>";
        }
        foreach ($errors as $e) {
            $html .= "<div class='alert alert-danger border-0 mb-2 py-2' style='border-radius:8px;'>{$e}</div>";
        }

        $html .= "<hr><div class='row g-3 text-center'>
            <div class='col-6 col-md-3'><div class='card border-primary p-3'><h4 class='fw-bold text-primary mb-0'>{$totalTransaksi}</h4><small class='text-muted'>Transaksi</small></div></div>
            <div class='col-6 col-md-3'><div class='card border-info p-3'><h4 class='fw-bold text-info mb-0'>{$totalJurnal}</h4><small class='text-muted'>Jurnal Umum</small></div></div>
            <div class='col-6 col-md-3'><div class='card border-warning p-3'><h4 class='fw-bold text-warning mb-0'>{$totalAJE}</h4><small class='text-muted'>Jurnal AJE</small></div></div>
            <div class='col-6 col-md-3'><div class='card border-success p-3'><h4 class='fw-bold text-success mb-0'>{$totalPelanggan}</h4><small class='text-muted'>Pelanggan</small></div></div>
        </div>
        <div class='mt-4 d-flex gap-2 flex-wrap'>
            <a href="' . base_url('/') . '" class="btn btn-primary fw-bold">🏠 Dashboard</a>
            <a href="' . base_url('kasir') . '" class="btn btn-outline-primary fw-bold">🧺 Lihat Transaksi</a>
            <a href="' . base_url('laporan') . '" class="btn btn-outline-success fw-bold">📊 Laporan Keuangan</a>
            <a href="' . base_url('neraca-lajur') . '" class="btn btn-outline-info fw-bold">📋 Neraca Lajur</a>
        </div>
        </div></div></div></body></html>';

        return $this->response->setBody($html);
    }

    public function gitDeploy()
    {
        $dir = 'C:\\xampp\\htdocs\\laundry';
        chdir($dir);

        // Matikan prompt interaktif agar Git TIDAK PERNAH HANG menunggu input
        putenv('GIT_TERMINAL_PROMPT=0');
        putenv('GCM_INTERACTIVE=never');

        $token = trim((string)$this->request->getVar('token'));
        if (!empty($token)) {
            // Jika token diisi, gunakan format https://username:token@github.com/...
            $remoteUrl = "https://BestyAnjaliPutri:{$token}@github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2.git";
        } else {
            $remoteUrl = "https://github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2.git";
        }

        $cmds = [
            'git config --global user.name "BestyAnjaliPutri"',
            'git config --global user.email "bestyanjali@gmail.com"',
            'git config --global --add safe.directory C:/xampp/htdocs/laundry',
            'git init',
            'git branch -M main',
            'git remote remove origin 2>&1',
            "git remote add origin $remoteUrl 2>&1",
            'git add .',
            'git add -f vendor',
            'git commit -m "Fix: Add /seed-data-november endpoint to seed 50 transaksi + jurnal November 2026 ke TiDB Cloud" 2>&1',
            'git push -u origin main 2>&1'
        ];

        $output = [];
        foreach ($cmds as $cmd) {
            $maskedCmd = preg_replace('/https:\/\/.*?@/', 'https://***@', $cmd);
            $res = shell_exec($cmd);
            $maskedRes = preg_replace('/https:\/\/.*?@/', 'https://***@', (string)$res);
            $output[] = "=== [CMD] $maskedCmd ===\n" . (trim($maskedRes) ?: '(sukses / no output)') . "\n";
        }

        return $this->response->setContentType('text/plain')->setBody(implode("\n", $output));
    }
}
