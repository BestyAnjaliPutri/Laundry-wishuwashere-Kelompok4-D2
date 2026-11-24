<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class Pelanggan extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Pelanggan - wishuwashere.',
            'menu'      => 'pelanggan',
            'pelanggan' => $this->pelangganModel->findAll(),
        ];

        return view('pelanggan/index', $data);
    }

    public function simpan()
    {
        $this->pelangganModel->insert([
            'nama'   => $this->request->getPost('nama'),
            'no_hp'  => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
        ]);

        return redirect()->to(base_url('pelanggan'))->with('success', 'Data pelanggan berhasil disimpan!');
    }

    public function update($id)
    {
        $this->pelangganModel->update($id, [
            'nama'   => $this->request->getPost('nama'),
            'no_hp'  => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
        ]);

        return redirect()->to(base_url('pelanggan'))->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $this->pelangganModel->delete($id);
        return redirect()->to(base_url('pelanggan'))->with('success', 'Data pelanggan berhasil dihapus!');
    }
}
