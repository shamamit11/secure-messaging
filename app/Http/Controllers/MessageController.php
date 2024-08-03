<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageReadRequest;
use App\Http\Requests\MessageStoreRequest;
use App\Services\MessageService;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(MessageStoreRequest $request)
    {
        $validated = $request->validated();

        $message = $this->messageService->storeMessage(
            $validated['text'],
            $validated['recipient']
        );

        return $message;
    }

    public function read(MessageReadRequest $request)
    {
        $validated = $request->validated();

        $message = $this->messageService->readMessage(
            $validated['messageId'],
            $validated['decryptionKey']
        );

        return $message;
    }
}
