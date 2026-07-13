<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php'; // Hoặc include thủ công các file của Ratchet nếu không dùng Composer

class PublicServiceServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Phát dữ liệu nhận được từ client (User) đến tất cả các client khác (Admin Dashboard)
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

echo "Starting WebSocket server...\n";

$app = new Ratchet\App('localhost', 8080);
$app->route('/ws', new PublicServiceServer(), ['*']);

echo "Server started on ws://localhost:8080/ws\n";

$app->run();

echo "Server stopped\n";