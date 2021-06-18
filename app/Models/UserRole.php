<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class UserRole extends Model
{
	protected $table = 'role_user';

	public $fillable =['role_id','user_id'];

	public  $timestamps = false;

}
