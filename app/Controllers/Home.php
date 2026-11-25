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
            'git commit -m "Deploy: Connect wishuwasherelaundry with new active password mJGks9RYclPnbAKQ" 2>&1',
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
