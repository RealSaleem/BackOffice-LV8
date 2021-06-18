<?php

namespace App\Models;

//use Silber\Bouncer\Database\Concerns\HasRoles;
//use Silber\Bouncer\Database\HasRolesAndAbilities;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PasswordReset;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Models\Role;
use Auth;

class User extends Authenticatable
{
    use HasRoles;
//    use Notifiable;
    use HasApiTokens;

//	 use HasRolesAndAbilities;

    protected $table = 'users';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function delete(array $options = [])
    {
        $this->deleted_by = Auth::user()->id;
        $this->deleted_at = time();
        return $this->save();
    }

    public function save(array $options = [])
    {
        if ($this->created_at == null) {
            $this->created = Auth::user()->id;
        } else {
            $this->updated_by = Auth::user()->id;
        }
        return parent::save();
    }



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','email_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function order()
	{
		return $this->hasMany('App\Models\Order');
	}

	public function cashflow()
	{
		return $this->hasMany('App\Models\CashFlow');
	}

	public function store(){
		return $this->belongsTo('App\Models\Store');
	}

	public function outlets()
	{
		return $this->belongsToMany('App\Models\Outlet');
	}

	public function usr_outlets()
	{
		return $this->hasMany('App\Models\UserOutlets')->join('outlets', 'outlet_user.outlet_id', '=', 'outlets.id');
	}
	public function sendPasswordResetNotification($token)
    {
//        $this->notify(new PasswordReset($token));
    }

    public function stores()
	{
		return $this->hasMany('App\Models\UsersStore','user_id')->join('stores', 'userstores.store_id', '=', 'stores.id');
	}

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public function userRole()
    {
        return $this->hasMany('App\Models\UserRole');
    }








}
