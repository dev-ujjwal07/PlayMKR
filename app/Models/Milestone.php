<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [

        'deal_id',

        'name',

        'due_date',

        'status'
    ];
}