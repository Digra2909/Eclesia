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
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_seance')->nullable(false)->default(1);
            $table->date('date_seance')->nullable(false);
            $table->time('heure_debut')->nullable(false);
            $table->time('heure_fin')->nullable();
            $table->string('lieu', 50)->nullable(false)->default('temple de l\'église');
            $table->integer('delai_rappel')->nullable();
            $table->foreignId('programme_id')->nullable(false)->constrained('programmes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
