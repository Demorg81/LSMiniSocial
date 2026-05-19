<?php

namespace App\Controllers;

// LS-MiniSocial-Core

use App\Models\PostModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $postModel = new PostModel();
        $posts = $postModel->getFeedPosts();

        return view('home', ['posts' => $posts]);
    }
}
