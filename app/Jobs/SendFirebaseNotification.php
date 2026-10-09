<?php

namespace App\Jobs;

use App\Services\FirebaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendFirebaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
        public string $title,
        public string $body,
        public array $data = [],
    ) {}

    // public function handle(FirebaseService $firebase): void
    // {
    //     $firebase->send(
    //         $this->token,
    //         $this->title,
    //         $this->body,
    //         $this->data
    //     );
    // }
    public function handle(FirebaseService $firebase)
{
    try {
        $firebase->send(
            $this->token,
            $this->title,
            $this->body,
            $this->data
        );

    } catch (\Throwable $e) {

        if (
            str_contains($e->getMessage(), 'UNREGISTERED') ||
            str_contains($e->getMessage(), 'NotRegistered')
        ) {
            \Log::warning(
                'Skipping invalid FCM token: ' . $this->token
            );

            return; // Skip this notification
        }

        // Other Firebase errors should still be reported
        throw $e;
    }
}
}