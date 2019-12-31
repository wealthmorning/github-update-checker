<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;


    $token = 'Your Sendgrid Api Key';
    $listId = 'Your Sendgrid List ID';
   

$headers = [
    'Authorization' => 'Bearer '. $token,
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
];

$email = $_POST['email'] ?? $_GET['email'] ?? null;

$data = [
    "email" => $email,
];
$data_string = json_encode($data);

$client = new Client();

$response = $client->post('https://api.sendgrid.com/v3/contactdb/recipients', [
    'headers' => $headers,
    'json' => [
        $data
    ],
]);

if ($response->getBody()) {
    $result = json_decode($response->getBody());
} else {
    echo(json_encode([
        'message' => "Request failed",
        'code' => 500,
    ]));
    die;
}

$id = $result->persisted_recipients[0] ?? null;

if ($id) {

    $response = $client->delete("https://api.sendgrid.com/v3/contactdb/lists/$listId/recipients/$id", [
        'headers' => $headers,
        'json' => [
            $data
        ],
    ]);
  
    if ($response->getBody()) {
        $result = json_decode($response->getBody());
    } else {
        echo(json_encode([
            'message' => "Failed to Delete Contact From The List",
            'code' => 500,
        ]));
        die;
    }

}

echo(json_encode([
    'message' => "Successfully Deleted Contact From The List",
    'code' => 200,
]));
die;
