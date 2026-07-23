<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $contactSubmission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('New Contact Message: :subject', ['subject' => $this->contactSubmission->subject]),
            replyTo: [
                new Address($this->contactSubmission->email, $this->contactSubmission->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-submission-received',
        );
    }
}
