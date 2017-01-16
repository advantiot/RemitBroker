<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

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
        Log::info($remitter->remitter_id);
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

        // Since we do not want to pass the api_keey in sesssion, retrieve it here from DB
        $results = DB::table('remitters')->
                        select('api_key')->
                        where([
                        ['remitter_id', '=', $remitter->remitter_id],
                        ['status', '=', 1] //status = Active
                        ])->first();

        $api_key = $results->api_key;
        Log::info($api_key);

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

        //return view('dashboard', ['token' => $response->getBody()]);
        
        // Pass token received on login and create a new client, with header
        $headers = ['x-key' => $remitter->remitter_id,
                    'x-access-token' => $token];

        $client_header = new Client($base_uri, ['headers' => $headers]);
        //
        //Get TxnRequest counts
        $response = $client->get('v1/analytics/senttxnrequestcounts', [
                        'headers' => $headers
                    ]); 

        //$txn_counts = $response->getBody();;
        $txnrequests_sent_counts_raw = json_decode($response->getBody(), true);
        //Log::info($txnrequests_sent_counts_raw);

        //Get TxnResponse counts
        $response = $client->get('v1/analytics/rcvdtxnresponsecounts', [
                        'headers' => $headers
                    ]); 

        $txnresponses_rcvd_counts_raw = json_decode($response->getBody(), true);
        //Log::info($txnresponses_rcvd_counts_raw);
        
        // Iterate through the API response and prepare an array to send to view
        // The same array will hold both the txnrequests and txnresponses counts
        // indexed by the remitter receiving the request
        $request_response_counts = array();          
        //Total counts
        $total_req_new = 0;
        $total_req_mod = 0;
        $total_req_can = 0;

        // There will be one row for each remitter and status
        foreach($txnrequests_sent_counts_raw as $txn_sent_count){
            //Get the receiver remitter id
            $to_rmtr_id = $txn_sent_count['_id']['to_rmtr_id'];
            //
            // Fetch the remitter name from the DB 
            $results = DB::table('remitters')->
                        select('name')->where([['remitter_id', '=', $to_rmtr_id]])->first();
            //Using the receiver remitter id as the first dimension key and add a second dimensional value for name
            $request_response_counts[$txn_sent_count['_id']['to_rmtr_id']]['name'] = $results->name;

            if($txn_sent_count['_id']['message_type'] == 'REQ_NEW'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_sent_count['_id']['to_rmtr_id']]['req_new'] = $txn_sent_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_req_new += $txn_sent_count['count'];
            }
            if($txn_sent_count['_id']['message_type'] == 'REQ_MOD'){
                //add a second dimensional value for modify count
                $request_response_counts[$txn_sent_count['_id']['to_rmtr_id']]['req_mod'] = $txn_sent_count['count'];
                $total_req_mod += $txn_sent_count['count'];
            }
            if($txn_sent_count['_id']['message_type'] == 'REQ_CAN'){
                //add a second dimensional value for cancel count
                $request_response_counts[$txn_sent_count['_id']['to_rmtr_id']]['req_can'] = $txn_sent_count['count'];
                $total_req_can += $txn_sent_count['count'];
            }
            if($txn_sent_count['_id']['message_type'] == 'CNF_PD'){
                //add a second dimensional value for cancel count
                $request_response_counts[$txn_sent_count['_id']['to_rmtr_id']]['cnf_pd'] = $txn_sent_count['count'];
                $total_cnf_pd += $txn_sent_count['count'];
            }
        } 
        //Log::info($request_response_counts);
        
        // Iterate through the API response and prepare an array to send to view
        $txn_rcvd_counts = array();          
        //Total counts
        $total_ack_req = 0;
        $total_rej_req = 0;
        $total_cnf_pd = 0;
        $total_cnf_can = 0;

        // There will be one row for each remitter and status
        foreach($txnresponses_rcvd_counts_raw as $txn_rcvd_count){
            //Get the sender remitter id
            $from_rmtr_id = $txn_rcvd_count['_id']['from_rmtr_id'];
            //
            // Fetch the remitter name from the DB 
            $results = DB::table('remitters')->
                        select('name')->where([['remitter_id', '=', $from_rmtr_id]])->first();
            //Using the receiver remitter id as the first dimension key and add a second dimensional value for name
            $txn_rcvd_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['name'] = $results->name;

            if($txn_rcvd_count['_id']['message_type'] == 'ACK_REQ'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['ack_req'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_ack_req += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['message_type'] == 'REJ_REQ'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['rej_req'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_rej_req += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['message_type'] == 'CNF_PD'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['cnf_pd'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_cnf_pd += $txn_rcvd_count['count'];
            }
            if($txn_rcvd_count['_id']['message_type'] == 'CNF_CAN'){
                //Using the receiver remitter id as the first dimension key and add a second dimensional value for new count
                $request_response_counts[$txn_rcvd_count['_id']['from_rmtr_id']]['cnf_can'] = $txn_rcvd_count['count'];
                //Add the individual new counts to a variable for a cumulative new count
                $total_cnf_can += $txn_rcvd_count['count'];
            }
        } 
        
        //Get Fxrates received counts
        $response = $client->get('v1/analytics/rcvdfxratecounts', [
                        'headers' => $headers
                    ]); 

        $fxrates_rcvd_counts_raw = json_decode($response->getBody(), true);
        //$total_fxrate = count($fxrates_rcvd_counts_raw);

        if($fxrates_rcvd_counts_raw){
            $total_fxrate = $fxrates_rcvd_counts_raw[0]['count'];
        }else{
            $total_fxrate = 0;
        }

        //return view('dashboard', $data);
        return view('dashboard')->with('remitter', $remitter)
                                ->with('request_response_counts', $request_response_counts)
                                ->with('total_req_new', $total_req_new)
                                ->with('total_req_mod', $total_req_mod)
                                ->with('total_req_can', $total_req_can)
                                ->with('total_ack_req', $total_ack_req)
                                ->with('total_rej_req', $total_rej_req)
                                ->with('total_cnf_pd', $total_cnf_pd)
                                ->with('total_cnf_can', $total_cnf_can)
                                ->with('total_fxrate', $total_fxrate);
    }
}
