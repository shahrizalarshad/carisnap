<?php

namespace App\Http\Controllers;

use App\Models\PhotographerProfile;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $profiles = PhotographerProfile::visible()
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', ['profiles' => $profiles])
            ->header('Content-Type', 'application/xml');
    }
}
