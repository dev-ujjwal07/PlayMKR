<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
public function deliverable()
{
    return $this->belongsTo(
        \App\Models\Deliverable::class
    );
}

public function reports()
{
    return $this->hasMany(
        Report::class
    );
}


    protected $fillable = [

        'team_id',

        'deliverable_id',

        'name',

        'email',

        'password'
    ];
}