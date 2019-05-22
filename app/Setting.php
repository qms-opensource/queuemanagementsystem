<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
       'display_name','mail_driver','mail_host','mail_port','mail_username','mail_password','mail_encryption','logo_path'
    ];

}
