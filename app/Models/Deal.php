<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [

        'sponsor_id',

        'deal_title',

        'deal_description',

        'status',

        'deal_type_id'
    ];
}