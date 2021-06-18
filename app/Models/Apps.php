<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\UserApps;
use Auth;


class Apps extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app';

    public function StoreApps(){
        return $this->hasMany(StoreApp::class,'app_id');
    }
    public function purchased_apps(){
        return $this->hasMany(UserApps::class, 'app_id', 'id')->where('user_id', Auth::user()->id);
    }

}
