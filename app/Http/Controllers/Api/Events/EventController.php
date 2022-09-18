<?php

namespace App\Http\Controllers\Api\Events;

use App\Actions\Events\CreateEventAction;
use App\Actions\Events\UpdateEventAction;
use App\DataTransferObjects\EventData;
use App\DataTransferObjects\ResponseData;
use App\DataTransferObjects\ResponsePaginationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    public function index()
    {
        $events = Event::query()
            ->whereByUser(auth()->user())
            ->paginate()
            ->through(fn ($event) => EventData::from($event));

        return ResponsePaginationData::fromPaginator($events, $events->items());
    }

    public function show(Event $event)
    {
        return ResponseData::from(['result' => EventData::from($event)]);
    }

    public function store(EventStoreRequest $request)
    {
        $event = EventData::from(CreateEventAction::execute($request->all()));

        return ResponseData::from(['result' => $event, 'httpCode' => Response::HTTP_CREATED]);
    }

    public function update(Event $event, EventUpdateRequest $request)
    {
        $event = EventData::from(UpdateEventAction::execute($event, $request->all()));

        return ResponseData::from(['result' => $event]);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return ResponseData::from(['httpCode' => Response::HTTP_NO_CONTENT]);
    }
}
