<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_number',
        'website_url',
        'industry',
        'address'
    ];



    protected $hidden = [

    'password'

];
}