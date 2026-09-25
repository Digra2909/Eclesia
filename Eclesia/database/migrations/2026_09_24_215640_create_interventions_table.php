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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_intervention_id')->nullable(false)->constrained('type_interventions')->cascadeOnDelete();
            $table->foreignId('fidele_id')->nullable(false)->constrained('fideles')->cascadeOnDelete();
            $table->foreignId('seance_id')->nullable(false)->constrained('seances')->cascadeOnDelete();
            $table->unique(['type_intervention_id', 'fidele_id', 'seance_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
