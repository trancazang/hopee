<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AdviceRequest;

class AdviceScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
   
    public $request;

    public function __construct(AdviceRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Yêu cầu tư vấn đã được tiếp nhận')
                    ->markdown('emails.advice.scheduled')
                    ->with([
                        'user' => $this->request->user,
                        'scheduled_at' => $this->request->scheduled_at,
                        'meeting_link' => $this->request->meeting_link,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Advice Scheduled Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.advice.scheduled',
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
