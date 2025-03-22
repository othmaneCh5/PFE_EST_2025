<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Remove the status column
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down()
    {
        // Add the status column back if the migration is rolled back
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->default('Publish'); // Adjust the default value as needed
        });
    }
};