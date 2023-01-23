<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomingSMS extends Model
{
    //Fillables
    protected $fillable = [

    	'userId', 'smartPhoneId', 'phoneNumber', 'messageContent'

    ];
}
