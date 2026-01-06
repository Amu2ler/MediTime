<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            // supprime
            $table->dropColumn(['first_name', 'last_name']);

            // ajoute
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->string('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');

            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['specialty_id', 'address']);
        });
    }

};
