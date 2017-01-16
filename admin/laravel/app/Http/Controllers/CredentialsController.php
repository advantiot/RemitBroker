<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Database\QueryException;
use App\Http\Requests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Log;

class CredentialsController extends Controller
{
    //
    // Function to display Credentials page
    // 
    public function index(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        Log::info($remitter->remitter_id);

        return view('credentials_enter')->with('remitter', $remitter);
    }

    //
    // Function to display change master password view
    // 
    public function showChngMstrPwd(Request $request){
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        Log::info($remitter->remitter_id);

        //Reflash superauth flag as it would be needed again for the Confirm Change Password function
        $request->session()->reflash();

        return view('credentials_chngmstrpwd')->with('remitter', $remitter);
    }

    //
    // Function to activate/save new API Key
    // 
    public function activateNewAPIKey(Request $request){
      
        //Check if superauth flag in session is set to true
        if(!$request->session()->get('superauth')){
            return response()->json([
                //'success' => false, //Not required and generic iteration displays this
                'message' => 'Invalid credentials'
            ], 403);
        }
        
        //User is superauth, continue 
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object

        //Update the remitter record with the new api key
        try{
            DB::table('remitters')->
            where('remitter_id', '=', $remitter->remitter_id)->
            update(['api_key' => DB::raw('new_api_key'),
                    'new_api_key' => null
                   ]);
        } catch(QueryException $ex){
            // Note any method of class PDOException can be called on $ex
            dd($ex->getMessage());//dd = Laravel's Dump cwandor Die
             
            return response()->json([
                'success' => false,
                'message' => 'Database error activating new api key.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
        ], 200);
    }     

    //
    // Function to validate credentials generate new api key
    // 
    public function generateNewApiKey(Request $request){

        //Check if superauth flag in session is set to true
        if(!$request->session()->get('superauth')){
            //initiate MessageBag
            $errors = new MessageBag(['login_failed' => ['Invalid credentials.']]);
            return redirect()->back()->withErrors($errors);

            //Required if ajax calls method
            /*
            return response()->json([
                //'success' => false, //Not required and generic iteration displays this
                'message' => 'Invalid credentials'
            ], 403);
             */
        }

        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        //Log::info($remitter->remitter_id);

        //NOTE: Do not use raw queries as they open doors for sql injection
        //Calling first() will return a single object rather than an aray of objects with get()
        //Need only the new_api_key - if exists go directly activate, else generate new
        
        $result = DB::table('remitters')->
                  select('new_api_key')->
                  where([
                      ['remitter_id', '=', $request->remitter_id],
                      ['status', '=', 1] //status = Active
                  ])->first();
        
        // If no matching record is found return an error response
        if(!$result){
            return response()->json([
                //'success' => false, //Not required and generic iteration displays this
                'message' => 'Invalid credentials'
            ], 403);
        }else{
            //If new_api_key is NULL generate the UUID and save to remitters table
            if(is_null($result->new_api_key)){
                $uuid = $this->genUUIDv4();

                DB::table('remitters')->
                where('remitter_id', '=', $remitter->remitter_id)->
                update(['new_api_key' => $uuid]);

            }else{
                //Return existing but inactive new_api_key
                $uuid = $result->new_api_key;
            }

            //Reflash superauth flag as it would be needed again for the Activate Key function
            $request->session()->reflash();

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'payload' => ['newapikey' => $uuid],
            ], 200);
        }
    }

    //
    // Function to validate credentials and then generate new api view or go to change master password view
    // 
    public function validateCredentials(Request $request){

        // Validate the input using in-built validator
        //This automatically returns errors through the MessageBag object 
        $this->validate($request, [
            'remitter_id' => 'bail|required',
            'master_password' => 'bail|required',
            'api_key' => 'bail|required',
        ]);

        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object
        //Log::info($remitter->remitter_id);

        //Check for valid credentials and return a 403 Forbidden if invalid
        //First check after basic validation: does entered remitter id match the one in session?
        if($request->remitter_id != $remitter->remitter_id){
            //initiate MessageBag
            $errors = new MessageBag(['login_failed' => ['Invalid credentials.']]);
            return redirect()->back()->withErrors($errors);

            //Required if ajax calls method
            /*
            return response()->json([
                //'success' => false, //Not required and generic iteration displays this
                'message' => 'Invalid credentials'
            ], 403);
             */
        }
        
        //Second check if Remitter Id, Master Password and Current API Key match values in the database
        //NOTE: Do not use raw queries as they open doors for sql injection
        //Calling first() will return a single object rather than an aray of objects with get()
        //Need only the new_api_key - if exists go directly activate, else generate new
        
        $result = DB::table('remitters')->
                  select(DB::raw(1))->
                  where([
                      ['remitter_id', '=', $request->remitter_id],
                      ['master_password', '=', $request->master_password],
                      ['api_key', '=', $request->api_key],
                      ['status', '=', 1] //status = Active
                  ])->first();
        
        // If no matching record is found return an error response
        if(!$result){
            //initiate MessageBag
            $errors = new MessageBag(['login_failed' => ['Invalid credentials.']]);
            return redirect()->back()->withErrors($errors);

            //Required if ajax calls method
            /*
            return response()->json([
                //'success' => false, //Not required and generic iteration displays this
                'message' => 'Credentials not authorised'
            ], 403);
             */
        }else{

            //Check which submit button was clicked and redirect accordingly
            if($request->submit == "newapikey"){

                $result = DB::table('remitters')->
                      select('new_api_key')->
                      where([
                          ['remitter_id', '=', $request->remitter_id],
                          ['status', '=', 1] //status = Active
                      ])->first();
                
                    // If no matching record is found return an error response
                if(!$result){
                    //initiate MessageBag
                    $errors = new MessageBag(['login_failed' => ['Invalid credentials.']]);
                    return redirect()->back()->withErrors($errors);

                    //Required if ajax calls method
                    /*
                            return response()->json([
                                //'success' => false, //Not required and generic iteration displays this
                                'message' => 'Invalid credentials'
                            ], 403);
                     */
                }else{
                    //If new_api_key is NULL generate one and save to remitters table
                    if(is_null($result->new_api_key)){
                        $new_api_key = $this->genUUIDv4();

                        DB::table('remitters')->
                        where('remitter_id', '=', $remitter->remitter_id)->
                        update(['new_api_key' => $new_api_key]);

                    }else{
                        //Return existing but inactive new_api_key
                        $new_api_key = $result->new_api_key;
                    }

                    //Add an "super authenticated" flag to session
                    //Check if set before task is executed and remove them when the task is completed
                    //Use Laravel' flash data for this, 
                    //only available for next request - either activate key or change master password

                    $request->session()->flash('superauth', true);

                    //Generate New API Key and redirect to credentials_newapikey view
                    return view('credentials_newapikey')->with('remitter', $remitter)
                                                        ->with('new_api_key',$new_api_key) ;
                    //Required if ajax calls method
                    /*
                    return response()->json([
                        'success' => true,
                        'message' => 'OK',
                        'payload' => ['newapikey' => $new_api_key],
                    ], 200);
                     */
                }
            }

            if($request->submit == "chngmstrpwd"){
                $request->session()->flash('superauth', true);
                //Redirect to credentials_chngmstrpwd view
                //return view('credentials_chngmstrpwd')->with('remitter', $remitter);
                return redirect('credentials/chngmstrpwd');
            }

            /* Return reponse if ajax call */
            /*
            return response()->json([
                'success' => true,
                'message' => 'OK',
            ], 200);
             */
        }
    }

    //
    // Function to change master password
    // 
    public function confChangeMasterPassword(Request $request){
      
        // Validate the input using in-built validator
        //This automatically returns errors through the MessageBag object 

         
        $this->validate($request, [
            'master_password' => 'bail|required',
            'confirm_master_password' => 'bail|required',
        ]);
        
         
        //Check if superauth flag in session is set to true
        if(!$request->session()->get('superauth')){
            //initiate MessageBag
            $errors = new MessageBag(['login_failed' => ['Please check if you have the permissions to change the master password.']]);
            return redirect()->back()->withErrors($errors);
        }
        //User is superauth, continue 
        //Gets the data set in session by the login process into an array
        $remitter = $request->session()->get('remitter'); //gets the remitter object

        //Update the remitter record with the new api key
        try{
            DB::table('remitters')->
            where('remitter_id', '=', $remitter->remitter_id)->
            update(['master_password' => $request->master_password]);
        } catch(QueryException $ex){
            // Note any method of class PDOException can be called on $ex
            dd($ex->getMessage());//dd = Laravel's Dump cwandor Die
             
            //initiate MessageBag
            $errors = new MessageBag(['login_failed' => ['Database error when changing master password.']]);
            return redirect()->back()->withErrors($errors);
        }

        //return redirect()->back()->withSuccess("Master password changed successfully.");
        
        //return success view which is a generic view to display success messages
        return view('success')->with('remitter', $remitter)  
                              ->with('message', 'Master password changed successfully.');
        
    }     

    private function genUUIDv4()
    {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
