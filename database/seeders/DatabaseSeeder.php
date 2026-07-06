<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Models\PortfolioItem;
use App\Models\Quote;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1 admin
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'name' => 'System Admin',
        ]);

        // 25 verified photographer profiles
        $profiles = PhotographerProfile::factory(25)->create([
            'verified_at' => now(),
        ]);

        foreach ($profiles as $profile) {
            // Packages
            $packages = Package::factory(3)->create([
                'profile_id' => $profile->id,
            ]);

            // Portfolio items with sample images
            $portfolioItems = PortfolioItem::factory(5)->create([
                'profile_id' => $profile->id,
            ]);

            $sampleImages = [
                storage_path('app/wedding1.jpg'),
                storage_path('app/wedding2.jpg'),
                storage_path('app/wedding3.jpg'),
            ];

            foreach ($portfolioItems as $item) {
                $image = $sampleImages[array_rand($sampleImages)];
                if (file_exists($image)) {
                    try {
                        $item->addMedia($image)
                            ->preservingOriginal()
                            ->toMediaCollection('portfolio');
                    } catch (\Exception $e) {
                        // ignore if media upload fails in seeder
                    }
                }
            }

            // Availabilities for the next 12 months (e.g. 10 random dates per profile)
            $dates = [];
            for ($i = 0; $i < 10; $i++) {
                $dates[] = now()->addDays(rand(1, 360))->format('Y-m-d');
            }
            $dates = array_unique($dates);

            foreach ($dates as $date) {
                Availability::factory()->create([
                    'profile_id' => $profile->id,
                    'date' => $date,
                ]);
            }
        }

        // 40 booking requests in mixed statuses
        $clients = User::factory(10)->create();

        for ($i = 0; $i < 40; $i++) {
            $profile = $profiles->random();
            $package = $profile->packages->random();
            $status = fake()->randomElement(BookingStatus::cases());

            $booking = BookingRequest::factory()->create([
                'client_id' => fake()->boolean(80) ? $clients->random()->id : null,
                'profile_id' => $profile->id,
                'package_id' => $package->id,
                'status' => $status,
            ]);

            if ($status === BookingStatus::Quoted) {
                Quote::factory()->create([
                    'booking_request_id' => $booking->id,
                ]);
            } elseif ($status === BookingStatus::Accepted) {
                Quote::factory()->create([
                    'booking_request_id' => $booking->id,
                    'status' => QuoteStatus::Accepted,
                ]);

                if (fake()->boolean(70)) {
                    Review::factory()->create([
                        'booking_request_id' => $booking->id,
                    ]);
                }
            }
        }
    }
}
