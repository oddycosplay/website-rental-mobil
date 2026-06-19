<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $cars = Car::where('is_available', true)->get();
        
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => url('/cars'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('/about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => url('/faq'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('/contact'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        foreach ($cars as $car) {
            $urls[] = [
                'loc' => url('/cars/' . $car->slug),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $car->updated_at->toAtomString(),
            ];
        }

        return response()->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
