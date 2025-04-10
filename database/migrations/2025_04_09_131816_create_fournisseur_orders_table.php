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
        Schema::create('fournisseur_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained()->onDelete('cascade'); // supplier
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // single product per order
            $table->foreignId('ordered_by_user_id')->constrained('users')->onDelete('cascade'); // who ordered
            $table->integer('quantity'); // quantity of product
            $table->decimal('price', 10, 2); // unit price
            $table->enum('status', ['pending', 'received', 'canceled'])->default('pending'); // status
            $table->timestamp('order_date')->useCurrent(); // order timestamp
            $table->text('notes')->nullable(); // optional notes
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseur_orders');
    }
};
