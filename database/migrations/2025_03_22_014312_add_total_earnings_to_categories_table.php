<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add the total_earnings column
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('total_earnings', 10, 2)->default(0.00); // 10 digits total, 2 digits after the decimal
        });
    }

    public function down()
    {
        // Remove the total_earnings column if the migration is rolled back
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('total_earnings');
        });
    }
};