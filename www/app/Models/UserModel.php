<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['email', 'password', 'created_at', 'updated_at'];

    protected $useTimestamps = true;

    public function findByEmail(string $email): ?array
    {
        $query = $this->db->query(
            'SELECT * FROM users WHERE email = ?',
                [$email]
        );
        return $query->getRowArray() ?: null;
    }

    public function emailExists(string $email): bool
    {
        $query = $this->db->query(
            'SELECT COUNT(*) as total FROM users WHERE email = ?',
                [$email]
        );
        $row = $query->getRow();
        return $row->total > 0;
    }
}
