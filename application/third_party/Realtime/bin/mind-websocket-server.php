<?php

require __DIR__ . "/../vendor/autoload.php";

use Chat\Chat;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$port = isset($argv[1]) ? $argv[1] : 2000;

$server = IoServer::factory(new HttpServer(new WsServer(new Chat)), $port);

$server->run();

