<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)
                        ->where('is_active', true)
                        ->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->setting_value, $setting->setting_type);
    }

    /**
     * Set setting value by key
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return SeoSetting
     */
    public static function setValue(string $key, $value, string $type = 'text', ?string $description = null): SeoSetting
    {
        return static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => static::prepareValue($value, $type),
                'setting_type' => $type,
                'description' => $description,
                'is_active' => true
            ]
        );
    }

    /**
     * Get all active settings as key-value array
     *
     * @return array
     */
    public static function getAllSettings(): array
    {
        $settings = static::where('is_active', true)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->setting_key] = static::castValue($setting->setting_value, $setting->setting_type);
        }

        return $result;
    }

    /**
     * Cast value based on type
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return is_string($value) ? explode(',', $value) : $value;
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage based on type
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private static function prepareValue($value, string $type)
    {
        switch ($type) {
            case 'json':
                return is_array($value) ? json_encode($value) : $value;
            case 'array':
                return is_array($value) ? implode(',', $value) : $value;
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return $value;
        }
    }

    /**
     * Get global SEO settings
     *
     * @return array
     */
    public static function getGlobalSettings(): array
    {
        return [
            'site_name' => static::getValue('site_name', 'JippyMart'),
            'site_description' => static::getValue('site_description', 'Your one-stop destination for groceries, medicines, and daily essentials'),
            'site_keywords' => static::getValue('site_keywords', 'groceries, medicines, delivery, online shopping'),
            'default_og_image' => static::getValue('default_og_image', '/images/og-default.jpg'),
            'twitter_handle' => static::getValue('twitter_handle', '@jippymart'),
            'google_analytics_id' => static::getValue('google_analytics_id'),
            'google_search_console_verification' => static::getValue('google_search_console_verification'),
            'facebook_app_id' => static::getValue('facebook_app_id'),
            'contact_email' => static::getValue('contact_email', 'contact@jippymart.in'),
            'contact_phone' => static::getValue('contact_phone'),
            'business_address' => static::getValue('business_address'),
            'business_hours' => static::getValue('business_hours'),
        ];
    }
}
