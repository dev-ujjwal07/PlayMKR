<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [

        'team_id',

        'deliverable_id',

        'name',

        'email',

        'password'
    ];
}