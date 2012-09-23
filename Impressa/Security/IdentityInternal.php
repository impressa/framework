<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Security;
/**
 * Description of IdentityInternal
 *
 * @author puty
 */
class IdentityInternal extends \Nette\Object implements \Nette\Security\IIdentity{
    
    
    protected $id;
    
    protected $name;
    
    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getRoles() {
        return array();
    }


}


