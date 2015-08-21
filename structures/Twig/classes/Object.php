<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Object
 *
 * @author micael
 */
class Object {
    //put your code here
    
    protected function __construct($datas) {
        if(!empty($datas))
                $this->hydrate($datas);
        
    }
    protected function hydrate(array $object){
           foreach ($object as $key => $value) {
                $method = 'set'.ucfirst($key);
                if(method_exists($this, $method)){
                        $this->$method($value);
                }
            }
    }
}
