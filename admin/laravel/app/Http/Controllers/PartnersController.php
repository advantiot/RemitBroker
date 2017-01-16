<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

class PartnersController extends Controller
{
    //
    // Function to display Partners page
    // 
    public function index(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        Log::info($remitter->remitter_id);
        //
        // Get data from the MySQL database for display on Partners page
        // Since transactions are on MongoDB, fetch transaction satistics using the nodejs API
        // 1. # List of partners with details
        //

        $partners = DB::table('partners')
                        ->join('remitters', 'partners.partner_id', '=', 'remitters.remitter_id')
                        ->select('partners.*','remitters.name') 
                        ->where([
                                ['partners.remitter_id', '=', $remitter->remitter_id]
                                ])
                        ->get();


        return view('partners')->with('remitter', $remitter)
                                ->with('partners', $partners);
    }
}
