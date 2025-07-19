<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        // Skip location check for this controller
        // This allows direct access to privacy policy without location requirement
    }

    public function staticprivacypolicy()
    {
        return view('static.staticprivacyandpolicy');
    }

    public function deleteaccount()
    {
        return view('static.deleteaccount');
    }

     public function deletedatarequest()
    {
        return view('static.deletedatarequest');
    }

    public function deletedriver()
    {
        return view('static.deletedriveraccount');
    }

    public function qrcode()
    {
        return view('static.qrcode');
    }

}
