<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
// SEO models removed for performance optimization
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

        // SEO data removed for performance optimization
        $seoData = [
            'title' => ucfirst($page) . ' - JippyMart',
            'description' => 'Learn more about ' . $page . ' at JippyMart. Get groceries, medicines, and daily essentials delivered to your doorstep.'
        ];

        // Prepare data for view
        $data = [
            'pageKey' => $page,
            'seoData' => $seoData,
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

