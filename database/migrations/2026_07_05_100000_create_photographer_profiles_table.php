<?php

use App\Enums\ProfileTier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photographer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('business_name');
            $table->text('bio');
            $table->string('location_area');
            $table->json('coverage_areas');
            $table->string('instagram_handle')->nullable();
            $table->string('whatsapp_number');
            $table->string('tier')->default(ProfileTier::Free->value);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('featured_until')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photographer_profiles');
    }
};
