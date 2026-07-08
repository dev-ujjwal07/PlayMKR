<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [

        'ticket_id',
        'deal_id',
        'team_id',
        'sponsor_id',
         'admin_id',
        'number_of_tickets',
        'internal_team_description',
        'name',
        'ticket_status',
        'priority',
        'start_date',
        'attachment',
        'status'
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(
            Report::class
        );
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(
            Deal::class,
            'deal_id'
        );
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(
            Team::class,
            'team_id'
        );
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(
            Sponsor::class,
            'sponsor_id'
        );
    }

    public function admin()
{
    return $this->belongsTo(
        User::class,
        'admin_id'
    );
}


}
