<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
     protected $guarded = ['id'];


    protected $casts = [
        'read' => 'boolean',
    ];

   
}
