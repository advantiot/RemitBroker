<?php
//
// This program posts a series of Cancellation Requests for a subset of new transactions previously posted
// File execution sequence:3
// The program will log every transaction got to logs/[sndr_rmtr_id]_post_can_txnrequests_log.csv

require '../vendor/autoload.php';
require '../models/common_model.php';
require '../models/txnrequest_model.php';

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

$rmtr_currency['164831']='EUR';
$rmtr_currency['443444']='GBP';
$rmtr_currency['475246']='EUR';
$rmtr_currency['505113']='EUR';
$rmtr_currency['506863']='CNY';
$rmtr_currency['516712']='USD';
$rmtr_currency['594629']='BDT';
$rmtr_currency['642247']='INR';
$rmtr_currency['760613']='MXP';
$rmtr_currency['922196']='PHP';

$rmtr_country['164831']='ITA';
$rmtr_country['443444']='GBR';
$rmtr_country['475246']='FRA';
$rmtr_country['505113']='ESP';
$rmtr_country['506863']='CHN';
$rmtr_country['516712']='USA';
$rmtr_country['594629']='BGD';
$rmtr_country['642247']='IND';
$rmtr_country['760613']='MEX';
$rmtr_country['922196']='PHL';

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

$product_codes = ['CCP','CCP','CAD','CBP','CMO','ACP','ACD','AAD','ABP','AMO','DCP','DCD','DAD','DBP','DMO'];

//For transactions from sender to receiver
//$txn_types = ['REQ_NEW','REQ_MOD','REQ_CAN','CNF_PAY','CNF_CAN','REQ_REJ'];
//$txn_status_codes = ['NEW','MOD','CAN','PD','REJ'];

$message_type = 'REQ_CAN'; //This program only sends cancellation requests
$txn_status_code = 'NEW';

$base_rates = [
	'USDEUR' =>'0.91',
	'USDINR' =>'66.81',
	'USDCNY' =>'6.4',
	'USDPHP' =>'47.03',
	'USDMXN' =>'16.7',
	'USDBDT' =>'75.54',
	'EURINR' =>'73.13',
	'EURCNY' =>'7.00',
	'EURPHP' =>'51.55',
	'EURMXN' =>'18.26',
	'EURBDT' =>'82.64',
	'GBPEUR' =>'1.38',
	'GBPINR' =>'101.13',
	'GBPCNY' =>'9.68',
	'GBPPHP' =>'71.18',
	'GBPMXN' =>'25.28',
	'GBPBDT' =>'114.38',
	'EUREUR' =>'1.00'
	];

$first_names = array('Christopher','Ryan','Ethan','John','Zoey','Sarah','Michelle','Samantha',);
$last_names = array('Walker','Thompson','Anderson','Johnson','Tremblay','Peltier','Cunningham','Simpson','Mercado','Sellers');

//In an initial pass create a file with the header row for each sending remitter
foreach($sndr_rmtr_ids as $sndr_rmtr_id){
    $filename = '../logs/'.$sndr_rmtr_id."_post_can_txnrequests_log.csv";
    //Write the headers
    $header_string = "UUID,"."Posted On,"."From Remitter,"."To Remitter,"."Message Type,"."Sender Txn Num\n";
    file_put_contents($filename, $header_string, FILE_APPEND | LOCK_EX);
}

$txnrequests_sent = 0;
//Run this in a loop for each sending remitter
foreach($sndr_rmtr_ids as $sndr_rmtr_id){
    $sndr_rmtr_api_key = $rmtr_api_keys[$sndr_rmtr_id];

    //Read posted transactions metadata from file
    $inputfile = "../logs/".$sndr_rmtr_id."_post_new_txnrequests_log.csv";

    if (($handle = fopen($inputfile, "r")) !== FALSE) {
    fgetcsv($handle, 0, ","); //Get first row of headers and do nothing with them.
    //Run this for as many transactions posted in input file
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

        //Only a small percentage of the new transactions should be cancelled
        //Generate a number between 0 and 100 and post a can request id number <10 (10% on an average)
        if(mt_rand(0,100) > 9) continue;

        //The fields below are copied form the original txn
        $from_rmtr_id = $data[2];
        $sndr_rmtr_id = $data[2];

        $to_rmtr_id = $data[3];
        $rcvr_rmtr_id = $data[3];

        $sndr_txn_num = $data[5];

        //Get API keys from array
        $sndr_rmtr_api_key = $rmtr_api_keys[$sndr_rmtr_id];
        $rcvr_rmtr_api_key = $rmtr_api_keys[$rcvr_rmtr_id];

        /*** Transaction Modification Request ***/
        $txn = new Transaction();

        /*** TransactionMetadata ***/
        $txn->metadata =  new Metadata();

        $txn->metadata->from_rmtr_id = $from_rmtr_id;
        $txn->metadata->to_rmtr_id = $to_rmtr_id;
        //$txn->metadata->message_type = $txn_types[mt_rand(0, sizeof($txn_types)-1)];
        $txn->metadata->message_type = $message_type;
        $txn->metadata->posted_on = time();

        /*** TransactionData ***/
        $txn->data =  new TransactionData();
        $txn->data->sndr_txn_num = $sndr_txn_num;
        $txn->data->rcvr_txn_num = "";
        $txn->data->bene_code = "";
        $txn->data->sndr_cntry_code = "";
        $txn->data->rcvr_cntry_code = "";
        /*** Transaction Status ***/
        $txn->data->status = new Status();
            $txn->data->status->code = $txn_status_code;
            $txn->data->status->notes = "Request to modify and pay";
            $txn->data->changed_on = time();

        $txn->data->created_on = time();

        /*** Customer (Sender) ***/
        $txn->data->sender = new Customer();

            /*** Name ***/
            $txn->data->sender->name = new Name();
                $txn->data->sender->name->title = "";
                $txn->data->sender->name->first = $first_names[mt_rand(0, sizeof($first_names)-1)];
                $txn->data->sender->name->first_other = "";
                $txn->data->sender->name->last = $last_names[mt_rand(0, sizeof($last_names)-1)];
                $txn->data->sender->name->last_other = "";

            /*** Address ***/
            $txn->data->sender->curr_address = new Address();
                $txn->data->sender->curr_address->street = "101, Hope Stree";
                $txn->data->sender->curr_address->locality = "";
                $txn->data->sender->curr_address->city = "Los Angeles";
                $txn->data->sender->curr_address->postcode = "999999";
                $txn->data->sender->curr_address->state = "CA";
                $txn->data->sender->curr_address->country = $rmtr_country[$sndr_rmtr_id];

            /*** Address ***/
            $txn->data->sender->perm_address = new Address();
                $txn->data->sender->perm_address->street = "101, Hope Street";
                $txn->data->sender->perm_address->locality = "";
                $txn->data->sender->perm_address->city = "Los Angeles";
                $txn->data->sender->perm_address->postcode = "999999-9999";
                $txn->data->sender->perm_address->state = "California";
                $txn->data->sender->perm_address->country = $rmtr_country[$sndr_rmtr_id];

            $txn->data->sender->email = "";
            $txn->data->sender->phone = "";
            $txn->data->sender->dob = "";
            $txn->data->sender->nationality = "";


            /*** IdDoc ***/
            $txn->data->sender->id_doc = new IdDoc();
                $txn->data->sender->id_doc->number = "";
                $txn->data->sender->id_doc->type = "";
                $txn->data->sender->id_doc->expires_on = "";
                $txn->data->sender->id_doc->country = "";
                $txn->data->sender->id_doc->image = "";

            /*** NameValuePair ***/
            $txn->data->sender->other_info = array();
                $key_value_pair1 = new KeyValuePair();
                    $key_value_pair1->key = "key1";
                    $key_value_pair1->value = "value1";

                array_push($txn->data->sender->other_info, $key_value_pair1);

        /*** Customer (Beneficiary) ***/
        $txn->data->beneficiary = new Customer();

            /*** Name ***/
            $txn->data->beneficiary->name = new Name();
                $txn->data->beneficiary->name->title = "";
                $txn->data->beneficiary->name->first = $first_names[mt_rand(0, sizeof($first_names)-1)];
                $txn->data->beneficiary->name->first_other = "";
                $txn->data->beneficiary->name->last = $last_names[mt_rand(0, sizeof($last_names)-1)];
                $txn->data->beneficiary->name->last_other = "";

            /*** Address ***/
            $txn->data->beneficiary->curr_address = new Address();
                $txn->data->beneficiary->curr_address->street = "202, ZigZag Road";
                $txn->data->beneficiary->curr_address->locality = "Bandra (W)";
                $txn->data->beneficiary->curr_address->city = "Mumbai";
                $txn->data->beneficiary->curr_address->postcode = "400050";
                $txn->data->beneficiary->curr_address->state = "Maha";
                $txn->data->beneficiary->curr_address->country = $rmtr_country[$rcvr_rmtr_id];

            /*** Address ***/
            $txn->data->beneficiary->perm_address = new Address();
                $txn->data->beneficiary->perm_address->street = "202, ZigZag Road";
                $txn->data->beneficiary->perm_address->locality = "Bandra (W)";
                $txn->data->beneficiary->perm_address->city = "Mumbai";
                $txn->data->beneficiary->perm_address->postcode = "400054";
                $txn->data->beneficiary->perm_address->state = "Maha";
                $txn->data->beneficiary->perm_address->country = $rmtr_country[$rcvr_rmtr_id];

            $txn->data->beneficiary->email = "";
            $txn->data->beneficiary->phone = "";
            $txn->data->beneficiary->dob = "";
            $txn->data->beneficiary->nationality = "";


            /*** IdDoc ***/
            $txn->data->beneficiary->id_doc = new IdDoc();
                $txn->data->beneficiary->id_doc->number = "";
                $txn->data->beneficiary->id_doc->type = "";
                $txn->data->beneficiary->id_doc->expires_on = "";
                $txn->data->beneficiary->id_doc->country = "";
                $txn->data->beneficiary->id_doc->image = "";

            /*** NameValuePair ***/
            $txn->data->beneficiary->other_info = array();
                $key_value_pair_bene1 = new KeyValuePair();
                    $key_value_pair_bene1->key = "key1";
                    $key_value_pair_bene1->value = "value1";

                array_push($txn->data->beneficiary->other_info, $key_value_pair_bene1);

        /*** Product ***/
        $txn->data->product = new Product();
            $txn->data->product->code = "CCP";
            $txn->data->product->desc = "Cash To Cash Pickup";

        /*** Money (Send Amount) ***/
        $txn->data->send_amnt = new Money();
            $txn->data->send_amnt->currency = $rmtr_currency[$sndr_rmtr_id];
            $txn->data->send_amnt->amount = "100";

        /*** Money (Bene Amount) ***/
        $txn->data->bene_amnt = new Money();
            $txn->data->bene_amnt->currency = $rmtr_currency[$rcvr_rmtr_id];
            $txn->data->bene_amnt->amount = "100";

        $txn->data->fxrate = "1.00";

        /*** Charge (Fees) ***/
        $txn->data->fees = array();
            $base_fee = new Charge();
                $base_fee->name = "Base Fee";
                $base_fee->value = new Money();
                    $base_fee->value->currency = $rmtr_currency[$sndr_rmtr_id];
                    $base_fee->value->amount = 10.00;
        array_push($txn->data->fees, $base_fee);

        /*** Charge (Taxes) ***/
        $txn->data->taxes = array();
            $base_tax = new Charge();
                $base_tax->name = "Base Tax";
                $base_tax->value = new Money();
                    $base_tax->value->currency = $rmtr_currency[$sndr_rmtr_id];
                    $base_tax->value->amount = 1.50;
        array_push($txn->data->taxes, $base_tax);

        /*** Charge (Discounts) ***/
        $txn->data->discounts = array();
            $base_discount = new Charge();
                $base_discount->name = "Base Discount";
                $base_discount->value = new Money();
                    $base_discount->value->currency = $rmtr_currency[$sndr_rmtr_id];
                    $base_discount->value->amount = 0.00;
        array_push($txn->data->discounts, $base_discount);

        /*** Account ***/
        $txn->data->account = new Account();
            $txn->data->account->name = "";
            $txn->data->account->number = "";
            $txn->data->account->code = "";
            $txn->data->account->type = "";
            $txn->data->account->inst_name = "";
            $txn->data->account->inst_code = "";
            $txn->data->account->branch_name = "";
            $txn->data->account->branch_code = "";
            $txn->data->account->branch_address = new Address();
                $txn->data->account->branch_address->street = "";
                $txn->data->account->branch_address->locality = "";
                $txn->data->account->branch_address->city = "";
                $txn->data->account->branch_address->postcode = "";
                $txn->data->account->branch_address->state = "";
                $txn->data->account->branch_address->country = "";

        $txn->data->purpose = "";

        /*** Message ***/
        $txn->data->message = new Message();
            $txn->data->message->type = "INF";
            $txn->data->message->body = "";

        /*
         * Post transaction to API
         * Request Bin is a cool service that allows you to view the data posted. Good for debugging.
         * $uri = 'http://requestb.in/q3jr32q3';
         */

        //First call the login method with the remitter id and api key in the body to get the JWT
        $headers = [];
        $params = ['remitter_id' => $sndr_rmtr_id,
                   'api_key' => $sndr_rmtr_api_key 
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
        $txn->metadata->uuid = (string)$uuid;

        //JSON encode the transaction to post to API
        $json_txn = json_encode($txn);

        $uri = 'http://api.remitbroker.com/v1/transactions/requests';
        //content-type is automagically set to json and content is formatted to json
        //so to 'json' => pass any object that can be passed to json_encode
        //if you pass json it will be double encoded, evidenced by the \ in the output
        $response = $client->post($uri, ['headers' => $headers,
                                          'body' => $json_txn,
                                          //'json' => $txn
                                  ]);
         
        $status_code = $response->getStatusCode();

        if($status_code = 200){
            $filename = '../logs/'.$sndr_rmtr_id."_post_can_txnrequests_log.csv";

            /* Log the transaction metadata to file */
            $data_string = $txn->metadata->uuid.","
            .$txn->metadata->posted_on.","
            .$txn->metadata->from_rmtr_id.","
            .$txn->metadata->to_rmtr_id.","
            .$txn->metadata->message_type.","
            .$txn->data->sndr_txn_num.","
            ."\n";

            file_put_contents($filename, $data_string, FILE_APPEND | LOCK_EX);
            //Increment sent transactions count
            $txnrequests_sent++;
            echo ".";
        }
        else{ 
            $reason_phrase = $response->getReasonPhrase();
            echo 'The POST has a response with a statuscode of '.$status_code.' and a reasonphrase of '.$reason_phrase."\n";
        }
    } //end of while reading data fields from a line
    } //end if
} //end for loop
echo "\nCAN requests posted: ".$txnrequests_sent."\n";
?>
