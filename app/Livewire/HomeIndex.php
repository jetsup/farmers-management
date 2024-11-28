<?php

namespace App\Livewire;

use App\Http\Controllers\MailController;
use App\Models\EmailMessage;
use Livewire\Component;

class HomeIndex extends Component
{
    public string $sender_name = '';
    public string $sender_email = '';
    public string $sender_message = '';
    public int $number = 90;
    public function render()
    {
        return view('livewire.home-index')->layout('layouts.guest');
    }

    public function sendMessage()
    {
        $this->validate([
            'sender_name' => 'required',
            'sender_email' => 'required|email',
            'sender_message' => 'required',
        ]);

        EmailMessage::create([
            'sender_name' => $this->sender_name,
            'sender_email' => $this->sender_email,
            'message' => $this->sender_message,
        ]);

        $email_subject = "Thank You for Contacting Us";

        (new MailController())->sendEmailMessage(
            $this->sender_email,
            $this->sender_name,
            $email_subject,
            $this->sender_message
        );

        $this->sender_name = '';
        $this->sender_email = '';
        $this->sender_message = '';

        session()->flash('message', 'Message sent successfully.');
    }
}
