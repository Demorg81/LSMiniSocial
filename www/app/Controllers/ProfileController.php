<?php

namespace App\Controllers;

// LS-MiniSocial-Core

use App\Models\UserModel;
use App\Models\PostModel;

class ProfileController extends BaseController
{
    // GET /profile
    public function index(): string
    {
        $userId = (int)session()->get('user_id');
        $userModel = new UserModel();
        $postModel = new PostModel();

        $user = $userModel->find($userId);
        $posts = $postModel->getPostsByUser($userId);

        return view('profile', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    // POST /profile
    public function update()
    {
        $userId = (int)session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $action = $this->request->getPost('action') ?? 'update';

        // Borrar cuenta
        if ($action === 'delete') {
            $userModel->delete($userId);
            session()->destroy();
            return redirect()->to('/');
        }

        // Actualizar perfil
        $username = trim($this->request->getPost('username') ?? '');
        $password = $this->request->getPost('password') ?? '';
        $repeatPassword = $this->request->getPost('repeat_password') ?? '';
        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Username must not be empty.';
        }

        // Contraseña
        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors['password'] = 'The password must contain at least 8 characters.';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
                $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
            } elseif ($password !== $repeatPassword) {
                $errors['repeat_password'] = 'Passwords do not match.';
            }
        }

        if (!empty($errors)) {
            $postModel = new PostModel();
            return view('profile', [
                'user' => $user,
                'posts' => $postModel->getPostsByUser($userId),
                'errors' => $errors,
            ]);
        }

        $data = ['username' => $username];

        // Nueva foto de perfil
        $file = $this->request->getFile('profile_pic');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars', $newName);
            $data['profile_pic'] = 'uploads/avatars/' . $newName;
        }

        // Nueva contraseña
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($userId, $data);

        // Actualizar sesión con los nuevos datos
        session()->set([
            'username' => $username,
            'profile_pic' => $data['profile_pic'] ?? $user['profile_pic'],
        ]);

        session()->setFlashdata('success', 'Profile updated successfully.');
        return redirect()->to('/profile');
    }
}
