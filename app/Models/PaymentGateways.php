<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PaymentGateways;

class PaymentGateways extends Model
{  

    protected $table = 'web_payment_methods';

}