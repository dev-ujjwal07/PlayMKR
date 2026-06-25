<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;
use App\Models\Deal;
use App\Models\DeliverType;
use App\Models\Team;

class Deliverable extends Model
{

public function deal()
{
    return $this->belongsTo(
        Deal::class,
        'deal_id'
    );
}

public function deliverType()
{
    return $this->belongsTo(
        DeliverType::class
    );
}

public function team()
{
    return $this->belongsTo(
        Team::class
    );
}





public function sponsor()
{
    return $this->belongsTo(
        Sponsor::class,
        'sponsor_id'
    );
}





    public function attachments()
{
    return $this->hasMany(
        Attachment::class
    );
}

protected $fillable = [

    'deal_id',
    'deliver_type_id',
    'title',
    'description',
    'quantity',
         'name',
    'team_id',
    'attachment',
    'sponsor_id',
    'assigned_to',
    'status',
    'status_updated_at',
    'distribution_date',
    'priority',
    'start_date',
    'due_date'
];
}
