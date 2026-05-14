<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'movie_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('movie_id');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('movie_id', 'movies', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('comments', true, [
            'ENGINE'          => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE'         => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('comments', true);
    }
}