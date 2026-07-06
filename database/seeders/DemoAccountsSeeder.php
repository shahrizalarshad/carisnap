<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Enums\UserRole;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Models\Quote;
use App\Models\User;
use Database\Factories\MalayTestData;
use Illuminate\Database\Seeder;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'role' => UserRole::Admin,
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $photographer = User::query()->updateOrCreate(
            ['email' => 'photographer@example.com'],
            [
                'name' => 'Aiman Rahman',
                'phone' => '0123456789',
                'role' => UserRole::Photographer,
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $profile = PhotographerProfile::query()->updateOrCreate(
            ['slug' => 'studio-cahaya-permata'],
            [
                'user_id' => $photographer->id,
                'business_name' => 'Studio Cahaya Permata',
                'bio' => MalayTestData::bio(),
                'location_area' => MalayTestData::locationArea(),
                'coverage_areas' => MalayTestData::coverageAreas(),
                'instagram_handle' => MalayTestData::instagramHandle(),
                'whatsapp_number' => MalayTestData::whatsappNumber(),
                'verified_at' => now(),
            ]
        );

        $client = User::query()->updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Siti Aisyah',
                'phone' => '0198765432',
                'role' => UserRole::Client,
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $package = Package::query()->firstOrCreate(
            [
                'profile_id' => $profile->id,
                'name' => 'Pakej Demo Perkahwinan',
            ],
            Package::factory()->make([
                'profile_id' => $profile->id,
                'name' => 'Pakej Demo Perkahwinan',
            ])->toArray()
        );

        if ($client->bookingRequests()->where('profile_id', $profile->id)->doesntExist()) {
            BookingRequest::factory()->create([
                'client_id' => $client->id,
                'profile_id' => $profile->id,
                'package_id' => $package->id,
                'status' => BookingStatus::Pending,
                'event_date' => now()->addMonths(4)->format('Y-m-d'),
                'location' => 'Shah Alam, Selangor',
                'message' => 'Majlis di dewan, anggaran 300 tetamu. Nak full-day coverage dengan album digital.',
            ]);

            $quotedBooking = BookingRequest::factory()->create([
                'client_id' => $client->id,
                'profile_id' => $profile->id,
                'package_id' => $package->id,
                'status' => BookingStatus::Quoted,
                'event_date' => now()->addMonths(5)->format('Y-m-d'),
                'location' => 'Klang, Selangor',
                'responded_at' => now(),
            ]);

            Quote::factory()->create([
                'booking_request_id' => $quotedBooking->id,
                'status' => QuoteStatus::Sent,
                'valid_until' => now()->addDays(7)->toDateString(),
            ]);
        }
    }
}
