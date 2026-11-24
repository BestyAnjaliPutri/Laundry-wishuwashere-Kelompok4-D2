<?php
namespace App\Controllers;

use App\Models\JurnalPenyesuaianModel;
use App\Models\AkunModel;

class Penyesuaian extends BaseController
{
    protected $model;
    protected $akunModel;

    public function __construct()
    {
        $this->model     = new JurnalPenyesuaianModel();
        $this->akunModel = new AkunModel();
    }

    public function index()
    {
        $allAJE = $this->model->orderBy('tanggal', 'DESC')->orderBy('no_bukti', 'DESC')->findAll();
        $totalNominal = 0;
        foreach ($allAJE as $a) {
            $totalNominal += (int)$a['nominal'];
        }

        // Auto-generate no. bukti berikutnya
        $lastRow = $this->model->orderBy('id', 'DESC')->first();
        $nextNoBukti = 'AJE-' . date('Ym') . '-001';
        if ($lastRow) {
            preg_match('/(\d+)$/', $lastRow['no_bukti'], $m);
            $nextNum = isset($m[1]) ? (int)$m[1] + 1 : 1;
            $nextNoBukti = 'AJE-' . date('Ym') . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        }

        $data = [
            'title'        => 'Jurnal Penyesuaian (AJE) — wishuwashere.',
            'menu'         => 'penyesuaian',
            'penyesuaian'  => $allAJE,
            'daftarAkun'   => $this->akunModel->orderBy('kode_akun', 'ASC')->findAll(),
            'totalNominal' => $totalNominal,
            'nextNoBukti'  => $nextNoBukti,
        ];
        return view('akuntansi/penyesuaian/index', $data);
    }

    public function simpan()
    {
        $this->model->insert([
            'no_bukti'        => $this->request->getPost('no_bukti'),
            'tanggal'         => $this->request->getPost('tanggal'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'debit_kode_akun' => $this->request->getPost('debit_kode_akun'),
            'debit_nama_akun' => $this->request->getPost('debit_nama_akun'),
            'kredit_kode_akun'=> $this->request->getPost('kredit_kode_akun'),
            'kredit_nama_akun'=> $this->request->getPost('kredit_nama_akun'),
            'nominal'         => $this->request->getPost('nominal'),
        ]);
        return redirect()->to('/penyesuaian')->with('success', 'Jurnal penyesuaian berhasil disimpan!');
    }

    public function edit($id)
    {
        $entry = $this->model->find($id);
        if (!$entry) return redirect()->to('/penyesuaian')->with('error', 'Data tidak ditemukan!');
        $data = [
            'title'       => 'Edit Penyesuaian',
            'menu'        => 'penyesuaian',
            'penyesuaian' => $entry,
            'daftarAkun'  => $this->akunModel->orderBy('kode_akun', 'ASC')->findAll(),
            'action'      => 'edit',
        ];
        return view('akuntansi/penyesuaian/form', $data);
    }

    public function update($id)
    {
        $this->model->update($id, [
            'no_bukti'        => $this->request->getPost('no_bukti'),
            'tanggal'         => $this->request->getPost('tanggal'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'debit_kode_akun' => $this->request->getPost('debit_kode_akun'),
            'debit_nama_akun' => $this->request->getPost('debit_nama_akun'),
            'kredit_kode_akun'=> $this->request->getPost('kredit_kode_akun'),
            'kredit_nama_akun'=> $this->request->getPost('kredit_nama_akun'),
            'nominal'         => $this->request->getPost('nominal'),
        ]);
        return redirect()->to('/penyesuaian')->with('success', 'Jurnal penyesuaian berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to('/penyesuaian')->with('success', 'Jurnal penyesuaian berhasil dihapus!');
    }

    public function cetak()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->model->orderBy('tanggal', 'ASC')->orderBy('no_bukti', 'ASC');
        if (!empty($tglAwal)) {
            $builder->where('tanggal >=', $tglAwal);
        }
        if (!empty($tglAkhir)) {
            $builder->where('tanggal <=', $tglAkhir);
        }
        $penyesuaian = $builder->findAll();

        $totalNominal = 0;
        foreach ($penyesuaian as $p) {
            $totalNominal += (int)$p['nominal'];
        }

        $data = [
            'title'        => 'Cetak Jurnal Penyesuaian (AJE) — wishuwashere.',
            'penyesuaian'  => $penyesuaian,
            'totalNominal' => $totalNominal,
            'tglAwal'      => $tglAwal,
            'tglAkhir'     => $tglAkhir,
        ];
        return view('akuntansi/penyesuaian/cetak', $data);
    }
}
