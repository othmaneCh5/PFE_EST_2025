<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->constrained('clients')->onDelete('cascade'); 
            $table->string('paiement')->default('en cours'); // Default: en cours       // en cours, payé, échoué
            $table->string('status')->default('initiée');    // Default: initiée        // initiée, en cours, terminée
            $table->string('methode')->default('mastercard-cc'); // Default: mastercard-cc
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
