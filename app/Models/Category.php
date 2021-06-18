<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Category extends Model
{
    protected $table = 'categories';
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

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function child()
    {
        // recursively return all children
        return $this->hasOne(Category::class, 'parent_id')->with('child');
    }


    public function parent()
    {
        // recursively return all children
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // This is method where we implement recursive relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function images()
    {
        return $this->hasMany('App\Models\CategoryImage');
    }

    public function transalation()
    {
        return $this->hasMany('App\Models\LanguageTranslation');
    }

    public function product_categories()
    {
        return $this->hasMany('App\Models\ProductCategories');
    }
}
