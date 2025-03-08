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
        Schema::table('convegrations', function (Blueprint $table) {
            $table->foreignId('last_massege_id')->nullable()->constrained('masseges')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convegrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_massege_id');
        });
    }
};
