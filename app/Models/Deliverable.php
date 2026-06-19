<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;

class Deliverable extends Model
{
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
