<?php

namespace App\Http\Controllers;

use App\Models\EmailMessage;
use Illuminate\Http\Request;

class EmailMessageController extends Controller
{
    function getMessages(){
        return EmailMessage::all();
    }
}
