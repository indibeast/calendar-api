<?php

namespace App\Actions\Events;

use App\Models\Event;

class UpdateEventAction
{
    public static function execute(Event $event, array $input): Event
    {
        $event->update([
            'title' => $input['title'],
            'start' => $input['start'],
            'end' => $input['end'],
        ]);

        return $event->fresh();
    }
}
