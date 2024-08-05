<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Mail\PurchasedTicket;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketController
{
    public function purchase(Request $request, Event $event) {
        $user = $request->user()['id'];
        $eventId = $event['id'];
        $availableTicket = $event['available'];
        $message = 'Transaksi berhasil!';
        $code = 201;
        $hasTicket = $this->hasTicket([
            'event' => $eventId,
            'user' => $user,
        ]);

        if($hasTicket){
            $message = 'Tiket telah anda miliki.';
            $code = 409;
        }
        else if(!$availableTicket){
            $message = 'Event telah ditutup.';
            $code = 409;
        }
        else {
            $randomNumber = $this->randomNumber();
            $ticketCode = $this->ticketCode();
            $qrcode = $this->qrGenerator($ticketCode);
            $saveQr = $this->saveQr([
                'randomNumber' => $randomNumber,
                'event' => $eventId,
                'user' => $user,
                'qrcode' => $qrcode
            ]);

            $ticket = Ticket::create([
                "event_id" => $eventId,
                "user_id" => $user,
                "ticket_code" => $ticketCode,
                "status" => 'Dibeli',
                "qr_img" => Storage::url($saveQr),
            ]);
            Mail::to($ticket->user['email'])->queue(new PurchasedTicket($ticket));
        }

        return new EventResource($message, $code);
    }
    
    public function getDetail(Ticket $ticket) {
        $gate = Gate::inspect('view', $ticket);
        $result = null;

        if($gate->allowed()){
            $ticket->event;
            $ticket->user;
            $result = $ticket;
        } else {
            $result = $gate->allowed();
        }

        return new EventResource($gate->message(), $gate->code(), $result);
    }

    private function randomNumber(): int{
        return random_int(100000, 999999);
    }
    
    private function hasTicket($comparison): bool{
        return Ticket::where([
            ['event_id', $comparison['event']],
            ['user_id', $comparison['user']],
            ])->exists();
    }

    private function ticketCode(): string{
        do{
            $uniqueCode = Str::random(8);
        } while(Ticket::where('ticket_code', $uniqueCode)->exists());
        return $uniqueCode;
    }

    private function qrGenerator($uniqueCode) {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($uniqueCode)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(5)
            ->build();
    }

    private function saveQr($metadata) {
        Storage::makeDirectory('qrcodes');
        $filename = 'qrcodes/'.$metadata['randomNumber'].''.$metadata['event'].''.$metadata['user'].'.png';
        $metadata['qrcode']->saveToFile(storage_path('app/public/'.$filename));

        return $filename;
    }
}
