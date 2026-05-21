<?php

namespace App\Controllers;

// LS-MiniSocial-Core

use App\Models\PostModel;

class PostController extends BaseController
{
    public function create(): string
    {
        return view('posts/create');
    }

    public function store()
    {
        $content = trim($this->request->getPost('content') ?? '');
        $errors  = [];

        if ($content === '') {
            $errors['content'] = 'Content must not be empty.';
        }

        if (!empty($errors)) {
            return view('posts/create', ['errors' => $errors, 'old_content' => $content]);
        }

        $imagePath = null;
        $image     = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $newName);
            $imagePath = 'uploads/' . $newName;
        }

        $postModel = new PostModel();
        $postModel->insert([
            'user_id' => session()->get('user_id'),
            'content' => $content,
            'image'   => $imagePath,
        ]);

        session()->setFlashdata('success', 'Post created successfully.');
        return redirect()->to('/home');
    }

    public function edit(int $id)
    {
        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (!$post || (int)$post['user_id'] !== (int)session()->get('user_id')) {
            session()->setFlashdata('error', 'Post not found or access denied.');
            return redirect()->to('/home');
        }

        return view('posts/edit', ['post' => $post]);
    }

    public function update(int $id)
    {
        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (!$post || (int)$post['user_id'] !== (int)session()->get('user_id')) {
            session()->setFlashdata('error', 'Post not found or access denied.');
            return redirect()->to('/home');
        }

        $content = trim($this->request->getPost('content') ?? '');
        $errors  = [];

        if ($content === '') {
            $errors['content'] = 'Content must not be empty.';
        }

        if (!empty($errors)) {
            return view('posts/edit', ['post' => $post, 'errors' => $errors]);
        }

        $imagePath = $post['image'];
        $image     = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName   = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $newName);
            $imagePath = 'uploads/' . $newName;
        }

        $postModel->update($id, [
            'content' => $content,
            'image'   => $imagePath,
        ]);

        session()->setFlashdata('success', 'Post updated successfully.');
        return redirect()->to('/home');
    }

    public function delete(int $id)
    {
        $postModel = new PostModel();
        $post      = $postModel->find($id);

        if (!$post || (int)$post['user_id'] !== (int)session()->get('user_id')) {
            session()->setFlashdata('error', 'Post not found or access denied.');
            return redirect()->to('/home');
        }

        $postModel->delete($id);
        session()->setFlashdata('success', 'Post deleted.');
        return redirect()->to('/home');
    }
}