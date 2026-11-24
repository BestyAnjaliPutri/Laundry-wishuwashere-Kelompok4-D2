<?php
namespace App\Models;
use CodeIgniter\Model;

class JurnalPenyesuaianModel extends Model
{
    protected $table      = 'jurnal_penyesuaian';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'no_bukti','tanggal','keterangan',
        'debit_kode_akun','debit_nama_akun',
        'kredit_kode_akun','kredit_nama_akun',
        'nominal','created_at','updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
