<?php

namespace App\Traits;

use App\Models\SeoPage;
use App\Models\SeoSetting;

trait SeoTrait
{
    /**
     * Get SEO data for a page
     *
     * @param string $pageKey
     * @param array $fallbacks
     * @return array
     */
    protected function getSeoData(string $pageKey, array $fallbacks = []): array
    {
        $seoPage = SeoPage::getForPage($pageKey);
        $seoSettings = SeoSetting::getAllSettings();

        return [
            'title' => $seoPage ? $seoPage->getMetaTitle($fallbacks['title'] ?? null) : ($fallbacks['title'] ?? $seoSettings['site_name'] ?? 'JippyMart'),
            'description' => $seoPage ? $seoPage->getMetaDescription($fallbacks['description'] ?? null) : ($fallbacks['description'] ?? $seoSettings['site_description'] ?? 'Get groceries, medicines, and daily essentials delivered to your doorstep'),
            'keywords' => $seoPage ? $seoPage->keywords : ($fallbacks['keywords'] ?? 'groceries, delivery, online shopping'),
            'og_title' => $seoPage ? $seoPage->getOgTitle($fallbacks['og_title'] ?? null) : ($fallbacks['og_title'] ?? $fallbacks['title'] ?? $seoSettings['site_name'] ?? 'JippyMart'),
            'og_description' => $seoPage ? $seoPage->getOgDescription($fallbacks['og_description'] ?? null) : ($fallbacks['og_description'] ?? $fallbacks['description'] ?? $seoSettings['site_description'] ?? 'Get groceries, medicines, and daily essentials delivered to your doorstep'),
            'og_image' => $seoPage ? $seoPage->getOgImage($fallbacks['og_image'] ?? null) : ($fallbacks['og_image'] ?? $seoSettings['default_og_image'] ?? null),
            'structured_data' => $seoPage ? $seoPage->getStructuredData() : ($fallbacks['structured_data'] ?? []),
            'canonical_url' => $fallbacks['canonical_url'] ?? url($pageKey),
            'page_key' => $pageKey
        ];
    }

    /**
     * Get global SEO settings
     *
     * @return array
     */
    protected function getGlobalSeoSettings(): array
    {
        return SeoSetting::getAllSettings();
    }

    /**
     * Generate meta tags HTML
     *
     * @param array $seoData
     * @return string
     */
    protected function generateMetaTags(array $seoData): string
    {
        $html = '';
        
        // Basic meta tags
        $html .= '<title>' . e($seoData['title']) . '</title>' . "\n";
        $html .= '<meta name="description" content="' . e($seoData['description']) . '">' . "\n";
        
        if (!empty($seoData['keywords'])) {
            $html .= '<meta name="keywords" content="' . e($seoData['keywords']) . '">' . "\n";
        }
        
        // Canonical URL
        $html .= '<link rel="canonical" href="' . e($seoData['canonical_url']) . '">' . "\n";
        
        // Open Graph tags
        $html .= '<meta property="og:title" content="' . e($seoData['og_title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . e($seoData['og_description']) . '">' . "\n";
        $html .= '<meta property="og:type" content="website">' . "\n";
        $html .= '<meta property="og:url" content="' . e($seoData['canonical_url']) . '">' . "\n";
        
        if (!empty($seoData['og_image'])) {
            $html .= '<meta property="og:image" content="' . e($seoData['og_image']) . '">' . "\n";
        }
        
        // Twitter Card tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . e($seoData['og_title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . e($seoData['og_description']) . '">' . "\n";
        
        if (!empty($seoData['og_image'])) {
            $html .= '<meta name="twitter:image" content="' . e($seoData['og_image']) . '">' . "\n";
        }
        
        // Structured data
        if (!empty($seoData['structured_data'])) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($seoData['structured_data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            $html .= '</script>' . "\n";
        }
        
        return $html;
    }
}
