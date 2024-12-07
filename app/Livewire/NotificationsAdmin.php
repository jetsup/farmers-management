<?php

namespace App\Livewire;

use App\Http\Controllers\EmailMessageController;
use DateTime;
use Livewire\Component;

class NotificationsAdmin extends Component
{
    public function render()
    {
        $messages = (new EmailMessageController)->getMessages();
        foreach ($messages as &$message) {
            $message->sent_date = $this->formatTimeDifference($message->created_at);
        }
        // dd($messages);
        return view('livewire.notifications-admin', [
            'messages' => $messages
        ])->layout('layouts.admin')->title('Notifications');
    }

    private function formatTimeDifference($datetimeStr)
    {
        $datetimeObj = new DateTime($datetimeStr);
        $now = new DateTime();
        $diff = $now->diff($datetimeObj);

        if ($diff->days > 1) {
            return $diff->days . ' days ago';
        } elseif ($diff->days == 1) {
            return 'Yesterday';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        } elseif ($diff->s > 0) {
            return $diff->s . ' second' . ($diff->s > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }
}
