<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

//require 'vendor/autoload.php';
require 'CommonFunctions.php';
require 'scripts/1_post_new_txnposts.php';
//require 'models/common_model.php';
//require 'models/txnpost_model.php';

class TestEnvController extends Controller
{
    /*
     * Function to display test environment post requests view
     */ 
    public function showPostTxnPosts(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object

        //Post is only allowed for remitters with send service type
        if(!($remitter->service_type & 1)){
            return view('testenv_posttxnposts')->with('remitter', $remitter)
                                                  ->with('partners', []);
        }

        $where_conditions = [
                                ['partners.remitter_id', '=', $remitter->remitter_id],
                                ['remitters.service_type', '&', 2] //only payout partners
                            ];

        $partners = DB::table('partners')
                        ->join('remitters', 'partners.partner_id', '=', 'remitters.remitter_id')
                        ->select('partners.*','remitters.name','remitters.country_code','remitters.service_type')
                        ->where($where_conditions)
                        ->get();


        foreach($partners as $key => &$partner){
            $reverse_mapping = DB::table('partners')
                                   ->select('partners.*')
                                   ->where([
                                            ['remitter_id', '=', $partner->partner_id],
                                            ['partner_id', '=', $partner->remitter_id],
                                            ['status', '=', '1'],
                                            ])
                                    ->first();

            //If the reverse mapping is not found, do not show in the list of pay partners to post txnposts to
            if(!$reverse_mapping){
                unset($partners[$key]);
            }
        }
        unset($partner);// break the reference with the last element

        //Get all transactions of the selected type posted to but not processed by the selected remitter
        //TODO: No method in API for this, create one for testing only?
        //All preliminary steps for API access need to be completed

        return view('testenv_posttxnposts')->with('remitter', $remitter)
                                              ->with('partners', $partners);
    }

    /* 
     * FUNCTION:Post Transaction Requests to a Remitter
     * PARAMETERS (IN REQUEST): Remitter Id, Transaction Type, Number of Transactions to post
     */

    public function postTxnPosts(Request $request){
        //Gets the data set in session by the login process into an array, if no data is set, force logout
        if ($request->session()->has('remitter')) {
            $sndr_remitter = $request->session()->get('remitter');
        }else{
            //Force sign out
            Auth::logout();
            return redirect()->action('LoginController@index');
        }

        $token = getToken($sndr_remitter->remitter_id);

        //Process depending on which submit button is clicked
        if($request->submit == "post"){
            //POST transactions of the selected type to the selected remitter
            //All preliminary steps for API access need to be completed
            // Pass token received on login and create a new client, with header
            $headers = ['x-key' => $sndr_remitter->remitter_id,
                        'x-access-token' => $token];

            // Get Partner object from DB based on the selected partner_id
            $where_conditions = [
                                    ['remitters.remitter_id', '=', $request->to_remitter_id]
                                ];

            $to_remitter = DB::table('remitters')
                            ->join('country_currency', 'remitters.country_code', '=', 'country_currency.country_code')
                            ->select('remitters.*','country_currency.*')
                            ->where($where_conditions)
                            ->first();

            //Post TxnPosts
            for($i = 0; $i < $request->num_txn; $i++){
                $retval_array = postTxnPost($sndr_remitter, $to_remitter, $request->txnpost_type, $headers);

                //retval_array has the status_code, UUID and posted_on timestamp. 
                //Make an entry into testenv_logs with UUID as key and insert txnpost metadata
                //Txnreponses will also insert origin_uuid to indicate which txnpost the response is for
                DB::table('testenv_logs')->insert([
                                                    'uuid' => $retval_array['uuid'],
                                                    'origin_uuid' => '',
                                                    'from_rmtr_id' => $sndr_remitter->remitter_id,
                                                    'to_rmtr_id' => $to_remitter->remitter_id,
                                                    'type' => $request->txnpost_type,
                                                    'posted_on' => date('Y-m-d H:i:s', $retval_array['posted_on']) //Need to convert from string to timestamp
                                                 ]);

                Log::info($retval_array['posted_on']);
            }

            //To redisplay form with entered data
            $request->flash();

            return redirect('testenv/posttxnposts')->withSuccess($request->num_txn." ".$request->txnpost_type." transactions posted successfully!");

        }else if($request->submit == "delete"){

            //DELETE transactions of the selected type to the selected remitter
            //All preliminary steps for API access need to be completed
            // Pass token received on login and create a new client, with header
            $headers = ['x-key' => $sndr_remitter->remitter_id,
                        'x-access-token' => $token];
            $client = new client();

            $uri = 'http://api.remitbroker.com/v1/transaction/posts/remitter/'.$request->to_remitter_id;
            $response = $client->delete($uri, ['headers' => $headers]);

            $status_code = $response->getStatusCode();

            //TODO: Delete fron testenv_logs table

            //To redisplay form with entered data
            $request->flash();

            return redirect('testenv/posttxnposts')->withSuccess("All unprocessed ".$request->txnpost_type." transactions deleted successfully!");
        }
    }

    /* 
     * FUNCTION:Delete Unprocessed Transaction Requests to a Remitter
     * PARAMETERS (IN REQUEST): To Remitter Id, Transaction Type
     */

    public function deleteTxnPosts(Request $request){
        //Gets the data set in session by the login process into an array, if no data is set, force logout
        if ($request->session()->has('remitter')) {
            $sndr_remitter = $request->session()->get('remitter');
        }else{
            //Force sign out
            Auth::logout();
            return redirect()->action('LoginController@index');
        }

        $token = getToken($sndr_remitter->remitter_id);

        //DELETE transactions of the selected type to the selected remitter
        //All preliminary steps for API access need to be completed
        // Pass token received on login and create a new client, with header
        $headers = ['x-key' => $sndr_remitter->remitter_id,
                    'x-access-token' => $token];
        $client = new client();

        $uri = 'http://api.remitbroker.com/v1/transactions/posts';
        $params = ['to_remitter_id' => $request->$to_remitter_id,
                   'post_type' => $request->$txnpost_type
                  ];
        $response = $client->delete($uri, ['headers' => $headers,
                                           'form_params' => $params,
                                   ]);

        $status_code = $response->getStatusCode();
        
        //TODO: Delete fron testenv_logs table

        return redirect('testenv/posttxnposts')->withSuccess("All unprocessed ".$request->txnpost_type." transactions deleted successfully!");
    }

    //
    // Function to display test environment post responses view which starts by getting txn requests pending a response
    // FIRST: Get all transactions from API/MongoDB. These are deleted on GET and recorded in testenv_logs pending ack
    // The following filters are available:
    // PENDING ACK: Get all txnposts from testenv_logs that have not yet been acknowledged
    // ALL ACKNOWLEDGED: Get all txnposts from testenv_logs that have been acknowledged
    // REQ_NEW: Get all REQ_NEW txnposts from testenv_logs that have been acknowledged
    // REQ_MOD: Get all REQ_MOD txnposts from testenv_logs that have been acknowledged
    // REQ_CAN: Get all REQ_CAN txnposts from testenv_logs that have been acknowledged
    // 
    public function getTxnPosts(Request $request){
        //Gets the data set in session by the login process into an array, if no data is set, force logout
        if ($request->session()->has('remitter')) {
            $remitter = $request->session()->get('remitter');
        }else{
            //Force sign out
            Auth::logout();
            return redirect()->action('LoginController@index');
        }

        //Create empty arrays to pass if no values are found
        $partners = [];
        $txnposts = [];

        //Post responses is only required for remitters with payout service type (service_type = 2 or 3)
        //If not service type payout send empty list
        $payout_bitpos = 2;
        if((intval($remitter->service_type) >> ($payout_bitpos-1)) & 1){

            $where_conditions = [
                                    ['partners.remitter_id', '=', $remitter->remitter_id],
                                    ['remitters.service_type', '&', 1] //only send partners
                                ];

            $partners = DB::table('partners')
                            ->join('remitters', 'partners.partner_id', '=', 'remitters.remitter_id')
                            ->select('partners.*','remitters.name','remitters.country_code','remitters.service_type')
                            ->where($where_conditions)
                            ->get();


            foreach($partners as $key => &$partner){
                $reverse_mapping = DB::table('partners')
                                       ->select('partners.*')
                                       ->where([
                                                ['remitter_id', '=', $partner->partner_id],
                                                ['partner_id', '=', $partner->remitter_id],
                                                ['status', '=', '1'],
                                                ])
                                        ->first();

                if($reverse_mapping){
                    $partner->partner_status = 1;
                }else{
                    $partner->partner_status = 0;
                }
            }
            unset($partner);// break the reference with the last element
        }

        // Get txnposts only if a remitter id is specified
        if(!empty($request->from_remitter_id) && $request->from_remitter_id != '-1'){
            
            //------ START SECTION 1 ------
            
            //FIRST: Get all transactions from API/MongoDB. These are deleted on GET and recorded in testenv_logs pending ack
            //All preliminary steps for API access need to be completed

            $token = getToken($remitter->remitter_id);

            // Pass token received on login and create a new client, with header
            $headers = ['x-key' => $remitter->remitter_id,
                        'x-access-token' => $token];
            $client = new client();

            $uri = 'http://api.remitbroker.com/v1/transactions/posts/remitter/'.$request->from_remitter_id;
            $response = $client->get($uri, ['headers' => $headers]);

            $txnposts = json_decode($response->getBody(), true);
            $status_code = $response->getStatusCode();

            //For each txnpost got from API, update the downloaded field against the UUID in testenv_logs
            foreach($txnposts as $key => &$txnpost){
                $downloaded = DB::table('testenv_logs')
                                       ->where([
                                                ['uuid', '=', $txnpost['metadata']['uuid']],
                                               ])
                                       ->update(['downloaded' => true]);
            }

            //------ END SECTION 1 ------

            //------ START SECTION 2 ------
            if($request->txnpost_type == 'PENDING_ACK'){

                //Get all txnposts which have been downloaded and do not have a corresponding ACK_REQ for that UUID
                $where_conditions = [
                                        ['from_rmtr_id', '=', $request->from_remitter_id],
                                        ['to_rmtr_id', '=', $remitter->remitter_id],
                                        ['downloaded', '=', true],
                                    ];

                $txnposts = DB::table('testenv_logs')
                                       ->select('*')
                                       ->where($where_conditions)
                                       ->get();

                /* 
                 * txnposts from the API returns an array of arrays whereas get() returns an array of objects
                 * if the view iterates through an array of arrays, convert the get() output to an array using the command line below
                 * or have the view iterate with array of objects notation
                 */
                 //$txnposts = collect($txnposts)->map(function($x){ return (array) $x; })->toArray();
            }
            
            //------ END SECTION 2 ------
            
         //------ START SECTION TO REVIEW ------
            
            //if($request->txnpost_type == 'PENDING_ACK'){
            //}
            
            //else{
            //    $uri = 'http://api.remitbroker.com/v1/transactions/posts/remitter/'.$request->from_remitter_id.'/type/'.$request->txnpost_type;
            //}


            /* The filtering below is not required siince txnposts are deleted from MongoDB on get*/
            /* So all transactions got from API/MongoDB are to be acknowledged */
            //After getting all transactions, if filter is PENDING_ACK show the ones that have not been acknowledged
            //Those show up in the PENDING_ACK search and not in any other
            //Else show the ones that have the required type and have been acknowledged
            /*
            foreach($txnposts as $key => &$txnpost){
                $acknowledged = DB::table('testenv_logs')
                                       ->select('*')
                                       ->where([
                                                ['uuid', '=', $txnpost['metadata']['uuid']],
                                                ['ackreq_timestamp', '!=', 'null'],
                                               ])
                                        ->first();

                if($request->txnpost_type == 'PENDING_ACK' && $acknowledged){
                    unset($txnposts[$key]);
                }elseif($request->txnpost_type != 'PENDING_ACK' && !$acknowledged){
                    unset($txnposts[$key]);
                }
            }
            unset($txnpost);// break the reference with the last element
             */
         //------ END SECTION TO REVIEW ------
        } //End If remitter_id provided 

        //------ START SECTION TO REVIEW ------
        //When getting txnposts PENDING_ACK, also write to file and proompt user to download
        //This is for verification of file content before confirming or rejecting
        //In the real world these would be saved to the remitter system datastore
        
        //if($request->txnpost_type == 'PENDING_ACK'){

            //TODO: Hard coding file path now, need to look into file and folder persissions such that Laravel has rights to write into somiewhere
            //$handle = fopen('/home/ubuntu/projects/RemitBroker/admin/laravel/storage/logs/testfile.csv', 'w');
            //foreach($txnposts as $key => $txnpost){
            //    fputs($handle, json_encode($txnpost));
            //}
            //fclose($handle);
            /*
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename('testfile.csv'));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize('/home/ubuntu/projects/RemitBroker/admin/laravel/storage/logs/testfile.csv'));
            readfile('/home/ubuntu/projects/RemitBroker/admin/laravel/storage/logs/testfile.csv');
             */
        //}
        
        //------ END SECTION TO REVIEW ------
        
        //To redisplay form with entered data
        $request->flash();

        //Depending on conditions above value arrays passed may be empty
        return view('testenv_posttxnresponses')->with('remitter', $remitter)
                                               ->with('partners', $partners)
                                               ->with('txnposts', $txnposts);
    }

    //Function to post txn acks and responses
    public function postTxnResponses(Request $request){
        //Gets the data set in session by the login process into an array, if no data is set, force logout
        if ($request->session()->has('remitter')) {
            $sndr_remitter = $request->session()->get('remitter');
        }else{
            //Force sign out
            Auth::logout();
            return redirect()->action('LoginController@index');
        }

        $token = getToken($sndr_remitter->remitter_id);

        //Process depending on which submit button is clicked
        if($request->submit == "ACK_REQ"){
            //Log::info("Selected checkboxes: ".implode(" ",$request->select_txnposts));
            //Update the ACK_REQ column in testenv_logs for selected txnposts
            foreach($request->selected_uuids as $uuid){
                //Txnreponses will also insert origin_uuid to indicate which txnpost the response is for
                DB::table('testenv_logs')->insert([
                    'uuid' => '0',
                    'origin_uuid' => $uuid,
                    'from_rmtr_id' => $sndr_remitter->remitter_id,
                    'to_rmtr_id' => '0',
                    'type' => 'ACK_REQ',
                    'posted_on' => date('Y-m-d H:i:s') //$retval_array['posted_on'])
                ]);
            }
            
            //To redisplay form with entered data
            $request->flash();

            return redirect('testenv/gettxnposts')->withSuccess("Transactions acknowledged successfully!");
        }
        //TODO: For other response types
    }
}
