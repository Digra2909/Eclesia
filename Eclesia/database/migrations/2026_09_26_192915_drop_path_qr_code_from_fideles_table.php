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
        Schema::table('fideles', function (Blueprint $table) {
            if (Schema::hasColumn('fideles', 'path_qr_code')) {
                $table->dropColumn('path_qr_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fideles', function (Blueprint $table) {
            if (! Schema::hasColumn('fideles', 'path_qr_code')) {
                $table->string('path_qr_code', 'max')->nullable();
            }
        });
    }
};
