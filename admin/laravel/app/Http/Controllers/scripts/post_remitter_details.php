<?php

// This program will post remitter details
// Remitter details remain in the Queue/DB until the next post, gets are non-destructive
// Posts will delete previous records and insert new record
// The program will log every transaction got to logs/post_remitter_details_log.csv

require '../vendor/autoload.php';
require '../models/common_model.php';
require '../models/remitter_model.php';

//Start with the Guzzle client set up.
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

define("OPENSSL_PUB_KEY","1");
define("OPENSSL_PVT_KEY","2");

/* Define arrays of values to pick from */
$rmtr_ids= ['516712','443444','475246','164831','505113','642247','506863','922196','760613','594629',505113];

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

$rmtr_legal_names['164831']='Tanyx Express SEL';
$rmtr_legal_names['443444']='Eiboo Money Txfr. P. ltd.';
$rmtr_legal_names['475246']='Quadel Wires S.A.R.L.';
$rmtr_legal_names['505113']='Zoomia Dinero SL';
$rmtr_legal_names['506863']='Yozu Bank Corp.';
$rmtr_legal_names['516712']='Agible Wire Transfers Inc.';
$rmtr_legal_names['594629']='Bangla Bank Ltd.';
$rmtr_legal_names['642247']='Rupaisa Lenders Pvt. Ltd.';
$rmtr_legal_names['760613']='Banco Pico Mesa';
$rmtr_legal_names['922196']='Rhyloo Money Pay';

$rmtr_trading_names['164831']='Tanyx';
$rmtr_trading_names['443444']='Eiboo';
$rmtr_trading_names['475246']='Quadel';
$rmtr_trading_names['505113']='Zoomia';
$rmtr_trading_names['506863']='Yozu';
$rmtr_trading_names['516712']='Agible';
$rmtr_trading_names['594629']='Bangla Bank';
$rmtr_trading_names['642247']='Rupaisa';
$rmtr_trading_names['760613']='Pico Mesa';
$rmtr_trading_names['922196']='Rhyloo';

$rmtr_services['164831']= 1;
$rmtr_services['443444']= 1;
$rmtr_services['475246']= 1;
$rmtr_services['505113']= 3;
$rmtr_services['506863']= 2;
$rmtr_services['516712']= 1;
$rmtr_services['594629']= 2;
$rmtr_services['642247']= 2;
$rmtr_services['760613']= 2;
$rmtr_services['922196']= 2;

$rmtr_product_codes['164831']= ['CCP','CAD','CMO'];
$rmtr_product_codes['443444']= ['CCP','CAD','CMO'];
$rmtr_product_codes['475246']= ['CCP','CAD','CMO'];
$rmtr_product_codes['505113']= ['CCP','CAD','CMO'];
$rmtr_product_codes['506863']= ['CCP','CAD','CMO'];
$rmtr_product_codes['516712']= ['CCP','CAD','CMO'];
$rmtr_product_codes['594629']= ['CCP','CAD','CMO'];
$rmtr_product_codes['642247']= ['CCP','CAD','CMO'];
$rmtr_product_codes['760613']= ['CCP','CAD','CMO'];
$rmtr_product_codes['922196']= ['CCP','CAD','CMO'];

$rmtr_countries['164831']='ITA';
$rmtr_countries['443444']='GBR';
$rmtr_countries['475246']='FRA';
$rmtr_countries['505113']='ESP';
$rmtr_countries['506863']='CHN';
$rmtr_countries['516712']='USA';
$rmtr_countries['594629']='BGD';
$rmtr_countries['642247']='IND';
$rmtr_countries['760613']='MEX';
$rmtr_countries['922196']='PHL';

$rmtr_currencies['164831']=['EUR','USD'];
$rmtr_currencies['443444']=['GBP','EUR','USD'];
$rmtr_currencies['475246']=['EUR'];
$rmtr_currencies['505113']=['EUR'];
$rmtr_currencies['506863']=['CNY'];
$rmtr_currencies['516712']=['USD'];
$rmtr_currencies['594629']=['BDT'];
$rmtr_currencies['642247']=['INR'];
$rmtr_currencies['760613']=['MXP'];
$rmtr_currencies['922196']=['PHP'];

//$product_codes = ['CCP','CCD','CAD','CBP','CMO','ACP','ACD','AAD','ABP','AMO','DCP','DCD','DAD','DBP','DMO'];

$message_type = 'NEW_RMT'; //Remitter information

//In an initial pass create a file with the header row
$filename = '../logs/'."post_remitter_details_log.csv";
//Write the headers
$header_string = "UUID,"."Posted On,"."Remitter\n";
file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);

$remitterdetails_sent = 0;

//Post details for each remitter
foreach($rmtr_ids as $rmtr_id){
    $rmtr_api_key = $rmtr_api_keys[$rmtr_id];

    /*** Remitter ***/
    $remitter = new Remitter();

    /*** RemitterMetadata ***/
    $remitter->metadata =  new Metadata();

    //Assign UUIDS later as it needs an API call
    $remitter->metadata->from_rmtr_id = $rmtr_id;
    $remitter->metadata->to_rmtr_id = "";
    $remitter->metadata->message_type = $message_type;
    $remitter->metadata->posted_on = time();

    /*** RemitterData ***/
    $remitter->data =  new RemitterData();
    
    $remitter->data->rmtr_id = $rmtr_id;
    $remitter->data->legal_name = $rmtr_legal_names[$rmtr_id];
    $remitter->data->trading_name = $rmtr_trading_names[$rmtr_id];
    $remitter->data->services = $rmtr_services[$rmtr_id];

    $remitter->data->products = array();
        foreach($rmtr_product_codes[$rmtr_id] as $rmtr_product){
            $product = new Product();
                $product->code = $rmtr_product;
                $product->desc = $rmtr_product;
        
            array_push($remitter->data->products, $product);
        }

    $remitter->data->country = $rmtr_countries[$rmtr_id];

    $remitter->data->currencies = array();
        foreach($rmtr_currencies[$rmtr_id] as $rmtr_currency){
            $currency = new Currency();
                $currency->currency = $rmtr_currency;
        
            array_push($remitter->data->currencies, $currency);
        }

    $remitter->data->locations = array();
        $location = new Location();
                $location->name = $rmtr_trading_names[$rmtr_id]."-Location-".mt_rand(1,1000);
                $location->address = new Address();
                $location->operating_hours = array();
                    $operating_hours = new OperatingHours();
                        $operating_hours->day_of_week = "Monday";
                        $operating_hours->start_time = "0900";
                        $operating_hours->end_time = "2100";
                    array_push($location->operating_hours, $operating_hours);    
                $location->email = $rmtr_trading_names[$rmtr_id]."@gmail.com";
                $location->phone = '9999999999';
        
        array_push($remitter->data->locations, $location);

        /*
         * Post remitter details to API
         */

        //First call the login method with the remitter id and api key in the body to get the JWT
        $headers = [];
        $params = ['remitter_id' => $rmtr_id,
                   'api_key' => $rmtr_api_key 
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
            'x-key' => $rmtr_id, //pass requesting remitter id 
            //Pass the JWT received from the login call
            'x-access-token' => $jwt
        ];

        //Get a UUID
        $uri = 'http://api.remitbroker.com/v1/uuid';
        $response = $client->get($uri, ['headers' => $headers]);
        $uuid = $response->getBody();
        // Set the UUID in the remitter metadata
        $remitter->metadata->uuid = (string)$uuid;

        //JSON encode the remitter to post to API
        $json_remitter = json_encode($remitter);

        $uri = 'http://api.remitbroker.com/v1/remitters/';
        //content-type is automagically set to json and content is formatted to json
        //so to 'json' => pass any object that can be passed to json_encode
        //if you pass json it will be double encoded, evidenced by the \ in the output
        $response = $client->post($uri, ['headers' => $headers,
                                          'body' => $json_remitter,
                                          //'json' => $remitter
                                  ]);
         
        $status_code = $response->getStatusCode();

        if($status_code = 200){
            $filename = "../logs/post_remitter_details_log.csv";

            /* Log the remitter metadata to file */
            $data_string = $remitter->metadata->uuid.","
            .$remitter->metadata->posted_on.","
            .$remitter->metadata->from_rmtr_id.","
            .$remitter->metadata->to_rmtr_id.","
            .$remitter->metadata->message_type.","
            ."\n";

            file_put_contents($filename, $data_string, FILE_APPEND | LOCK_EX);
            //Increment sent transactions count
            $remitterdetails_sent++;
            echo ".";
         }else{ // not ok
            $reason_phrase = $response->getReasonPhrase();
            echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
         }
} //end for loop

echo "\nNEW requests posted: ".$remitterdetails_sent."\n";
?>
