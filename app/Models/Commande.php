<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'id_client', 'paiement', 'status', 'methode',
    ];
    

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commande_product')
                    ->withPivot('qte') // Important
                    ->withTimestamps();
    }
    

}
