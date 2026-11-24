<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LaundrySeeder extends Seeder
{
    public function run()
    {
        // 1. Data Pelanggan
        $pelanggan = [
            [
                'nama'       => 'Farhan Ramadhan',
                'no_hp'      => '081234567890',
                'alamat'     => 'Jl. Mawar No. 12, Jakarta',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Ibu Nuraini',
                'no_hp'      => '085711223344',
                'alamat'     => 'Perum Permata Indah B-4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Dimas Anggara',
                'no_hp'      => '081399887766',
                'alamat'     => 'Kost Melati Kamar 05',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Anisa Rahma',
                'no_hp'      => '082133445566',
                'alamat'     => 'Apartemen Green Lake Lt 3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('pelanggan')->insertBatch($pelanggan);

        // 2. Data Paket Laundry
        $paket = [
            [
                'nama_paket'    => 'Cuci + Setrika Wangi',
                'jenis'         => 'kiloan',
                'harga'         => 8000,
                'estimasi_hari' => 2,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'    => 'Express Kilat 1 Hari',
                'jenis'         => 'kiloan',
                'harga'         => 12000,
                'estimasi_hari' => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'    => 'Cuci Lipat Saja',
                'jenis'         => 'kiloan',
                'harga'         => 6000,
                'estimasi_hari' => 2,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'    => 'Bed Cover King Jumbo',
                'jenis'         => 'satuan',
                'harga'         => 45000,
                'estimasi_hari' => 3,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_paket'    => 'Jas / Blazer Formal',
                'jenis'         => 'satuan',
                'harga'         => 35000,
                'estimasi_hari' => 2,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('paket')->insertBatch($paket);

        // 3. Data Transaksi
        $transaksi = [
            [
                'kode_transaksi' => 'KW-2601',
                'pelanggan_id'   => 1,
                'paket_id'       => 1,
                'berat_qty'      => 5.0,
                'total_biaya'    => 40000,
                'status_cucian'  => 'Sedang Dicuci',
                'status_bayar'   => 'Lunas',
                'metode_bayar'   => 'QRIS',
                'catatan'        => 'Detergen wangi lavender',
                'tgl_masuk'      => date('Y-m-d 09:15:00'),
                'tgl_selesai'    => date('Y-m-d 17:00:00', strtotime('+2 days')),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_transaksi' => 'KW-2600',
                'pelanggan_id'   => 2,
                'paket_id'       => 4,
                'berat_qty'      => 2.0,
                'total_biaya'    => 90000,
                'status_cucian'  => 'Siap Diambil',
                'status_bayar'   => 'Belum Lunas',
                'metode_bayar'   => 'Tunai',
                'catatan'        => 'Plastik bungkus rapat',
                'tgl_masuk'      => date('Y-m-d 14:00:00', strtotime('-1 day')),
                'tgl_selesai'    => date('Y-m-d 15:00:00', strtotime('+2 days')),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_transaksi' => 'KW-2599',
                'pelanggan_id'   => 3,
                'paket_id'       => 2,
                'berat_qty'      => 3.5,
                'total_biaya'    => 42000,
                'status_cucian'  => 'Proses Setrika',
                'status_bayar'   => 'Lunas',
                'metode_bayar'   => 'Tunai',
                'catatan'        => 'Kemeja digantung',
                'tgl_masuk'      => date('Y-m-d 08:30:00'),
                'tgl_selesai'    => date('Y-m-d 08:30:00', strtotime('+1 day')),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_transaksi' => 'KW-2598',
                'pelanggan_id'   => 4,
                'paket_id'       => 3,
                'berat_qty'      => 6.0,
                'total_biaya'    => 36000,
                'status_cucian'  => 'Baru Masuk',
                'status_bayar'   => 'Lunas',
                'metode_bayar'   => 'Transfer',
                'catatan'        => 'Jangan dicampur pemutih',
                'tgl_masuk'      => date('Y-m-d 11:00:00'),
                'tgl_selesai'    => date('Y-m-d 11:00:00', strtotime('+2 days')),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('transaksi')->insertBatch($transaksi);
    }
}
