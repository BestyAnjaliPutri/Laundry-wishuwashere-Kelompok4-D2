<?php

namespace App\Controllers;

use App\Models\AkunModel;
use App\Models\Akun1Model;
use App\Models\Akun2Model;
use App\Models\TransaksiModel;

class Akuntansi extends BaseController
{
    protected $akunModel;
    protected $akun1Model;
    protected $akun2Model;

    public function __construct()
    {
        $this->ensureAkunTables();
        $this->akunModel  = new AkunModel();
        $this->akun1Model = new Akun1Model();
        $this->akun2Model = new Akun2Model();
    }

    private function ensureAkunTables()
    {
        try {
            $db = \Config\Database::connect();

            $db->query("CREATE TABLE IF NOT EXISTS akun1 (
                id INT AUTO_INCREMENT PRIMARY KEY,
                kode_akun1 VARCHAR(10) NOT NULL UNIQUE,
                nama_akun1 VARCHAR(100) NOT NULL,
                posisi VARCHAR(20) DEFAULT 'Debit',
                keterangan VARCHAR(255) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $db->query("CREATE TABLE IF NOT EXISTS akun2 (
                id INT AUTO_INCREMENT PRIMARY KEY,
                kode_akun1 VARCHAR(10) NULL,
                nama_akun1 VARCHAR(100) NOT NULL,
                kode_akun2 VARCHAR(10) NOT NULL UNIQUE,
                nama_akun2 VARCHAR(100) NOT NULL,
                posisi VARCHAR(20) DEFAULT 'Debit',
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Seed akun1 jika masih kosong
            $cek1 = $db->query("SELECT COUNT(*) as jml FROM akun1")->getRow();
            if ($cek1 && $cek1->jml == 0) {
                $now = date('Y-m-d H:i:s');
                $defaultAkun1 = [
                    ['kode_akun1' => '1', 'nama_akun1' => 'Aset', 'posisi' => 'Debit', 'keterangan' => 'Klasifikasi Aktiva / Harta Laundry', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '2', 'nama_akun1' => 'Liabilitas', 'posisi' => 'Kredit', 'keterangan' => 'Kewajiban / Hutang Usaha & Beban Terutang', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '3', 'nama_akun1' => 'Ekuitas', 'posisi' => 'Kredit', 'keterangan' => 'Modal Pemilik & Prive', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '4', 'nama_akun1' => 'Pendapatan', 'posisi' => 'Kredit', 'keterangan' => 'Pendapatan Jasa Laundry & Operasional', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '5', 'nama_akun1' => 'Beban', 'posisi' => 'Debit', 'keterangan' => 'Beban Operasional, Gaji, Listrik, Air, & Penyusutan', 'created_at' => $now, 'updated_at' => $now],
                ];
                $db->table('akun1')->insertBatch($defaultAkun1);
            }

            // Seed akun2 jika masih kosong
            $cek2 = $db->query("SELECT COUNT(*) as jml FROM akun2")->getRow();
            if ($cek2 && $cek2->jml == 0) {
                $now = date('Y-m-d H:i:s');
                $defaultAkun2 = [
                    ['kode_akun1' => '1', 'nama_akun1' => 'Aset', 'kode_akun2' => '11', 'nama_akun2' => 'Aset Lancar', 'posisi' => 'Debit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '1', 'nama_akun1' => 'Aset', 'kode_akun2' => '12', 'nama_akun2' => 'Aset Tetap', 'posisi' => 'Debit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '2', 'nama_akun1' => 'Liabilitas', 'kode_akun2' => '21', 'nama_akun2' => 'Liabilitas Lancar (Jangka Pendek)', 'posisi' => 'Kredit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '2', 'nama_akun1' => 'Liabilitas', 'kode_akun2' => '22', 'nama_akun2' => 'Liabilitas Jangka Panjang', 'posisi' => 'Kredit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '3', 'nama_akun1' => 'Ekuitas', 'kode_akun2' => '31', 'nama_akun2' => 'Ekuitas Pemilik', 'posisi' => 'Kredit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '4', 'nama_akun1' => 'Pendapatan', 'kode_akun2' => '41', 'nama_akun2' => 'Pendapatan Jasa Laundry', 'posisi' => 'Kredit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '5', 'nama_akun1' => 'Beban', 'kode_akun2' => '51', 'nama_akun2' => 'Beban Operasional & Administrasi', 'posisi' => 'Debit', 'created_at' => $now, 'updated_at' => $now],
                    ['kode_akun1' => '5', 'nama_akun1' => 'Beban', 'kode_akun2' => '52', 'nama_akun2' => 'Beban Perlengkapan & Penyusutan', 'posisi' => 'Debit', 'created_at' => $now, 'updated_at' => $now],
                ];
                $db->table('akun2')->insertBatch($defaultAkun2);
            }
        } catch (\Throwable $e) {
            log_message('error', 'ensureAkunTables error: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DATA AKUN 1 (Klasifikasi Utama)
    // ==========================================
    public function akun1()
    {
        $daftarAkun1 = $this->akun1Model->orderBy('kode_akun1', 'ASC')->findAll();

        $data = [
            'title'       => 'Data Akun 1 - SIA Laundry',
            'menu'        => 'akun1',
            'daftarAkun1' => $daftarAkun1,
        ];

        return view('akuntansi/akun1', $data);
    }

    public function simpanAkun1()
    {
        $kodeAkun1  = trim($this->request->getPost('kode_akun1'));
        $namaAkun1  = trim($this->request->getPost('nama_akun1'));
        $posisi     = $this->request->getPost('posisi') ?: 'Debit';
        $keterangan = trim($this->request->getPost('keterangan'));

        if (empty($kodeAkun1) || empty($namaAkun1)) {
            return redirect()->to('/akun/1')->with('error', 'Kode Akun 1 dan Nama Akun 1 wajib diisi!');
        }

        $exists = $this->akun1Model->where('kode_akun1', $kodeAkun1)->first();
        if ($exists) {
            return redirect()->to('/akun/1')->with('error', "Kode Akun 1 '{$kodeAkun1}' sudah terdaftar!");
        }

        $this->akun1Model->insert([
            'kode_akun1'  => $kodeAkun1,
            'nama_akun1'  => $namaAkun1,
            'posisi'      => $posisi,
            'keterangan'  => $keterangan,
        ]);

        return redirect()->to('/akun/1')->with('success', "Akun 1 '{$namaAkun1}' ({$kodeAkun1}) berhasil ditambahkan!");
    }

    public function updateAkun1($id)
    {
        $row = $this->akun1Model->find($id);
        if (!$row) {
            return redirect()->to('/akun/1')->with('error', 'Data Akun 1 tidak ditemukan!');
        }

        $kodeAkun1  = trim($this->request->getPost('kode_akun1'));
        $namaAkun1  = trim($this->request->getPost('nama_akun1'));
        $posisi     = $this->request->getPost('posisi') ?: 'Debit';
        $keterangan = trim($this->request->getPost('keterangan'));

        if (empty($kodeAkun1) || empty($namaAkun1)) {
            return redirect()->to('/akun/1')->with('error', 'Kode Akun 1 dan Nama Akun 1 wajib diisi!');
        }

        $dup = $this->akun1Model->where('kode_akun1', $kodeAkun1)->where('id !=', $id)->first();
        if ($dup) {
            return redirect()->to('/akun/1')->with('error', "Kode Akun 1 '{$kodeAkun1}' sudah digunakan!");
        }

        $this->akun1Model->update($id, [
            'kode_akun1'  => $kodeAkun1,
            'nama_akun1'  => $namaAkun1,
            'posisi'      => $posisi,
            'keterangan'  => $keterangan,
        ]);

        return redirect()->to('/akun/1')->with('success', "Data Akun 1 '{$namaAkun1}' berhasil diperbarui!");
    }

    public function hapusAkun1($id)
    {
        $row = $this->akun1Model->find($id);
        if (!$row) {
            return redirect()->to('/akun/1')->with('error', 'Data Akun 1 tidak ditemukan!');
        }

        $this->akun1Model->delete($id);
        return redirect()->to('/akun/1')->with('success', "Data Akun 1 '{$row['nama_akun1']}' berhasil dihapus!");
    }

    // ==========================================
    // DATA AKUN 2 (Sub-Klasifikasi / Kelompok)
    // ==========================================
    public function akun2()
    {
        $daftarAkun2 = $this->akun2Model->orderBy('kode_akun2', 'ASC')->findAll();
        $daftarAkun1 = $this->akun1Model->orderBy('kode_akun1', 'ASC')->findAll();

        $data = [
            'title'       => 'Data Akun 2 - SIA Laundry',
            'menu'        => 'akun2',
            'daftarAkun2' => $daftarAkun2,
            'daftarAkun1' => $daftarAkun1,
        ];

        return view('akuntansi/akun2', $data);
    }

    public function simpanAkun2()
    {
        $kodeAkun1 = trim($this->request->getPost('kode_akun1'));
        $namaAkun1 = trim($this->request->getPost('nama_akun1'));
        $kodeAkun2 = trim($this->request->getPost('kode_akun2'));
        $namaAkun2 = trim($this->request->getPost('nama_akun2'));
        $posisi    = $this->request->getPost('posisi') ?: 'Debit';

        if (empty($kodeAkun2) || empty($namaAkun2)) {
            return redirect()->to('/akun/2')->with('error', 'Kode Akun 2 dan Nama Akun 2 wajib diisi!');
        }

        $exists = $this->akun2Model->where('kode_akun2', $kodeAkun2)->first();
        if ($exists) {
            return redirect()->to('/akun/2')->with('error', "Kode Akun 2 '{$kodeAkun2}' sudah terdaftar!");
        }

        $this->akun2Model->insert([
            'kode_akun1' => $kodeAkun1,
            'nama_akun1' => $namaAkun1,
            'kode_akun2' => $kodeAkun2,
            'nama_akun2' => $namaAkun2,
            'posisi'     => $posisi,
        ]);

        return redirect()->to('/akun/2')->with('success', "Akun 2 '{$namaAkun2}' ({$kodeAkun2}) berhasil ditambahkan!");
    }

    public function updateAkun2($id)
    {
        $row = $this->akun2Model->find($id);
        if (!$row) {
            return redirect()->to('/akun/2')->with('error', 'Data Akun 2 tidak ditemukan!');
        }

        $kodeAkun1 = trim($this->request->getPost('kode_akun1'));
        $namaAkun1 = trim($this->request->getPost('nama_akun1'));
        $kodeAkun2 = trim($this->request->getPost('kode_akun2'));
        $namaAkun2 = trim($this->request->getPost('nama_akun2'));
        $posisi    = $this->request->getPost('posisi') ?: 'Debit';

        if (empty($kodeAkun2) || empty($namaAkun2)) {
            return redirect()->to('/akun/2')->with('error', 'Kode Akun 2 dan Nama Akun 2 wajib diisi!');
        }

        $dup = $this->akun2Model->where('kode_akun2', $kodeAkun2)->where('id !=', $id)->first();
        if ($dup) {
            return redirect()->to('/akun/2')->with('error', "Kode Akun 2 '{$kodeAkun2}' sudah digunakan!");
        }

        $this->akun2Model->update($id, [
            'kode_akun1' => $kodeAkun1,
            'nama_akun1' => $namaAkun1,
            'kode_akun2' => $kodeAkun2,
            'nama_akun2' => $namaAkun2,
            'posisi'     => $posisi,
        ]);

        return redirect()->to('/akun/2')->with('success', "Data Akun 2 '{$namaAkun2}' berhasil diperbarui!");
    }

    public function hapusAkun2($id)
    {
        $row = $this->akun2Model->find($id);
        if (!$row) {
            return redirect()->to('/akun/2')->with('error', 'Data Akun 2 tidak ditemukan!');
        }

        $this->akun2Model->delete($id);
        return redirect()->to('/akun/2')->with('success', "Data Akun 2 '{$row['nama_akun2']}' berhasil dihapus!");
    }

    // ==========================================
    // DATA AKUN 3 (Detail Bagan Akun Buku Besar)
    // ==========================================
    public function akun()
    {
        $daftarAkun  = $this->akunModel->orderBy('kode_akun', 'ASC')->findAll();
        $daftarAkun1 = $this->akun1Model->orderBy('kode_akun1', 'ASC')->findAll();
        $daftarAkun2 = $this->akun2Model->orderBy('kode_akun2', 'ASC')->findAll();

        $data = [
            'title'       => 'Data Akun 3 - SIA Laundry',
            'menu'        => 'akun3',
            'daftarAkun'  => $daftarAkun,
            'daftarAkun1' => $daftarAkun1,
            'daftarAkun2' => $daftarAkun2,
        ];

        return view('akuntansi/akun', $data);
    }

    public function simpanAkun()
    {
        $namaAkun1 = trim($this->request->getPost('nama_akun1'));
        $namaAkun2 = trim($this->request->getPost('nama_akun2'));
        $kodeAkun  = trim($this->request->getPost('kode_akun'));
        $namaAkun3 = trim($this->request->getPost('nama_akun3'));
        $posisi    = $this->request->getPost('posisi') ?: 'Debit';

        if (empty($kodeAkun) || empty($namaAkun3)) {
            return redirect()->to('/akun')->with('error', 'Kode Akun dan Nama Akun 3 wajib diisi!');
        }

        // Cek duplikasi kode akun
        $exists = $this->akunModel->where('kode_akun', $kodeAkun)->first();
        if ($exists) {
            return redirect()->to('/akun')->with('error', "Kode Akun {$kodeAkun} sudah terdaftar!");
        }

        $this->akunModel->insert([
            'nama_akun1' => $namaAkun1,
            'nama_akun2' => $namaAkun2,
            'kode_akun'  => $kodeAkun,
            'nama_akun3' => $namaAkun3,
            'posisi'     => $posisi,
        ]);

        return redirect()->to('/akun')->with('success', "Akun '{$namaAkun3}' ({$kodeAkun}) berhasil ditambahkan!");
    }

    public function updateAkun($id)
    {
        $akun = $this->akunModel->find($id);
        if (!$akun) {
            return redirect()->to('/akun')->with('error', 'Data akun tidak ditemukan!');
        }

        $namaAkun1 = trim($this->request->getPost('nama_akun1'));
        $namaAkun2 = trim($this->request->getPost('nama_akun2'));
        $kodeAkun  = trim($this->request->getPost('kode_akun'));
        $namaAkun3 = trim($this->request->getPost('nama_akun3'));
        $posisi    = $this->request->getPost('posisi') ?: 'Debit';

        if (empty($kodeAkun) || empty($namaAkun3)) {
            return redirect()->to('/akun')->with('error', 'Kode Akun dan Nama Akun 3 wajib diisi!');
        }

        // Cek jika kode akun bentrok dengan id lain
        $duplicate = $this->akunModel->where('kode_akun', $kodeAkun)->where('id !=', $id)->first();
        if ($duplicate) {
            return redirect()->to('/akun')->with('error', "Kode Akun {$kodeAkun} sudah digunakan oleh akun lain!");
        }

        $this->akunModel->update($id, [
            'nama_akun1' => $namaAkun1,
            'nama_akun2' => $namaAkun2,
            'kode_akun'  => $kodeAkun,
            'nama_akun3' => $namaAkun3,
            'posisi'     => $posisi,
        ]);

        return redirect()->to('/akun')->with('success', "Data Akun '{$namaAkun3}' ({$kodeAkun}) berhasil diperbarui!");
    }

    public function hapusAkun($id)
    {
        $akun = $this->akunModel->find($id);
        if (!$akun) {
            return redirect()->to('/akun')->with('error', 'Data akun tidak ditemukan!');
        }

        $this->akunModel->delete($id);
        return redirect()->to('/akun')->with('success', "Akun '{$akun['nama_akun3']}' ({$akun['kode_akun']}) berhasil dihapus!");
    }

    /**
     * Display list of independent journal entries.
     */
    public function jurnal()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $jurnal = $jurnalModel->orderBy('tanggal', 'DESC')->findAll();

        // also fetch accounts for dropdowns in view if needed
        $akunModel = $this->akunModel; // already loaded in constructor
        $daftarAkun = $akunModel->orderBy('kode_akun', 'ASC')->findAll();

        $data = [
            'title'      => 'Jurnal Umum Laundry - wishuwashere.',
            'menu'       => 'jurnal',
            'jurnal'     => $jurnal,
            'daftarAkun' => $daftarAkun,
        ];
        return view('akuntansi/jurnal/index', $data);
    }

    /**
     * Show form to add a new journal entry.
     */
    public function tambahJurnal()
    {
        $daftarAkun = $this->akunModel->orderBy('kode_akun', 'ASC')->findAll();
        $data = [
            'title'      => 'Tambah Jurnal Umum - wishuwashere.',
            'menu'       => 'jurnal_tambah',
            'daftarAkun' => $daftarAkun,
            'action'     => 'tambah',
        ];
        return view('akuntansi/jurnal/form', $data);
    }

    /**
     * Save new journal entry.
     */
    public function simpanJurnal()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $data = [
            'no_bukti'        => $this->request->getPost('no_bukti'),
            'tanggal'         => $this->request->getPost('tanggal'),
            'tipe_transaksi'  => $this->request->getPost('tipe_transaksi'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'debit_kode_akun' => $this->request->getPost('debit_kode_akun'),
            'debit_nama_akun' => $this->request->getPost('debit_nama_akun'),
            'kredit_kode_akun'=> $this->request->getPost('kredit_kode_akun'),
            'kredit_nama_akun'=> $this->request->getPost('kredit_nama_akun'),
            'nominal'         => $this->request->getPost('nominal'),
        ];
        $jurnalModel->insert($data);
        return redirect()->to('/jurnal')->with('success', 'Jurnal berhasil ditambahkan');
    }

    /**
     * Show form to edit an existing journal entry.
     */
    public function editJurnal($id)
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $entry = $jurnalModel->find($id);
        if (!$entry) {
            return redirect()->to('/jurnal')->with('error', 'Data jurnal tidak ditemukan');
        }
        $daftarAkun = $this->akunModel->orderBy('kode_akun', 'ASC')->findAll();
        $data = [
            'title'      => 'Edit Jurnal Umum - wishuwashere.',
            'menu'       => 'jurnal_tambah',
            'daftarAkun' => $daftarAkun,
            'jurnal'     => $entry,
            'action'     => 'edit',
        ];
        return view('akuntansi/jurnal/form', $data);
    }

    /**
     * Update existing journal entry.
     */
    public function updateJurnal($id)
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $data = [
            'no_bukti'        => $this->request->getPost('no_bukti'),
            'tanggal'         => $this->request->getPost('tanggal'),
            'tipe_transaksi'  => $this->request->getPost('tipe_transaksi'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'debit_kode_akun' => $this->request->getPost('debit_kode_akun'),
            'debit_nama_akun' => $this->request->getPost('debit_nama_akun'),
            'kredit_kode_akun'=> $this->request->getPost('kredit_kode_akun'),
            'kredit_nama_akun'=> $this->request->getPost('kredit_nama_akun'),
            'nominal'         => $this->request->getPost('nominal'),
        ];
        $jurnalModel->update($id, $data);
        return redirect()->to('/jurnal')->with('success', 'Jurnal berhasil diperbarui');
    }

    /**
     * Delete a journal entry.
     */
    public function hapusJurnal($id)
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $jurnalModel->delete($id);
        return redirect()->to('/jurnal')->with('success', 'Jurnal berhasil dihapus');
    }

    public function neracaLajur()
    {
        $db = \Config\Database::connect();

        // 1. Ambil seluruh bagan akun dari tabel akun
        $daftarAkun = $db->query("SELECT kode_akun, nama_akun3, posisi FROM akun ORDER BY kode_akun ASC")->getResultArray();

        // 2. Ambil saldo per akun dari Jurnal Umum
        $juRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_umum
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_umum
            ) as t GROUP BY kode
        ")->getResultArray();
        $juData = [];
        foreach ($juRows as $r) {
            $juData[$r['kode']] = ['d' => (int)$r['d'], 'k' => (int)$r['k']];
        }

        // 3. Ambil saldo per akun dari Jurnal Penyesuaian (AJE)
        $ajpRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_penyesuaian
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_penyesuaian
            ) as t GROUP BY kode
        ")->getResultArray();
        $ajpData = [];
        foreach ($ajpRows as $r) {
            $ajpData[$r['kode']] = ['d' => (int)$r['d'], 'k' => (int)$r['k']];
        }

        // 4. Hitung 10 Kolom Kertas Kerja Neraca Lajur
        $worksheet = [];
        $total_ns_d = 0; $total_ns_k = 0;
        $total_ajp_d = 0; $total_ajp_k = 0;
        $total_nsd_d = 0; $total_nsd_k = 0;
        $total_lr_d = 0; $total_lr_k = 0;
        $total_neraca_d = 0; $total_neraca_k = 0;

        foreach ($daftarAkun as $a) {
            $k = $a['kode_akun'];
            $posisi = $a['posisi']; // 'Debit' atau 'Kredit'

            // Kolom 1-2: Neraca Saldo
            $d_ju = $juData[$k]['d'] ?? 0;
            $k_ju = $juData[$k]['k'] ?? 0;
            $ns_d = 0; $ns_k = 0;
            if ($posisi === 'Debit') {
                $net = $d_ju - $k_ju;
                if ($net >= 0) $ns_d = $net; else $ns_k = abs($net);
            } else {
                $net = $k_ju - $d_ju;
                if ($net >= 0) $ns_k = $net; else $ns_d = abs($net);
            }
            $total_ns_d += $ns_d;
            $total_ns_k += $ns_k;

            // Kolom 3-4: Penyesuaian (AJE)
            $ajp_d = $ajpData[$k]['d'] ?? 0;
            $ajp_k = $ajpData[$k]['k'] ?? 0;
            $total_ajp_d += $ajp_d;
            $total_ajp_k += $ajp_k;

            // Kolom 5-6: Neraca Saldo Disesuaikan (NSD)
            $nsd_d = 0; $nsd_k = 0;
            if ($posisi === 'Debit') {
                $net_nsd = ($ns_d - $ns_k) + ($ajp_d - $ajp_k);
                if ($net_nsd >= 0) $nsd_d = $net_nsd; else $nsd_k = abs($net_nsd);
            } else {
                $net_nsd = ($ns_k - $ns_d) + ($ajp_k - $ajp_d);
                if ($net_nsd >= 0) $nsd_k = $net_nsd; else $nsd_d = abs($net_nsd);
            }
            $total_nsd_d += $nsd_d;
            $total_nsd_k += $nsd_k;

            // Kolom 7-8: Laba Rugi vs Kolom 9-10: Neraca Akhir
            $lr_d = 0; $lr_k = 0;
            $neraca_d = 0; $neraca_k = 0;

            $golongan = substr($k, 0, 1);
            if ($golongan === '4' || $golongan === '5') {
                $lr_d = $nsd_d;
                $lr_k = $nsd_k;
                $total_lr_d += $lr_d;
                $total_lr_k += $lr_k;
            } else {
                $neraca_d = $nsd_d;
                $neraca_k = $nsd_k;
                $total_neraca_d += $neraca_d;
                $total_neraca_k += $neraca_k;
            }

            $worksheet[] = [
                'kode_akun' => $k,
                'nama_akun' => $a['nama_akun3'],
                'posisi'    => $posisi,
                'ns_d'      => $ns_d,
                'ns_k'      => $ns_k,
                'ajp_d'     => $ajp_d,
                'ajp_k'     => $ajp_k,
                'nsd_d'     => $nsd_d,
                'nsd_k'     => $nsd_k,
                'lr_d'      => $lr_d,
                'lr_k'      => $lr_k,
                'neraca_d'  => $neraca_d,
                'neraca_k'  => $neraca_k,
            ];
        }

        // Laba Bersih = Total Kredit Laba Rugi - Total Debit Laba Rugi
        $laba = $total_lr_k - $total_lr_d;

        $data = [
            'title'          => 'Neraca Lajur 10 Kolom - wishuwashere.',
            'menu'           => 'neraca_lajur',
            'worksheet'      => $worksheet,
            'total_ns_d'     => $total_ns_d,
            'total_ns_k'     => $total_ns_k,
            'total_ajp_d'    => $total_ajp_d,
            'total_ajp_k'    => $total_ajp_k,
            'total_nsd_d'    => $total_nsd_d,
            'total_nsd_k'    => $total_nsd_k,
            'total_lr_d'     => $total_lr_d,
            'total_lr_k'     => $total_lr_k,
            'total_neraca_d' => $total_neraca_d,
            'total_neraca_k' => $total_neraca_k,
            'laba'           => $laba,
        ];

        return view('akuntansi/neraca_lajur', $data);
    }

    /**
     * Neraca Saldo — aggregate debit/kredit per account from jurnal_umum & penyesuaian.
     * Mendukung Saldo Bersih (Standar Laporan Keuangan) dan Mutasi Bruto.
     */
    public function neracaSaldo()
    {
        $db = \Config\Database::connect();

        // 1. Ambil daftar master akun untuk klasifikasi & posisi saldo normal
        $masterAkuns = $db->query("SELECT kode_akun, nama_akun1, nama_akun2, nama_akun3, posisi FROM akun ORDER BY kode_akun ASC")->getResultArray();
        $akunMap = [];
        foreach ($masterAkuns as $ma) {
            $akunMap[$ma['kode_akun']] = $ma;
        }

        // 2. Ambil mutasi dari jurnal umum
        $juRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_umum
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_umum
            ) as t GROUP BY kode
        ")->getResultArray();

        // 3. Ambil mutasi dari jurnal penyesuaian
        $ajpRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_penyesuaian
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_penyesuaian
            ) as t GROUP BY kode
        ")->getResultArray();

        // 4. Gabungkan mutasi
        $merged = [];
        foreach ($juRows as $r) {
            $k = trim($r['kode']);
            if (empty($k)) continue;
            if (!isset($merged[$k])) $merged[$k] = ['d' => 0, 'k' => 0];
            $merged[$k]['d'] += (int)$r['d'];
            $merged[$k]['k'] += (int)$r['k'];
        }
        foreach ($ajpRows as $r) {
            $k = trim($r['kode']);
            if (empty($k)) continue;
            if (!isset($merged[$k])) $merged[$k] = ['d' => 0, 'k' => 0];
            $merged[$k]['d'] += (int)$r['d'];
            $merged[$k]['k'] += (int)$r['k'];
        }

        // 5. Hitung Saldo Bersih (Net) dan Gross
        $neracaSaldoNet   = [];
        $totalNetD        = 0;
        $totalNetK        = 0;
        $totalGrossD      = 0;
        $totalGrossK      = 0;

        foreach ($merged as $kode => $vals) {
            $info   = $akunMap[$kode] ?? null;
            $nama   = $info['nama_akun3'] ?? ('Akun ' . $kode);
            $kat    = $info['nama_akun1'] ?? '';
            $subKat = $info['nama_akun2'] ?? '';
            
            // Tentukan posisi normal
            if (!empty($info['posisi'])) {
                $posisi = $info['posisi'];
            } else {
                $posisi = (in_array($kode[0] ?? '1', ['1', '5'])) ? 'Debit' : 'Kredit';
            }

            if (empty($kat)) {
                $firstChar = $kode[0] ?? '1';
                $katMap = [
                    '1' => 'Aset',
                    '2' => 'Liabilitas',
                    '3' => 'Ekuitas',
                    '4' => 'Pendapatan',
                    '5' => 'Beban'
                ];
                $kat = $katMap[$firstChar] ?? 'Lainnya';
            }

            $grossD = $vals['d'];
            $grossK = $vals['k'];
            $totalGrossD += $grossD;
            $totalGrossK += $grossK;

            // Perhitungan saldo akhir bersih sesuai posisi normal
            $netD = 0;
            $netK = 0;
            if ($posisi === 'Debit') {
                $selisih = $grossD - $grossK;
                if ($selisih >= 0) {
                    $netD = $selisih;
                } else {
                    $netK = abs($selisih);
                }
            } else {
                $selisih = $grossK - $grossD;
                if ($selisih >= 0) {
                    $netK = $selisih;
                } else {
                    $netD = abs($selisih);
                }
            }
            $totalNetD += $netD;
            $totalNetK += $netK;

            $neracaSaldoNet[$kode] = [
                'kode_akun'    => $kode,
                'nama_akun'    => $nama,
                'kategori'     => $kat,
                'sub_kategori' => $subKat,
                'posisi'       => $posisi,
                'debit'        => $netD,
                'kredit'       => $netK,
                'gross_debit'  => $grossD,
                'gross_kredit' => $grossK,
            ];
        }

        ksort($neracaSaldoNet);

        $data = [
            'title'            => 'Neraca Saldo (Trial Balance) — wishuwashere.',
            'menu'             => 'neraca_saldo',
            'neracaSaldo'      => array_values($neracaSaldoNet),
            'totalNetDebit'    => $totalNetD,
            'totalNetKredit'   => $totalNetK,
            'totalGrossDebit'  => $totalGrossD,
            'totalGrossKredit' => $totalGrossK,
        ];
        return view('akuntansi/neraca_saldo', $data);
    }

    /**
     * Buku Besar (Posting) — full ledger per account with running balance.
     */
    public function bukuBesar()
    {
        $db = \Config\Database::connect();

        // Fetch all journal entries (umum + penyesuaian) ordered by date
        $rows = $db->query("
            SELECT tanggal, no_bukti, keterangan,
                   debit_kode_akun AS kode, debit_nama_akun AS nama, nominal AS debit, 0 AS kredit
            FROM jurnal_umum
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   kredit_kode_akun AS kode, kredit_nama_akun AS nama, 0 AS debit, nominal AS kredit
            FROM jurnal_umum
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   debit_kode_akun AS kode, debit_nama_akun AS nama, nominal AS debit, 0 AS kredit
            FROM jurnal_penyesuaian
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   kredit_kode_akun AS kode, kredit_nama_akun AS nama, 0 AS debit, nominal AS kredit
            FROM jurnal_penyesuaian
            ORDER BY kode, tanggal, no_bukti
        ")->getResultArray();

        $bukuBesar = [];
        foreach ($rows as $r) {
            $k = $r['kode'];
            if (!isset($bukuBesar[$k])) {
                $bukuBesar[$k] = ['nama_akun' => $r['nama'], 'transaksi' => [], 'total_debit' => 0, 'total_kredit' => 0];
            }
            $bukuBesar[$k]['transaksi'][]    = ['tanggal' => $r['tanggal'], 'no_bukti' => $r['no_bukti'], 'keterangan' => $r['keterangan'], 'debit' => $r['debit'], 'kredit' => $r['kredit']];
            $bukuBesar[$k]['total_debit']   += $r['debit'];
            $bukuBesar[$k]['total_kredit']  += $r['kredit'];
        }

        ksort($bukuBesar);

        $data = [
            'title'     => 'Buku Besar — wishuwashere.',
            'menu'      => 'buku_besar',
            'bukuBesar' => $bukuBesar,
        ];
        return view('akuntansi/buku_besar', $data);
    }

    /**
     * Cetak Jurnal Umum — format baku akuntansi
     */
    public function cetakJurnal()
    {
        $jurnalModel = new \App\Models\JurnalModel();
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $jurnalModel->orderBy('tanggal', 'ASC')->orderBy('no_bukti', 'ASC');
        if (!empty($tglAwal)) {
            $builder->where('tanggal >=', $tglAwal);
        }
        if (!empty($tglAkhir)) {
            $builder->where('tanggal <=', $tglAkhir);
        }
        $jurnal = $builder->findAll();

        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($jurnal as $j) {
            $totalDebit  += (int) $j['nominal'];
            $totalKredit += (int) $j['nominal'];
        }

        $data = [
            'title'       => 'Cetak Jurnal Umum — wishuwashere.',
            'jurnal'      => $jurnal,
            'totalDebit'  => $totalDebit,
            'totalKredit' => $totalKredit,
            'tglAwal'     => $tglAwal,
            'tglAkhir'    => $tglAkhir,
        ];

        return view('akuntansi/jurnal/cetak', $data);
    }

    /**
     * Cetak Neraca Saldo (Trial Balance) — format resmi
     */
    public function cetakNeracaSaldo()
    {
        $db = \Config\Database::connect();
        $tipe = $this->request->getGet('tipe') ?: 'sebelum';

        $masterAkuns = $db->query("SELECT kode_akun, nama_akun1, nama_akun2, nama_akun3, posisi FROM akun ORDER BY kode_akun ASC")->getResultArray();
        $akunMap = [];
        foreach ($masterAkuns as $ma) {
            $akunMap[$ma['kode_akun']] = $ma;
        }

        $juRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_umum
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_umum
            ) as t GROUP BY kode
        ")->getResultArray();

        $ajpRows = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_penyesuaian
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_penyesuaian
            ) as t GROUP BY kode
        ")->getResultArray();

        $merged = [];
        foreach ($juRows as $r) {
            $k = trim($r['kode']);
            if (empty($k)) continue;
            if (!isset($merged[$k])) $merged[$k] = ['d' => 0, 'k' => 0];
            $merged[$k]['d'] += (int)$r['d'];
            $merged[$k]['k'] += (int)$r['k'];
        }

        // Jika tipe = sesudah (NSD), sertakan juga penyesuaian (AJP)
        if ($tipe === 'sesudah' || $tipe === 'nsd') {
            foreach ($ajpRows as $r) {
                $k = trim($r['kode']);
                if (empty($k)) continue;
                if (!isset($merged[$k])) $merged[$k] = ['d' => 0, 'k' => 0];
                $merged[$k]['d'] += (int)$r['d'];
                $merged[$k]['k'] += (int)$r['k'];
            }
        }

        $neracaSaldoNet = [];
        $totalNetD = 0;
        $totalNetK = 0;

        foreach ($merged as $kode => $vals) {
            $info   = $akunMap[$kode] ?? null;
            $nama   = $info['nama_akun3'] ?? ('Akun ' . $kode);
            $kat    = $info['nama_akun1'] ?? '';
            $subKat = $info['nama_akun2'] ?? '';
            
            if (!empty($info['posisi'])) {
                $posisi = $info['posisi'];
            } else {
                $posisi = (in_array($kode[0] ?? '1', ['1', '5'])) ? 'Debit' : 'Kredit';
            }

            if (empty($kat)) {
                $katMap = ['1' => 'Aset', '2' => 'Liabilitas', '3' => 'Ekuitas', '4' => 'Pendapatan', '5' => 'Beban'];
                $kat = $katMap[$kode[0] ?? '1'] ?? 'Lainnya';
            }

            $grossD = $vals['d'];
            $grossK = $vals['k'];

            $netD = 0;
            $netK = 0;
            if ($posisi === 'Debit') {
                $selisih = $grossD - $grossK;
                if ($selisih >= 0) $netD = $selisih; else $netK = abs($selisih);
            } else {
                $selisih = $grossK - $grossD;
                if ($selisih >= 0) $netK = $selisih; else $netD = abs($selisih);
            }
            $totalNetD += $netD;
            $totalNetK += $netK;

            $neracaSaldoNet[$kode] = [
                'kode_akun'    => $kode,
                'nama_akun'    => $nama,
                'kategori'     => $kat,
                'sub_kategori' => $subKat,
                'posisi'       => $posisi,
                'debit'        => $netD,
                'kredit'       => $netK,
            ];
        }

        ksort($neracaSaldoNet);

        $judulLaporan = ($tipe === 'sesudah' || $tipe === 'nsd') ? 'NERACA SALDO SETELAH PENYESUAIAN (NSD)' : 'NERACA SALDO';

        $data = [
            'title'          => 'Cetak Neraca Saldo — wishuwashere.',
            'tipe'           => $tipe,
            'judulLaporan'   => $judulLaporan,
            'neracaSaldo'    => array_values($neracaSaldoNet),
            'totalNetDebit'  => $totalNetD,
            'totalNetKredit' => $totalNetK,
        ];
        return view('akuntansi/cetak_neraca_saldo', $data);
    }

    /**
     * Cetak Buku Besar (General Ledger)
     */
    public function cetakBukuBesar()
    {
        $db = \Config\Database::connect();

        $rows = $db->query("
            SELECT tanggal, no_bukti, keterangan,
                   debit_kode_akun AS kode, debit_nama_akun AS nama, nominal AS debit, 0 AS kredit
            FROM jurnal_umum
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   kredit_kode_akun AS kode, kredit_nama_akun AS nama, 0 AS debit, nominal AS kredit
            FROM jurnal_umum
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   debit_kode_akun AS kode, debit_nama_akun AS nama, nominal AS debit, 0 AS kredit
            FROM jurnal_penyesuaian
            UNION ALL
            SELECT tanggal, no_bukti, keterangan,
                   kredit_kode_akun AS kode, kredit_nama_akun AS nama, 0 AS debit, nominal AS kredit
            FROM jurnal_penyesuaian
            ORDER BY kode, tanggal, no_bukti
        ")->getResultArray();

        $bukuBesar = [];
        foreach ($rows as $r) {
            $k = $r['kode'];
            if (!isset($bukuBesar[$k])) {
                $bukuBesar[$k] = ['nama_akun' => $r['nama'], 'transaksi' => [], 'total_debit' => 0, 'total_kredit' => 0];
            }
            $bukuBesar[$k]['transaksi'][]   = ['tanggal' => $r['tanggal'], 'no_bukti' => $r['no_bukti'], 'keterangan' => $r['keterangan'], 'debit' => $r['debit'], 'kredit' => $r['kredit']];
            $bukuBesar[$k]['total_debit']  += $r['debit'];
            $bukuBesar[$k]['total_kredit'] += $r['kredit'];
        }

        ksort($bukuBesar);

        $data = [
            'title'     => 'Cetak Buku Besar — wishuwashere.',
            'bukuBesar' => $bukuBesar,
        ];
        return view('akuntansi/cetak_buku_besar', $data);
    }

    /**
     * Cetak Neraca Lajur 10 Kolom (Worksheet)
     */
    public function cetakNeracaLajur()
    {
        $db = \Config\Database::connect();

        $daftarAkun = $db->query("SELECT kode_akun, nama_akun3, posisi FROM akun ORDER BY kode_akun ASC")->getResultArray();

        $ju = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_umum
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_umum
            ) as t GROUP BY kode
        ")->getResultArray();
        $juData = [];
        foreach ($ju as $j) {
            $juData[$j['kode']] = ['d' => $j['d'], 'k' => $j['k']];
        }

        $ajp = $db->query("
            SELECT kode, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, nominal as debit, 0 as kredit FROM jurnal_penyesuaian
                UNION ALL
                SELECT kredit_kode_akun as kode, 0 as debit, nominal as kredit FROM jurnal_penyesuaian
            ) as t GROUP BY kode
        ")->getResultArray();
        $ajpData = [];
        foreach ($ajp as $a) {
            $ajpData[$a['kode']] = ['d' => $a['d'], 'k' => $a['k']];
        }

        $worksheet = [];
        $total_ns_d = 0; $total_ns_k = 0;
        $total_ajp_d = 0; $total_ajp_k = 0;
        $total_nsd_d = 0; $total_nsd_k = 0;
        $total_lr_d = 0; $total_lr_k = 0;
        $total_neraca_d = 0; $total_neraca_k = 0;

        foreach ($daftarAkun as $a) {
            $k = $a['kode_akun'];
            $posisi = $a['posisi'];

            $d_ju = $juData[$k]['d'] ?? 0;
            $k_ju = $juData[$k]['k'] ?? 0;
            $ns_d = 0; $ns_k = 0;
            if ($posisi === 'Debit') {
                $net = $d_ju - $k_ju;
                if ($net >= 0) $ns_d = $net; else $ns_k = abs($net);
            } else {
                $net = $k_ju - $d_ju;
                if ($net >= 0) $ns_k = $net; else $ns_d = abs($net);
            }
            $total_ns_d += $ns_d;
            $total_ns_k += $ns_k;

            $ajp_d = $ajpData[$k]['d'] ?? 0;
            $ajp_k = $ajpData[$k]['k'] ?? 0;
            $total_ajp_d += $ajp_d;
            $total_ajp_k += $ajp_k;

            $nsd_d = 0; $nsd_k = 0;
            if ($posisi === 'Debit') {
                $net_nsd = ($ns_d - $ns_k) + ($ajp_d - $ajp_k);
                if ($net_nsd >= 0) $nsd_d = $net_nsd; else $nsd_k = abs($net_nsd);
            } else {
                $net_nsd = ($ns_k - $ns_d) + ($ajp_k - $ajp_d);
                if ($net_nsd >= 0) $nsd_k = $net_nsd; else $nsd_d = abs($net_nsd);
            }
            $total_nsd_d += $nsd_d;
            $total_nsd_k += $nsd_k;

            $lr_d = 0; $lr_k = 0;
            $neraca_d = 0; $neraca_k = 0;

            $golongan = substr($k, 0, 1);
            if ($golongan === '4' || $golongan === '5') {
                $lr_d = $nsd_d;
                $lr_k = $nsd_k;
                $total_lr_d += $lr_d;
                $total_lr_k += $lr_k;
            } else {
                $neraca_d = $nsd_d;
                $neraca_k = $nsd_k;
                $total_neraca_d += $neraca_d;
                $total_neraca_k += $neraca_k;
            }

            $worksheet[] = [
                'kode_akun' => $k,
                'nama_akun' => $a['nama_akun3'],
                'posisi'    => $posisi,
                'ns_d'      => $ns_d,
                'ns_k'      => $ns_k,
                'ajp_d'     => $ajp_d,
                'ajp_k'     => $ajp_k,
                'nsd_d'     => $nsd_d,
                'nsd_k'     => $nsd_k,
                'lr_d'      => $lr_d,
                'lr_k'      => $lr_k,
                'neraca_d'  => $neraca_d,
                'neraca_k'  => $neraca_k,
            ];
        }

        $laba = $total_lr_k - $total_lr_d;

        $data = [
            'title'          => 'Cetak Neraca Lajur 10 Kolom — wishuwashere.',
            'worksheet'      => $worksheet,
            'total_ns_d'     => $total_ns_d,
            'total_ns_k'     => $total_ns_k,
            'total_ajp_d'    => $total_ajp_d,
            'total_ajp_k'    => $total_ajp_k,
            'total_nsd_d'    => $total_nsd_d,
            'total_nsd_k'    => $total_nsd_k,
            'total_lr_d'     => $total_lr_d,
            'total_lr_k'     => $total_lr_k,
            'total_neraca_d' => $total_neraca_d,
            'total_neraca_k' => $total_neraca_k,
            'laba'           => $laba,
        ];

        return view('akuntansi/cetak_neraca_lajur', $data);
    }

    /**
     * Seeder Akuntansi Lengkap:
     * - Menambahkan/memastikan akun lengkap (aset, liabilitas, ekuitas, pendapatan, beban)
     * - Menambahkan transaksi pembelian (tunai & kredit) di Jurnal Umum
     * - Menambahkan transaksi pendapatan jasa borongan & pelunasan di Jurnal Umum
     * - Menambahkan seluruh Jurnal Penyesuaian (AJE) akhir periode akuntansi
     */
    public function seedAkuntansiLengkap()
    {
        $db = \Config\Database::connect();

        // 1. Pastikan seluruh bagan akun standar (Akun 3) terdaftar
        $standarAkun = [
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Lancar', 'kode_akun' => '1101', 'nama_akun3' => 'Kas di Tangan / Bank', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Lancar', 'kode_akun' => '1102', 'nama_akun3' => 'Piutang Usaha Pelanggan', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Lancar', 'kode_akun' => '1103', 'nama_akun3' => 'Perlengkapan Deterjen & Parfum', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Lancar', 'kode_akun' => '1104', 'nama_akun3' => 'Sewa Dibayar di Muka', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Tetap', 'kode_akun' => '1201', 'nama_akun3' => 'Peralatan Mesin Cuci & Dryer', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Aset', 'nama_akun2' => 'Aset Tetap', 'kode_akun' => '1202', 'nama_akun3' => 'Akumulasi Penyusutan Peralatan', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2101', 'nama_akun3' => 'Utang Usaha / Operasional', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2102', 'nama_akun3' => 'Beban Terutang (Accrued Expense)', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Liabilitas', 'nama_akun2' => 'Liabilitas Lancar', 'kode_akun' => '2103', 'nama_akun3' => 'Pendapatan Diterima di Muka', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Ekuitas', 'nama_akun2' => 'Ekuitas Pemilik', 'kode_akun' => '3101', 'nama_akun3' => 'Modal Pemilik wishuwashere.', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Ekuitas', 'nama_akun2' => 'Ekuitas Pemilik', 'kode_akun' => '3102', 'nama_akun3' => 'Prive Pemilik wishuwashere.', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Pendapatan', 'nama_akun2' => 'Pendapatan Jasa Laundry', 'kode_akun' => '4101', 'nama_akun3' => 'Pendapatan Jasa Laundry', 'posisi' => 'Kredit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5101', 'nama_akun3' => 'Beban Pemakaian Bahan Deterjen', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5102', 'nama_akun3' => 'Beban Listrik, Air & Gas', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5103', 'nama_akun3' => 'Beban Gaji Karyawan', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Operasional & Administrasi', 'kode_akun' => '5104', 'nama_akun3' => 'Beban Perawatan Mesin / Servis', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Perlengkapan & Penyusutan', 'kode_akun' => '5105', 'nama_akun3' => 'Beban Penyusutan Peralatan Mesin', 'posisi' => 'Debit'],
            ['nama_akun1' => 'Beban', 'nama_akun2' => 'Beban Perlengkapan & Penyusutan', 'kode_akun' => '5106', 'nama_akun3' => 'Beban Perlengkapan & Operasional Lain', 'posisi' => 'Debit'],
        ];

        foreach ($standarAkun as $sa) {
            $cek = $db->table('akun')->where('kode_akun', $sa['kode_akun'])->get()->getRow();
            if (!$cek) {
                $db->table('akun')->insert($sa);
            }
        }

        // 2. Data Transaksi Pembelian & Beban Operasional di Jurnal Umum
        $jurnalModel = new \App\Models\JurnalModel();
        $transaksiBeli = [
            [
                'no_bukti'        => 'BKK-2611-001',
                'tanggal'         => '2026-11-03',
                'tipe_transaksi'  => 'Pembelian Perlengkapan',
                'keterangan'      => 'Pembelian Bahan Deterjen Matic & Softener Wangi 5 Jerigen (Tunai)',
                'debit_kode_akun' => '1103',
                'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 450000,
            ],
            [
                'no_bukti'        => 'BKK-2611-002',
                'tanggal'         => '2026-11-07',
                'tipe_transaksi'  => 'Pembelian Perlengkapan',
                'keterangan'      => 'Pembelian Stok Plastik Packing Bening & Hanger Kawat Laundry (Tunai)',
                'debit_kode_akun' => '1103',
                'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 320000,
            ],
            [
                'no_bukti'        => 'FB-2611-001',
                'tanggal'         => '2026-11-10',
                'tipe_transaksi'  => 'Pembelian Perlengkapan',
                'keterangan'      => 'Pembelian Stok Bahan Kimia, Alkali & Spotting Anti-Noda (Kredit CV Sumber Wangi)',
                'debit_kode_akun' => '1103',
                'debit_nama_akun' => 'Perlengkapan Deterjen & Parfum',
                'kredit_kode_akun'=> '2101',
                'kredit_nama_akun'=> 'Utang Usaha / Operasional',
                'nominal'         => 1250000,
            ],
            [
                'no_bukti'        => 'BKK-2611-003',
                'tanggal'         => '2026-11-14',
                'tipe_transaksi'  => 'Pembelian Peralatan',
                'keterangan'      => 'Pembelian Mesin Steamer Boiler Uap Listrik Tambahan (Tunai)',
                'debit_kode_akun' => '1201',
                'debit_nama_akun' => 'Peralatan Mesin Cuci & Dryer',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 2500000,
            ],
            [
                'no_bukti'        => 'BKK-2611-004',
                'tanggal'         => '2026-11-18',
                'tipe_transaksi'  => 'Pembayaran Utang',
                'keterangan'      => 'Pembayaran Sebagian Utang Usaha Pembelian Bahan ke CV Sumber Wangi',
                'debit_kode_akun' => '2101',
                'debit_nama_akun' => 'Utang Usaha / Operasional',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 750000,
            ],
            [
                'no_bukti'        => 'BKK-2611-005',
                'tanggal'         => '2026-11-21',
                'tipe_transaksi'  => 'Pengeluaran Beban',
                'keterangan'      => 'Pembayaran Jasa Servis Rutin & Penggantian Sparepart V-Belt Mesin Cuci',
                'debit_kode_akun' => '5104',
                'debit_nama_akun' => 'Beban Perawatan Mesin / Servis',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 350000,
            ],
            [
                'no_bukti'        => 'BKK-2611-006',
                'tanggal'         => '2026-11-01',
                'tipe_transaksi'  => 'Sewa Dibayar di Muka',
                'keterangan'      => 'Pembayaran Sewa Tempat/Kios Usaha Laundry Dimuka 1 Tahun (Tunai)',
                'debit_kode_akun' => '1104',
                'debit_nama_akun' => 'Sewa Dibayar di Muka',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 1800000,
            ],
            [
                'no_bukti'        => 'BKK-2611-007',
                'tanggal'         => '2026-11-16',
                'tipe_transaksi'  => 'Penarikan Prive',
                'keterangan'      => 'Penarikan Kas Prive Pemilik wishuwashere. untuk Keperluan Pribadi',
                'debit_kode_akun' => '3102',
                'debit_nama_akun' => 'Prive Pemilik wishuwashere.',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 850000,
            ],
            [
                'no_bukti'        => 'BKK-2611-008',
                'tanggal'         => '2026-11-20',
                'tipe_transaksi'  => 'Pembayaran Beban Gaji',
                'keterangan'      => 'Pembayaran Gaji Karyawan Operasional Kasir & Penyetrika Paruh Waktu (Tunai)',
                'debit_kode_akun' => '5103',
                'debit_nama_akun' => 'Beban Gaji Karyawan',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 1250000,
            ],
            [
                'no_bukti'        => 'BKK-2611-009',
                'tanggal'         => '2026-11-23',
                'tipe_transaksi'  => 'Pengeluaran Beban',
                'keterangan'      => 'Pembayaran Biaya Cetak Banner, Brosur & Iklan Promosi Laundry (Tunai)',
                'debit_kode_akun' => '5106',
                'debit_nama_akun' => 'Beban Perlengkapan & Operasional Lain',
                'kredit_kode_akun'=> '1101',
                'kredit_nama_akun'=> 'Kas di Tangan / Bank',
                'nominal'         => 300000,
            ],
        ];

        foreach ($transaksiBeli as $tb) {
            $ada = $jurnalModel->where('no_bukti', $tb['no_bukti'])->first();
            if (!$ada) {
                $jurnalModel->insert($tb);
            }
        }

        // 3. Data Transaksi Pendapatan Jasa Tambahan di Jurnal Umum
        $transaksiPendapatan = [
            [
                'no_bukti'        => 'BKM-2611-002',
                'tanggal'         => '2026-11-08',
                'tipe_transaksi'  => 'Penerimaan Pendapatan',
                'keterangan'      => 'Penerimaan Pendapatan Jasa Laundry Borongan Seragam Hotel Salak Heritage',
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => 1450000,
            ],
            [
                'no_bukti'        => 'BKM-2611-003',
                'tanggal'         => '2026-11-15',
                'tipe_transaksi'  => 'Penerimaan Pendapatan',
                'keterangan'      => 'Penerimaan Pendapatan Jasa Cuci Taplak Meja, Gordyn & Banner Wisuda IPB',
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => 2100000,
            ],
            [
                'no_bukti'        => 'BKM-2611-004',
                'tanggal'         => '2026-11-19',
                'tipe_transaksi'  => 'Penerimaan Pendapatan',
                'keterangan'      => 'Penerimaan Uang Muka Paket Laundry Bulanan Kost Mahasiswa Baranangsiang',
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '2103',
                'kredit_nama_akun'=> 'Pendapatan Diterima di Muka',
                'nominal'         => 600000,
            ],
            [
                'no_bukti'        => 'BKM-2611-005',
                'tanggal'         => '2026-11-22',
                'tipe_transaksi'  => 'Pelunasan Piutang',
                'keterangan'      => 'Pelunasan Piutang Usaha Cucian dari Pelanggan Kantor Notaris',
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '1102',
                'kredit_nama_akun'=> 'Piutang Usaha Pelanggan',
                'nominal'         => 380000,
            ],
            [
                'no_bukti'        => 'FB-2611-002',
                'tanggal'         => '2026-11-05',
                'tipe_transaksi'  => 'Pendapatan Jasa',
                'keterangan'      => 'Pendapatan Jasa Laundry Seragam Kantor Notaris & Rekanan (Kredit / Tempo)',
                'debit_kode_akun' => '1102',
                'debit_nama_akun' => 'Piutang Usaha Pelanggan',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => 1200000,
            ],
        ];

        foreach ($transaksiPendapatan as $tp) {
            $ada = $jurnalModel->where('no_bukti', $tp['no_bukti'])->first();
            if (!$ada) {
                $jurnalModel->insert($tp);
            }
        }

        // 4. Data Jurnal Penyesuaian (AJE) Akhir Periode Sesuai Akun
        $penyesuaianModel = new \App\Models\JurnalPenyesuaianModel();
        $daftarAJE = [
            [
                'no_bukti'        => 'AJP-261130-001',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Perlengkapan Deterjen Terpakai',
                'debit_kode_akun' => '5101',
                'debit_nama_akun' => 'Beban Pemakaian Bahan Deterjen',
                'kredit_kode_akun'=> '1103',
                'kredit_nama_akun'=> 'Perlengkapan Deterjen & Parfum',
                'nominal'         => 550000,
            ],
            [
                'no_bukti'        => 'AJP-261130-002',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Penyusutan Peralatan/Mesin Cuci',
                'debit_kode_akun' => '5105',
                'debit_nama_akun' => 'Beban Penyusutan Peralatan Mesin',
                'kredit_kode_akun'=> '1202',
                'kredit_nama_akun'=> 'Akumulasi Penyusutan Peralatan',
                'nominal'         => 350000,
            ],
            [
                'no_bukti'        => 'AJP-261130-003',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Beban Gaji Terutang (Accrued)',
                'debit_kode_akun' => '5103',
                'debit_nama_akun' => 'Beban Gaji Karyawan',
                'kredit_kode_akun'=> '2102',
                'kredit_nama_akun'=> 'Beban Terutang (Accrued Expense)',
                'nominal'         => 500000,
            ],
            [
                'no_bukti'        => 'AJP-261130-004',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Beban Listrik/Air Terutang (Accrued)',
                'debit_kode_akun' => '5102',
                'debit_nama_akun' => 'Beban Listrik, Air & Gas',
                'kredit_kode_akun'=> '2102',
                'kredit_nama_akun'=> 'Beban Terutang (Accrued Expense)',
                'nominal'         => 275000,
            ],
            [
                'no_bukti'        => 'AJP-261130-005',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Pendapatan Diterima di Muka (Unearned)',
                'debit_kode_akun' => '2103',
                'debit_nama_akun' => 'Pendapatan Diterima di Muka',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => 250000,
            ],
            [
                'no_bukti'        => 'AJP-261130-006',
                'tanggal'         => '2026-11-30',
                'keterangan'      => 'Beban Dibayar di Muka (Prepaid)',
                'debit_kode_akun' => '5106',
                'debit_nama_akun' => 'Beban Perlengkapan & Operasional Lain',
                'kredit_kode_akun'=> '1104',
                'kredit_nama_akun'=> 'Sewa Dibayar di Muka',
                'nominal'         => 150000,
            ],
        ];

        foreach ($daftarAJE as $aje) {
            $ada = $penyesuaianModel->where('no_bukti', $aje['no_bukti'])->first();
            if (!$ada) {
                $penyesuaianModel->insert($aje);
            }
        }

        return redirect()->to('/neraca-lajur')->with('success', 'Transaksi pembelian, pendapatan jasa borongan, dan seluruh jurnal penyesuaian (AJE) berhasil ditambahkan!');
    }
}
