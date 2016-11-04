<?php
class Opinion extends Object {

    private $id_opinion;
    private $opinion;
    private $name;

    public function __construct($opinion =array()) {
        parent::__construct($opinion);
    }

    function getId_opinion() {
        return $this->id_opinion;
    }

    function getOpinion() {
        return $this->opinion;
    }

    function getName() {
        return $this->name;
    }


    function setId_opinion($id_opinion) {
        return $this->id_opinion = $id_opinion ;
    }

    function setOpinion($opinion) {
        return $this->opinion = $opinion ;
    }

    function setName($name) {
        return $this->name = $name ;
    }

}
