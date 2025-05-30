<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;
use React\Socket\SecureServer;
use React\Socket\SocketServer;
use App\Libraries\WebSocketServer as ChatHandler;

class StartWebSocket extends BaseCommand
{
    protected $group = 'websocket';
    protected $name = 'websocket:start';
    protected $description = 'Starts the CodeIgniter WebSocket server with WSS (Secure WebSocket).';
    protected $usage = 'websocket:start';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params = [])
    {
        $port = 8081; // Atau port lain yang Anda inginkan
        $host = '0.0.0.0';
        $uri = 'tcp://' . $host . ':' . $port; // URI untuk SocketServer

        $loop = Loop::get();
        $chat = new ChatHandler();
        $wsServer = new WsServer($chat);

        // Membuat SocketServer untuk mendengarkan koneksi TCP biasa
        $tcpServer = new SocketServer($uri, [], $loop); // Memberikan URI, konteks kosong, dan loop
        $certificatePath = '/home/u1733924/ssl/certs/www_templates_walisongosragen_com_bc33e_98a23_1753550268_d8da8c32c3d6984c310b45bd42176f93.crt';
        $combinedCertificatePath = '/home/u1733924/ssl/miftah/combined.crt';
        $privateKeyPath = '/home/u1733924/ssl/keys/bc33e_98a23_aee55c81a8807a2308fe868a1b19e76b.key';

        CLI::write("Memeriksa file sertifikat: " . $certificatePath);
        if (!file_exists($certificatePath)) {
            CLI::error("File sertifikat TIDAK ditemukan.");
            return;
        } else {
            CLI::write("File sertifikat ditemukan.", 'green');
        }

        CLI::write("Memeriksa file private key: " . $privateKeyPath);
        if (!file_exists($privateKeyPath)) {
            CLI::error("File private key TIDAK ditemukan.");
            return;
        } else {
            CLI::write("File private key ditemukan.", 'green');
        }
        // Konfigurasi untuk WSS (menggunakan SecureServer yang membungkus SocketServer)

        // $certificatePath = __DIR__ . '/certs/private.crt';
        // $privateKeyPath = __DIR__ . '/certs/private.key';

        $secureServer = new SecureServer(
            $tcpServer, // Menggunakan instance SocketServer yang sudah dibuat
            $loop,
            [
                'local_cert' => $certificatePath, // GANTI DENGAN PATH SEBENARNYA
                'local_pk' => $privateKeyPath,     // GANTI DENGAN PATH SEBENARNYA
                // 'passphrase' => 'jika_private_key_memiliki_passphrase', // HAPUS JIKA TIDAK ADA
                'allow_self_signed' => false, // HANYA UNTUK PENGEMBANGAN, JANGAN GUNAKAN DI PRODUKSI
            ]
        );

        $server = new IoServer(
            $wsServer,
            $secureServer, // Sekarang menggunakan SecureServer yang benar
            $loop
        );

        CLI::write("WebSocket Server started on port {$port} with WSS (Secure WebSocket).", 'green');

        $server->run();
    }
}
