<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_role extends Model
{
    protected $table = 'permission_role';

    public $fillable = ['permission_id','role_id'];
    public $timestamps = false;
}
