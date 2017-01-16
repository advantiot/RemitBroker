<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
 */

Route::get('/', 'LoginController@index')->name('login'); 
Route::post('/login', 'LoginController@login');

//
// All routes, except the Login page view are secured by auth
// 'web' is the guard and 'auth' is the default middleware
//


Route::group(['middleware' => ['auth']], function () {
    // 
    // GET routes for page views, all are named routes
    // 
   
    //Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard'); 
    //Route::get('/partners', 'PartnersController@index')->middleware('auth')->name('partners'); 

    Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);
    Route::get('/partners', ['uses' => 'PartnersController@index', 'as' => 'partners']);
    Route::get('/credentials', ['uses' => 'CredentialsController@index', 'as' => 'credentials']);
    Route::get('/logout', 'LoginController@logout')->name('logout'); 
    Route::get('/credentials/chngmstrpwd', ['uses'=>'CredentialsController@showChngMstrPwd', 'as'=>'changeMasterPasword']);

    Route::get('/got', [
          'uses' => function () {
            echo "You are allowed to view this page!";
          }]);
    //
    // POST routes for form processing
    // and wherever data needs to be passed to the view
    //
        
    Route::post('/credentials/validate', ['uses'=>'CredentialsController@validateCredentials', 'as'=>'validateCredentials']);
    Route::post('/newapikey', ['uses'=>'CredentialsController@generateNewAPIKey', 'as'=>'newapikey']);
    Route::post('/newapikey/activate', ['uses'=>'CredentialsController@activateNewAPIKey', 'as'=>'newapikeyactivate']);
    Route::post('/credentials/confchngmstrpwd', ['uses'=>'CredentialsController@confChangeMasterPassword', 'as'=>'confChangeMasterPassword']);
    
});
