<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewChatTable extends Migration
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
            'sender' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => null,
                'null'    => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('chat', true); // Tambahkan 'true' untuk if_not_exists
    }

    public function down()
    {
        $this->forge->dropTable('chat');
    }
}
