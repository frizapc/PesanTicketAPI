<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishedEventPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Event $event)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Ticket $ticket): void
    {
        $ticket->where([
                ['event_id', $this->event['id']],
                ['status', 'Check-In'],
        ])->update(['status' => 'Selesai']);
    }
}
