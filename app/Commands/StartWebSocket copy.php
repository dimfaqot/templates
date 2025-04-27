<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Libraries\WebSocketServer;

class StartWebSocket extends BaseCommand
{
    protected $group = 'WebSocket';
    protected $name = 'websocket:start';
    protected $description = 'Start WebSocket Server';

    public function run(array $params)
    {
        CLI::write("Starting WebSocket Server on port 8080...", 'green');

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer()
                )
            ),
            8080,
            '0.0.0.0' // Ganti kembali ke '0.0.0.0'
        );
        $server->run();
    }
}
