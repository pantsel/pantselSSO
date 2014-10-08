<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SSOToken
 *
 * @author Panagis Tselentis <tselentispanagis at gmail.com>
 */
namespace Pantsel\Sso;

use Illuminate\Support\Facades\Crypt;

class SSOToken {
    
    private $sig = null;
    private $expires = null;
    private $parts = array();
    
    public static function unpack($string)
    {
        $decoded = unserialize(Crypt::decrypt($string));
        
        $sso_token = new SSOToken;
        $sso_token->setSigParts($decoded->getSigParts());
        $sso_token->setSig($decoded->getSig());
        $sso_token->setExpires($decoded->getExpires());
        return $sso_token;
    }
    
    public static function make($parts=array())
    {
        $token = new SSOToken;
        
        $token->setSigParts($parts);
        $token->setSig(SSOTokenSig::make($parts));
        $token->setExpires(time() + (60)); // Expires in 1 sec
        return Crypt::encrypt(serialize($token));
        
    }
    
    
    public function setSigParts($parts=array())
    {
        
        $this->parts = $parts;
    }
    
    public function getSigParts()
    {
        return $this->parts;
    }

    public function setSig($sig)
    {
        $this->sig = $sig;
    }
    
    public function getSig()
    {
        return $this->sig;
    }
    
    public function setExpires($timestamp)
    {
        $this->expires = $timestamp;
    }
    
    public function getExpires()
    {
        return $this->expires;
    }
}
