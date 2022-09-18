<?php

namespace App\Http\Requests;

use App\Models\Event;
use App\Rules\EventOverlapRule;

class EventUpdateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $event = $this->route('event');

        return $event && $this->user()->can('update', $event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $event = $this->route('event');

        if ($event instanceof Event) {
            $event = $event->id;
        }

        return [
            'title' => ['required'],
            'start' => ['required', 'date', 'date_format:Y-m-d\TH:i:sP'],
            'end' => ['required', 'date', 'date_format:Y-m-d\TH:i:sP', 'after:start', new EventOverlapRule(auth()->user(), $event)],
        ];
    }
}
