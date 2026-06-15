<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [

    'deal_id',
    'sponsor_id',
    'invoice_id',
    'invoice_title',
    'invoice_amount',
    'payment_type',
    'tax',
    'discount',
    'total_amount',
    'currency',
    'invoice_date',
    'due_date',
    'payment_status',
    'billing_address',
    'contact_email'
];
}
