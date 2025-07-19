<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
      public function __construct()
    {
    	if(!isset($_COOKIE['address_name'])) {
    		\Redirect::to('set-location')->send();
		}
		 
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Debug: Check user authentication
        if (Auth::check()) {
            $user = Auth::user();
            $user_email = $user->email;
            $user_uuid = VendorUsers::select('uuid')->where('email', $user_email)->first();
            
            // Log debugging information
            \Log::info('OrderController - User authenticated', [
                'user_id' => $user->id,
                'user_email' => $user_email,
                'user_uuid' => $user_uuid ? $user_uuid->uuid : 'NOT_FOUND'
            ]);
            
            // Pass user information to view for debugging
            return view('my_order.my_order', [
                'debug_user_id' => $user->id,
                'debug_user_email' => $user_email,
                'debug_user_uuid' => $user_uuid ? $user_uuid->uuid : 'NOT_FOUND'
            ]);
        } else {
            \Log::warning('OrderController - User not authenticated');
            return redirect()->route('login');
        }
    }

    public function completedOrders()
    {
        return view('my_order.completed_order');
    }

    public function myDinein()
    {
        return view('my_dinein.my_dinein');
    }

    public function dinein()
    {
        return view('my_dinein.dinein');
    }
    
    public function pendingOrder()
    {
        return view('my_order.pending_order');
    }

    public function cancelledOrder()
    {
        return view('my_order.cancelled_order');
    }

     
      public function edit($id)
    {   
        return view('my_order.edit',['id'=>$id]);
    }   

    public function addCartNote(Request $request)
    {   
        $req=$request->all();
        $addnote=$req['addnote'];
        $cart = Session::get('cart', []);
        $cart['order-note']=$addnote;
        Session::put('cart', $cart);
        Session::save();
        echo json_encode(array('success' =>true,));exit;
    }   


}
