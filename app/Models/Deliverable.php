<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
protected $fillable = [

    'deal_id',
    'deliver_type_id',
    'title',
    'description',
    'quantity',

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
