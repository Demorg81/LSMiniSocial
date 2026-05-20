<?php

namespace App\Models;

use CodeIgniter\Model;

class LikeModel extends Model
{
    protected $table = 'likes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'post_id'];
    protected $useTimestamps = true;
    protected $updatedField = '';

    public function hasLiked(int $userId, int $postId): bool
    {
        $row = $this->db->query(
            'SELECT COUNT(*) AS total FROM likes WHERE user_id = ? AND post_id = ?',
            [$userId, $postId]
        )->getRow();

        return $row->total > 0;
    }

    public function countForPost(int $postId): int
    {
        $row = $this->db->query(
            'SELECT COUNT(*) AS total FROM likes WHERE post_id = ?',
            [$postId]
        )->getRow();

        return (int)$row->total;
    }
}
