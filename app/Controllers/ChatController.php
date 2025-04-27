<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ChatModel;

class ChatController extends Controller
{

    public function index()
    {
        return view('chat', ['Judul' => "Chat"]);
    }
    public function king()
    {
        return view('king', ['Judul' => "WAITING FOR KING"]);
    }
    public function sendMessage()
    {
        $chatModel = new ChatModel();
        $data = [
            'sender' => $this->request->getPost('sender'),
            'message' => $this->request->getPost('message'),
        ];
        $chatModel->insert($data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function getMessages()
    {
        $chatModel = new ChatModel();
        $messages = $chatModel->orderBy('created_at', 'ASC')->findAll();
        return $this->response->setJSON($messages);
    }
}
