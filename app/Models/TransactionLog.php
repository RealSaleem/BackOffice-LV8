<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class TransactionLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'transaction_attempt_log';
   
}
