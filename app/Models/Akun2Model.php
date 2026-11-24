<?php

namespace App\Models;

use CodeIgniter\Model;

class Akun2Model extends Model
{
    protected $table            = 'akun2';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_akun1',
        'nama_akun1',
        'kode_akun2',
        'nama_akun2',
        'posisi'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
