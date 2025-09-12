<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display static pages with SEO.
     */
    public function show($page)
    {
        $validPages = ['about', 'contact', 'privacy', 'terms', 'faq', 'offers'];
        
        if (!in_array($page, $validPages)) {
            abort(404);
        }
        
        // Get SEO data for the page
        $seoData = SeoPage::getForPage($page);
        $globalSettings = SeoSetting::getGlobalSettings();
        
        // Prepare data for view
        $data = [
            'pageKey' => $page,
            'seoData' => $seoData,
            'globalSettings' => $globalSettings,
            'pageTitle' => ucfirst(str_replace('-', ' ', $page)),
        ];
        
        return view("customer.pages.{$page}", $data);
    }
    
    /**
     * Display about page.
     */
    public function about()
    {
        return $this->show('about');
    }
    
    /**
     * Display contact page.
     */
    public function contact()
    {
        return $this->show('contact');
    }
    
    /**
     * Display privacy policy page.
     */
    public function privacy()
    {
        return $this->show('privacy');
    }
    
    /**
     * Display terms of service page.
     */
    public function terms()
    {
        return $this->show('terms');
    }
    
    /**
     * Display FAQ page.
     */
    public function faq()
    {
        return $this->show('faq');
    }
    
    /**
     * Display offers page.
     */
    public function offers()
    {
        return $this->show('offers');
    }
}
