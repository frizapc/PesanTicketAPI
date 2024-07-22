<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventValidator;
use App\Http\Resources\EventResource;
use App\Jobs\FinishedEventPodcast;
use App\Mail\RegisteredTicket;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
                
        return new EventResource("Event berhasil dibuat", 201);
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
            if ($request->hasFile('picture')) {
                $filename = 'pictures/pict-'.$event['id'].'.jpg';
                $request->picture->storeAs('', $filename);  
                $event->picture = Storage::url($filename);

                $event->save();
            }
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
            Storage::delete('pictures/pict-'.$event['id'].'.jpg');
            $event->delete();
            
            $tickets = Ticket::where('event_id', $event['id'])->get();
            foreach ($tickets as $ticket) {
                $qr_name = Str::after($ticket['qr_img'], 'qrcodes/');
                Storage::delete('qrcodes/'.$qr_name);
            }
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
        $user = $request->user();
        
        $registerGuard = $this->registerGuard($event, $user);
        return new EventResource($registerGuard['message'], $registerGuard['code']);
    }

    public function organizerCheckIn(Event $event, Ticket $ticket) {
        $gate = Gate::inspect('view', $event);
        if($gate->allowed()){
            $attendees = Attendee::where('event_id', $event['id']);
            $attendees->update(['check_in_time' => now('Asia/Jakarta')]);
            $tickets = $ticket->where([
                ['event_id', $event['id']],
                ['status', 'Teregistrasi'],
            ]);
            $event->update(['available' => false]);
            $tickets->update(['status' => 'Check-In']);
            $time = Carbon::parse('2024-06-24 21:13:00');
            FinishedEventPodcast::dispatch($event)->delay($time);
        }
        return new EventResource($gate->message(), $gate->code(), $gate->allowed());
    }

    private function registerGuard ($event, $user){
        $result = ['message'=>'Tiket berhasil diregistrasikan', 'code'=>201];

        $userTicket = Ticket::where([
            ['event_id', $event['id']],
            ['user_id', $user['id']],
            ['status', 'Dibeli'],
        ]);

        if(!$event['available']){
            $result['message'] = "Maaf, anda terlambat registrasi pada event ini";
            $result['code'] = 403;
            return $result;
        }

        if(!$userTicket->exists()){
            $result['message'] = "Anda sudah terdaftar";
            $result['code'] = 409;
            return $result;
        }

        Attendee::create([
                'event_id' => $event['id'],
                'user_id' => $user['id'],
        ]);
        $userTicket = $userTicket->first();
        $userTicket->status = 'Teregistrasi';
        $userTicket->save();
        Mail::to($user['email'])->queue(new RegisteredTicket($userTicket));
        return $result;
    }
}