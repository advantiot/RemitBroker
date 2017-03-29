<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

class PartnersController extends Controller
{
    //
    // Function to display Partners page
    // 
    public function index(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        //
        // Get data from the MySQL database for display on Partners page using filter parameters
        // 1. # List of partners with details
        // First get the partners for the calling remitter, this status is the self_status
        // Then get the remitter where the calling remitter is the partner, this status is the partner_ status
        // If the reverse entry does not exist return partner_status as Inactive
        // Transactions can flow only when both self_ and partner_statuses are active

        $where_conditions = [
                                ['partners.remitter_id', '=', $remitter->remitter_id]
                            ];

        if($request->remitter_id != ""){
            $where_conditions[] = ['remitters.remitter_id', '=', $request->remitter_id];
        }
        if($request->remitter_name != ""){
            $where_conditions[] = ['remitters.name', 'like', '%'.$request->remitter_name.'%'];
        }
        if($request->remitter_country != "" && $request->remitter_country != -1){
            $where_conditions[] = ['remitters.country_code', '=', $request->remitter_country];
        }
        if($request->self_status != "" && $request->self_status != -1){
            $where_conditions[] = ['partners.status', '=', $request->self_status];
        }
        //Service type is a bitwise comparison, All is the binary value of all bits set
        if($request->service_type != "" && $request->service_type != -1){
            $where_conditions[] = ['remitters.service_type', '=', $request->service_type];
        }

        $partners = DB::table('partners')
                        ->join('remitters', 'partners.partner_id', '=', 'remitters.remitter_id')
                        ->join('country_currency', 'country_currency.country_code', '=', 'remitters.country_code')
                        ->select('partners.*','remitters.name','remitters.country_code', 'country_currency.*') 
                        ->where($where_conditions)
                        ->get();

        //Iterate through the list of partners and get the reverse status (is this remitter an active partner of the partner?)
        //In order to be able to directly modify array elements within the loop precede $value with &.
        //In that case the value will be assigned by reference.
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
                //Now remove this record if the partner_status filter condition is set to show only 0
                if($request->partner_status == '0'){
                    unset($partners[$key]);
                }
            }else{
                $partner->partner_status = 0;
                //Now remove this record if the partner_status filter condition is set to show only 1
                if($request->partner_status == '1'){
                    unset($partners[$key]);
                }
            }
        }

        //Reference of a $value and the last array element remain even after the foreach loop. It is recommended to destroy it by unset().
        unset($value);// break the reference with the last element


        //Get a list of countries of remitters in the system, no conditions, to populate the country dropdown
        $countries = DB::table('remitters')
                        ->select('country_code') 
                        ->distinct()
                        ->get();

        //To redisplay form with entered data
        $request->flash();

        return view('partners')->with('remitter', $remitter)
                                ->with('partners', $partners)
                                ->with('countries', $countries);
    }

    //
    // Function to add partner 
    // 
    public function addPartner(Request $request){

        // Validate the input using in-built validator
        $this->validate($request, [
            'remitter_id' => 'bail|required',
        ]);

        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        
        //Check if remitter id is valid (exists and not the same as the calling remitter)
        $result = DB::table('remitters')
                  ->select(DB::raw(1))
                  ->where([
                          ['remitters.remitter_id', '!=', $remitter->remitter_id],
                          ['remitters.remitter_id', '=', $request->remitter_id],
                          ['status', '=', 1], //status = Active
                    ])->first();

        // If no matching record is found return an error response
        if(!$result){
            //initiate MessageBag
            $errors = new MessageBag(['invalid_remitter' => ['Invalid remitter.']]);
            return redirect()->back()->withErrors($errors);
            
           //Required if ajax calls method
            /*
            return response()->json([
            //'success' => false, //Not required and generic iteration displays this
            'message' => 'Credentials not authorised'
            ], 403);
            */
        }else{
            //Check if remitter already added as partner
            //(can also be enforced by a PK check at the database level) 
            $result = DB::table('partners')
                  ->select(DB::raw(1))
                  ->where([
                          ['partners.remitter_id', '=', $remitter->remitter_id],
                          ['partners.partner_id', '=', $request->remitter_id],
                    ])->first();

            if($result){
                //initiate MessageBag
                $errors = new MessageBag(['invalid_remitter' => ['Remitter already a partner.']]);
                return redirect()->back()->withErrors($errors);
            }else{
                DB::table('partners')
                    ->insert([
                            'remitter_id' => $remitter->remitter_id,
                            'partner_id' => $request->remitter_id,
                            'status' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            ]); 
                return redirect('partners')->withSuccess("Partner added. Please activate when ready.");
            }
        }
    }
    
    //
    // Function to activate/deactivate partner 
    // 
    public function changePartnerStatus(Request $request){

        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        
        // Validate the input using in-built validator
        $this->validate($request, [
            'partner_id' => 'bail|required',
            'status_to' => 'bail|required',
        ]);

        //Toggle the partner status
        try{
                DB::table('partners')
                    ->where([
                        ['remitter_id', '=', $remitter->remitter_id],
                        ['partner_id', '=', $request->partner_id],
                      ])
                    ->update([
                            'status' => $request->status_to,
                            'updated_at' => date('Y-m-d H:i:s'),
                      ]); 

                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                ], 200);
        } catch(QueryException $ex){
            // Note any method of class PDOException can be called on $ex
            dd($ex->getMessage());//dd = Laravel's Dump andor Die
            
            return response()->json([
                'success' => false,
                'message' => 'Database error activating new partner.',
            ], 500);
        }  
    }
}
