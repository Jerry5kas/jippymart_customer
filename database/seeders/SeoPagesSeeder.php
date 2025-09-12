<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoPage;
use App\Models\SeoSetting;

class SeoPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default SEO pages
        $seoPages = [
            [
                'page_key' => 'home',
                'title' => 'JippyMart - Your One-Stop Destination for Groceries & Medicines',
                'description' => 'Get fresh groceries, medicines, and daily essentials delivered to your doorstep. Fast delivery, quality products, and great prices at JippyMart.',
                'keywords' => 'groceries, medicines, delivery, online shopping, fresh food, pharmacy, daily essentials',
                'og_title' => 'JippyMart - Groceries & Medicines Delivered',
                'og_description' => 'Order groceries and medicines online with fast delivery. Quality products at great prices.',
                'og_image' => '/images/og-home.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'about',
                'title' => 'About JippyMart - Your Trusted Online Grocery Store',
                'description' => 'Learn about JippyMart\'s mission to provide fresh groceries, medicines, and daily essentials with fast and reliable delivery service.',
                'keywords' => 'about jippymart, online grocery store, company information, our story',
                'og_title' => 'About JippyMart - Your Trusted Online Store',
                'og_description' => 'Discover JippyMart\'s commitment to quality and service in online grocery delivery.',
                'og_image' => '/images/og-about.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'contact',
                'title' => 'Contact JippyMart - Get in Touch with Us',
                'description' => 'Contact JippyMart for support, feedback, or inquiries. We\'re here to help with your grocery and medicine delivery needs.',
                'keywords' => 'contact jippymart, customer support, help, feedback, inquiries',
                'og_title' => 'Contact JippyMart - Customer Support',
                'og_description' => 'Get in touch with JippyMart for support, feedback, or any inquiries.',
                'og_image' => '/images/og-contact.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'privacy',
                'title' => 'Privacy Policy - JippyMart',
                'description' => 'Read JippyMart\'s privacy policy to understand how we collect, use, and protect your personal information.',
                'keywords' => 'privacy policy, data protection, personal information, jippymart privacy',
                'og_title' => 'Privacy Policy - JippyMart',
                'og_description' => 'Learn how JippyMart protects your privacy and personal information.',
                'og_image' => '/images/og-privacy.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'terms',
                'title' => 'Terms of Service - JippyMart',
                'description' => 'Read JippyMart\'s terms of service to understand the rules and guidelines for using our platform.',
                'keywords' => 'terms of service, terms and conditions, user agreement, jippymart terms',
                'og_title' => 'Terms of Service - JippyMart',
                'og_description' => 'Review JippyMart\'s terms of service and user agreement.',
                'og_image' => '/images/og-terms.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'faq',
                'title' => 'FAQ - Frequently Asked Questions - JippyMart',
                'description' => 'Find answers to frequently asked questions about JippyMart\'s services, delivery, payments, and more.',
                'keywords' => 'faq, frequently asked questions, help, support, jippymart faq',
                'og_title' => 'FAQ - JippyMart Help Center',
                'og_description' => 'Get answers to common questions about JippyMart\'s services.',
                'og_image' => '/images/og-faq.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'offers',
                'title' => 'Special Offers & Deals - JippyMart',
                'description' => 'Discover amazing offers, discounts, and deals on groceries and medicines at JippyMart. Save more on your daily essentials.',
                'keywords' => 'offers, deals, discounts, promotions, special offers, jippymart deals',
                'og_title' => 'Special Offers & Deals - JippyMart',
                'og_description' => 'Find great deals and offers on groceries and medicines.',
                'og_image' => '/images/og-offers.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'restaurants',
                'title' => 'Restaurants & Food Delivery - JippyMart',
                'description' => 'Order food from top restaurants in your area. Fast delivery, fresh food, and great prices at JippyMart.',
                'keywords' => 'restaurants, food delivery, online food ordering, restaurant delivery',
                'og_title' => 'Restaurant Food Delivery - JippyMart',
                'og_description' => 'Order food from your favorite restaurants with fast delivery.',
                'og_image' => '/images/og-restaurants.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'categories',
                'title' => 'Product Categories - JippyMart',
                'description' => 'Browse all product categories at JippyMart. Find groceries, medicines, and daily essentials organized by category.',
                'keywords' => 'product categories, groceries categories, medicine categories, browse products',
                'og_title' => 'Product Categories - JippyMart',
                'og_description' => 'Browse all product categories and find what you need.',
                'og_image' => '/images/og-categories.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'search',
                'title' => 'Search Products - JippyMart',
                'description' => 'Search for groceries, medicines, and daily essentials at JippyMart. Find exactly what you need with our powerful search.',
                'keywords' => 'search products, find products, product search, jippymart search',
                'og_title' => 'Search Products - JippyMart',
                'og_description' => 'Search and find products quickly and easily.',
                'og_image' => '/images/og-search.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'mart',
                'title' => 'JippyMart - Online Grocery & Medicine Store',
                'description' => 'Shop for groceries, medicines, and daily essentials at JippyMart. Quality products, fast delivery, and great prices.',
                'keywords' => 'online grocery store, medicine store, daily essentials, jippymart shopping',
                'og_title' => 'JippyMart - Online Grocery Store',
                'og_description' => 'Shop for groceries and medicines online with fast delivery.',
                'og_image' => '/images/og-mart.jpg',
                'is_active' => true,
            ],
            [
                'page_key' => 'product',
                'title' => '{product_name} - JippyMart',
                'description' => 'Buy {product_name} online at JippyMart. Quality product with fast delivery and great prices.',
                'keywords' => '{product_name}, online shopping, jippymart product',
                'og_title' => '{product_name} - JippyMart',
                'og_description' => 'Buy {product_name} online with fast delivery.',
                'og_image' => '{product_image}',
                'is_active' => true,
            ],
            [
                'page_key' => 'restaurant',
                'title' => '{restaurant_name} - Food Delivery - JippyMart',
                'description' => 'Order food from {restaurant_name} at JippyMart. Fast delivery, fresh food, and great prices.',
                'keywords' => '{restaurant_name}, food delivery, restaurant ordering, jippymart',
                'og_title' => '{restaurant_name} - Food Delivery',
                'og_description' => 'Order food from {restaurant_name} with fast delivery.',
                'og_image' => '{restaurant_image}',
                'is_active' => true,
            ],
            [
                'page_key' => 'category',
                'title' => '{category_name} - JippyMart',
                'description' => 'Shop {category_name} products at JippyMart. Quality products with fast delivery and great prices.',
                'keywords' => '{category_name}, products, online shopping, jippymart',
                'og_title' => '{category_name} - JippyMart',
                'og_description' => 'Shop {category_name} products online.',
                'og_image' => '{category_image}',
                'is_active' => true,
            ],
        ];

        foreach ($seoPages as $page) {
            SeoPage::updateOrCreate(
                ['page_key' => $page['page_key']],
                $page
            );
        }

        // Create default SEO settings
        $seoSettings = [
            [
                'setting_key' => 'site_name',
                'setting_value' => 'JippyMart',
                'setting_type' => 'text',
                'description' => 'The name of the website',
                'is_active' => true,
            ],
            [
                'setting_key' => 'site_description',
                'setting_value' => 'Your one-stop destination for groceries, medicines, and daily essentials',
                'setting_type' => 'text',
                'description' => 'Default site description for SEO',
                'is_active' => true,
            ],
            [
                'setting_key' => 'site_keywords',
                'setting_value' => 'groceries, medicines, delivery, online shopping, fresh food, pharmacy, daily essentials',
                'setting_type' => 'text',
                'description' => 'Default site keywords for SEO',
                'is_active' => true,
            ],
            [
                'setting_key' => 'default_og_image',
                'setting_value' => '/images/og-default.jpg',
                'setting_type' => 'text',
                'description' => 'Default Open Graph image for social sharing',
                'is_active' => true,
            ],
            [
                'setting_key' => 'twitter_handle',
                'setting_value' => '@jippymart',
                'setting_type' => 'text',
                'description' => 'Twitter handle for social media',
                'is_active' => true,
            ],
            [
                'setting_key' => 'google_analytics_id',
                'setting_value' => '',
                'setting_type' => 'text',
                'description' => 'Google Analytics tracking ID',
                'is_active' => true,
            ],
            [
                'setting_key' => 'google_search_console_verification',
                'setting_value' => 'googlee8775aee3a719706.html',
                'setting_type' => 'text',
                'description' => 'Google Search Console verification file',
                'is_active' => true,
            ],
            [
                'setting_key' => 'facebook_app_id',
                'setting_value' => '',
                'setting_type' => 'text',
                'description' => 'Facebook App ID for social sharing',
                'is_active' => true,
            ],
            [
                'setting_key' => 'contact_email',
                'setting_value' => 'contact@jippymart.in',
                'setting_type' => 'text',
                'description' => 'Contact email address',
                'is_active' => true,
            ],
            [
                'setting_key' => 'contact_phone',
                'setting_value' => '',
                'setting_type' => 'text',
                'description' => 'Contact phone number',
                'is_active' => true,
            ],
            [
                'setting_key' => 'business_address',
                'setting_value' => '',
                'setting_type' => 'text',
                'description' => 'Business address for local SEO',
                'is_active' => true,
            ],
            [
                'setting_key' => 'business_hours',
                'setting_value' => '24/7',
                'setting_type' => 'text',
                'description' => 'Business operating hours',
                'is_active' => true,
            ],
        ];

        foreach ($seoSettings as $setting) {
            SeoSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }

        $this->command->info('SEO pages and settings seeded successfully!');
    }
}
