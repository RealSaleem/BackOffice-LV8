<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abilities extends Model
{
	protected $table = 'abilities';

	public function permissions()
	{
		return $this->hasMany(Permission::class, 'ability_id', 'id');
	}

}