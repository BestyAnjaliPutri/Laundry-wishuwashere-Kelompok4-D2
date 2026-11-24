<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'kode_transaksi',
        'pelanggan_id',
        'paket_id',
        'berat_qty',
        'total_biaya',
        'status_cucian',
        'status_bayar',
        'metode_bayar',
        'catatan',
        'tgl_masuk',
        'tgl_selesai'
    ];
    protected $useTimestamps    = true;

    public function getTransaksiWithDetail($id = null)
    {
        $builder = $this->select('transaksi.*, pelanggan.nama as nama_pelanggan, pelanggan.no_hp, pelanggan.alamat, paket.nama_paket, paket.jenis as jenis_paket, paket.harga as harga_paket')
            ->join('pelanggan', 'pelanggan.id = transaksi.pelanggan_id')
            ->join('paket', 'paket.id = transaksi.paket_id')
            ->orderBy('transaksi.id', 'DESC');

        if ($id !== null) {
            return $builder->where('transaksi.id', $id)->first();
        }

        return $builder->findAll();
    }
}
