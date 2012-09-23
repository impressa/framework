<?php
/**
 * FacadeFactory
 */
namespace Impressa\Model;

class FacadeFactory extends \Nette\Object
{
	/**
	 * @var SystemContainer
	 */
	protected $container;

	function __construct(\Impressa\DI\Container $container) {
		$this->container = $container;
	}

	public function createFacade($name, $entityManager){
		$facade = new $name($entityManager);
		foreach ($facade->reflection->getProperties() as $property) {
			/* @var $property \Nette\Reflection\Property */
			if($property->hasAnnotation('inject') && $property->hasAnnotation('var')){
				$dependencyClass = $property->getAnnotation('var');
				$propertyName = $property->name;
				if($property->hasAnnotation('lazy')){
					$serviceName = $this->container->getNameByType($dependencyClass);
					$facade->$propertyName = $this->container->createAccessor($serviceName);
				}else{
					$facade->$propertyName = $this->container->getByType($dependencyClass);
				}

			}
		}
		return $facade;
	}



}
