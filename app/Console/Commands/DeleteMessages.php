<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;

class DeleteMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:delete-expired';
    protected $description = 'Delete expired messages from the messages table';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $deleted = Message::where('expires_at', '<', $now)->delete();
        $this->info("Deleted $deleted expired messages.");
    }
}
