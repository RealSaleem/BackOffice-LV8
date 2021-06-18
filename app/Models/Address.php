<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */    
    protected $table = 'web_address';

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

    public function webuser()
    {
        return $this->belongsTo('App\Models\WebUser');
    }

    public function get()
    {
        $address = '';

        $this->house_detail = trim($this->house_detail);       
        $this->office_detail = trim($this->office_detail);       

        if(isset($this->house_detail) && strlen($this->house_detail) >= 1){
            $address = 'House: ' .$this->house_detail. '<br />';
        }else if(isset($this->office_detail) && strlen($this->office_detail) >= 1){
            $address = 'Apartment: ' .$this->office_detail. '<br />';
        }

        $this->office_detail = trim($this->office_detail);       

        if(isset($this->office_detail) && strlen($this->office_detail) >= 1){
            $address .= 'Street: ' .$this->office_detail. '<br />';
        }

        $this->block = trim($this->block);       

        if(isset($this->block) && strlen($this->block) >= 1){
            $address .= 'Block: ' .$this->block. '<br />';

        } 

        $this->area = trim($this->area);       
       
        if(isset($this->area) && strlen($this->area) >= 1){
            $address .= 'Area: '. $this->area. '<br />';
        }

        $address .= $this->city . ', '. $this->country;

        return $address;
    }    
}
