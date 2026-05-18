<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PostModel;
use CodeIgniter\HTTP\ResponseInterface;

// LS-MiniSocial-Core
class PostApiController extends BaseController
{
    private PostModel $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }


    // GET /posts
    // Devuelve todos los posts (feed completo).
    public function index(): ResponseInterface
    {
        $posts = $this->postModel->getFeedPosts();

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['data' => $posts]);
    }


    // POST /posts
    // Crea un nuevo post para el usuario autenticado.
    public function create(): ResponseInterface
    {
        $userId = session()->get('user_id');
        $content = trim($this->request->getPost('content') ?? '');

        if ($content === '') {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Content must not be empty.']);
        }

        $imagePath = null;
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . '../public/uploads', $newName);
            $imagePath = 'uploads/' . $newName;
        }

        $postId = $this->postModel->insert([
            'user_id' => $userId,
            'content' => $content,
            'image' => $imagePath,
        ]);

        $post = $this->postModel->getPostWithMeta((int)$postId);

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['data' => $post]);
    }


    // GET /posts/{id}
    // Devuelve un post concreto.
    public function show(int $id): ResponseInterface
    {
        $post = $this->postModel->getPostWithMeta($id);

        if (!$post) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['data' => $post]);
    }


    // PUT /posts/{id}
    // Actualiza el contenido de un post (solo el propietario).
    public function update(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');
        $post = $this->postModel->find($id);

        if (!$post) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        if ((int)$post['user_id'] !== (int)$userId) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['error' => 'You are not allowed to edit this post.']);
        }

        $raw = $this->request->getRawInput();
        $content = trim($raw['content'] ?? $this->request->getPost('content') ?? '');

        if ($content === '') {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Content must not be empty.']);
        }

        $this->postModel->update($id, ['content' => $content]);
        $updated = $this->postModel->getPostWithMeta($id);

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['data' => $updated]);
    }


    // DELETE /posts/{id}
    // Elimina un post (solo el propietario).
    public function delete(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');
        $post = $this->postModel->find($id);

        if (!$post) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        if ((int)$post['user_id'] !== (int)$userId) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['error' => 'You are not allowed to delete this post.']);
        }

        $this->postModel->delete($id);

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Post deleted successfully.']);
    }
}