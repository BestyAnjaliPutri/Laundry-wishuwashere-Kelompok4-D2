<?php

namespace App\Controllers;

use App\Models\PaketModel;

class Paket extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Paket Laundry - wishuwashere.',
            'menu'  => 'paket',
            'paket' => $this->paketModel->findAll(),
        ];

        return view('paket/index', $data);
    }

    public function simpan()
    {
        $this->paketModel->insert([
            'nama_paket'    => $this->request->getPost('nama_paket'),
            'jenis'         => $this->request->getPost('jenis'),
            'harga'         => (int) $this->request->getPost('harga'),
            'estimasi_hari' => (int) $this->request->getPost('estimasi_hari'),
        ]);

        return redirect()->to(base_url('paket'))->with('success', 'Paket laundry baru berhasil ditambahkan!');
    }

    public function update($id)
    {
        $this->paketModel->update($id, [
            'nama_paket'    => $this->request->getPost('nama_paket'),
            'jenis'         => $this->request->getPost('jenis'),
            'harga'         => (int) $this->request->getPost('harga'),
            'estimasi_hari' => (int) $this->request->getPost('estimasi_hari'),
        ]);

        return redirect()->to(base_url('paket'))->with('success', 'Paket laundry berhasil diupdate!');
    }

    public function hapus($id)
    {
        $this->paketModel->delete($id);
        return redirect()->to(base_url('paket'))->with('success', 'Paket laundry berhasil dihapus!');
    }
}
