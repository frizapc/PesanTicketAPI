<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventValidator;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EventController
{
    public function create(EventValidator $request) {
        $user = $request->user();
        
        $validator = $request->validated();
        $validator['organizer_id'] = $user['id'];
    
        $event = Event::create($validator);
        
        $filename = 'pict-'.$event['id'].'.jpg';
        $request->picture->storeAs('images', $filename);  
        $event->picture = Storage::url($filename);

        $event->save();

        return new EventResource("Event berhasil dibuat", 201, $event);
    }

    public function findAll() {
        $getEvents = Event::all();
        $events = [];
        foreach ($getEvents as $value) {
            $value->organizer;
            $events[] = $value;
        }
        return new EventResource("Event ditemukan", 200, $events);
    }

    public function findOne(Event $event) {
        $event->organizer;  
        return new EventResource("Event ditemukan", 200, $event);
    }

    public function update(Request $request, Event $event) {
        $gate = Gate::inspect('update', $event);
        if ($gate->allowed()) {
            try {
            $event->title = $request->title ?? $event->getOriginal('title');
            $event->description = $request->description ?? $event->getOriginal('description');
            $event->location = $request->location ?? $event->getOriginal('location');
            $event->start_time = $request->start_time ?? $event->getOriginal('start_time');
            $event->end_time = $request->end_time ?? $event->getOriginal('end_time');
            $event->saveOrFail();

            } catch (UniqueConstraintViolationException $th) {
                $gate->__construct(false, 'Judul sudah ada', 422);
            }
        }
        return new EventResource($gate->message(), $gate->code(), $gate->allowed());
    }

    public function deleteOne(Event $event) {
        $gate = Gate::inspect('delete', $event);
        if ($gate->allowed()) {
            $event->delete();
        }
        return new EventResource($gate->message(), $gate->code(), $gate->allowed());
    }
    public function deleteAll(Request $request, Event $event) {
        $userEvents =  $request->user()->userEvents;
        $respons = count($userEvents) ?? false;
        foreach ($userEvents as $userEvent) {
            $event->where('id', $userEvent['id'])->delete();
        }
        
        return new EventResource("$respons data terhapus", 200, $respons);
    }
}
