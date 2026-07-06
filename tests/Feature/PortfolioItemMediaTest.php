<?php

use App\Models\PortfolioItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('attaches portfolio images to the portfolio media collection', function () {
    $item = PortfolioItem::factory()->create();

    $item->addMedia(UploadedFile::fake()->image('wedding.jpg', 1600, 1200))
        ->toMediaCollection('portfolio');

    $media = $item->fresh()->getFirstMedia('portfolio');

    expect($media)->not->toBeNull()
        ->and($media->collection_name)->toBe('portfolio')
        ->and($media->mime_type)->toStartWith('image/');
});

it('queues webp thumbnail and display conversions for portfolio uploads', function () {
    Queue::fake();

    $item = PortfolioItem::factory()->create();

    $item->addMedia(UploadedFile::fake()->image('wedding.jpg', 1600, 1200))
        ->toMediaCollection('portfolio');

    Queue::assertPushed(PerformConversionsJob::class);
});

it('generates thumbnail and display webp conversions when conversion jobs run', function () {
    Queue::fake();

    $item = PortfolioItem::factory()->create();

    $item->addMedia(UploadedFile::fake()->image('wedding.jpg', 1600, 1200))
        ->toMediaCollection('portfolio');

    $media = $item->fresh()->getFirstMedia('portfolio');

    foreach (Queue::pushed(PerformConversionsJob::class) as $job) {
        $job->handle(app(FileManipulator::class));
    }

    $media->refresh();

    expect($media->hasGeneratedConversion('thumbnail'))->toBeTrue()
        ->and($media->hasGeneratedConversion('display'))->toBeTrue();
});

it('replaces the existing portfolio image when a new one is uploaded', function () {
    $item = PortfolioItem::factory()->create();

    $item->addMedia(UploadedFile::fake()->image('first.jpg', 800, 600))
        ->toMediaCollection('portfolio');

    $item->addMedia(UploadedFile::fake()->image('second.jpg', 800, 600))
        ->toMediaCollection('portfolio');

    expect($item->fresh()->getMedia('portfolio'))->toHaveCount(1);
});
