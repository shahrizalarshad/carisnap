<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('photographer_profiles')->cascadeOnDelete();
            $table->date('date');
            $table->string('status');
            $table->timestamps();

            $table->unique(['profile_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
