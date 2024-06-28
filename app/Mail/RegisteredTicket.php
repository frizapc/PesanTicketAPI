<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisteredTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private Ticket $ticket)
    {
        $this->reciever = $ticket->user['name'];
        $this->email = $ticket->user['email'];
        $this->eventTitle = $ticket->event['title']; 
        $this->eventLocation = $ticket->event['location']; 
        $this->start_time = $ticket->event['start_time'];
        $this->end_time = $ticket->event['end_time'];
        $this->qr_img = $ticket['qr_img'];
    }

    private $reciever = null;
    private $email = null;
    private $eventTitle = null;
    private $eventLocation = null;
    private $start_time = null;
    private $end_time = null;
    private $qr_img = null;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembelian Tiket Berhasil',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registered',
            with: [
                'reciever'=>$this->reciever,
                'email'=>$this->email,
                'eventTitle'=>$this->eventTitle,
                'eventLocation'=>$this->eventLocation,
                'start_time'=>$this->start_time,
                'end_time'=>$this->end_time,
                'qr_img'=>$this->qr_img,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
