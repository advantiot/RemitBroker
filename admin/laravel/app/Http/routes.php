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
    Route::get('/dashboard/outbound', ['uses' => 'DashboardController@showOutbound', 'as' => 'outbound']);
    Route::get('/dashboard/inbound', ['uses' => 'DashboardController@showInbound', 'as' => 'inbound']);

    Route::get('/partners', ['uses' => 'PartnersController@index', 'as' => 'partners']);
    Route::get('/notifications', ['uses' => 'NotificationsController@index', 'as' => 'notifications']);
    Route::get('/credentials', ['uses' => 'CredentialsController@index', 'as' => 'credentials']);
    Route::get('/credentials/chngmstrpwd', ['uses'=>'CredentialsController@showChngMstrPwd', 'as'=>'chngmstrpwd']);
    Route::get('/credentials/newkeypair', ['uses'=>'CredentialsController@showNewKeyPair', 'as'=>'newkeypair']);
    Route::get('/analytics', ['uses' => 'AnalyticsController@index', 'as' => 'analytics']);
    Route::get('/billing', ['uses' => 'BillingController@index', 'as' => 'billing']);
    Route::get('/settings', ['uses' => 'SettingsController@index', 'as' => 'settings']);
    Route::get('/logout', 'LoginController@logout')->name('logout'); 

    Route::get('/testenv/posttxnposts', 'TestEnvController@showPostTxnPosts')->name('testenvposttxnposts'); 
    Route::get('/testenv/gettxnposts', 'TestEnvController@getTxnPosts')->name('testenvgettxnposts'); 
    Route::get('/testenv/txnacks', 'TestEnvController@postTxnAcks')->name('testenvposttxnacks'); 

    Route::get('/help/overview', 'HelpController@overview')->name('helpoverview'); 

    Route::get('/got', [
          'uses' => function () {
            echo "You are allowed to view this page!";
          }]);
    //
    // POST routes for form processing
    // and wherever data needs to be passed to the view
    //
        
    Route::post('/credentials/validate', ['uses'=>'CredentialsController@validateCredentials', 'as'=>'validateCredentials']);
    Route::post('/credentials/newapikey', ['uses'=>'CredentialsController@generateNewAPIKey', 'as'=>'newapikey']);
    Route::post('/credentials/newapikey/activate', ['uses'=>'CredentialsController@activateNewAPIKey', 'as'=>'newapikeyactivate']);
    Route::post('/credentials/uploadpubkeyfile', ['uses'=>'CredentialsController@uploadPubKeyFile', 'as'=>'uploadnewpubkey']);
    Route::post('/credentials/confchngmstrpwd', ['uses'=>'CredentialsController@confChangeMasterPassword', 'as'=>'confChangeMasterPassword']);
    //Since find is reloading the main page with parameters there is a get and post route for /partners
    //Since two form actions exist on the same page there is a routing issue from one nested route to another (add after find)
    Route::post('/partners', ['uses'=>'PartnersController@index', 'as'=>'partners']);
    Route::post('/partners/add', ['uses'=>'PartnersController@addPartner', 'as'=>'addPartner']);
    Route::post('/partners/changeStatus', ['uses'=>'PartnersController@changePartnerStatus', 'as'=>'changePartnerStatus']);

    //Test Environment routes
    Route::post('/testenv/posttxnposts', 'TestEnvController@postTxnPosts')->name('testenvposttxnposts'); 
    Route::post('/testenv/gettxnposts', 'TestEnvController@getTxnPosts')->name('testenvgettxnposts'); 
    Route::post('/testenv/posttxnresponses', 'TestEnvController@postTxnResponses')->name('testenvposttxnresponses'); 
    Route::post('/testenv/posttxnacks', 'TestEnvController@postTxnAcks')->name('testenvposttxnacks'); 
});
