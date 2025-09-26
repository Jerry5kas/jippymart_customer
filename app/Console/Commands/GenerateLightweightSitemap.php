<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateLightweightSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate-lightweight {--force : Force regeneration even if sitemap exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate lightweight sitemap.xml for shared hosting (static pages only)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting lightweight sitemap generation...');

        // Set strict limits for shared hosting
        ini_set('memory_limit', '128M');
        set_time_limit(60); // 1 minute max

        try {
            $sitemap = Sitemap::create();

            // Add only static pages - no Firebase queries
            $this->addStaticPages($sitemap);

            // Write sitemap to file
            $sitemapPath = public_path('sitemap.xml');
            $sitemap->writeToFile($sitemapPath);

            $this->info("Lightweight sitemap generated successfully at: {$sitemapPath}");
            $this->info('Sitemap URL: ' . url('sitemap.xml'));

            // Log sitemap generation
            \Log::info('Lightweight sitemap generated successfully', [
                'path' => $sitemapPath,
                'url' => url('sitemap.xml'),
                'generated_at' => now()
            ]);

        } catch (\Exception $e) {
            $this->error('Error generating sitemap: ' . $e->getMessage());
            \Log::error('Lightweight sitemap generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Add static pages to sitemap
     *
     * @param Sitemap $sitemap
     */
    private function addStaticPages(Sitemap $sitemap)
    {
        $this->info('Adding static pages...');

        $staticPages = [
            [
                'url' => '/',
                'priority' => 1.0,
                'changefreq' => 'daily',
                'lastmod' => now()
            ],
            [
                'url' => '/about',
                'priority' => 0.8,
                'changefreq' => 'monthly',
                'lastmod' => now()
            ],
            [
                'url' => '/contact-us',
                'priority' => 0.8,
                'changefreq' => 'monthly',
                'lastmod' => now()
            ],
            [
                'url' => '/privacy',
                'priority' => 0.5,
                'changefreq' => 'yearly',
                'lastmod' => now()
            ],
            [
                'url' => '/terms',
                'priority' => 0.5,
                'changefreq' => 'yearly',
                'lastmod' => now()
            ],
            [
                'url' => '/faq',
                'priority' => 0.7,
                'changefreq' => 'monthly',
                'lastmod' => now()
            ],
            [
                'url' => '/offers',
                'priority' => 0.9,
                'changefreq' => 'weekly',
                'lastmod' => now()
            ],
            [
                'url' => '/restaurants',
                'priority' => 0.9,
                'changefreq' => 'daily',
                'lastmod' => now()
            ],
            [
                'url' => '/categories',
                'priority' => 0.8,
                'changefreq' => 'weekly',
                'lastmod' => now()
            ],
            [
                'url' => '/search',
                'priority' => 0.6,
                'changefreq' => 'daily',
                'lastmod' => now()
            ],
            [
                'url' => '/mart',
                'priority' => 0.9,
                'changefreq' => 'daily',
                'lastmod' => now()
            ],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setLastModificationDate($page['lastmod'])
                    ->setChangeFrequency($page['changefreq'])
                    ->setPriority($page['priority'])
            );
        }

        $this->info('Added ' . count($staticPages) . ' static pages');
    }
}
