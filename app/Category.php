<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;
    public function Product(){
        return $this->hasMany("App\Product","category_id");
    }
    public function childs(){
        return $this->hasMany('App\Category','parent_id');
    }
    protected $primaryKey = 'category_id';
}
