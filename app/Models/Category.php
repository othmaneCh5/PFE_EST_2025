<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'parent_id'];

    // Relationship to the parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship to the subcategories
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    //Relationship to products
    public function products()
    {
        return $this->HasMany(Product::class);
    }
}
