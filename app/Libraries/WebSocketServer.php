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
            if ($sender === 'King') {
                $notification = ['type' => 'king_found'];
                foreach ($this->clients as $client) {
                    $client->send(json_encode($notification));
                }
            } else {
                // Kirim pesan chat biasa ke semua klien lain (jika ini juga merupakan fungsi chat)
                $response = [
                    'sender' => $sender,
                    'message' => $message
                ];
                foreach ($this->clients as $client) {
                    $client->send(json_encode($response));
                }
            }
        }
    }

    // Jika ada handler lain yang menyimpan data (misalnya dari HTTP):
    public function handleDataFromOtherSource($sender, $message)
    {
        $dataToSave = [
            'sender'  => $sender,
            'message' => $message,
        ];

        if ($this->chatModel->save($dataToSave)) {
            log_message('info', 'Data baru disimpan (dari sumber lain). Sender: ' . $sender);
            if ($sender === 'King') {
                $notification = ['type' => 'king_found'];
                foreach ($this->clients as $client) {
                    $client->send(json_encode($notification));
                }
            }
        } else {
            log_message('error', 'Gagal menyimpan data (dari sumber lain). Error: ' . json_encode($this->chatModel->errors()));
        }
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
