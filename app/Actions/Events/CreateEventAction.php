<?php

namespace App\Actions\Events;

use App\Models\Event;

class CreateEventAction
{
    public static function execute(array $input)
    {
        return Event::create([
            'title' => $input['title'],
            'start' => $input['start'],
            'end' => $input['end'],
            'user_id' => auth()->user()->id,
        ]);
    }
}
