<?php

namespace App\Interfaces;

interface MessageServiceInterface
{
    public function storeMessage(string $text, string $recipient);
    public function readMessage(int $id, string $decryptionKey);
}
