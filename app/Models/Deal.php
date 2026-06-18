<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Deliverable;
use App\Models\Milestone;
use App\Models\Invoice;

class Deal extends Model
{
public function deliverables()
{
    return $this->hasMany(
        Deliverable::class
    );
}

public function milestones()
{
    return $this->hasMany(
        Milestone::class
    );
}

public function invoices()
{
    return $this->hasMany(
        Invoice::class
    );
}

    protected $fillable = [

        'sponsor_id',

        'deal_title',

        'deal_description',
        'total_deal_value',

        'status',

        'deal_type_id'
    ];
}