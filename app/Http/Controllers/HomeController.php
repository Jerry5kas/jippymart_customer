<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SeoPage;
use App\Models\SeoSetting;
use App\Traits\SeoTrait;

class HomeController extends Controller
{
    use SeoTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $route = \Route::currentRouteName();
        if(!isset($_COOKIE['address_name']) && $route != "set-location"){
    		\Redirect::to('set-location')->send();
      	}
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get SEO data for homepage using SeoTrait
        $seoData = $this->getSeoData('home', [
            'title' => 'JippyMart - Your One-Stop Destination for Groceries & Daily Essentials',
            'description' => 'Get fresh groceries, medicines, and daily essentials delivered to your doorstep. Fast delivery, quality products, and great prices at JippyMart.'
        ]);
        
        // Pass SEO data to your existing home view
        return view('home', compact('seoData'));
    }
    public function setLocation()
    {
    	return view('layer');
    }
    public function storeFirebaseService(Request $request){
		if(!empty($request->serviceJson) && !Storage::disk('local')->has('firebase/credentials.json')){
			Storage::disk('local')->put('firebase/credentials.json',file_get_contents(base64_decode($request->serviceJson)));
		}
	}
}
