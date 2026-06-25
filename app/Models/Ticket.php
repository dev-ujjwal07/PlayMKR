<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [

        'deal_id',
        'team_id',
        'sponsor_id',
        'name',
        'priority',
        'start_date',
        'attachment',
         'status'
    ];
}
