<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class WebOrder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'web_orders';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    //public $timestamps = false;    

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    //protected $dateFormat = 'U';    

    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    //protected $connection = 'connection-name';        

    //use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];    

    public function orderItems()
    {
        return $this->hasMany('App\Models\WebOrderItem','order_id');
    }

    public function webuser()
    {
        return $this->belongsTo('App\Models\WebUser','web_user_id');
    }

    public function shipping_method()
    {
        return $this->belongsTo('App\Models\ShippingMethod','web_shipping_id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Models\PaymentMethod','web_payment_id');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address','web_address_id');
    }  

    public function outlet(){
        return $this->belongsTo('App\Models\Outlet');
    }  
}
