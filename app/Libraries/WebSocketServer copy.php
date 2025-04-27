<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Config\Services; // Untuk mendapatkan instance model

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $chatModel; // Instance model Chat

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->chatModel = model('App\Models\ChatModel'); // Mendapatkan instance model
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";

        $messages = $this->chatModel->findAll();

        foreach ($messages as $message) {
            $response = [
                'sender' => $message['sender'],
                'message' => $message['message']
            ];
            $conn->send(json_encode($response)); // Pastikan dikirim sebagai JSON
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $dataReceived = json_decode($msg, true);
        $sender = isset($dataReceived['sender']) ? trim($dataReceived['sender']) : 'Anonymous';
        $message = isset($dataReceived['message']) ? trim($dataReceived['message']) : '';

        $dataToSave = [
            'sender'  => $sender,
            'message' => $message,
        ];


        if ($this->chatModel->save($dataToSave)) {
            $response = [
                'sender' => $sender,
                'message' => $message
            ];
            foreach ($this->clients as $client) {
                $client->send(json_encode($response)); // Pastikan dikirim sebagai JSON
            }
        }
        // ...
    }
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
