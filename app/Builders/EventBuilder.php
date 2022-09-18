<?php

namespace App\Builders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class EventBuilder extends Builder
{
    public function whereOverlaps($start, $end): self
    {
        $this->where(function ($query) use ($start, $end) {
            $query->where(function ($query) use ($start, $end) {
                $query->where('start', '>=', $start);
                $query->where('start', '<=', $end);
            });

            $query->orWhere(function ($query) use ($start, $end) {
                $query->where('end', '>=', $start);
                $query->where('end', '<=', $end);
            });

            $query->orWhere(function ($query) use ($start, $end) {
                $query->where('start', '<=', $start);
                $query->where('end', '>=', $end);
            });

            $query->orWhere(function ($query) use ($start, $end) {
                $query->where('start', '>=', $start);
                $query->where('end', '<=', $end);
            });
        });

        return $this;
    }

    public function whereByUser(User $user)
    {
        $this->where('user_id', $user->id);

        return $this;
    }
}
