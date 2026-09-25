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
        Schema::create('fideles', function (Blueprint $table) {
            $table->id();
            $table->string('code_fidele', 15)->nullable(false)->unique();
            $table->string('nom', 20)->nullable(false);
            $table->string('postnom', 20)->nullable(false);
            $table->string('prenom', 20)->nullable(false);
            $table->date('date_naissance')->nullable();
            $table->string('telephone', 13)->nullable();
            $table->longText('grace')->nullable();
            $table->enum('genre', ['M', 'F'])->nullable(false);
            $table->string('path_qr_code', 'max')->nullable(false);
            $table->foreignId('statut_id')->nullable()->constrained('statut_fideles')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fideles');
    }
};
