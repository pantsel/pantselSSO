<?php

Route::filter('pantsel.sso', function()
{   
    $request =  \Illuminate\Http\Request::createFromGlobals();
    $sso = new \Pantsel\Sso\SSOBroker;
    return $sso->auth($request);
});

