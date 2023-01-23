<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmartPhone extends Model
{
    //Fillables
    protected $fillable = [

    	'userId', 'title', 'info', 'username', 'password', 'connectionStatus'

    ];
}
