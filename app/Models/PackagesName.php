<?php 

namespace App\Models;

use App\Models\User;
use App\Models\Features;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class PackagesName extends Model
{
	protected $table = 'package_name';
 
 	public function package_list(){
        return $this->hasOne('App\Models\Packages', 'id', 'package_name_id');
    }

}