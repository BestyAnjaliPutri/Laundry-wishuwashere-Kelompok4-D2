<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Laporan extends BaseController
{
    protected function getLaporanData($tglAwal = null, $tglAkhir = null)
    {
        $transaksiModel = new TransaksiModel();
        
        $builder = $transaksiModel->select('transaksi.*, pelanggan.nama as nama_pelanggan, pelanggan.no_hp, paket.nama_paket, paket.jenis as jenis_paket, paket.harga as harga_paket')
            ->join('pelanggan', 'pelanggan.id = transaksi.pelanggan_id', 'left')
            ->join('paket', 'paket.id = transaksi.paket_id', 'left');

        if (!empty($tglAwal)) {
            $builder->where('DATE(transaksi.created_at) >=', $tglAwal);
        }
        if (!empty($tglAkhir)) {
            $builder->where('DATE(transaksi.created_at) <=', $tglAkhir);
        }

        $transaksi = $builder->orderBy('transaksi.id', 'DESC')->findAll();

        // 1. Perhitungan Pendapatan & Piutang Aktual dari Database
        $pendapatanLunas   = 0;
        $piutangBelumLunas = 0;
        $totalBeratKiloan  = 0;

        foreach ($transaksi as $t) {
            if ($t['status_bayar'] === 'Lunas') {
                $pendapatanLunas += (int) $t['total_biaya'];
            } else {
                $piutangBelumLunas += (int) $t['total_biaya'];
            }
            if ($t['jenis_paket'] === 'kiloan') {
                $totalBeratKiloan += (float) $t['berat_qty'];
            }
        }

        // Total Omzet / Pendapatan Usaha
        $totalPendapatanUsaha = $pendapatanLunas + $piutangBelumLunas;

        // 2. Beban Operasional Laundry
        $bebanDeterjenParfum  = round($totalPendapatanUsaha * 0.12); // 12% bahan deterjen
        $bebanListrikAirGas   = round($totalPendapatanUsaha * 0.10); // 10% utilitas
        $bebanPerawatanMesin  = round($totalPendapatanUsaha * 0.05); // 5% maintenance
        $bebanOperasionalLain = round($totalPendapatanUsaha * 0.03); // 3% plastik packing

        $db = \Config\Database::connect();

        // 1. Ambil rekap Debit dan Kredit setiap akun dari Jurnal Umum & Jurnal Penyesuaian
        $queryJurnal = "
            SELECT kode, nama, SUM(debit) as d, SUM(kredit) as k FROM (
                SELECT debit_kode_akun as kode, debit_nama_akun as nama, nominal as debit, 0 as kredit FROM jurnal_umum
                UNION ALL
                SELECT kredit_kode_akun as kode, kredit_nama_akun as nama, 0 as debit, nominal as kredit FROM jurnal_umum
                UNION ALL
                SELECT debit_kode_akun as kode, debit_nama_akun as nama, nominal as debit, 0 as kredit FROM jurnal_penyesuaian
                UNION ALL
                SELECT kredit_kode_akun as kode, kredit_nama_akun as nama, 0 as debit, nominal as kredit FROM jurnal_penyesuaian
            ) as t GROUP BY kode, nama
        ";
        $saldoRows = $db->query($queryJurnal)->getResultArray();

        $saldoAkun = [];
        foreach ($saldoRows as $s) {
            $saldoAkun[$s['kode']] = [
                'nama'   => $s['nama'],
                'debit'  => (int)$s['d'],
                'kredit' => (int)$s['k'],
                'net_d'  => (int)$s['d'] - (int)$s['k'],
                'net_k'  => (int)$s['k'] - (int)$s['d'],
            ];
        }

        // Helper untuk menjumlah saldo net per prefix kode akun
        $getNetD = function($prefix) use ($saldoAkun) {
            $tot = 0;
            foreach ($saldoAkun as $kode => $val) {
                if (strpos($kode, $prefix) === 0) {
                    $tot += $val['net_d'];
                }
            }
            return $tot;
        };
        $getNetK = function($prefix) use ($saldoAkun) {
            $tot = 0;
            foreach ($saldoAkun as $kode => $val) {
                if (strpos($kode, $prefix) === 0) {
                    $tot += $val['net_k'];
                }
            }
            return $tot;
        };

        // A. PENDAPATAN USAHA (Akun 4xxx)
        $totalPendapatanUsaha = max(0, $getNetK('4'));

        // B. KAS & PIUTANG USAHA (Akun 1101 & 1102)
        $kasDiTangan       = max(0, $getNetD('1101'));
        $piutangUsaha      = max(0, $getNetD('1102'));
        $pendapatanLunas   = max(0, $totalPendapatanUsaha - $piutangUsaha);
        $piutangBelumLunas = $piutangUsaha;

        // C. BEBAN OPERASIONAL (Akun 5xxx)
        $bebanDeterjenParfum  = max(0, $getNetD('5101'));
        $bebanListrikAirGas   = max(0, $getNetD('5102'));
        $bebanPerawatanMesin  = max(0, $getNetD('5103'));
        $bebanOperasionalLain = max(0, $getNetD('5104'));
        $totalBebanOperasional = max(0, $getNetD('5'));

        // D. LABA / RUGI BERSIH
        $labaBersih = $totalPendapatanUsaha - $totalBebanOperasional;

        // E. PERUBAHAN EKUITAS (Akun 3101 - Modal & 3102 - Prive)
        // Murni dari inputan database jurnal umum (jika dihapus/kosong maka 0)
        $modalAwal  = max(0, $getNetK('3101'));
        $prive      = max(0, $getNetD('3102'));
        $modalAkhir = $modalAwal + $labaBersih - $prive;

        // F. NERACA KEUANGAN
        // Aset Lancar
        $persediaanBahan   = max(0, $getNetD('1103')); // 1103 - Perlengkapan Deterjen & Parfum
        $sewaDibayarDiMuka = max(0, $getNetD('1104')); // 1104 - Sewa Dibayar di Muka
        $totalAsetLancar   = $kasDiTangan + $piutangUsaha + $persediaanBahan + $sewaDibayarDiMuka;

        // Aset Tetap
        $peralatanMesin      = max(0, $getNetD('1201')); // 1201 - Peralatan Mesin Cuci
        $penyusutanVal       = $getNetK('1202');         // 1202 - Akumulasi Penyusutan (kontra aset)
        $akumulasiPenyusutan = ($penyusutanVal > 0) ? -$penyusutanVal : 0;
        $totalAsetTetap      = $peralatanMesin + $akumulasiPenyusutan;

        // Total Aset
        $totalAset = $totalAsetLancar + $totalAsetTetap;

        // Liabilitas & Ekuitas
        $utangUsahaDetail  = max(0, $getNetK('2101')); // 2101 - Utang Usaha / Operasional
        $bebanTerutang     = max(0, $getNetK('2102')); // 2102 - Beban Terutang (Accrued Expense)
        $pendapatanDiMuka  = max(0, $getNetK('2103')); // 2103 - Pendapatan Diterima di Muka
        $totalLiabilitas   = max(0, $getNetK('2'));    // Akun 2xxx Total Liabilitas
        $totalLiabilitasDanEkuitas = $totalLiabilitas + $modalAkhir;

        // G. ARUS KAS
        // Arus Kas Operasi = Penerimaan Kas Usaha - Beban Kas
        $arusKasOperasi   = $pendapatanLunas - $totalBebanOperasional;
        $arusKasInvestasi = -$peralatanMesin;
        $arusKasPendanaan = $modalAwal - $prive;
        $kenaikanKas      = $arusKasOperasi + $arusKasInvestasi + $arusKasPendanaan;
        $saldoKasAwal     = 0;
        $saldoKasAkhir    = $kasDiTangan;

        return [
            'transaksi'                  => $transaksi,
            'tgl_awal'                   => $tglAwal,
            'tgl_akhir'                  => $tglAkhir,
            // Laba Rugi
            'total_pendapatan_usaha'     => $totalPendapatanUsaha,
            'pendapatan_lunas'           => $pendapatanLunas,
            'piutang_belum_lunas'        => $piutangBelumLunas,
            'beban_deterjen'             => $bebanDeterjenParfum,
            'beban_listrik_air'          => $bebanListrikAirGas,
            'beban_mesin'                => $bebanPerawatanMesin,
            'beban_lain'                 => $bebanOperasionalLain,
            'total_beban'                => $totalBebanOperasional,
            'laba_bersih'                => $labaBersih,
            // Ekuitas
            'modal_awal'                 => $modalAwal,
            'prive'                      => $prive,
            'modal_akhir'                => $modalAkhir,
            // Neraca
            'kas'                        => $kasDiTangan,
            'piutang'                    => $piutangUsaha,
            'persediaan'                 => $persediaanBahan,
            'sewa_dibayar_muka'          => $sewaDibayarDiMuka,
            'total_aset_lancar'          => $totalAsetLancar,
            'peralatan_mesin'            => $peralatanMesin,
            'akumulasi_penyusutan'       => $akumulasiPenyusutan,
            'total_aset_tetap'           => $totalAsetTetap,
            'total_aset'                 => $totalAset,
            'utang_usaha'                => $utangUsahaDetail,
            'beban_terutang'             => $bebanTerutang,
            'pendapatan_dimuka'          => $pendapatanDiMuka,
            'total_liabilitas'           => $totalLiabilitas,
            'total_liabilitas_ekuitas'   => $totalLiabilitasDanEkuitas,
            // Arus Kas
            'arus_kas_operasi'           => $arusKasOperasi,
            'arus_kas_investasi'         => $arusKasInvestasi,
            'arus_kas_pendanaan'         => $arusKasPendanaan,
            'saldo_kas_awal'             => $saldoKasAwal,
            'saldo_kas_akhir'            => $saldoKasAkhir,
            'total_berat'                => $totalBeratKiloan,
            'total_transaksi'            => count($transaksi),
        ];
    }

    public function index()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $activeTab = $this->request->getGet('tab') ?: 'labarugi';

        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title']     = 'Laporan Keuangan Lengkap - wishuwashere.';
        $data['menu']      = 'laporan';
        $data['activeTab'] = $activeTab;

        return view('laporan/index', $data);
    }

    // 1. Cetak Laba Rugi Terpisah
    public function cetakLabaRugi()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title'] = 'Cetak Laporan Laba Rugi - wishuwashere.';

        return view('laporan/cetak_labarugi', $data);
    }

    // 2. Cetak Neraca Terpisah
    public function cetakNeraca()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title'] = 'Cetak Neraca Keuangan - wishuwashere.';

        return view('laporan/cetak_neraca', $data);
    }

    // 3. Cetak Arus Kas Terpisah
    public function cetakArusKas()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title'] = 'Cetak Laporan Arus Kas - wishuwashere.';

        return view('laporan/cetak_aruskas', $data);
    }

    // 4. Cetak Perubahan Ekuitas Terpisah
    public function cetakEkuitas()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title'] = 'Cetak Laporan Perubahan Ekuitas - wishuwashere.';

        return view('laporan/cetak_ekuitas', $data);
    }

    // 5. Cetak Rekap Transaksi Terpisah
    public function cetakTransaksi()
    {
        $tglAwal  = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $data = $this->getLaporanData($tglAwal, $tglAkhir);
        $data['title'] = 'Cetak Rekap Riwayat Transaksi - wishuwashere.';

        return view('laporan/cetak_transaksi', $data);
    }
}
