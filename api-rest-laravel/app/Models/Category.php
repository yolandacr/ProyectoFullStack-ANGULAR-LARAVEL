<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    
    //Relación de uno a muchos.
    public function posts(){
        return $this -> hasMany('App\Models\Post');
    }
}
