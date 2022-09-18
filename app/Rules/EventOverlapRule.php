<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class EventOverlapRule implements DataAwareRule, Rule
{
    protected $data;

    public function __construct(protected User $user, protected ?int $eventId = null)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! Arr::has($this->data, 'start') || ! Arr::has($this->data, 'end')) {
            return false;
        }

        return $this->user->events()
            ->when($this->eventId, fn ($query) => $query->where('id', '!=', $this->eventId))
            ->whereOverlaps($this->data['start'], $this->data['end'])
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Event is overlapping with another';
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
