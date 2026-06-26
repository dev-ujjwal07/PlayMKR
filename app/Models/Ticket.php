<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id',
        'deal_id',
        'team_id',
        'sponsor_id',
        'number_of_tickets',
        'internal_team_description',
        'name',
        'priority',
        'start_date',
        'attachment',
         'status'
    ];
}
