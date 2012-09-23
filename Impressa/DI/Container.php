<?php
/**
 * Container
 */
namespace Impressa\DI;

class Container extends \Nette\DI\Container
{
	public function createInstance($class, array $args = array()) {
		$instance = parent::createInstance($class, $args);

		//property injection
		foreach ($instance->getReflection()->getProperties() as $property) {
			/* @var $property \Nette\Reflection\Property */
			if($property->hasAnnotation('inject') && $property->hasAnnotation('var')){
				$propertyName = $property->name;
				$instance->$propertyName = $this->getByType($property->getAnnotation('var'));
			}
		}
		return $instance;
	}

	public function createAccessor($service){
		/** @var $container Container */
		$container = $this;
		return new \CallbackAccessor(function() use ($container, $service) {return $container->getService($service);});
	}


	/**
	 * Resolves service by type.
	 * @param  string  class or interface
	 * @param  bool    throw exception if service doesn't exist?
	 * @return object  service or NULL
	 * @throws MissingServiceException
	 */
	public function getNameByType($class, $need = TRUE)
	{
		$lower = ltrim(strtolower($class), '\\');
		if (!isset($this->classes[$lower])) {
			if ($need) {
				throw new MissingServiceException("Service of type $class not found.");
			}
		} elseif ($this->classes[$lower] === FALSE) {
			throw new MissingServiceException("Multiple services of type $class found.");
		} else {
			return $this->classes[$lower];
		}
	}


}
