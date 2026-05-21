<?php

namespace App\Controllers;

// LS-MiniSocial-Core

use App\Models\PostModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $postModel = new PostModel();
        $currentUserId = (int) session()->get('user_id');
        $posts = $postModel->getFeedPosts($currentUserId);

        return view('home', ['posts' => $posts]);
    }
}