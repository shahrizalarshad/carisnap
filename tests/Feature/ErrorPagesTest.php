<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('renders the custom 404 page for missing photographer slugs', function () {
    $this->get('/studio-tiada-wujud')
        ->assertNotFound()
        ->assertSee('Halaman tidak dijumpai', false)
        ->assertSee('Kembali ke Laman Utama', false)
        ->assertSee('Cari Jurugambar', false);
});

it('renders the custom 500 page when debug mode is off', function () {
    config(['app.debug' => false]);

    $response = $this->app->make(ExceptionHandler::class)
        ->render(
            Request::create('/'),
            new HttpException(500),
        );

    expect($response->getStatusCode())->toBe(500)
        ->and($response->getContent())->toContain('Alami sedikit masalah')
        ->and($response->getContent())->toContain('Kembali ke Laman Utama');
});

it('marks error pages as noindex', function () {
    $this->get('/studio-tiada-wujud')
        ->assertNotFound()
        ->assertSee('noindex', false);
});
