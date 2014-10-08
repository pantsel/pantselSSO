<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SSOTokenSig
 *
 * @author Panagis Tselentis <tselentispanagis at gmail.com>
 */
namespace Pantsel\Sso;

use Illuminate\Support\Facades\Config;

class SSOTokenSig {
    private $args = [];
    
    public function setArgs($args=array())
    {
        $this->args = $args;
    }
    
    public function getArgs()
    {
        return $this->args;
    }
    
    public static function make($parts=array())
    {
        return md5(implode("", $parts) . Config::get('app.key'));
    }
    
    public static function validateSigOf(SSOToken $token)
    {
        return self::make($token->getSigParts()) == $token->getSig();
    }
}
