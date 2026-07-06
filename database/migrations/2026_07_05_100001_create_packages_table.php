<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('photographer_profiles')->cascadeOnDelete();
            $table->string('name');
            $table->string('event_type');
            $table->integer('price_from');
            $table->text('deliverables');
            $table->integer('duration_hours');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
