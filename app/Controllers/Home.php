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
            'git commit -m "Deploy wishuwashere. Laundry SIA CodeIgniter 4 - Kelompok 4" 2>&1',
            'git -c credential.helper= push -u origin main 2>&1'
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
