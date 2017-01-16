<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use App\Http\Requests;

use Auth;
use Log;

class LoginController extends Controller
{
    
    //
    // Function to display Login page
    //
    public function index(){
        //Check if user already logged in, then direct to Dashboard
        if (Auth::check()) {
            // The user is logged in...
            return redirect()->route('dashboard');
        }       

        //Else
        return view('login');
    }

    //
    // Function to process login credentials passed from Login page
    //
    public function login(Request $request){
        
        // Validate the input using in-built validator
        /* DISABLE TEMPORARILY DURING DEVELOPMENT */
        $this->validate($request, [
            'email' => 'bail|required|email',
            'user_password' => 'bail|required|min:6|max:16',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->user_password, 'status' => 1])) {
            // User authentication passed
            // Get the user_id and assigned remitter_id and use that to fetch the remitter details form DB
            $user_id = Auth::user()->user_id;
            $remitter_id = Auth::user()->remitter_id;

            // Calling first() will return a single object rather than an aray of objects with get()
            $remitter = DB::table('remitters')->
                                select('remitter_id', 'name', 'country_code', 'service_type')->
                                where([
                                    ['remitter_id', '=', $remitter_id],
                                    ['status', '=', 1] //status = Active
                                ])->first();


            // If a remitter was found save the remitter object to session
            if($remitter){
                Log::info('Remitter Id:Name: '.$remitter->remitter_id.':'.$remitter->name);

                $request->session()->put('remitter', $remitter);

                //intended() takes the user directly to the path asked for before being authenticated
                //default is dashboard
                //return redirect()->intended('dashboard');
                //safer to always send to dashboard
                return redirect('dashboard');
            }
            else{
                Log::info('Remitter Not Found error: '.$request->email);
                //The user is logged in but a remitter is not assigned for some reason
                //First logout the user, but use the Auth facade method don't call logout
                //since we want to redirect to login with an error message unlike logout
                Auth::logout();

                //initiate MessageBag
                $login_errors = new MessageBag(['login_failed' => ['A remitter is not mapped to your id.']]);

                return redirect()->back()->withErrors($login_errors);
            }
        }
        else{
            //initiate MessageBag
            $login_errors = new MessageBag(['login_failed' => ['The credentials you entered are not valid.']]);

            Log::info('Login error: '.$request->email);
            return redirect()->back()->withErrors($login_errors);
        }
    }

    //
    // Function to process login credentials passed from Login page
    //
    public function login_old(Request $request){
        
        //
        // Authentication will be a 3-step process
        // First check if the remitter id and master password are valid
        // Then check if the user email is mapped to the remitter id
        // Finally check if the user password is valid
        //
        $remitter = DB::table('tbl_remitter_master')->where([
                                ['remitter_id', '=', $request->remitter_id],
                                ['master_password', '=', $request->master_password],
                                ['status', '=', 1] //status = Active
                            ])->first();

        if($remitter){
            $user = DB::table('tbl_user_master')->where([
                                ['email_id', '=', $request->email_id],
                                ['password', '=', $request->user_password],
                                //double check by taking the remitter_id from the retrieved remitter record rather than from the request
                                ['remitter_id', '=', $remitter->remitter_id],
                                ['status', '=', 1] //status = Active
                            ])->first(); 
        }

        //Create a data array of various information that needs to be passed to the Dashboard
        // If user credentials are valid
        if($remitter && $user){
            $user_data = ['remitter_id' => $remitter->remitter_id,
                 'remitter_name' => $remitter->remitter_name,
                 'user_id' => $user->user_id,
                 'email_id' => $user->email_id
                ];

            $request->session()->push('user_data', $user_data);

            //return view('dashboard', compact('remitter'));
            //return redirect()->action('DashboardController@index');
        }
        else{
            return redirect()->back()->withErrors($login_errors);
        }
    }

    //
    // Function to process logout user
    //
    public function logout_old($user_id){
        $request->session()->flush();
        return view('login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->action('LoginController@index');
    }


}
