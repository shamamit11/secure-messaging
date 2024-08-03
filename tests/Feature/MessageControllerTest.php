<?php

it('can store a message', function () {
    $response = $this->post('/api/messages', [
        'text' => 'Hello World',
        'recipient' => 'recipient@example.com',
    ]);
    $response->assertStatus(201);
});

it('can read a message', function () {
    $storeResponse = $this->post('/api/messages', [
        'text' => 'Hello World',
        'recipient' => 'recipient@example.com',
    ]);
    $storeResponse->assertStatus(201);

    $responseData = $storeResponse->json('data');
    $messageId = $responseData['messageId'];
    $decryptionKey = $responseData['decryption_key'];

    $response = $this->post('/api/messages/read', [
        'messageId' => $messageId,
        'decryptionKey' => $decryptionKey,
    ]);

    $response->assertStatus(200);
});
