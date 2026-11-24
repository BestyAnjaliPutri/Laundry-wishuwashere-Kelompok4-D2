<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_transaksi' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true,
            ],
            'pelanggan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'paket_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'berat_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 1.00,
            ],
            'total_biaya' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status_cucian' => [
                'type'       => 'ENUM',
                'constraint' => ['Baru Masuk', 'Sedang Dicuci', 'Proses Setrika', 'Siap Diambil', 'Sudah Diambil'],
                'default'    => 'Baru Masuk',
            ],
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Lunas', 'Lunas'],
                'default'    => 'Belum Lunas',
            ],
            'metode_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'Tunai',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tgl_masuk' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'tgl_selesai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('paket_id', 'paket', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi');
    }
}
