<?php

namespace App\Http\Requests;

use App\Rules\EventOverlapRule;

class EventStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'start' => ['required', 'date', 'date_format:Y-m-d\TH:i:sP'],
            'end' => ['required', 'date', 'date_format:Y-m-d\TH:i:sP', 'after:start', new EventOverlapRule(auth()->user())],
        ];
    }
}
