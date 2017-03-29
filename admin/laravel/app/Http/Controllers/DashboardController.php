<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

require 'CommonFunctions.php';

class DashboardController extends Controller
{
    //
    // Constructor
    // 
    public function __construct(){
        $this->middleware('auth');
    } 

    //
    // Function to display Dashboard page
    // 
    public function index(Request $request){
        //Gets the data set in session by the login process into an array
        //$data = $request->session()->get('remitter');
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        //
        // Get additional data from database for display on Dashboard
        // Since transactions are on MongoDB, fetch transaction satistics using the nodejs API
        // 1. # Transactions Pending Payout
        // 2. # Transactions Pending Ack
        // 3. # Number of Pending Cancellation Requests
        // 4. # Number of Pending Amend Requests
        // 5. # FX Rate Updates
        // 6. # Price Updates
        // 7. Notifications
        //

        /* Since Dashbaord shows both count sumamries need to call twice, once for outbound and once for inbound */
        $outbound_value_arrays = $this->getTxnCounts($remitter, 'outbound');
        $inbound_value_arrays = $this->getTxnCounts($remitter, 'inbound');

        return view('dashboard')->with('remitter', $remitter)
                                ->with('outbound_total_req_new', $outbound_value_arrays['total_req_new'])
                                ->with('outbound_total_req_mod', $outbound_value_arrays['total_req_mod'])
                                ->with('outbound_total_req_can', $outbound_value_arrays['total_req_can'])
                                ->with('outbound_total_ack_req', $outbound_value_arrays['total_ack_req'])
                                ->with('outbound_total_rej_req', $outbound_value_arrays['total_rej_req'])
                                ->with('outbound_total_cnf_pd', $outbound_value_arrays['total_cnf_pd'])
                                ->with('outbound_total_cnf_can', $outbound_value_arrays['total_cnf_can'])
                                ->with('outbound_total_fxrate', $outbound_value_arrays['total_fxrate_count'])
                                ->with('inbound_total_req_new', $inbound_value_arrays['total_req_new'])
                                ->with('inbound_total_req_mod', $inbound_value_arrays['total_req_mod'])
                                ->with('inbound_total_req_can', $inbound_value_arrays['total_req_can'])
                                ->with('inbound_total_ack_req', $inbound_value_arrays['total_ack_req'])
                                ->with('inbound_total_rej_req', $inbound_value_arrays['total_rej_req'])
                                ->with('inbound_total_cnf_pd', $inbound_value_arrays['total_cnf_pd'])
                                ->with('inbound_total_cnf_can', $inbound_value_arrays['total_cnf_can'])
                                ->with('inbound_total_fxrate', $inbound_value_arrays['total_fxrate_count']);
    }

    //
    // Function to display Outbound tranactions view
    //
    public function showOutbound(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object

        $value_arrays = $this->getTxnCounts($remitter, 'outbound');

        return view('dashboard_outbound')->with('remitter', $remitter)
                                ->with('request_response_counts', $value_arrays['request_response_counts'])
                                ->with('total_req_new', $value_arrays['total_req_new'])
                                ->with('total_req_mod', $value_arrays['total_req_mod'])
                                ->with('total_req_can', $value_arrays['total_req_can'])
                                ->with('total_ack_req', $value_arrays['total_ack_req'])
                                ->with('total_rej_req', $value_arrays['total_rej_req'])
                                ->with('total_cnf_pd', $value_arrays['total_cnf_pd'])
                                ->with('total_cnf_can', $value_arrays['total_cnf_can'])
                                ->with('total_fxrate', $value_arrays['total_fxrate_count']);
    }

    //
    // Function to display Inbound tranactions view
    //
    public function showInbound(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object

        $value_arrays = $this->getTxnCounts($remitter, 'inbound');

        return view('dashboard_inbound')->with('remitter', $remitter)
                                ->with('request_response_counts', $value_arrays['request_response_counts'])
                                ->with('total_req_new', $value_arrays['total_req_new'])
                                ->with('total_req_mod', $value_arrays['total_req_mod'])
                                ->with('total_req_can', $value_arrays['total_req_can'])
                                ->with('total_ack_req', $value_arrays['total_ack_req'])
                                ->with('total_rej_req', $value_arrays['total_rej_req'])
                                ->with('total_cnf_pd', $value_arrays['total_cnf_pd'])
                                ->with('total_cnf_can', $value_arrays['total_cnf_can'])
                                ->with('total_fxrate', $value_arrays['total_fxrate_count']);
    }

    //
    // Function to peform calculations separated from view functions for reusability across summary and detail views
    // Accepts remitter_id and direction ('outbound' or 'inbound' as parameters)
    //
    function getTxnCounts($remitter, $direction){

        /*
         * Common tasks for both directions
         */

        //Call from CommonFunctions
        $api_key = getAPIKey($remitter->remitter_id);

        // Parameters to create a client
        $base_uri =  ['base_uri' => 'http://api.remitbroker.com/'];
        $client = new Client($base_uri); //No headers required for login
        //$client = new Client(['base_uri' => 'http://api.remitbroker.com/']);

        //Get token and store it TODO
        $response = $client->post('login', [
                        'form_params' => [
                            'remitter_id' => $remitter->remitter_id, //as logged in from session
                            'api_key' => $api_key
                        ]
                    ]); 
   
        $token = $response->getBody();

        // Pass token received on login and create a new client, with header
        $headers = ['x-key' => $remitter->remitter_id,
                    'x-access-token' => $token];

        $client_header = new Client($base_uri, ['headers' => $headers]);

        /*
         * Invoke the outbound or inbound analytics API methods depending on $direction
         */
        
        if($direction == 'outbound'){
            //Get Outbound TxnPost counts
            $response = $client->get('v1/analytics/senttxnpostcounts', [
                            'headers' => $headers
                        ]); 

            $txnposts_rawcounts = json_decode($response->getBody(), true);

            //Get TxnResponse counts
            $response = $client->get('v1/analytics/rcvdtxnresponsecounts', [
                            'headers' => $headers
                        ]); 

            $txnresponses_rawcounts = json_decode($response->getBody(), true);

            //Get Fxrates received counts
            $response = $client->get('v1/analytics/rcvdfxratecounts', [
                        'headers' => $headers
                        ]); 

            $fxrates_rawcounts= json_decode($response->getBody(), true);
        }
        elseif($direction == 'inbound'){
            //Get Outbound TxnPost counts
            $response = $client->get('v1/analytics/rcvdtxnpostcounts', [
                            'headers' => $headers
                        ]); 

            $txnposts_rawcounts = json_decode($response->getBody(), true);

            //Get TxnResponse counts
            $response = $client->get('v1/analytics/senttxnresponsecounts', [
                            'headers' => $headers
                        ]); 

            $txnresponses_rawcounts = json_decode($response->getBody(), true);
            
            //Get Fxrates sent counts
            $response = $client->get('v1/analytics/sentfxratecounts', [
                        'headers' => $headers
                        ]); 

            $fxrates_rawcounts = json_decode($response->getBody(), true);
        }
        
        // Iterate through the API response and prepare an array to send to view
        // The same array will hold both the txnposts and txnresponses counts
        // indexed by the remitter receiving the request
        $request_response_counts = array();          
        //Total counts
        $total_req_new = 0;
        $total_req_mod = 0;
        $total_req_can = 0;

        // There will be one row for each remitter and status
        foreach($txnposts_rawcounts as $txnposts_count){
            //Get the remitter id
            if($direction == 'outbound'){
                $rmtr_id = $txnposts_count['_id']['to_rmtr_id'];
            }
            if($direction == 'inbound'){
                $rmtr_id = $txnposts_count['_id']['from_rmtr_id'];
            }
            //
            // Fetch the remitter name from the DB 
            $results = DB::table('remitters')->
                        select('name')->where([['remitter_id', '=', $rmtr_id]])->first();
            //Using the receiver remitter id as the first dimension key and add a second dimensional value for name
            $request_response_counts[$rmtr_id]['name'] = $results->name;

            if($txnposts_count['_id']['txnpost_type'] == 'REQ_NEW'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$rmtr_id]['req_new'] = $txnposts_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_req_new += $txnposts_count['count'];
            }
            if($txnposts_count['_id']['txnpost_type'] == 'REQ_MOD'){
                //add a second dimensional value for modify count
                $request_response_counts[$rmtr_id]['req_mod'] = $txnposts_count['count'];
                $total_req_mod += $txnposts_count['count'];
            }
            if($txnposts_count['_id']['txnpost_type'] == 'REQ_CAN'){
                //add a second dimensional value for cancel count
                $request_response_counts[$rmtr_id]['req_can'] = $txnposts_count['count'];
                $total_req_can += $txnposts_count['count'];
            }
        } 
        
        // Iterate through the API response and prepare an array to send to view
        $txn_rcvd_counts = array();          
        //Total counts
        $total_ack_req = 0;
        $total_rej_req = 0;
        $total_cnf_pd = 0;
        $total_cnf_can = 0;

        // There will be one row for each remitter and status
        foreach($txnresponses_rawcounts as $txn_rcvd_count){
            //Get the sender remitter id
            $from_rmtr_id = $txn_rcvd_count['_id']['from_rmtr_id'];
            //
            // Fetch the remitter name from the DB 
            $results = DB::table('remitters')->
                        select('name')->where([['remitter_id', '=', $from_rmtr_id]])->first();
            //Using the receiver remitter id as the first dimension key and add a second dimensional value for name
            $txn_rcvd_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['name'] = $results->name;

            if($txn_rcvd_count['_id']['txnpost_type'] == 'ACK_REQ'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['ack_req'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_ack_req += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['txnpost_type'] == 'REJ_REQ'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['rej_req'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_rej_req += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['txnpost_type'] == 'CNF_PD'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['cnf_pd'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_cnf_pd += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['txnpost_type'] == 'CNF_CAN'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['cnf_can'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_cnf_can += $txn_rcvd_count['count'];
            }
        } 
        
        //Get the fxrates count from the API responses already called above
        if($fxrates_rawcounts){
            $total_fxrate_count = $fxrates_rawcounts[0]['count'];
        }else{
            $total_fxrate_count = 0;
        }

        //Create an array of arrays to return to the main function which will then pass individual arrays to the view
        $value_arrays = [];
        
        $value_arrays['request_response_counts'] = $request_response_counts;
        $value_arrays['total_req_new'] = $total_req_new;
        $value_arrays['total_req_mod'] = $total_req_mod;
        $value_arrays['total_req_can'] = $total_req_can;
        $value_arrays['total_ack_req'] = $total_ack_req;
        $value_arrays['total_rej_req'] = $total_rej_req;
        $value_arrays['total_cnf_pd'] = $total_cnf_pd;
        $value_arrays['total_cnf_can'] = $total_cnf_can;
        $value_arrays['total_fxrate_count'] = $total_fxrate_count;

        return $value_arrays;
    } 
}
