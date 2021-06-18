<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentTransaction extends Model{

    protected $table = 'transaction';
    // public $primaryKey = 'id';
    

    protected $fillable = [
        'paid_amount',
        'net_amount',
        'payment_ref',
        'is_active',
        'payment_no',
        'payment_track_id',
        'payment_method',
        'invoice_id',
        'type',
        'is_completed',
        'method',
    ];

    protected $hidden = [

    ];

}

