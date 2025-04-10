<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FournisseurOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'product_id',
        'ordered_by_user_id',
        'quantity',
        'price',
        'status',
        'order_date',
        'notes',
    ];

    // RELATIONSHIPS

    /**
     * The supplier who provides the product
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * The product being ordered
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The user who placed the order
     */
    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }
}
