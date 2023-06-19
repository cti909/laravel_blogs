<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id', 
        'name', 
        'path'
    ];

    /**
     * Posts
     */
    function posts() {
        return $this->hasMany(Post::class);
    }
    
    //-----------------------------------------function---------------------------------------------------
    /**
     * get all categories
     */
    static public function getAllCategories() {
        $categories = Category::all();
        return $categories;
    }
}
