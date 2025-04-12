<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'commande_id',
        'product_id',
        'price',
        'quantity',
        'sale_date',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
    
}
