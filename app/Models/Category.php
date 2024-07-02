<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    //Fillables protect against mass-assignment, it means users with bad intentions can't insert data on fields other than these
    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
    ];

    public function categories(){
        return $this->hasMany(Product::class);
    }
}
