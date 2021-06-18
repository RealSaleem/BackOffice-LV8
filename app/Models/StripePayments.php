<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripePayments extends Model{
    protected $table = 'subscription_payments';

   // public function stripePayment(){
   //      return $this->belongsTo('App\Models\Modules');
   //  }   
   //  public function subscription(){
   //      return $this->belongsTo('App\Models\Subscription');
   //  }
    
}