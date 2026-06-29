<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
public function reports()
{
    return $this->hasMany(
        Report::class
    );
}

    protected $fillable = [

        'name',
        'email',
        'password',
        'contact_number',
        'website_url',
        'industry',
        'address',
        'status'

    ];

    protected $hidden = [

        'password'

    ];
}