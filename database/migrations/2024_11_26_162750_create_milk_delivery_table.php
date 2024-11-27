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
        Schema::create('milk_delivery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->nullable()
                ->references('id')->on('users')->constrained()->onDelete('set null');
            $table->foreignId('rate_id')->nullable()
                ->references('id')->on('rates')->constrained()->onDelete('set null');
            $table->float('milk_capacity');
            $table->boolean('is_paid')->default(false);
            $table->boolean('had_issues')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_delivery');
    }
};
