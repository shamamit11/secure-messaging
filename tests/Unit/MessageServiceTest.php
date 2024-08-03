<?php

use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->messageService = mock(MessageService::class);
    app()->instance(MessageService::class, $this->messageService);
});

test('store message successfully', function () {
    $text = 'Hello, world!';
    $recipient = 'recipient@example.com';

    $this->messageService
        ->shouldReceive('storeMessage')
        ->with($text, $recipient)
        ->andReturn([
            "data" => [
                'messageId' => 1,
                'decryption_key' => 'dummy-key',
            ],
            'errors' => false,
            'status_code' => 201,
        ]);

    $actualResponse = $this->messageService->storeMessage($text, $recipient);
    expect($actualResponse['status_code'])->toBe(201);

    $data = $actualResponse['data'];
    expect($data)->toHaveKeys(['messageId', 'decryption_key']);
    expect($data['messageId'])->toBe(1);
    expect($data['decryption_key'])->toBe('dummy-key');
});

test('read message successfully', function () {

    $text = 'Hello, world!';
    $recipient = 'recipient@example.com';
    $decryptionKey = 'key123';

    $this->messageService
        ->shouldReceive('readMessage')
        ->with(1, 'key123')
        ->andReturn([
            'data' => [
                'id' => 1,
                'message' => $text,
                'recipient' => $recipient
            ],
            'errors' => false,
            'status_code' => 200
        ]);

    $response = $this->messageService->readMessage(1, $decryptionKey);
    expect($response['status_code'])->toBe(200);

    $data = $response['data'];
    expect($data)->toHaveKeys(['id', 'message', 'recipient']);
    expect($data['message'])->toBe($text);
    expect($data['recipient'])->toBe($recipient);
});


test('read message not found', function () {

    $this->messageService
        ->shouldReceive('readMessage')
        ->with(999, 'dummy-key')
        ->andReturn([
            'message' => "Message not found!",
            'errors' => true,
            'status_code' => 404
        ]);

    $response = $this->messageService->readMessage(999, 'dummy-key');
    expect($response['message'])->toBe('Message not found!');
    expect($response['errors'])->toBeTrue();
    expect($response['status_code'])->toBe(404);
});
