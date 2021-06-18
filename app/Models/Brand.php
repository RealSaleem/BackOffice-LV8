<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Brand extends Model{

    protected $table = 'brands';
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
        if($this->created_at == null){
            $this->created = Auth::user()->id;
        }else{
            $this->updated_by = Auth::user()->id;
        }

        return parent::save();
    }

    public function products(){
    	return $this->hasMany('App\Models\Product');
    }


    public function images()
    {
        return $this->hasMany('App\Models\BrandImage');
    }

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation');
    }

   public function product_brands()
    {
        return $this->hasMany('App\Models\ProductBrands');
    }
    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }
}
