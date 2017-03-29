<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


function getAPIKey($remitter_id){
    $results = DB::table('remitters')
                   ->select('api_key')
                   ->where([
                            ['remitter_id', '=', $remitter_id],
                            ['status', '=', 1] //status = Active 
                          ])
                   ->first();

    $api_key = $results->api_key;

    return $api_key;
}


function getToken($remitter_id){

    // Parameters to create a client
    // TODO: Get from config file
    $base_uri =  ['base_uri' => 'http://api.remitbroker.com/'];

    $client = new Client($base_uri); //No headers required for login
    //$client = new Client(['base_uri' => 'http://api.remitbroker.com/']);

    $api_key = getAPIKey($remitter_id);

    Log::info("In getToken() after getAPIKey(): ".$api_key);

    //Get token and TODO: store it in session
    $response = $client->post('login', [
                                'form_params' => [
                                    'remitter_id' => $remitter_id, //as logged in from session
                                    'api_key' => $api_key
                                ]
                             ]);

    $token = $response->getBody();

    return $token;
}
?>
