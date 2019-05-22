<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio;
class MessageController extends Controller
{
   public function index(){
	    return view('users');
   }
   
   public function sendMessage(Request $request){
	   
	   Twilio::message("+919878434308",$request->input('sendmessage'));
	   die();
   }
}
