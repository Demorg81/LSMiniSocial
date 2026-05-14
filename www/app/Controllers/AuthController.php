<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // GET
    public function signUp(): string
    {
        return view('auth/signup');
    }

    // POST
    public function signUpPost()
    {
        $email          = $this->request->getPost('email');
        $password       = $this->request->getPost('password');
        $repeatPassword = $this->request->getPost('repeat_password');

        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (!str_ends_with($email, '@salle.url.edu')) {
            $errors['email'] = 'Only emails from the domain @salle.url.edu are accepted.';
        } else {
            $userModel = new UserModel();
            if ($userModel->emailExists($email)) {
                $errors['email'] = 'The email address is already registered.';
            }
        }


        if (strlen($password) < 7) {
            $errors['password'] = 'The password must contain at least 7 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
            $errors['password'] = 'The password must contain both upper and lower case letters and at least one number.';
        }

        if (empty($errors['password']) && $password !== $repeatPassword) {
            $errors['repeat_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            return view('auth/signup', [
                'errors'    => $errors,
                'old_email' => $email,
            ]);
        }

        $userModel = new UserModel();
        $userModel->insert([
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        return redirect()->to('/sign-in');
    }

    // GET
    public function signIn(): string
    {
        return view('auth/signin');
    }

    // POST
    public function signInPost()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (!str_ends_with($email, '@salle.url.edu')) {
            $errors['email'] = 'Only emails from the domain @salle.url.edu are accepted.';
        }

        if (strlen($password) < 7) {
            $errors['password'] = 'The password must contain at least 7 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }

        if (!empty($errors)) {
            return view('auth/signin', ['errors' => $errors, 'old_email' => $email]);
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $errors['email'] = 'User with this email address does not exist.';
            return view('auth/signin', ['errors' => $errors, 'old_email' => $email]);
        }

        if (!password_verify($password, $user['password'])) {
            $errors['general'] = 'Your email and/or password are incorrect.';
            return view('auth/signin', ['errors' => $errors, 'old_email' => $email]);
        }

        session()->set([
            'user_id' => $user['id'],
            'email'   => $user['email'],
        ]);

        return redirect()->to('/');
    }

    // GET
    public function signOut()
    {
        session()->destroy();
        return redirect()->to('/sign-in');
    }
}