<?php

namespace Impressa\Security;

class Resource implements \Nette\Security\IResource {
    
    private $name;
    private $id;
    
    public function __construct($name, $id = null) {
        $this->name = $name;
        $this->id = $id;
    }

    public function getResourceId() {
        return $this->name;
    }
    
    public function getId() {
        return $this->id;
    }
    
}


