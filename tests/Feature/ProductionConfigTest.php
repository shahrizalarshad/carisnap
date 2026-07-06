<?php

use Tests\TestCase;

uses(TestCase::class);

it('documents required production variables in env example', function () {
    $env = file_get_contents(base_path('.env.example'));

    $required = [
        'APP_NAME=CariSnap',
        'MEDIA_DISK=s3',
        'FILESYSTEM_DISK=s3',
        'AWS_ENDPOINT',
        'MAIL_MAILER',
        'RESEND_API_KEY',
        'GOOGLE_CLIENT_ID',
        'QUEUE_CONNECTION=redis',
        'HORIZON_PATH',
    ];

    foreach ($required as $key) {
        expect($env)->toContain($key);
    }
});

it('configures media library disk from MEDIA_DISK env', function () {
    expect(config('media-library.disk_name'))->toBe(env('MEDIA_DISK', 'public'));
});

it('configures resend mailer when RESEND_API_KEY is set', function () {
    config([
        'mail.default' => 'resend',
        'services.resend.key' => 're_test_key',
    ]);

    expect(config('mail.default'))->toBe('resend')
        ->and(config('services.resend.key'))->toBe('re_test_key');
});

it('configures s3 disk for cloudflare r2 style endpoints', function () {
    $disk = config('filesystems.disks.s3');

    expect($disk['driver'])->toBe('s3')
        ->and($disk)->toHaveKeys(['endpoint', 'url', 'use_path_style_endpoint']);
});
