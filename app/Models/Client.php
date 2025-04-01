<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 'email', 'tel', 'adresse', 'num_carte', 'exp_date', 'cvv_code',
    ];
    
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_client');
    }

}
