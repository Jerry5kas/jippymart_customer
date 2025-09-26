<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
// SEO models removed for performance optimization
use App\Services\FirebaseService;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--force : Force regeneration even if sitemap exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for JippyMart public site';

    /**
     * Firebase service instance
     *
     * @var FirebaseService
     */
    protected $firebaseService;

    /**
     * Create a new command instance.
     */
    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->firebaseService = $firebaseService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sitemap generation...');

        // Set memory and time limits for shared hosting
        ini_set('memory_limit', '256M');
        set_time_limit(300); // 5 minutes max

        try {
            $sitemap = Sitemap::create();

            // Add static pages
            $this->addStaticPages($sitemap);

            // Skip Firebase pages for shared hosting to prevent resource conflicts
            $this->warn('Skipping Firebase pages for shared hosting compatibility');
            // $this->addFirebasePages($sitemap);

            // Write sitemap to file
            $sitemapPath = public_path('sitemap.xml');
            $sitemap->writeToFile($sitemapPath);

            $this->info("Sitemap generated successfully at: {$sitemapPath}");
            $this->info('Sitemap URL: ' . url('sitemap.xml'));

            // Log sitemap generation
            \Log::info('Sitemap generated successfully', [
                'path' => $sitemapPath,
                'url' => url('sitemap.xml'),
                'generated_at' => now()
            ]);

        } catch (\Exception $e) {
            $this->error('Error generating sitemap: ' . $e->getMessage());
            \Log::error('Sitemap generation failed', [
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

    /**
     * Add dynamic pages from Firebase to sitemap
     *
     * @param Sitemap $sitemap
     */
    private function addFirebasePages(Sitemap $sitemap)
    {
        $this->info('Adding dynamic pages from Firebase...');

        try {
            // Add restaurant pages
            $this->addRestaurantPages($sitemap);

            // Add product pages
            $this->addProductPages($sitemap);

            // Add category pages
            $this->addCategoryPages($sitemap);

            // Add subcategory pages
            $this->addSubcategoryPages($sitemap);

        } catch (\Exception $e) {
            $this->warn('Error adding Firebase pages: ' . $e->getMessage());
            \Log::warning('Error adding Firebase pages to sitemap', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Add restaurant pages from Firebase vendors collection
     *
     * @param Sitemap $sitemap
     */
    private function addRestaurantPages(Sitemap $sitemap)
    {
        $this->info('Adding restaurant pages...');

        try {
            // Get all published vendors from Firebase
            $vendors = $this->getFirebaseVendors();

            $count = 0;
            foreach ($vendors as $vendor) {
                if ($this->isValidVendor($vendor)) {
                    $url = "/restaurant/{$vendor['id']}/{$vendor['restaurant_slug']}/{$vendor['zone_slug']}";

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($this->getVendorLastMod($vendor))
                            ->setChangeFrequency('daily')
                            ->setPriority(0.8)
                    );

                    $count++;
                }
            }

            $this->info("Added {$count} restaurant pages");

        } catch (\Exception $e) {
            $this->warn('Error adding restaurant pages: ' . $e->getMessage());
        }
    }

    /**
     * Add product pages from Firebase mart_items collection
     *
     * @param Sitemap $sitemap
     */
    private function addProductPages(Sitemap $sitemap)
    {
        $this->info('Adding product pages...');

        try {
            // Get all published products from Firebase
            $products = $this->getFirebaseProducts();

            $count = 0;
            foreach ($products as $product) {
                if ($this->isValidProduct($product)) {
                    $url = "/product/{$product['id']}";

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($this->getProductLastMod($product))
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.7)
                    );

                    $count++;
                }
            }

            $this->info("Added {$count} product pages");

        } catch (\Exception $e) {
            $this->warn('Error adding product pages: ' . $e->getMessage());
        }
    }

    /**
     * Add category pages from Firebase mart_categories collection
     *
     * @param Sitemap $sitemap
     */
    private function addCategoryPages(Sitemap $sitemap)
    {
        $this->info('Adding category pages...');

        try {
            // Get all published categories from Firebase
            $categories = $this->getFirebaseCategories();

            $count = 0;
            foreach ($categories as $category) {
                if ($this->isValidCategory($category)) {
                    $url = "/category/{$category['id']}";

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($this->getCategoryLastMod($category))
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.6)
                    );

                    $count++;
                }
            }

            $this->info("Added {$count} category pages");

        } catch (\Exception $e) {
            $this->warn('Error adding category pages: ' . $e->getMessage());
        }
    }

    /**
     * Add subcategory pages from Firebase mart_subcategories collection
     *
     * @param Sitemap $sitemap
     */
    private function addSubcategoryPages(Sitemap $sitemap)
    {
        $this->info('Adding subcategory pages...');

        try {
            // Get all published subcategories from Firebase
            $subcategories = $this->getFirebaseSubcategories();

            $count = 0;
            foreach ($subcategories as $subcategory) {
                if ($this->isValidSubcategory($subcategory)) {
                    $url = "/subcategory/{$subcategory['id']}";

                    $sitemap->add(
                        Url::create($url)
                            ->setLastModificationDate($this->getSubcategoryLastMod($subcategory))
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.5)
                    );

                    $count++;
                }
            }

            $this->info("Added {$count} subcategory pages");

        } catch (\Exception $e) {
            $this->warn('Error adding subcategory pages: ' . $e->getMessage());
        }
    }

    /**
     * Get vendors from Firebase
     *
     * @return array
     */
    private function getFirebaseVendors(): array
    {
        try {
            // This would need to be implemented based on your Firebase structure
            // For now, return empty array
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching vendors from Firebase', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get products from Firebase
     *
     * @return array
     */
    private function getFirebaseProducts(): array
    {
        try {
            // This would need to be implemented based on your Firebase structure
            // For now, return empty array
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching products from Firebase', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get categories from Firebase
     *
     * @return array
     */
    private function getFirebaseCategories(): array
    {
        try {
            // This would need to be implemented based on your Firebase structure
            // For now, return empty array
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching categories from Firebase', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get subcategories from Firebase
     *
     * @return array
     */
    private function getFirebaseSubcategories(): array
    {
        try {
            // This would need to be implemented based on your Firebase structure
            // For now, return empty array
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching subcategories from Firebase', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Check if vendor is valid for sitemap
     *
     * @param array $vendor
     * @return bool
     */
    private function isValidVendor(array $vendor): bool
    {
        return isset($vendor['id']) &&
               isset($vendor['restaurant_slug']) &&
               isset($vendor['zone_slug']) &&
               ($vendor['isOpen'] ?? false) &&
               ($vendor['publish'] ?? false);
    }

    /**
     * Check if product is valid for sitemap
     *
     * @param array $product
     * @return bool
     */
    private function isValidProduct(array $product): bool
    {
        return isset($product['id']) &&
               ($product['publish'] ?? false) &&
               ($product['isAvailable'] ?? false);
    }

    /**
     * Check if category is valid for sitemap
     *
     * @param array $category
     * @return bool
     */
    private function isValidCategory(array $category): bool
    {
        return isset($category['id']) &&
               ($category['publish'] ?? false);
    }

    /**
     * Check if subcategory is valid for sitemap
     *
     * @param array $subcategory
     * @return bool
     */
    private function isValidSubcategory(array $subcategory): bool
    {
        return isset($subcategory['id']) &&
               ($subcategory['publish'] ?? false);
    }

    /**
     * Get vendor last modification date
     *
     * @param array $vendor
     * @return Carbon
     */
    private function getVendorLastMod(array $vendor): Carbon
    {
        if (isset($vendor['updated_at'])) {
            return Carbon::parse($vendor['updated_at']);
        }
        return now();
    }

    /**
     * Get product last modification date
     *
     * @param array $product
     * @return Carbon
     */
    private function getProductLastMod(array $product): Carbon
    {
        if (isset($product['updated_at'])) {
            return Carbon::parse($product['updated_at']);
        }
        return now();
    }

    /**
     * Get category last modification date
     *
     * @param array $category
     * @return Carbon
     */
    private function getCategoryLastMod(array $category): Carbon
    {
        if (isset($category['updated_at'])) {
            return Carbon::parse($category['updated_at']);
        }
        return now();
    }

    /**
     * Get subcategory last modification date
     *
     * @param array $subcategory
     * @return Carbon
     */
    private function getSubcategoryLastMod(array $subcategory): Carbon
    {
        if (isset($subcategory['updated_at'])) {
            return Carbon::parse($subcategory['updated_at']);
        }
        return now();
    }
}

