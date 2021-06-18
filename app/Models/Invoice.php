<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model{

    protected $table = 'invoices';


    public function order(){
        return $this->belongsTo('App\Models\SyncParkedSales','park_order_id');
    }

    // public function product(){
    //     return $this->belongsTo('App\Models\Product');
    // }
}