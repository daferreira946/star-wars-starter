<?php

namespace App\Listeners;

use App\Events\DetailGetPerformed;
use App\Models\DetailGet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveDetailGet implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(DetailGetPerformed $event): void
    {
        DetailGet::create(
            [
                'type' => $event->type,
                'item_id' => $event->itemId,
            ]
        );
    }
}
