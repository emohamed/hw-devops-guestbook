<?php
require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', true);

$mongoHost = isset($_ENV['mongo-host']) ? $_ENV['mongo-host'] : 'localhost';
$client = new MongoDB\Client("mongodb://{$mongoHost}:27017");
$guestbook = $client->guestbook->entries;

if (isset($_GET['cmd']) === true) {
    header('Content-Type: application/json');
    if ($_GET['cmd'] == 'set') {
        $input = json_decode(file_get_contents('php://input'), true);
        $guestbook->insertOne([
            "message" => $input['message'],
            "createdAt" => date(DATE_RFC822),
        ]);
        print('{"message": "Updated"}');
    } else {
        $entries = $guestbook->find()->toArray();
        echo json_encode(["data" => $entries]);
    }
} else {
    phpinfo();
} ?>
