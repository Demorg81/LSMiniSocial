<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table         = 'posts';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'content', 'image'];
    protected $useTimestamps = true;

    /**
     * Devuelve todos los posts ordenados por fecha descendente,
     * junto con los datos del autor, contadores de likes/comentarios
     * y si el usuario actual ya ha dado like.
     */
    public function getFeedPosts(int $currentUserId = 0): array
    {
        $sql = 'SELECT p.id,
                       p.content,
                       p.image,
                       p.created_at,
                       p.user_id,
                       u.username,
                       u.profile_pic,
                       (SELECT COUNT(*) FROM likes l    WHERE l.post_id    = p.id)              AS like_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.post_id    = p.id)              AS comment_count,
                       (SELECT COUNT(*) FROM likes l2   WHERE l2.post_id   = p.id
                                                          AND l2.user_id   = ?)                 AS user_has_liked
                FROM posts p
                JOIN users u ON u.id = p.user_id
                ORDER BY p.created_at DESC';

        return $this->db->query($sql, [$currentUserId])->getResultArray();
    }

    /**
     * Devuelve un post concreto con datos de autor y contadores.
     */
    public function getPostWithMeta(int $id): ?array
    {
        $sql = 'SELECT p.id,
                       p.content,
                       p.image,
                       p.created_at,
                       p.updated_at,
                       p.user_id,
                       u.username,
                       u.profile_pic,
                       (SELECT COUNT(*) FROM likes l    WHERE l.post_id  = p.id) AS like_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.post_id  = p.id) AS comment_count
                FROM posts p
                JOIN users u ON u.id = p.user_id
                WHERE p.id = ?';

        return $this->db->query($sql, [$id])->getRowArray() ?: null;
    }

    /**
     * Devuelve todos los posts de un usuario concreto.
     */
    public function getPostsByUser(int $userId): array
    {
        $sql = 'SELECT p.id,
                       p.content,
                       p.image,
                       p.created_at,
                       (SELECT COUNT(*) FROM likes l    WHERE l.post_id  = p.id) AS like_count,
                       (SELECT COUNT(*) FROM comments c WHERE c.post_id  = p.id) AS comment_count
                FROM posts p
                WHERE p.user_id = ?
                ORDER BY p.created_at DESC';

        return $this->db->query($sql, [$userId])->getResultArray();
    }
}