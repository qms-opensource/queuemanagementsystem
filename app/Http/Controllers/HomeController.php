<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pageNotFound()
    {
    	return view('Error.404');
    }

    public function badMethod()
    {
    	return view('Error.bad_method');
    }
	
	public function methodNotAllowed()
    {
    	return view('Error.method_not_allowed');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
	
	public function myTest($title,$body,$message,$registrationIds){
		$registrationIds = "eZl3u955TJw:APA91bH6Z6ykmwKrJHW6ed2LZ_8saNDn3m3uVUQwZIbQAg86kdbBTyrcHSNJEpHgTno2CcR9ioMZLRMMPLzj53BpDZkIeM5LgkikaOq4F66tpKlqgefO2UvhkdV7QKF0VY2AurGE_CSK";
		#prep the bundle
		 $msg = array
			  (
			'body' 	=> $body,
			'title'	=> $title,
					'icon'	=> 'myicon',/*Default Icon*/
					'sound' => 'mySound'/*Default sound*/
			  );
		$fields = array
				(
					'to'		=> $registrationIds,
					'notification'	=> $msg
				);
		
		
		$headers = array
				(
					'Authorization: key=' . Config::get('settings.API_ACCESS_KEY'),
					'Content-Type: application/json'
				);
	#Send Reponse To FireBase Server	
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
		#Echo Result Of FireBase Server
		return true;
	}
}
