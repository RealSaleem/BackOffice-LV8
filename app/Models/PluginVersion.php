<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PluginVersion extends Model
{
	protected $table = 'plugin_version';

	public function plugin()
	{
		return $this->belongsTo('\App\Models\Plugin');
	}
}