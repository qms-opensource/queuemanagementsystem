<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoctorEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$password,$email,$message,$login_link)
    {
		$this->name =$name;
		$this->password =$password;
		$this->email =$email;
		$this->message =$message;
		$this->login_link =$login_link;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$userdata = array('name'=>$this->name,'password'=>$this->password,'email'=>$this->email,'message'=>$this->message,'login_link'=>$this->login_link);
		$emailData['subject']="welcome Email";
        //return $this->markdown('emails.doctors.mailtemplate');
		return $this->from($userdata['email'])->subject($emailData['subject'])->markdown('emails.doctors.mailtemplate')->with('htmldata',$userdata);
    }
}
