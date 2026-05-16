<?php


namespace App\Controllers;

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