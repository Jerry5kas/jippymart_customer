<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'extra',
        'is_active'
    ];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get SEO data for a specific page
     *
     * @param string $pageKey
     * @return SeoPage|null
     */
    public static function getForPage(string $pageKey): ?SeoPage
    {
        return static::where('page_key', $pageKey)
                    ->where('is_active', true)
                    ->first();
    }

    /**
     * Get all active SEO pages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActivePages()
    {
        return static::where('is_active', true)
                    ->orderBy('page_key')
                    ->get();
    }

    /**
     * Get formatted meta title
     *
     * @param string|null $fallback
     * @return string
     */
    public function getMetaTitle(?string $fallback = null): string
    {
        return $this->title ?: $fallback ?: 'JippyMart - Your One-Stop Destination';
    }

    /**
     * Get formatted meta description
     *
     * @param string|null $fallback
     * @return string
     */
    public function getMetaDescription(?string $fallback = null): string
    {
        return $this->description ?: $fallback ?: 'Get groceries, medicines, and daily essentials delivered to your doorstep';
    }

    /**
     * Get Open Graph title
     *
     * @param string|null $fallback
     * @return string
     */
    public function getOgTitle(?string $fallback = null): string
    {
        return $this->og_title ?: $this->getMetaTitle($fallback);
    }

    /**
     * Get Open Graph description
     *
     * @param string|null $fallback
     * @return string
     */
    public function getOgDescription(?string $fallback = null): string
    {
        return $this->og_description ?: $this->getMetaDescription($fallback);
    }

    /**
     * Get Open Graph image
     *
     * @param string|null $fallback
     * @return string|null
     */
    public function getOgImage(?string $fallback = null): ?string
    {
        return $this->og_image ?: $fallback;
    }

    /**
     * Get keywords as array
     *
     * @return array
     */
    public function getKeywordsArray(): array
    {
        if (!$this->keywords) {
            return [];
        }

        return array_map('trim', explode(',', $this->keywords));
    }

    /**
     * Get structured data from extra field
     *
     * @return array
     */
    public function getStructuredData(): array
    {
        return $this->extra['structured_data'] ?? [];
    }
}
