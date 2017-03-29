<?php
//
// CONVERTING TO A FUNCTION TO USE FROM CONTROLLER
// This is a standalone program to post a series of NEW transactions with randomly varying values within a meaningful range
// File name starts with 1 to indicate sequence in which scripts should be executed
// Subsets of New will have a Confirmed Paid or Rejected response from receiver to sender
// Subsets of Modify will have a Confirmed Paid or Rejected response from receiver to sender
// Subsets of Cancel will have a Confirmed Cancelled or Rejected response from receiver to sender
// The program will log every transaction got to logs/[sndr_rmtr_id]_post_new_txns_log.csv

require 'models/common_model.php';
require 'models/txnpost_model.php';

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

define("OPENSSL_PUB_KEY","1");
define("OPENSSL_PVT_KEY","2");

//Returns UUID for calling function to log into testenv_logs table. Could have been logged here?
function postTxnPost($sndr_remitter, $rcvr_remitter, $txnpost_type, $headers ){

    $product_codes = ['CCP','CCP','CAD','CBP','CMO','ACP','ACD','AAD','ABP','AMO','DCP','DCD','DAD','DBP','DMO'];

    //For transactions from sender to receiver
    //$txn_types = ['REQ_NEW','REQ_MOD','REQ_CAN','CNF_PAY','CNF_CAN','REQ_REJ'];
    //$txn_status_codes = ['NEW','MOD','CAN','PD','REJ'];

    $txn_status_code = 'NEW';

    $first_names = array('Christopher','Ryan','Ethan','John','Zoey','Sarah','Michelle','Samantha',);
    $last_names = array('Walker','Thompson','Anderson','Johnson','Tremblay','Peltier','Cunningham','Simpson','Mercado','Sellers');

    $sndr_rmtr_id = $sndr_remitter->remitter_id;
    $rcvr_rmtr_id = $rcvr_remitter->remitter_id;

    /*** Transaction ***/
    $txn = new Transaction();

    /*** TransactionMetadata ***/
    $txn->metadata =  new Metadata();

    //Assign UUIS later as it needs an API call
    $txn->metadata->from_rmtr_id = $sndr_rmtr_id;
    $txn->metadata->to_rmtr_id = $rcvr_rmtr_id;
    $txn->metadata->txnpost_type = $txnpost_type;
    $txn->metadata->posted_on = time();

    /*** TransactionData ***/
    $txn->data =  new TransactionData();
    $txn->data->sndr_txn_num = (string)mt_rand(100000,999999);
    $txn->data->rcvr_txn_num = (string)mt_rand(100000,999999);
    $txn->data->bene_code = (string)mt_rand(100000,999999);
    $txn->data->sndr_cntry_code = $sndr_remitter->country_code;
    $txn->data->rcvr_cntry_code = $rcvr_remitter->country_code;
    /*** Transaction Status ***/
    $txn->data->status = new Status();
        $txn->data->status->code = $txn_status_code;
        $txn->data->status->notes = "Notes";
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
            $txn->data->sender->curr_address->country = $sndr_remitter->country_code;

        /*** Address ***/
        $txn->data->sender->perm_address = new Address();
            $txn->data->sender->perm_address->street = "101, Hope Street";
            $txn->data->sender->perm_address->locality = "";
            $txn->data->sender->perm_address->city = "Los Angeles";
            $txn->data->sender->perm_address->postcode = "999999-9999";
            $txn->data->sender->perm_address->state = "California";
            $txn->data->sender->perm_address->country = $sndr_remitter->country_code;

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
            $txn->data->beneficiary->curr_address->country = $rcvr_remitter->country_code;

        /*** Address ***/
        $txn->data->beneficiary->perm_address = new Address();
            $txn->data->beneficiary->perm_address->street = "202, ZigZag Road";
            $txn->data->beneficiary->perm_address->locality = "Bandra (W)";
            $txn->data->beneficiary->perm_address->city = "Mumbai";
            $txn->data->beneficiary->perm_address->postcode = "400054";
            $txn->data->beneficiary->perm_address->state = "Maha";
            $txn->data->beneficiary->perm_address->country = $rcvr_remitter->country_code;

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
        $txn->data->send_amnt->currency = $sndr_remitter->currency_code;
        $txn->data->send_amnt->amount = "100";

    /*** Money (Bene Amount) ***/
    $txn->data->bene_amnt = new Money();
        $txn->data->bene_amnt->currency = $rcvr_remitter->currency_code;
        $txn->data->bene_amnt->amount = "100";

    $txn->data->fxrate = "1.00";

    /*** Charge (Fees) ***/
    $txn->data->fees = array();
        $base_fee = new Charge();
            $base_fee->name = "Base Fee";
            $base_fee->value = new Money();
                $base_fee->value->currency = $sndr_remitter->currency_code;
                $base_fee->value->amount = 10.00;
    array_push($txn->data->fees, $base_fee);

    /*** Charge (Taxes) ***/
    $txn->data->taxes = array();
        $base_tax = new Charge();
            $base_tax->name = "Base Tax";
            $base_tax->value = new Money();
                $base_tax->value->currency = $sndr_remitter->currency_code;
                $base_tax->value->amount = 1.50;
    array_push($txn->data->taxes, $base_tax);

    /*** Charge (Discounts) ***/
    $txn->data->discounts = array();
        $base_discount = new Charge();
            $base_discount->name = "Base Discount";
            $base_discount->value = new Money();
                $base_discount->value->currency = $sndr_remitter->currency_code;
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
            $client = new client();
            //Get a UUID
            $uri = 'http://api.remitbroker.com/v1/uuid';
            $response = $client->get($uri, ['headers' => $headers]);
            $uuid = $response->getBody();
            // Set the UUID in the transaction metadata
            $txn->metadata->uuid = (string)$uuid;

            //JSON encode the transaction to post to API
            $json_txn = json_encode($txn);

            $uri = 'http://api.remitbroker.com/v1/transactions/posts';
            //content-type is automagically set to json and content is formatted to json
            //so to 'json' => pass any object that can be passed to json_encode
            //if you pass json it will be double encoded, evidenced by the \ in the output
            $response = $client->post($uri, ['headers' => $headers,
                                              //'body' => $json_txn,
                                              'json' => $txn
                                      ]);
             
            $status_code = $response->getStatusCode();

            $retval_array = [];
            $retval_array['status_code'] = $response->getStatusCode();
            $retval_array['uuid'] = $uuid;
            $retval_array['posted_on'] = $txn->metadata->posted_on;

            return $retval_array;

} //END FUNCTION
?>
