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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->boolean('est_present')->nullable(false);
            $table->foreignId('fidele_id')->nullable(false)->constrained('fideles')->cascadeOnDelete();
            $table->foreignId('seance_id')->nullable(false)->constrained('seances')->cascadeOnDelete();
            $table->unique(['fidele_id', 'seance_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
