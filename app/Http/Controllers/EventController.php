<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventValidator;
use App\Http\Resources\EventResource;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Ticket;
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
        
        $filename = 'pictures/pict-'.$event['id'].'.jpg';
        $request->picture->storeAs('', $filename);  
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

    public function attendeeRegister(Request $request, Event $event) {
        $user = $request->user()['id'];
        $event = $event['id'];
        
        $registerGuard = $this->registerGuard($event, $user);
        return new EventResource($registerGuard['message'], $registerGuard['code']);
    }

    public function organizerCheckIn(Request $request, Event $event) {
        $gate = Gate::inspect('view', $event);
        if($gate->allowed()){
            $attendees = Attendee::where('event_id', $event['id']);
            $attendees->update(['check_in_time' => now('Asia/Jakarta')]);
            $tickets = Ticket::where('event_id', $event['id']);
            $tickets->update(['status' => 'Check-In']);
        }
        return new EventResource($gate->message(), $gate->code(), $gate->allowed());
    }

    private function registerGuard ($event, $user){
        $result = ['message'=>'Tiket berhasil diregistrasikan', 'code'=>201];

        $userTicket = Ticket::where([
            ['event_id', $event],
            ['user_id', $user],
        ]);
        $registeredUser = Attendee::where([
            ['event_id', $event],
            ['user_id', $user],
        ]);

        if(!$userTicket->exists()){
            $result['message'] = "Tiket tidak ada";
            $result['code'] = 409;
            return $result;
        }

        if($registeredUser->exists()){
            $result['message'] = "Anda sudah terdaftar";
            $result['code'] = 409;
            return $result;
        }

        Attendee::create([
                'event_id' => $event,
                'user_id' => $user,
        ]);
        $userTicket = $userTicket->first();
        $userTicket->status = 'Teregistrasi';
        $userTicket->save();
        return $result;
    }
}