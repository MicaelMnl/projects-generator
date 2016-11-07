<?php

class Form {
    
    private  $array;
    private  $entity;
    
    
    public function __construct($array, $entity)
    {
        $this->array = $array;
        $this->entity = $entity;
    }
    
    public function getData() {
        return $this->array;
    }
    
    public function createView()
    {
        return $this->array;
    }
    
    public function hydrate()
    {
        if(isset($_POST))
        {
            foreach ($_POST as $key => $value) 
            {
                $method = 'set'.ucfirst($key);
                if(method_exists($this->entity, $method)){
                        $this->entity->$method($value);
                }
            }
        }
        return $this->entity ;
    }
}
