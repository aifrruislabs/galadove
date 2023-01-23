<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingSMS extends Model
{
    //Fillables
    protected $fillable = [

    	'userId', 'smartPhoneId', 'phoneNumber', 'messageContent',

    	'sentStatus', 'dlrReport'

    ];
}
