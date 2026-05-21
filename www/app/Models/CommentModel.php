<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table         = 'comments';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'post_id', 'content'];
    protected $useTimestamps = true;
    protected $updatedField  = '';

    /**
     * Devuelve los comentarios de un post con datos del autor
     * e indicador de si el usuario actual es el propietario.
     */
    public function getCommentsForPost(int $postId, int $currentUserId = 0): array
    {
        return $this->db->query(
            'SELECT c.id,
                    c.content,
                    c.created_at,
                    c.user_id,
                    u.username,
                    u.profile_pic,
                    IF(c.user_id = ?, 1, 0) AS is_owner
             FROM comments c
             JOIN users u ON u.id = c.user_id
             WHERE c.post_id = ?
             ORDER BY c.created_at ASC',
            [$currentUserId, $postId]
        )->getResultArray();
    }
}