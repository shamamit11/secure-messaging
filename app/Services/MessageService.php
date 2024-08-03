<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MessageService
{
    public function storeMessage(string $text, string $recipient)
    {
        try {
            $decryptionKey = bin2hex(random_bytes(16));

            $encryptedText = $this->encryptMessage($text, $decryptionKey);

            $expiryMinutes = (int) env('DEFAULT_MESSAGE_EXPIRY_MIN', 5);
            $expiresAt = now()->addMinutes($expiryMinutes);
            $expiresAtTimestamp = Carbon::instance($expiresAt)->timestamp;

            $message = Message::create([
                'text' => $encryptedText,
                'recipient' => $recipient,
                'expires_at' => $expiresAtTimestamp,
                'decryption_key' => $decryptionKey
            ]);

            $response = [
                'data' => [
                    'messageId' => $message->id,
                    'decryption_key' => $message->decryption_key,
                ],
                'errors' => false,
                'status_code' => 201
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function readMessage(int $messageId, string $decryptionKey)
    {
        try {
            $message = Message::where([['id', $messageId], ['decryption_key', $decryptionKey]])->first();

            if (!$message || $message->deleted_at || $message->expires_at < now()) {
                $res = [
                    'message' => "Message not found!",
                    'errors' => true,
                    'status_code' => 404
                ];
                return response()->json($res, 404);
            }

            $decryptedMessage = $this->decryptMessage($message->text, $decryptionKey);

            $response = [
                'data' => [
                    'id' => $message->id,
                    'message' => $decryptedMessage,
                    'recipient' => $message->recipient
                ],
                'errors' => false,
                'status_code' => 200
            ];

            $this->markAsDelete($message);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    private function encryptMessage(string $message, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($message, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode("$iv$encrypted");
    }

    private function decryptMessage(string $message, string $key): string
    {
        $data = base64_decode($message);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }

    private function markAsDelete(Message $message): void
    {
        DB::transaction(function () use ($message) {
            $message->delete();
        });
    }
}
