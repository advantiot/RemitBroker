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

/*
 * This is a standalone program to get transactions posted for each receiving remitter
 * The program will log all transactions received to logs/gettxns_history.log
 * Meant for bulk testing instead of using the UI
 */

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

//Run this in a loop for each receiving remitter
foreach($rcvr_rmtr_ids as $rcvr_rmtr_id){
    $rcvr_rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

    /*
     * Get transaction from API
     */

    //First call the login method with the remitter id and api key in the body to get the JWT
    $headers = [];
    $params = ['remitter_id' => $rcvr_rmtr_id,
               'api_key' => $rcvr_rmtr_api_key 
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
        'x-key' => $rcvr_rmtr_id, //pass requesting remitter id 
        //Pass the JWT received from the login call
        'x-access-token' => $jwt
    ];

    $uri = 'http://api.remitbroker.com/v1/transactions/requests';
    $response = $client->get($uri, ['headers' => $headers]);
     
    $status_code = $response->getStatusCode();

    if($status_code = 200){
        $body = $response->getBody(); //Received as JSON string
        $txn_array = json_decode($body,true); //Convert to array
        
        // Log the transaction meta data to a csv file
        //TODO: Find a JSON to CSV converter that support nested JSON
        //Write header to a file, one for each request type

        $header_string = "UUID,"."Posted On,"."From Remitter,"."To Remitter,"."Message Type"."Sender Txn num\n";
        $filename = '../logs/'.$rcvr_rmtr_id."_get_new_txnrequests_log.csv";
        file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);
        $filename = '../logs/'.$rcvr_rmtr_id."_get_mod_txnrequests_log.csv";
        file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);
        $filename = '../logs/'.$rcvr_rmtr_id."_get_can_txnrequests_log.csv";
        file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);

        //Write the metadata
        foreach($txn_array as $transaction){
            $data_string = $transaction["metadata"]["uuid"].","
            .date('c', $transaction["metadata"]["posted_on"]).","
            .$transaction["metadata"]["from_rmtr_id"].","
            .$transaction["metadata"]["to_rmtr_id"].","
            .$transaction["metadata"]["message_type"].","
            .$transaction["data"]["sndr_txn_num"].","
            ."\n";
            
            //Write each type to a different file
            if($transaction["metadata"]["message_type"] == "REQ_NEW")
                $filename = '../logs/'.$rcvr_rmtr_id."_get_new_txnrequests_log.csv";
            if($transaction["metadata"]["message_type"] == "REQ_MOD")
                $filename = '../logs/'.$rcvr_rmtr_id."_get_mod_txnrequests_log.csv";
            if($transaction["metadata"]["message_type"] == "REQ_CAN")
                $filename = '../logs/'.$rcvr_rmtr_id."_get_can_txnrequests_log.csv";

            file_put_contents($filename, $data_string, FILE_APPEND | LOCK_EX);
            
            //Post an ack now that the transction request has been received successfully
            //
            postack($transaction["metadata"]["uuid"],$transaction["metadata"]["posted_on"],$transaction["metadata"]["from_rmtr_id"],$transaction["metadata"]["to_rmtr_id"],$transaction["metadata"]["message_type"],$transaction["data"]["sndr_txn_num"]); 

            echo "."; //Just to show progress, maybe show a count
        }
     }else{ //if not OK
        echo "Remitter Id: ".$rcvr_rmtr_id.": ";
        $reason_phrase = $response->getReasonPhrase();
        echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
    }

    echo "Remitter Id: ".$rcvr_rmtr_id."Processing complete!\n";
} //end for loop


//Writing the post ack code as a function make it more readable
//Takes all metadata as parameters and then constructs the txnrespose object and posts it
function postack($request_uuid, $request_posted_on, $request_from_rmtr_id, $request_to_rmtr_id, $request_message_type, $sndr_txn_num){

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
    $txn_ack->response_metadata->message_type = 'ACK_REQ';
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
        .$sndr_txn_num.","
        ."\n";

        file_put_contents($filename, $data_string, FILE_APPEND | LOCK_EX);
    }else{ //if not OK
        echo "Remitter Id: ".$rcvr_rmtr_id.": ";
        $reason_phrase = $response->getReasonPhrase();
        echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
    }
}

?>
