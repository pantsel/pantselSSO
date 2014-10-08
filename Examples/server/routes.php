<?php

/*
|--------------------------------------------------------------------------
| SSO SERVER Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
    
	return View::make('login');
});

Route::post('/login', function()
{
        
        // do some validation with the input here.....
        
        // Create dependencies from config files
        $dependencies = Pantsel\Sso\SSOServer::createDependeciesFromConfig();
        
        // Get input
        $input = Input::all();
        
        // Check if requested broker exists in the dictionary
        if(!$dependencies->dictionary->has($input['broker']))
        {
            return Redirect::to(Config::get('sso::sso.server_url'))->with('error','Broker not found!');
        }
        
        // Validate request without logging user in
        if (Auth::validate($dependencies->validation_fields))
        {
            // Define SSOToken parts
            $parts = [
                'user_id' => User::where('username','=',$input['username'])->first()->id,
                'username' => $input['username']
            ];
            
            // Create SSOToken
            $sso_token = \Pantsel\Sso\SSOToken::make($parts);
            
            return Redirect::to($dependencies->dictionary->get($input['broker'])
                    .'?sso_token=' . $sso_token);
            
        }else{
            return Redirect::to(Config::get('sso::sso.server_url'))->with('error','Invalid Credentials!');
        }
                
});
