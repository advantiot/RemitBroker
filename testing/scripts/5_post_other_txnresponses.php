<?php
//
// This program will get all transactions sent to receivers
// And post an ack if the transaction is successfully recovered
// File execution sequence:4
// The program will log every transaction got to logs/[rcvr_rmtr_id]_get_txnrequests_log.csv
// And will log every ack posted to logs/[rcvr_rmtr_id]_post_ack_txnresponses_log.csv

require '../vendor/autoload.php';
require '../models/common_model.php';
require '../models/txnresponse_model.php';

//Start with the Guzzle client set up.
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

define("OPENSSL_PUB_KEY","1");
define("OPENSSL_PVT_KEY","2");

/* Define arrays of values to pick from */
$sndr_rmtr_ids= ['516712','443444','475246','164831','505113'];
$rcvr_rmtr_ids = ['642247','506863','922196','760613','594629',505113];

$rmtr_api_keys['164831']='b579b5c5-771f-4a50-8d2c-668b17350dea';
$rmtr_api_keys['443444']='3786ea26-014c-4d87-9b79-76b16d80c634';
$rmtr_api_keys['475246']='691365c7-6e94-4709-9c21-759c01ba83cf';
$rmtr_api_keys['505113']='aa8263b5-4437-4136-8a49-128b0d2e20be';
$rmtr_api_keys['506863']='be9b5d06-b0c9-47e8-ba50-66cde4382e9b';
$rmtr_api_keys['516712']='7e7a19ac-1d92-4d9b-9c4d-75ffde9c3bbb';
$rmtr_api_keys['594629']='6ad65576-f29a-4f8e-9846-505e94b1ceea';
$rmtr_api_keys['642247']='121e4e51-9408-4c7d-b1f3-c9d0ca97acc2';
$rmtr_api_keys['760613']='df05d6ad-6a92-432c-96c9-95b8acda89a2';
$rmtr_api_keys['922196']='fa56801c-40dc-4c26-9e9e-4815dd2c4222';

//Array to store sndr_txn_nums of processed NEW TxnRequests (70% will be processed)
//If a MOD request is received for any of these sndr_txn_nums it must get a REJ_REQ, else it will get a CNF_PD
//If a CAN request is received for any of these sndr_txn_nums it must get a REJ_REQ, else it will get a CNF_CAN

$sndr_txn_nums_processed = array();

//First process all the NEW tranasctions and return CNF_PD for 70%
//Read all got TxnRequests from file
//Run this in a loop for each receiving remitter
foreach($rcvr_rmtr_ids as $rcvr_rmtr_id){
    $rcvr_rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

    $filename = '../logs/'.$rcvr_rmtr_id."_get_new_txnrequests_log.csv";

    if(($handle = fopen($filename, "r")) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $uuid = $data[0];
            $posted_on = $data[1];
            $from_rmtr_id = $data[2];
            $to_rmtr_id = $data[3];
            $message_type = $data[4];
            $sndr_txn_num = $data[5];

            //Post a CNF_PD for 7 in 10 NEW requests
            if(mt_rand(1,10) <= 7){
                post_txnresponse($uuid, $posted_on, $from_rmtr_id, $to_rmtr_id, $message_type,"CNF_PD");
                //Add the sndr_txn_num to the processed array
                $sndr_txn_nums_processed[] = $sndr_txn_num;
            }
        }
        fclose($handle);
    }
} //end for loop


//Then process all the MOD tranasctions and return CNF_PD if $sndr_txn_num not in processed array
//else post a REQ_REJ
foreach($rcvr_rmtr_ids as $rcvr_rmtr_id){
    $rcvr_rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

    $filename = '../logs/'.$rcvr_rmtr_id."_get_mod_txnrequests_log.csv";

    if(($handle = fopen($filename, "r")) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $uuid = $data[0];
            $posted_on = $data[1];
            $from_rmtr_id = $data[2];
            $to_rmtr_id = $data[3];
            $message_type = $data[4];
            $sndr_txn_num = $data[5];

            //Post a CNF_PD if not already processed, else post a REJ_REQ
            if(in_array($sndr_txn_num, $sndr_txn_nums_processed)){
                $response_message_type = "REJ_REQ";
            }else{
                $response_message_type = "CNF_PD";
                //Add the sndr_txn_num to the processed array
                $sndr_txn_nums_processed[] = $sndr_txn_num;
            }
            post_txnresponse($uuid, $posted_on, $from_rmtr_id, $to_rmtr_id, $message_type, $response_message_type);
        }
        fclose($handle);
    }
} //end for loop

//Then process all the CAN tranasctions and post CNF_CAN if $sndr_txn_num not in processed array
//else post a REQ_REJ
foreach($rcvr_rmtr_ids as $rcvr_rmtr_id){
    $rcvr_rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

    $filename = '../logs/'.$rcvr_rmtr_id."_get_can_txnrequests_log.csv";

    if(($handle = fopen($filename, "r")) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $uuid = $data[0];
            $posted_on = $data[1];
            $from_rmtr_id = $data[2];
            $to_rmtr_id = $data[3];
            $message_type = $data[4];
            $sndr_txn_num = $data[5];

            //Post a CNF_CAN if not already processed, else post a REJ_REQ
            if(in_array($sndr_txn_num, $sndr_txn_nums_processed)){
                $response_message_type = "REJ_REQ";
            }else{
                $response_message_type = "CNF_CAN";
                //Add the sndr_txn_num to the processed array
                $sndr_txn_nums_processed[] = $sndr_txn_num;
            }
            post_txnresponse($uuid, $posted_on, $from_rmtr_id, $to_rmtr_id, $message_type, $response_message_type);
        }
        fclose($handle);
    }
    echo "Finished processing for Remitter Id: ".$rcvr_rmtr_id."\n";
} //end for loop

//Writing the post txnresponse code as a function make it more readable
//Takes all metadata as parameters and then constructs the txnrespose object and posts it
function post_txnresponse($request_uuid, $request_posted_on, $request_from_rmtr_id, $request_to_rmtr_id, $request_message_type, $message_type){

//TODO: Need to globalize the api_keys array
$rmtr_api_keys['164831']='b579b5c5-771f-4a50-8d2c-668b17350dea';
$rmtr_api_keys['443444']='3786ea26-014c-4d87-9b79-76b16d80c634';
$rmtr_api_keys['475246']='691365c7-6e94-4709-9c21-759c01ba83cf';
$rmtr_api_keys['505113']='aa8263b5-4437-4136-8a49-128b0d2e20be';
$rmtr_api_keys['506863']='be9b5d06-b0c9-47e8-ba50-66cde4382e9b';
$rmtr_api_keys['516712']='7e7a19ac-1d92-4d9b-9c4d-75ffde9c3bbb';
$rmtr_api_keys['594629']='6ad65576-f29a-4f8e-9846-505e94b1ceea';
$rmtr_api_keys['642247']='121e4e51-9408-4c7d-b1f3-c9d0ca97acc2';
$rmtr_api_keys['760613']='df05d6ad-6a92-432c-96c9-95b8acda89a2';
$rmtr_api_keys['922196']='fa56801c-40dc-4c26-9e9e-4815dd2c4222';

    /*** Transaction Ack ***/
    $txn_ack = new TxnResponse();

    //Reconstruct request metadata
    $txn_ack->request_metadata =  new Metadata();

    $txn_ack->request_metadata->uuid = $request_uuid;
    $txn_ack->request_metadata->posted_on = $request_posted_on;
    $txn_ack->request_metadata->from_rmtr_id = $request_from_rmtr_id;
    $txn_ack->request_metadata->to_rmtr_id = $request_to_rmtr_id;
    $txn_ack->request_metadata->message_type = $request_message_type;


    //Construct response metadata
    $txn_ack->response_metadata = new Metadata();

    //Add UUID later
    $txn_ack->response_metadata->from_rmtr_id = $request_to_rmtr_id; //Switch
    $txn_ack->response_metadata->to_rmtr_id = $request_from_rmtr_id;
    $txn_ack->response_metadata->message_type = $message_type;
    $txn_ack->response_metadata->posted_on = time();


    $txn_ack->txn_rcvd_on = time(); 

    /*** Message ***/
    $txn_ack->message = new Message();
        $txn_ack->message->type = "INF";
        $txn_ack->message->body = "Acknowledging receipt only, not yet processed.";

    /*
     * Post transaction to API
     * Request Bin is a cool service that allows you to view the data posted. Good for debugging.
     * $uri = 'http://requestb.in/q3jr32q3';
     */

    //First call the login method with the remitter id and api key in the body to get the JWT
    $headers = [];
    $params = ['remitter_id' => $request_to_rmtr_id,
               'api_key' => $rmtr_api_keys[$request_to_rmtr_id] 
    ];
    //the login method is not versioned
    $uri = 'http://api.remitbroker.com/login';
    $client = new client();
    $response = $client->post($uri, ['headers' => $headers,
                                     'form_params' => $params,
                                    ]);
    $jwt = $response->getBody();


    //Set the header with the JWT
    $headers = [
        'content-type' => 'application/json',
        'x-key' => $request_to_rmtr_id, //pass requesting remitter id 
        //Pass the JWT received from the login call
        'x-access-token' => $jwt
    ];

    //Get a UUID
    $uri = 'http://api.remitbroker.com/v1/uuid';
    $response = $client->get($uri, ['headers' => $headers]);
    $uuid = $response->getBody();
    // Set the UUID in the response metadata
    $txn_ack->response_metadata->uuid = (string)$uuid;

    //JSON encode the transaction to post to API
    $json_txn_ack = json_encode($txn_ack);

    $uri = 'http://api.remitbroker.com/v1/transactions/responses';
    //content-type is automagically set to json and content is formatted to json
    //so to 'json' => pass any object that can be passed to json_encode
    //if you pass json it will be double encoded, evidenced by the \ in the output
    $response = $client->post($uri, ['headers' => $headers,
                                      'body' => $json_txn_ack,
                                      //'json' => $txn_ack
                              ]);
     
    $status_code = $response->getStatusCode();

    if($status_code = 200){
        $filename = '../logs/'.$request_to_rmtr_id."_post_ack_txnresponses_log.csv";

        /* Log the transaction metadata to file */
        $data_string = $txn_ack->response_metadata->uuid.","
        .$txn_ack->response_metadata->posted_on.","
        .$txn_ack->response_metadata->from_rmtr_id.","
        .$txn_ack->response_metadata->to_rmtr_id.","
        .$txn_ack->response_metadata->message_type.","
        ."\n";

        file_put_contents($filename, $data_string, FILE_APPEND | LOCK_EX);

        echo "."; //Just to show progress
    }
    else{ //if not OK 
        $reason_phrase = $response->getReasonPhrase();
        echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
    }
}

?>
