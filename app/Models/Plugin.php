<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StorePlugin;

class Plugin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plugin';

    public function pluginsStore(){
        return $this->hasMany(StorePlugin::class, 'plugin_id');
    }

}