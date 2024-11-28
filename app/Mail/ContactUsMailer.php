<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMailer extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $message;
    public string $mail_subject;

    /**
     * Create a new message instance.
     */
    function __construct($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->message = $data['message'];
        $this->mail_subject = $data['subject'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            replyTo: [new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))],
            subject: $this->mail_subject,
            // to: for broadcasting to multiple recipients
            // to: [new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')), new Address($this->email, $this->name)],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-us',
            with: [
                'sender_name' => $this->name,
                'sender_email' => $this->email,
                'sender_message' => $this->message,
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
