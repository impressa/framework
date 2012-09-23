<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Doctrine;
/**
 * Description of Entity
 *
 * @author puty
 */
class Entity extends \Nette\Object implements \ArrayAccess{

    public $scalars = array();

    public function offsetExists($offset) {
        // In this example we say that exists means it is not null
        $value = $this->{"get$offset"}();
        return $value !== null;
    }

    public function offsetSet($offset, $value) {
        $this->{"set$offset"}($value);
    }

    public function offsetGet($offset) {
        return $this->{"get$offset"}();
    }

    public function offsetUnset($offset) {
         throw new \BadMethodCallException("Array access of class " . get_class($this) . " does not support unset!");
    
    }
}
