<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

class SettingsController extends Controller
{
    //
    // Function to display Settings and Preferences page
    // 
    public function index(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        Log::info($remitter->remitter_id);
        //
        // Get data from the MySQL database for display on Analytics page

        return view('settings')->with('remitter', $remitter);
    }
}
