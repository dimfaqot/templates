<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['sender', 'message'];
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Jika tidak ada kolom updated_at
}
