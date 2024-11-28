<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farmer_cows', function (Blueprint $table) {
            $table->id();
            // farmerID and breedID are foreign keys
            $table->foreignId("farmer_id")->nullable()
                ->references("id")->on("farmers")->constrained()->onDelete('set null');
            $table->foreignId('breed_id')->nullable()
                ->references('id')->on('cow_breeds')->constrained()->onDelete('set null');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_cows');
    }
};
