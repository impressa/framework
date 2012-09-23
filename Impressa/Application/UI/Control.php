<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Application\UI;
/**
 * Description of Control
 *
 * @author puty
 */
class Control extends \Nette\Application\UI\Control{
    //put your code here
    public function addService($name, $service) {
        parent::addService($name, $service);
    }
    public function getService($name) {
        parent::getService($name);
    }
    public function hasService($name) {
        parent::hasService($name);
    }
    public function removeService($name) {
        parent::removeService($name);
    }

	/** @param $presenter \Nette\Application\UI\Presenter */
	protected function attached($presenter) {
		parent::attached($presenter);

		foreach ($this->getReflection()->getProperties() as $property) {
			/* @var $property \Nette\Reflection\Property */
			if($property->hasAnnotation('inject') && $property->hasAnnotation('var')){
				$propertyName = $property->name;
				$this->$propertyName = $this->presenter->context->getByType($property->getAnnotation('var'));
			}
		}


	}

}


