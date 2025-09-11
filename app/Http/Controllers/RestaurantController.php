<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;
use App\Helpers\UrlHelper;
use App\Models\SeoPage;
use App\Models\SeoSetting;

class RestaurantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Removed global cookie check to allow public access to restaurant pages
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Get SEO data for restaurants page
        $seoData = SeoPage::getForPage('restaurants');
        $globalSettings = SeoSetting::getGlobalSettings();
        
        return view('restaurant.restaurant', [
            'cart' => $cart,
            'pageKey' => 'restaurants',
            'seoData' => $seoData,
            'globalSettings' => $globalSettings,
        ]);
    }

    public function show($id, $restaurantSlug, $zoneSlug)
    {
        // In a real app, fetch restaurant from DB/Firestore using $id
        // Optionally, check if slugs match and redirect to canonical URL if not
        
        // Get SEO data for restaurant page
        $seoData = SeoPage::getForPage('restaurant');
        $globalSettings = SeoSetting::getGlobalSettings();
        
        // Dynamic SEO data for this specific restaurant
        $dynamicTitle = ucwords(str_replace('-', ' ', $restaurantSlug)) . ' - Restaurant - JippyMart';
        $dynamicDescription = 'Order food from ' . ucwords(str_replace('-', ' ', $restaurantSlug)) . ' restaurant. Fast delivery, fresh food, and great prices at JippyMart.';
        $dynamicImage = '/images/restaurants/' . $restaurantSlug . '.jpg';
        
        return view('restaurant.restaurant', [
            'restaurantId' => $id,
            'restaurantSlug' => $restaurantSlug,
            'zoneSlug' => $zoneSlug,
            'pageKey' => 'restaurant',
            'seoData' => $seoData,
            'globalSettings' => $globalSettings,
            'dynamicTitle' => $dynamicTitle,
            'dynamicDescription' => $dynamicDescription,
            'dynamicImage' => $dynamicImage,
        ]);
    }
    public function categoryList()
    {
        // Get SEO data for categories page
        $seoData = SeoPage::getForPage('categories');
        $globalSettings = SeoSetting::getGlobalSettings();
        
        return view('restaurant.categorylist', [
            'pageKey' => 'categories',
            'seoData' => $seoData,
            'globalSettings' => $globalSettings,
        ]);
    }

    public function categoryDetail($id)
    {
        return view('restaurant.list',['id'=>$id]);
    }

    public function sendnotification(Request $request)
    {
        if(Storage::disk('local')->has('firebase/credentials.json')){

            $client= new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];

            $fcm_token = $request->fcm;

            if(!empty($access_token) && !empty($fcm_token)){

                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';

                $data = [
                    'message' => [
                        'notification' => [
                            'title' => $request->subject,
                            'body' => $request->message,
                        ],
                        'token' => $fcm_token,
                    ],
                ];

                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result=json_decode($result);

                $response = array();
                $response['success'] = true;
                $response['message'] = 'Notification successfully sent.';
                $response['result'] = $result;

            }else{
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing sender id or token to send notification.';
            }

        }else{
            $response = array();
            $response['success'] = false;
            $response['message'] = 'Firebase credentials file not found.';
        }

        return response()->json($response);
    }
}

