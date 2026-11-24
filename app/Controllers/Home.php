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

    public function gitDeploy()
    {
        $dir = 'C:\\xampp\\htdocs\\laundry';
        chdir($dir);

        $token = trim((string)$this->request->getVar('token'));
        if (!empty($token)) {
            $remoteUrl = "https://{$token}@github.com/BestyAnjaliPutri/Laundry-wishuwashere-Kelompok4-D2.git";
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
            'git push -u origin main 2>&1'
        ];

        $output = [];
        foreach ($cmds as $cmd) {
            $maskedCmd = preg_replace('/https:\/\/.*?@/', 'https://***@', $cmd);
            $res = shell_exec($cmd . ' 2>&1');
            $maskedRes = preg_replace('/https:\/\/.*?@/', 'https://***@', (string)$res);
            $output[] = "=== [CMD] $maskedCmd ===\n" . trim($maskedRes) . "\n";
        }

        return $this->response->setContentType('text/plain')->setBody(implode("\n", $output));
    }
}
