<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'post_id', 'content'];
    protected $useTimestamps = true;
    protected $updatedField = '';

    public function getCommentsForPost(int $postId): array
    {
        return $this->db->query(
            'SELECT c.id,
                    c.content,
                    c.created_at,
                    c.user_id,
                    u.username,
                    u.profile_pic
             FROM comments c
             JOIN users u ON u.id = c.user_id
             WHERE c.post_id = ?
             ORDER BY c.created_at ASC',
            [$postId]
        )->getResultArray();
    }
}
