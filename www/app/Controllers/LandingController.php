<?php


namespace App\Controllers;

// LS-MiniSocial-Core

class LandingController extends BaseController
{
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }

        return view('landing');
    }
}