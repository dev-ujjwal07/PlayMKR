<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [

        'ticket_id',

        'sponsor_id',

        'team_id',

        'title',

        'description',

        'attachment',

        'status'
    ];

    public function ticket()
    {
        return $this->belongsTo(
            Ticket::class
        );
    }

    public function sponsor()
    {
        return $this->belongsTo(
            Sponsor::class
        );
    }

    public function team()
    {
        return $this->belongsTo(
            Team::class
        );
    }
}