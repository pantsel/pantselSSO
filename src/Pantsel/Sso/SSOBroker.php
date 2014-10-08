<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SSO
 *
 * @author Panagis Tselentis <tselentispanagis at gmail.com>
 */

namespace Pantsel\Sso;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Pantsel\Sso\SSOToken;

class SSOBroker {
    
    public function auth(\Illuminate\Http\Request $request)
    {
        if($request->input('sso_token')) // Someone trying to log in?
        {
            return $this->validateSSOToken(SSOToken::unpack($request->input('sso_token')));
            
        }else{
            
            return $this->validateUserSession();
            
        } 
    }
    
    public function validateSSOToken(SSOToken $token)
    {
        // Token has expired?
        if(time() > $token->getExpires())
        {
            return $this->validateSession(); // Validate Session
        }
        
        // Token hasn't expired? Validate token signature
        $validated =  SSOTokenSig::validateSigOf($token);
        if($validated)
        {
            Session::put('sso_authenticated',1);
            foreach($token->getSigParts() as $k=>$v)
            {
                Session::put($k,$v);
            }
        }
        
    }
    
    private function validateSession()
    {
        $authenticated = Session::get('sso_authenticated',0);
        $bag = Session::getMetadataBag();
        $max = Config::get('session.lifetime') * 60;
        
        // If session has expired or no user is set
        if (($bag && $max < (time() - $bag->getLastUsed())) || !$authenticated) {
            
            Session::clear();
            
            return Redirect::to(Config::get('sso::sso.server_url') 
                    . '?broker=' 
                    . Config::get('sso::sso.broker_id'));
        }
    }
}
