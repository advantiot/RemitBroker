<?php

// This program will post FX Rates
// FX Rates will remain in the Queue/DB until the next post, gets are non-destructive
// Posts will delete previous records and insert new record
// The program will log every FX Rate posted to logs/post_fxrates_log.csv

require '../vendor/autoload.php';
require '../models/common_model.php';
require '../models/fxrate_model.php';
//require 'libraries/Encryption.php';

//Start with the Guzzle client set up.
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

define("OPENSSL_PUB_KEY","1");
define("OPENSSL_PVT_KEY","2");

/* Define arrays of value to pick from */
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

$rmtr_partner_mappings = [
                        '443444.505113',
                        '443444.506863',
                        '443444.594629',
                        '443444.642247',
                        '443444.760613',
                        '443444.922196',
                        '164831.506863',
                        '164831.922196',
                        '475246.505113',
                        '475246.506863',
                        '475246.594629',
                        '475246.922196',
                        '505113.760613',
                        '516712.505113',
                        '516712.506863',
                        '516712.594629',
                        '516712.642247',
                        '516712.760613',
                        '516712.922196',
                        ];


$from_currencies = ['USD','EUR','GBP'];
$to_currencies = ['EUR','INR','CNY','PHP','MXN','BDT'];

$base_rates = [
	'USD.EUR' =>'0.91',
	'USD.INR' =>'66.81',
	'USD.CNY' =>'6.4',
	'USD.PHP' =>'47.03',
	'USD.MXP' =>'16.7',
	'USD.BDT' =>'75.54',
	'EUR.INR' =>'73.13',
	'EUR.CNY' =>'7.00',
	'EUR.PHP' =>'51.55',
	'EUR.MXP' =>'18.26',
	'EUR.BDT' =>'82.64',
	'GBP.EUR' =>'1.38',
	'GBP.INR' =>'101.13',
	'GBP.CNY' =>'9.68',
	'GBP.PHP' =>'71.18',
	'GBP.MXP' =>'25.28',
	'GBP.BDT' =>'114.38',
	'EUR.EUR' =>'1.00'
	];

$message_type = 'NEW_FXR'; //Remitter information

//In an initial pass create a file with the header row
$filename = '../logs/'."post_fxrates_log.csv";
//Write the headers
$header_string = "UUID,"."Posted On,"."Remitter\n";
file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);
//
$fxrates_posted = 0;

//Post details for each paying out remitter for each currency it trades in
foreach($rcvr_rmtr_ids as $rcvr_rmtr_id){
    $rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

    /*
    //For send agents, generate one beyond max index and then assign 0, which means any agent
    $rand_num = mt_rand(0, count($sndr_rmtr_ids));
    if($rand_num == count($sndr_rmtr_ids))
	    $sndr_rmtr_id = 0;
    else
	    $sndr_rmtr_id= $sndr_rmtr_ids[$rand_num];
    */

    //Temporarily...
    $rand_num = mt_rand(0, count($sndr_rmtr_ids)-1);
	$sndr_rmtr_id= $sndr_rmtr_ids[$rand_num];

    $rmtr_partner_mapping = $sndr_rmtr_id.'.'.$rcvr_rmtr_id;

    //if (in_array($rmtr_partner_mapping, $rmtr_partner_mappings)) {

        /*** Fxrate ***/
        $fxrate = new Fxrate();

        /*** FxrateMetadata ***/
        $fxrate->metadata =  new Metadata();

        //Assign UUIS later as it needs an API call
        $fxrate->metadata->from_rmtr_id = $rcvr_rmtr_id;
        $fxrate->metadata->to_rmtr_id = $rcvr_rmtr_id;
        $fxrate->metadata->message_type = $message_type;
        $fxrate->metadata->posted_on = time();

        /*** FxrateData ***/
        $fxrate->data =  new FxrateData();

        $fxrate->data->sndr_rmtr_id = $sndr_rmtr_id;
        $fxrate->data->rcvr_rmtr_id = $rcvr_rmtr_id;
        $fxrate->data->sndr_country = $rmtr_countries[$sndr_rmtr_id];
        $fxrate->data->sndr_currency = $rmtr_currencies[$sndr_rmtr_id][0];
        $fxrate->data->rcvr_country = $rmtr_countries[$rcvr_rmtr_id];
        $fxrate->data->rcvr_currency = $rmtr_currencies[$rcvr_rmtr_id][0];

        $fxrate->data->product = new Product();
            $fxrate->data->product->code = $rmtr_product_codes[$rcvr_rmtr_id][0];
            $fxrate->data->product->desc = "";

        $currency_pair = $rmtr_currencies[$sndr_rmtr_id][0].".".$rmtr_currencies[$rcvr_rmtr_id][0];

        $fxrate->data->fxrate = $base_rates[$currency_pair] * (1 + (mt_rand(0,10))/100);

        $fxrate->data->positive_margin = mt_rand(0,5)/100;
        $fxrate->data->negative_margin = mt_rand(0,2)/100;

        /* 
         * Code block for encryption/decryption when POSTing/GETing 
         *
         */
        /*
        //Guzzle auto converts to json if data is send as 'json' in the Guzzle client
        //But since we want to encrypt json, we will manually convert and pass as 'body'
        $json_fxrate = json_encode($fxrate);

        //Encrypt using the publisher's private key so it can be read by any consumer who has been given the publisher's public key
        $key_file = "file:///var/www/apiconsole/certs/642247_pub_key.pem";
        $json_fx_rate_base64_encrypted_compressed = Encryption::encrypt_string($json_fx_rate, $key_file, OPENSSL_PUB_KEY);

        //Now decrypt using the publisher's public key
        $key_file = "file:///var/www/apiconsole/certs/642247_pvt_key.pem";
        $json_fx_rate_decrypted = Encryption::decrypt_string($json_fx_rate_base64_encrypted_compressed, $key_file, OPENSSL_PVT_KEY);
        echo 'DECRYPTED STRING -->'.$json_fx_rate_decrypted."\n";
        */

        //First call the login method with the remitter id and api key in the body to get the JWT
        $headers = [];
        $params = ['remitter_id' => $rcvr_rmtr_id,
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
            'x-key' => $sndr_rmtr_id, //pass requesting remitter id
            //Pass the JWT received from the login call
            'x-access-token' => $jwt
        ];
    
        //Get a UUID
        $uri = 'http://api.remitbroker.com/v1/uuid';
        $response = $client->get($uri, ['headers' => $headers]);
        $uuid = $response->getBody();
        // Set the UUID in the transaction metadata
        $fxrate->metadata->uuid = (string)$uuid;
        
        //JSON encode the transaction to post to API
        $json_fxrate = json_encode($fxrate);

        $uri = 'http://api.remitbroker.com/v1/fxrates';
        //content-type is automagically set to json and content is     formatted to json
        //so to 'json' => pass any object that can be passed to        json_encode
        //if you pass json it will be double encoded, evidenced by the \ in the output
        $response = $client->post($uri, ['headers' => $headers,
        'body' => $json_fxrate,
        //'json' => $fxrate
        ]);

        $status_code = $response->getStatusCode();
        if($status_code = 200){
            /* Log the transaction metadata to file */
            $data_string = $fxrate->metadata->uuid.","
            .$fxrate->metadata->posted_on.","
            .$fxrate->metadata->from_rmtr_id.","
            .$fxrate->metadata->to_rmtr_id.","
            .$fxrate->metadata->message_type."," 
            ."\n";

            file_put_contents($filename, $data_string, FILE_APPEND |   LOCK_EX);
            //Increment sent transactions count
            $fxrates_posted++;
            echo ".";
        }else{ // not OK
            $reason_phrase = $response->getReasonPhrase();
            echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
        }
    //} else{
        //Invalid mapping
    //}
} //end for loop

echo "\nFX Rates posted: ".$fxrates_posted."\n";

?>
