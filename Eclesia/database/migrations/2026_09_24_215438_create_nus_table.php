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
        Schema::create('nus', function (Blueprint $table) {
            $table->id();
            $table->string('code_nu', 6)->nullable(false)->unique();
            $table->decimal('note_oral', 3, 1)->nullable();
            $table->decimal('note_ecrite', 3, 1)->nullable();
            $table->decimal('pourcentage', 3, 1)->nullable();
            $table->enum('statut', ['en règle', 'non en règle'])->default('en règle');
            $table->foreignId('fidele_id')->nullable(false)->constrained('fideles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nus');
    }
};
