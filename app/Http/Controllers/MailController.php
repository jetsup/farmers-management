<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMailer;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ];
        
        Mail::to(['mail@example.com'])->send(new ContactUsMailer($data));

        return back()->with('message', 'Your message has been sent successfully!');
    }
}
