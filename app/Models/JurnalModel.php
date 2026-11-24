<?php
namespace App\Models;

use CodeIgniter\Model;

/**
 * Model for the journal entries (jurnal_umum) table.
 * Provides CRUD operations for independent journal entries.
 */
class JurnalModel extends Model
{
    protected $table      = 'jurnal_umum';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'no_bukti',
        'tanggal',
        'tipe_transaksi',
        'keterangan',
        'debit_akun_id',
        'debit_kode_akun',
        'debit_nama_akun',
        'kredit_akun_id',
        'kredit_kode_akun',
        'kredit_nama_akun',
        'nominal',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
?>
