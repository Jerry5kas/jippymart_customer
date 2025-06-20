<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CmsController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $route = \Route::currentRouteName();
        if(!isset($_COOKIE['address_name']) && !in_array($route, ['staticprivacypolicy'])) {
    		\Redirect::to('set-location')->send();
      	}
    }

    public function index($slug)
    {
        return view('cms.index',['slug'=>$slug]);
    }

	public function privacypolicy()
    {
        return view('static.privacypolicy');
    }

	public function termsofuse()
    {
        return view('static.termsofuse');
    }

	public function deliveryofsupport()
    {
        return view('static.deliveryofsupport');
    }

    public function staticprivacypolicy()
    {
        return view('static.staticprivacyandpolicy');
    }
}
