<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = Cache::remember('sitemap.urls', 86400, function () {
            $staticRoutes = [
                ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => route('products.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => route('brands.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
                ['loc' => route('products.search'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['loc' => route('contact.create'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['loc' => route('track-order.create'), 'priority' => '0.4', 'changefreq' => 'monthly'],
                ['loc' => route('privacy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
                ['loc' => route('terms'), 'priority' => '0.3', 'changefreq' => 'yearly'],
                ['loc' => route('guides.choose-perfume'), 'priority' => '0.6', 'changefreq' => 'monthly'],
                ['loc' => route('guides.fragrance-notes'), 'priority' => '0.6', 'changefreq' => 'monthly'],
                ['loc' => route('guides.edt-vs-edp'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ];

            $products = Product::query()
                ->where('is_active', true)
                ->orderBy('updated_at', 'desc')
                ->get(['slug', 'updated_at']);

            $productRoutes = $products->map(fn (Product $product) => [
                'loc' => route('products.show', $product->slug),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $product->updated_at->toAtomString(),
            ]);

            return array_merge($staticRoutes, $productRoutes->all());
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.e($url['loc']).'</loc>'."\n";
            $xml .= '    <priority>'.$url['priority'].'</priority>'."\n";
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'."\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'."\n";
            }
            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
