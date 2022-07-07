<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotEnv->load();

$config = (new EasyHttp\WebSocketConfig())->setTimeout(10);
$server = new Server($_ENV['ACCESS_TOKEN'], $config);
$server->connect();