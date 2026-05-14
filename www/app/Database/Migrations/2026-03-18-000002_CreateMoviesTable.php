<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMoviesTable extends Migration
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
            'api_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'year' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'poster' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'introduction' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('api_id', 'movies_api_id_unique');

        $this->forge->createTable('movies', true, [
            'ENGINE'          => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE'         => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('movies', true);
    }
}