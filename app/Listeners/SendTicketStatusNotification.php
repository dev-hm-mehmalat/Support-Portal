<?php

namespace App\Listeners;

use App\Events\TicketStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTicketStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketStatusUpdated $event): void
    {
        //
    }
}
