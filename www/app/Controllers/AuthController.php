<?php

// LS-MiniSocial-Core

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // GET /sign-up
    public function signUp(): string
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }
        return view('auth/signup');
    }

    // POST /sign-up
    public function signUpPost()
    {
        $username       = trim($this->request->getPost('username') ?? '');
        $email          = trim($this->request->getPost('email') ?? '');
        $password       = $this->request->getPost('password') ?? '';
        $repeatPassword = $this->request->getPost('repeat_password') ?? '';

        $errors = [];

        // --- Email ---
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (
            !str_ends_with($email, '@students.salle.url.edu') &&
            !str_ends_with($email, '@ext.salle.url.edu') &&
            !str_ends_with($email, '@salle.url.edu')
        ) {
            $errors['email'] = 'Only emails from the domain @students.salle.url.edu, @ext.salle.url.edu or @salle.url.edu are accepted.';
        } else {
            $userModel = new UserModel();
            if ($userModel->emailExists($email)) {
                $errors['email'] = 'The email address is already registered.';
            }
        }

        // --- Password ---
        if (strlen($password) < 8) {
            $errors['password'] = 'The password must contain at least 8 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }

        // --- Repeat password ---
        if (empty($errors['password']) && $password !== $repeatPassword) {
            $errors['repeat_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            return view('auth/signup', [
                'errors'       => $errors,
                'old_email'    => $email,
                'old_username' => $username,
            ]);
        }

        if ($username === '') {
            $username = explode('@', $email)[0];
        }

        $profilePic = null;
        $file = $this->request->getFile('profile_pic');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars', $newName);
            $profilePic = 'uploads/avatars/' . $newName;
        }

        $userModel = new UserModel();
        $userModel->insert([
            'email'       => $email,
            'password'    => password_hash($password, PASSWORD_BCRYPT),
            'username'    => $username,
            'profile_pic' => $profilePic,
        ]);

        session()->setFlashdata('success', 'Account created successfully. Please sign in.');
        return redirect()->to('/sign-in');
    }

    // GET /sign-in
    public function signIn(): string
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }
        return view('auth/signin');
    }

    // POST /sign-in
    public function signInPost()
    {
        $email    = trim($this->request->getPost('email') ?? '');
        $password = $this->request->getPost('password') ?? '';

        $errors = [];

        // --- Email ---
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (
            !str_ends_with($email, '@students.salle.url.edu') &&
            !str_ends_with($email, '@ext.salle.url.edu') &&
            !str_ends_with($email, '@salle.url.edu')
        ) {
            $errors['email'] = 'The email address is not valid.';
        }

        if (!empty($errors)) {
            return view('auth/signin', ['errors' => $errors, 'old_email' => $email]);
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return view('auth/signin', [
                'errors'    => ['general' => 'Your email and/or password are incorrect.'],
                'old_email' => $email,
            ]);
        }

        session()->set([
            'user_id'     => $user['id'],
            'email'       => $user['email'],
            'username'    => $user['username'],
            'profile_pic' => $user['profile_pic'],
        ]);

        return redirect()->to('/home');
    }

    // GET /sign-out
    public function signOut()
    {
        session()->destroy();
        return redirect()->to('/sign-in');
    }
}