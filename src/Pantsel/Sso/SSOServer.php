<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SSOServer
 *
 * @author Panagis Tselentis <tselentispanagis at gmail.com>
 */
namespace Pantsel\Sso;

use Illuminate\Support\Facades\Config;

class SSOServer {
    
    public static function createDependeciesFromConfig()
    {
        $request = \Illuminate\Http\Request::createFromGlobals();
        
        // Create a dictionary from config
        $dictionary = new \Pantsel\Sso\SSOBrokersDictionary;
       
        foreach(Config::get('sso::dictionary.brokers') as $k => $v){
            $dictionary->add($k, $v);
        }
        
        // Create validation fields from config
        $validation_fields = [];
        foreach(Config::get('sso::sso.validation_fields') as $field)
        {
            $validation_fields[$field] = $request->get($field);
        }
        
        $response =  new \stdClass();
        $response->dictionary = $dictionary;
        $response->validation_fields = $validation_fields;
        
        return $response;
        
    }
}
