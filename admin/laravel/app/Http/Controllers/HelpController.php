<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

class HelpController extends Controller
{
    //
    // Functions to display Help page
    // 
    public function overview(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        Log::info($remitter->remitter_id);
        //
        return view('help/overview')->with('remitter', $remitter);
    }
}
