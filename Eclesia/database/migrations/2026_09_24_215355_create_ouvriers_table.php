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
        Schema::create('ouvriers', function (Blueprint $table) {
            $table->id();
            $table->string('code_ouvrier', 15)->nullable(false)->unique();
            $table->foreignId('poste_id')->nullable()->constrained('postes')->nullOnDelete();
            $table->foreignId('fidele_id')->nullable(false)->constrained('fideles')->cascadeOnDelete();
            $table->string('path_photo', 'max')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvriers');
    }
};
