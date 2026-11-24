<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $paketModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->paketModel     = new PaketModel();
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Kasir & Transaksi - wishuwashere.',
            'menu'      => 'kasir',
            'transaksi' => $this->transaksiModel->getTransaksiWithDetail(),
            'paket'     => $this->paketModel->findAll(),
            'pelanggan' => $this->pelangganModel->findAll(),
        ];

        return view('transaksi/index', $data);
    }

    public function simpan()
    {
        $pelangganId = $this->request->getPost('pelanggan_id');
        $namaBaru    = trim($this->request->getPost('nama_pelanggan_baru') ?? '');
        $noHpBaru    = trim($this->request->getPost('no_hp_baru') ?? '');

        // Jika memilih input pelanggan baru langsung di kasir
        if ($pelangganId === 'baru' || empty($pelangganId)) {
            if (!empty($namaBaru)) {
                $this->pelangganModel->insert([
                    'nama'   => $namaBaru,
                    'no_hp'  => $noHpBaru,
                    'alamat' => $this->request->getPost('alamat_baru') ?? '',
                ]);
                $pelangganId = $this->pelangganModel->getInsertID();
            } else {
                return redirect()->back()->with('error', 'Silakan pilih pelanggan atau isi nama pelanggan baru.');
            }
        }

        $paketId  = $this->request->getPost('paket_id');
        $beratQty = (float) $this->request->getPost('berat_qty');
        $paket    = $this->paketModel->find($paketId);

        if (!$paket) {
            return redirect()->back()->with('error', 'Paket laundry tidak valid.');
        }

        $totalBiaya = (int) ($beratQty * $paket['harga']);
        $estimasiHari = $paket['estimasi_hari'] ?? 2;
        $tglMasuk = date('Y-m-d H:i:s');
        $tglSelesai = date('Y-m-d H:i:s', strtotime("+{$estimasiHari} days"));

        // Generate Kode Transaksi Unik
        $kodeTransaksi = 'KW-' . date('d') . rand(100, 999);

        $this->transaksiModel->insert([
            'kode_transaksi' => $kodeTransaksi,
            'pelanggan_id'   => $pelangganId,
            'paket_id'       => $paketId,
            'berat_qty'      => $beratQty,
            'total_biaya'    => $totalBiaya,
            'status_cucian'  => 'Baru Masuk',
            'status_bayar'   => $this->request->getPost('status_bayar') ?? 'Belum Lunas',
            'metode_bayar'   => $this->request->getPost('metode_bayar') ?? 'Tunai',
            'catatan'        => $this->request->getPost('catatan'),
            'tgl_masuk'      => $tglMasuk,
            'tgl_selesai'    => $tglSelesai,
        ]);

        // Integrasi Otomatis ke Jurnal Umum saat Transaksi Diterima
        $statusBayar = $this->request->getPost('status_bayar') ?? 'Belum Lunas';
        $metodeBayar = $this->request->getPost('metode_bayar') ?? 'Tunai';
        $pelanggan   = $this->pelangganModel->find($pelangganId);
        $namaPelanggan = $pelanggan['nama'] ?? 'Pelanggan';
        $namaPaket     = $paket['nama_paket'] ?? 'Layanan Laundry';

        $jurnalModel = new \App\Models\JurnalModel();
        if ($statusBayar === 'Lunas') {
            $jurnalModel->insert([
                'no_bukti'        => $kodeTransaksi,
                'tanggal'         => date('Y-m-d'),
                'tipe_transaksi'  => 'Penerimaan Pendapatan',
                'keterangan'      => "Pendapatan Cucian {$namaPaket} - {$namaPelanggan} ({$kodeTransaksi}) [{$metodeBayar}]",
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => $totalBiaya,
            ]);
        } else {
            $jurnalModel->insert([
                'no_bukti'        => $kodeTransaksi,
                'tanggal'         => date('Y-m-d'),
                'tipe_transaksi'  => 'Piutang Laundry',
                'keterangan'      => "Piutang Cucian {$namaPaket} - {$namaPelanggan} ({$kodeTransaksi})",
                'debit_kode_akun' => '1102',
                'debit_nama_akun' => 'Piutang Usaha Pelanggan',
                'kredit_kode_akun'=> '4101',
                'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                'nominal'         => $totalBiaya,
            ]);
        }

        return redirect()->to(base_url('kasir'))->with('success', "Order transaksi {$kodeTransaksi} berhasil dicatat & masuk ke Jurnal Umum!");
    }

    public function updateStatus()
    {
        $id           = $this->request->getPost('id');
        $statusCucian = $this->request->getPost('status_cucian');
        $statusBayar  = $this->request->getPost('status_bayar');

        $oldTransaksi = $this->transaksiModel->find($id);

        $updateData = [];
        if ($statusCucian) {
            $updateData['status_cucian'] = $statusCucian;
        }
        if ($statusBayar) {
            $updateData['status_bayar'] = $statusBayar;
        }

        if (!empty($updateData)) {
            $this->transaksiModel->update($id, $updateData);
        }

        // Jika status pembayaran diperbarui menjadi Lunas dari sebelumnya Belum Lunas:
        // Catat pelunasan piutang ke Jurnal Umum
        if ($oldTransaksi && $oldTransaksi['status_bayar'] !== 'Lunas' && $statusBayar === 'Lunas') {
            $pelanggan = $this->pelangganModel->find($oldTransaksi['pelanggan_id']);
            $namaPelanggan = $pelanggan['nama'] ?? 'Pelanggan';

            $jurnalModel = new \App\Models\JurnalModel();
            $jurnalModel->insert([
                'no_bukti'        => 'PL-' . $oldTransaksi['kode_transaksi'],
                'tanggal'         => date('Y-m-d'),
                'tipe_transaksi'  => 'Pelunasan Piutang',
                'keterangan'      => "Pelunasan Piutang Cucian {$oldTransaksi['kode_transaksi']} - {$namaPelanggan}",
                'debit_kode_akun' => '1101',
                'debit_nama_akun' => 'Kas di Tangan / Bank',
                'kredit_kode_akun'=> '1102',
                'kredit_nama_akun'=> 'Piutang Usaha Pelanggan',
                'nominal'         => (int) $oldTransaksi['total_biaya'],
            ]);
        }

        return redirect()->back()->with('success', 'Status cucian & jurnal keuangan berhasil diperbarui!');
    }

    public function cetak($id)
    {
        $transaksi = $this->transaksiModel->getTransaksiWithDetail($id);

        if (!$transaksi) {
            return redirect()->to(base_url('kasir'))->with('error', 'Transaksi tidak ditemukan.');
        }

        $data = [
            'transaksi' => $transaksi,
        ];

        return view('transaksi/cetak_nota', $data);
    }

    public function seedTransaksi()
    {
        // 1. Tambah pelanggan sampel jika belum cukup
        $samplePelanggan = [
            ['nama' => 'Ibu Rina Kusuma', 'no_hp' => '081288991122', 'alamat' => 'Jl. Pajajaran No. 12, Bogor'],
            ['nama' => 'Bpk. Hendra Wijaya', 'no_hp' => '085711223344', 'alamat' => 'Perum Baranangsiang Indah Blok B3'],
            ['nama' => 'Kak Nadia Safitri', 'no_hp' => '081399887766', 'alamat' => 'Jl. Surya Kencana No. 45, Bogor'],
            ['nama' => 'Dr. Faisal Akbar', 'no_hp' => '081277665544', 'alamat' => 'Komplek Dosen IPB Baranangsiang'],
            ['nama' => 'Ibu Siti Rohmah', 'no_hp' => '085699443322', 'alamat' => 'Jl. Lodaya II No. 8, Bogor'],
            ['nama' => 'Mas Dimas Pratama', 'no_hp' => '081355443322', 'alamat' => 'Jl. Kumbang No. 15, Bogor'],
            ['nama' => 'Mbak Ayu Lestari', 'no_hp' => '081233445566', 'alamat' => 'Apartemen Bogor Icon Tower A'],
            ['nama' => 'Bpk. Agus Setiawan', 'no_hp' => '085811335577', 'alamat' => 'Jl. Malabar No. 20, Bogor'],
            ['nama' => 'Ibu Ratna Juwita', 'no_hp' => '081299001122', 'alamat' => 'Jl. Heulang No. 5, Tanah Sareal'],
            ['nama' => 'Mas Reza Pahlevi', 'no_hp' => '087822446688', 'alamat' => 'Taman Kencana No. 11, Bogor'],
            ['nama' => 'Mbak Tari Wulandari', 'no_hp' => '081377889900', 'alamat' => 'Jl. Salak No. 18, Bogor'],
            ['nama' => 'Bpk. Bambang Sutrisno', 'no_hp' => '081244556677', 'alamat' => 'Jl. Semboja No. 9, Bogor Tengah'],
            ['nama' => 'Ibu Dewi Sartika', 'no_hp' => '081211223344', 'alamat' => 'Jl. Pengadilan No. 4, Bogor'],
            ['nama' => 'Mas Aditya Nugraha', 'no_hp' => '085788990011', 'alamat' => 'Perum Bantarjati Blok F2 No. 10'],
            ['nama' => 'Kak Cindy Claudia', 'no_hp' => '081344556677', 'alamat' => 'Jl. Sancang No. 22, Bogor Timur'],
            ['nama' => 'Bpk. H. Rahmat Hidayat', 'no_hp' => '081299881133', 'alamat' => 'Jl. Ciheuleut No. 30, Baranangsiang'],
            ['nama' => 'Mbak Siska Amelia', 'no_hp' => '087733445566', 'alamat' => 'Kost Putri Mahasiswi IPB Dramaga'],
            ['nama' => 'Mas Fajar Ramadhan', 'no_hp' => '085611778899', 'alamat' => 'Jl. Pandu Raya No. 15, Bogor Utara'],
            ['nama' => 'Ibu Maya Indrawati', 'no_hp' => '081277332211', 'alamat' => 'Villa Duta Blok C No. 7, Bogor'],
            ['nama' => 'Bpk. Ir. Gunawan', 'no_hp' => '081388001122', 'alamat' => 'Perum Danau Bogor Raya Blok D'],
        ];

        foreach ($samplePelanggan as $sp) {
            $exists = $this->pelangganModel->where('no_hp', $sp['no_hp'])->first();
            if (!$exists) {
                $this->pelangganModel->insert($sp);
            }
        }
        $allPelanggan = $this->pelangganModel->findAll();
        $allPaket = $this->paketModel->findAll();

        if (empty($allPaket)) {
            $defaultPaket = [
                ['nama_paket' => 'Cuci Kering Setrika Reguler', 'jenis' => 'kiloan', 'harga' => 8000, 'estimasi_hari' => 2],
                ['nama_paket' => 'Cuci Kering Setrika Express', 'jenis' => 'kiloan', 'harga' => 12000, 'estimasi_hari' => 1],
                ['nama_paket' => 'Cuci Kering Lipat', 'jenis' => 'kiloan', 'harga' => 6000, 'estimasi_hari' => 2],
                ['nama_paket' => 'Bed Cover Besar', 'jenis' => 'satuan', 'harga' => 35000, 'estimasi_hari' => 3],
                ['nama_paket' => 'Jas / Blazer Formal', 'jenis' => 'satuan', 'harga' => 30000, 'estimasi_hari' => 2],
                ['nama_paket' => 'Sepatu Sneakers / Canvas', 'jenis' => 'satuan', 'harga' => 25000, 'estimasi_hari' => 2],
            ];
            foreach ($defaultPaket as $dp) {
                $this->paketModel->insert($dp);
            }
            $allPaket = $this->paketModel->findAll();
        }

        // 32 Transaksi Baru Realistis
        $dataset = [
            ['plg_idx' => 0, 'pkt_idx' => 0, 'qty' => 4.5, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-02 08:30:00', 'catatan' => 'Pewangi lavender'],
            ['plg_idx' => 1, 'pkt_idx' => 3, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-03 09:15:00', 'catatan' => 'Bed cover king size'],
            ['plg_idx' => 2, 'pkt_idx' => 1, 'qty' => 6.0, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-04 10:00:00', 'catatan' => 'Express jangan kusut'],
            ['plg_idx' => 3, 'pkt_idx' => 4, 'qty' => 3.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-05 11:20:00', 'catatan' => 'Jas kantor dokter'],
            ['plg_idx' => 4, 'pkt_idx' => 0, 'qty' => 7.2, 'bayar' => 'Belum Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-06 13:45:00', 'catatan' => 'Bayar pas akhir bulan'],
            ['plg_idx' => 5, 'pkt_idx' => 5, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-07 14:10:00', 'catatan' => 'Sepatu putih & running'],
            ['plg_idx' => 6, 'pkt_idx' => 0, 'qty' => 5.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-08 08:50:00', 'catatan' => 'Pakaian anak dipisah'],
            ['plg_idx' => 7, 'pkt_idx' => 2, 'qty' => 8.5, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-09 15:30:00', 'catatan' => 'Lipat rapi'],
            ['plg_idx' => 8, 'pkt_idx' => 3, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-10 16:20:00', 'catatan' => 'Selimut bulu lembut'],
            ['plg_idx' => 9, 'pkt_idx' => 1, 'qty' => 4.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-11 11:00:00', 'catatan' => 'Baju dinas'],
            ['plg_idx' => 10, 'pkt_idx' => 0, 'qty' => 6.5, 'bayar' => 'Belum Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-12 10:40:00', 'catatan' => 'Piutang langganan'],
            ['plg_idx' => 11, 'pkt_idx' => 0, 'qty' => 5.8, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-13 09:30:00', 'catatan' => 'Baju harian'],
            ['plg_idx' => 0, 'pkt_idx' => 1, 'qty' => 5.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-14 12:15:00', 'catatan' => 'Langganan Bu Rina'],
            ['plg_idx' => 1, 'pkt_idx' => 0, 'qty' => 9.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-15 14:00:00', 'catatan' => 'Pakaian keluarga besar'],
            ['plg_idx' => 2, 'pkt_idx' => 4, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-16 10:25:00', 'catatan' => 'Blazer kerja'],
            ['plg_idx' => 3, 'pkt_idx' => 3, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-16 16:50:00', 'catatan' => 'Bed cover 2 pcs'],
            ['plg_idx' => 4, 'pkt_idx' => 1, 'qty' => 4.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-17 08:40:00', 'catatan' => 'Baju dinas pemda'],
            ['plg_idx' => 5, 'pkt_idx' => 0, 'qty' => 6.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-18 11:10:00', 'catatan' => 'Kemeja kuliah'],
            ['plg_idx' => 6, 'pkt_idx' => 5, 'qty' => 3.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-18 15:45:00', 'catatan' => 'Sepatu 3 pasang'],
            ['plg_idx' => 7, 'pkt_idx' => 0, 'qty' => 7.0, 'bayar' => 'Belum Lunas', 'metode' => 'Tunai', 'status' => 'Siap Diambil', 'tgl' => '2026-11-19 09:20:00', 'catatan' => 'Siap diambil sore'],
            ['plg_idx' => 8, 'pkt_idx' => 1, 'qty' => 5.5, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Siap Diambil', 'tgl' => '2026-11-19 13:30:00', 'catatan' => 'Packing wangi sakura'],
            ['plg_idx' => 9, 'pkt_idx' => 0, 'qty' => 4.8, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Siap Diambil', 'tgl' => '2026-11-20 08:15:00', 'catatan' => 'Selesai tepat waktu'],
            ['plg_idx' => 10, 'pkt_idx' => 3, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Proses Setrika', 'tgl' => '2026-11-20 14:00:00', 'catatan' => 'Bed cover king'],
            ['plg_idx' => 11, 'pkt_idx' => 0, 'qty' => 8.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Proses Setrika', 'tgl' => '2026-11-21 09:50:00', 'catatan' => 'Kaos dan celana'],
            ['plg_idx' => 0, 'pkt_idx' => 2, 'qty' => 6.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Proses Setrika', 'tgl' => '2026-11-21 11:30:00', 'catatan' => 'Cuci lipat'],
            ['plg_idx' => 1, 'pkt_idx' => 1, 'qty' => 4.0, 'bayar' => 'Belum Lunas', 'metode' => 'Transfer', 'status' => 'Sedang Dicuci', 'tgl' => '2026-11-22 08:45:00', 'catatan' => 'Cucian express'],
            ['plg_idx' => 2, 'pkt_idx' => 5, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sedang Dicuci', 'tgl' => '2026-11-22 10:15:00', 'catatan' => 'Sepatu vans'],
            ['plg_idx' => 3, 'pkt_idx' => 0, 'qty' => 5.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sedang Dicuci', 'tgl' => '2026-11-22 13:20:00', 'catatan' => 'Sprei & sarung bantal'],
            ['plg_idx' => 4, 'pkt_idx' => 4, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sedang Dicuci', 'tgl' => '2026-11-23 09:00:00', 'catatan' => 'Jas resepsi'],
            ['plg_idx' => 5, 'pkt_idx' => 0, 'qty' => 6.8, 'bayar' => 'Belum Lunas', 'metode' => 'Tunai', 'status' => 'Baru Masuk', 'tgl' => '2026-11-23 11:30:00', 'catatan' => 'Order baru masuk siang'],
            ['plg_idx' => 6, 'pkt_idx' => 1, 'qty' => 3.5, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Baru Masuk', 'tgl' => '2026-11-23 14:15:00', 'catatan' => 'Express besok sore'],
            ['plg_idx' => 7, 'pkt_idx' => 3, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Baru Masuk', 'tgl' => '2026-11-23 16:00:00', 'catatan' => 'Bed cover tebal'],
            ['plg_idx' => 12, 'pkt_idx' => 0, 'qty' => 5.5, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-04 11:15:00', 'catatan' => 'Pakaian kantor rapi'],
            ['plg_idx' => 13, 'pkt_idx' => 1, 'qty' => 4.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-06 09:40:00', 'catatan' => 'Express 1 hari'],
            ['plg_idx' => 14, 'pkt_idx' => 3, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-08 14:20:00', 'catatan' => 'Bed cover katun jepang'],
            ['plg_idx' => 15, 'pkt_idx' => 4, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-10 10:10:00', 'catatan' => 'Jas resepsi pernikahan'],
            ['plg_idx' => 16, 'pkt_idx' => 0, 'qty' => 6.0, 'bayar' => 'Belum Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-12 13:50:00', 'catatan' => 'Bayar pas gajian'],
            ['plg_idx' => 17, 'pkt_idx' => 5, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-13 16:30:00', 'catatan' => 'Sepatu futsal & sneakers'],
            ['plg_idx' => 18, 'pkt_idx' => 0, 'qty' => 8.4, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-15 08:30:00', 'catatan' => 'Sprei villa & sarung bantal'],
            ['plg_idx' => 19, 'pkt_idx' => 1, 'qty' => 5.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-17 11:00:00', 'catatan' => 'Baju dinas pemkot'],
            ['plg_idx' => 12, 'pkt_idx' => 2, 'qty' => 7.0, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sudah Diambil', 'tgl' => '2026-11-18 15:10:00', 'catatan' => 'Cuci lipat hemat'],
            ['plg_idx' => 13, 'pkt_idx' => 0, 'qty' => 4.6, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Siap Diambil', 'tgl' => '2026-11-20 10:20:00', 'catatan' => 'Pewangi ocean fresh'],
            ['plg_idx' => 14, 'pkt_idx' => 3, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Siap Diambil', 'tgl' => '2026-11-21 13:40:00', 'catatan' => 'Bed cover king'],
            ['plg_idx' => 15, 'pkt_idx' => 1, 'qty' => 3.8, 'bayar' => 'Belum Lunas', 'metode' => 'Tunai', 'status' => 'Proses Setrika', 'tgl' => '2026-11-22 09:15:00', 'catatan' => 'Express kilat'],
            ['plg_idx' => 16, 'pkt_idx' => 4, 'qty' => 2.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Proses Setrika', 'tgl' => '2026-11-23 10:45:00', 'catatan' => 'Jas wisuda IPB'],
            ['plg_idx' => 17, 'pkt_idx' => 0, 'qty' => 5.2, 'bayar' => 'Lunas', 'metode' => 'Tunai', 'status' => 'Sedang Dicuci', 'tgl' => '2026-11-23 15:30:00', 'catatan' => 'Pakaian harian kost'],
            ['plg_idx' => 18, 'pkt_idx' => 5, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Baru Masuk', 'tgl' => '2026-11-24 08:30:00', 'catatan' => 'Sepatu running nike'],
            ['plg_idx' => 19, 'pkt_idx' => 0, 'qty' => 6.5, 'bayar' => 'Lunas', 'metode' => 'Transfer', 'status' => 'Baru Masuk', 'tgl' => '2026-11-24 09:15:00', 'catatan' => 'Cuci setrika reguler'],
            ['plg_idx' => 0, 'pkt_idx' => 1, 'qty' => 4.5, 'bayar' => 'Belum Lunas', 'metode' => 'Tunai', 'status' => 'Baru Masuk', 'tgl' => '2026-11-24 10:00:00', 'catatan' => 'Pelanggan langganan Bu Rina'],
            ['plg_idx' => 1, 'pkt_idx' => 3, 'qty' => 1.0, 'bayar' => 'Lunas', 'metode' => 'QRIS', 'status' => 'Baru Masuk', 'tgl' => '2026-11-24 10:30:00', 'catatan' => 'Bed cover anak motif kartun'],
        ];

        $jurnalModel = new \App\Models\JurnalModel();
        $counter = 101;
        $tambahCount = 0;

        foreach ($dataset as $row) {
            $plg = $allPelanggan[$row['plg_idx'] % count($allPelanggan)];
            $pkt = $allPaket[$row['pkt_idx'] % count($allPaket)];

            $total = (int) round($row['qty'] * $pkt['harga']);
            $kdTrans = 'KW-' . date('d', strtotime($row['tgl'])) . str_pad($counter, 3, '0', STR_PAD_LEFT);

            // Cari kode_transaksi yang belum pernah dipakai
            while ($this->transaksiModel->where('kode_transaksi', $kdTrans)->first()) {
                $counter++;
                $kdTrans = 'KW-' . date('d', strtotime($row['tgl'])) . str_pad($counter, 3, '0', STR_PAD_LEFT);
            }

            $estHari = $pkt['estimasi_hari'] ?? 2;
            $tglSelesai = date('Y-m-d H:i:s', strtotime($row['tgl'] . " +{$estHari} days"));

            $this->transaksiModel->insert([
                'kode_transaksi' => $kdTrans,
                'pelanggan_id'   => $plg['id'],
                'paket_id'       => $pkt['id'],
                'berat_qty'      => $row['qty'],
                'total_biaya'    => $total,
                'status_cucian'  => $row['status'],
                'status_bayar'   => $row['bayar'],
                'metode_bayar'   => $row['metode'],
                'catatan'        => $row['catatan'],
                'tgl_masuk'      => $row['tgl'],
                'tgl_selesai'    => $tglSelesai,
            ]);

            // Masukkan ke jurnal umum
            $tglJurnal = date('Y-m-d', strtotime($row['tgl']));
            if ($row['bayar'] === 'Lunas') {
                $jurnalModel->insert([
                    'no_bukti'        => $kdTrans,
                    'tanggal'         => $tglJurnal,
                    'tipe_transaksi'  => 'Penerimaan Pendapatan',
                    'keterangan'      => "Pendapatan Cucian {$pkt['nama_paket']} - {$plg['nama']} ({$kdTrans}) [{$row['metode']}]",
                    'debit_kode_akun' => '1101',
                    'debit_nama_akun' => 'Kas di Tangan / Bank',
                    'kredit_kode_akun'=> '4101',
                    'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                    'nominal'         => $total,
                ]);
            } else {
                $jurnalModel->insert([
                    'no_bukti'        => $kdTrans,
                    'tanggal'         => $tglJurnal,
                    'tipe_transaksi'  => 'Piutang Laundry',
                    'keterangan'      => "Piutang Cucian {$pkt['nama_paket']} - {$plg['nama']} ({$kdTrans})",
                    'debit_kode_akun' => '1102',
                    'debit_nama_akun' => 'Piutang Usaha Pelanggan',
                    'kredit_kode_akun'=> '4101',
                    'kredit_nama_akun'=> 'Pendapatan Jasa Laundry',
                    'nominal'         => $total,
                ]);
            }

            $counter++;
            $tambahCount++;
        }

        return redirect()->to(base_url('kasir'))->with('success', "Berhasil menambahkan {$tambahCount} transaksi baru & integrasi ke Jurnal Umum!");
    }
}
