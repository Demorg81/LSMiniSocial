<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\LikeModel;
use App\Models\CommentModel;
use App\Models\PostModel;
use CodeIgniter\HTTP\ResponseInterface;

// LS-MiniSocial-Core
class InteractionApiController extends BaseController
{
    private LikeModel $likeModel;
    private CommentModel $commentModel;
    private PostModel $postModel;

    public function __construct()
    {
        $this->likeModel = new LikeModel();
        $this->commentModel = new CommentModel();
        $this->postModel = new PostModel();
    }

    // POST /posts/{id}/like
    public function addLike(int $postId): ResponseInterface
    {
        $userId = session()->get('user_id');

        if (!$this->postModel->find($postId)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        if ($this->likeModel->hasLiked($userId, $postId)) {
            return $this->response
                ->setStatusCode(409)
                ->setJSON(['error' => 'You have already liked this post.']);
        }

        $this->likeModel->insert([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'message' => 'Post liked.',
                'like_count' => $this->likeModel->countForPost($postId),
            ]);
    }

    // DELETE /posts/{id}/like
    public function removeLike(int $postId): ResponseInterface
    {
        $userId = session()->get('user_id');

        if (!$this->postModel->find($postId)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        if (!$this->likeModel->hasLiked($userId, $postId)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'You have not liked this post.']);
        }

        $this->likeModel
            ->where('user_id', $userId)
            ->where('post_id', $postId)
            ->delete();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'message' => 'Like removed.',
                'like_count' => $this->likeModel->countForPost($postId),
            ]);
    }

    // GET /posts/{id}/comments
    public function getComments(int $postId): ResponseInterface
    {
        if (!$this->postModel->find($postId)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        $comments = $this->commentModel->getCommentsForPost($postId);

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['data' => $comments]);
    }

    // POST /posts/{id}/comments
    public function addComment(int $postId): ResponseInterface
    {
        $userId = session()->get('user_id');

        if (!$this->postModel->find($postId)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Post not found.']);
        }

        $json = $this->request->getJSON(true);
        $content = trim($json['content'] ?? $this->request->getPost('content') ?? '');

        if ($content === '') {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Content must not be empty.']);
        }

        $commentId = $this->commentModel->insert([
            'user_id' => $userId,
            'post_id' => $postId,
            'content' => $content,
        ]);

        $comments = $this->commentModel->getCommentsForPost($postId);
        $new = end($comments); // last inserted

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['data' => $new]);
    }

    // DELETE /comments/{id}
    public function deleteComment(int $commentId): ResponseInterface
    {
        $userId = session()->get('user_id');
        $comment = $this->commentModel->find($commentId);

        if (!$comment) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Comment not found.']);
        }

        if ((int)$comment['user_id'] !== (int)$userId) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['error' => 'You are not allowed to delete this comment.']);
        }

        // LS-MiniSocial-Core
        $this->_lsm_sanitize_comms($commentId);

        $this->commentModel->delete($commentId);

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Comment deleted.']);
    }


    private function _lsm_sanitize_comms(int $commentId): void
    {

    }
}
