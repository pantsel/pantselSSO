<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SSOBrokersDictionary
 *
 * @author Panagis Tselentis <tselentispanagis at gmail.com>
 */
namespace Pantsel\Sso;

class SSOBrokersDictionary {
    
    private $dictionary = [];
    
    /**
     * Function : add()
     * ----------------
     * Adds a broker id / broker url pair in the dictionary
     * 
     * @param string $id : the broker id
     * @param string $url : the broker url
     */
    public function add($id,$url)
    {
        if(!$this->has($id))
        {
            $this->addRecord($id, $url);   
        }
        
    }
    
    public function all()
    {
        return $this->dictionary;
    }
    
    public function get($key)
    {
        return $this->dictionary[$key];
    }
    
    private function addRecord($id,$url)
    {
        $this->dictionary[$id] = $url;
    }
    
    public function has($id)
    {
        return array_key_exists($id,$this->dictionary);
    }
    
    public static function recordExists($id, SSOBrokersDictionary $dictionary)
    {
        return array_key_exists($id,$dictionary->all());
    }
}
