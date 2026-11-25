<?php
namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        $this->ensureDefaultUsers();
        // If already logged in, redirect to dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    private function ensureDefaultUsers()
    {
        try {
            $userModel = new UserModel();
            $count = $userModel->countAllResults();
            if ($count === 0) {
                $now = date('Y-m-d H:i:s');
                $defaultUsers = [
                    [
                        'username'      => 'kelompok 4',
                        'name'          => 'Kelompok 4 - KOMAK',
                        'password_hash' => password_hash('kelompok4', PASSWORD_DEFAULT),
                        'role'          => 'admin',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ],
                    [
                        'username'      => 'admin',
                        'name'          => 'Administrator wishuwashere.',
                        'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                        'role'          => 'admin',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ],
                ];
                $userModel->insertBatch($defaultUsers);
            }
        } catch (\Throwable $e) {
            // Ignore if table not yet migrated
        }
    }

    public function doLogin()
    {
        $this->ensureDefaultUsers();

        $rawUsername = $this->request->getPost('username');
        $username = trim((string)$rawUsername);
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        // 1. Try exact username match
        $user = $userModel->where('username', $username)->first();
        // 2. If not found, try without spaces (e.g. 'kelompok4')
        if (!$user) {
            $normalized = preg_replace('/\s+/', '', $username);
            $user = $userModel->where('username', $normalized)->first();
        }
        // 3. If not found, try with single space (e.g. 'kelompok 4')
        if (!$user) {
            $withSpace = preg_replace('/\s+/', ' ', $username);
            $user = $userModel->where('username', $withSpace)->first();
        }

        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set([
                'user_id'   => $user['id'],
                'user_name' => $user['name'],
                'user_role' => $user['role'],
                'logged_in' => true,
            ]);
            return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['name'] . '!');
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }

    // ===== USER MANAGEMENT =====
    public function users()
    {
        $userModel = new UserModel();
        $data = [
            'title'   => 'Manajemen User',
            'menu'    => 'users',
            'users'   => $userModel->findAll(),
        ];
        return view('auth/users', $data);
    }

    public function simpanUser()
    {
        $userModel = new UserModel();
        $username = trim($this->request->getPost('username'));
        $name     = trim($this->request->getPost('name'));
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role') ?? 'operator';

        if (empty($username) || empty($password)) {
            return redirect()->to('/users')->with('error', 'Username dan password wajib diisi!');
        }
        if ($userModel->where('username', $username)->first()) {
            return redirect()->to('/users')->with('error', 'Username sudah terdaftar!');
        }

        $userModel->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'name'          => $name,
            'role'          => $role,
        ]);
        return redirect()->to('/users')->with('success', "User '$name' berhasil ditambahkan!");
    }

    public function editUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan!');
        }
        $data = [
            'title'  => 'Edit User',
            'menu'   => 'users',
            'user'   => $user,
        ];
        return view('auth/edit_user', $data);
    }

    public function updateUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan!');
        }

        $name     = trim($this->request->getPost('name'));
        $role     = $this->request->getPost('role') ?? 'operator';
        $password = $this->request->getPost('password');

        $updateData = ['name' => $name, 'role' => $role];
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);
        return redirect()->to('/users')->with('success', "User '$name' berhasil diperbarui!");
    }

    public function hapusUser($id)
    {
        $userModel = new UserModel();
        // Prevent deleting self
        if (session()->get('user_id') == $id) {
            return redirect()->to('/users')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan!');
        }
        $userModel->delete($id);
        return redirect()->to('/users')->with('success', "User '{$user['name']}' berhasil dihapus!");
    }
}
