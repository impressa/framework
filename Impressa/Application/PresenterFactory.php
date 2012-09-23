<?php
/**
 * PresenterFactory
 */
namespace Impressa\Application;

class PresenterFactory implements \Nette\Application\IPresenterFactory
{
	/** @var \Nette\Application\PresenterFactory */
	protected $defaultPresenterFactory;


	public function __construct( \Nette\DI\Container $container)
	{
		$this->defaultPresenterFactory = new \Nette\Application\PresenterFactory( "",$container);
		$this->container = $container;

	}

	public function createPresenter($name) {
		$presenter = $this->defaultPresenterFactory->createPresenter($name);

		//property injection

		/** @var $reflectionClass \Nette\Reflection\ClassType */
		$reflectionClass = $presenter->getReflection();

		foreach ($reflectionClass->getProperties() as $property) {
			/* @var $property \Nette\Reflection\Property */
			if($property->hasAnnotation('inject') && $property->hasAnnotation('var')){
				$propertyName = $property->name;
				$presenter->$propertyName = $this->container->getByType($property->getAnnotation('var'));
			}
		}
		return $presenter;
	}

	/**
	 * @param  string  presenter name
	 * @return string  class name
	 * @throws InvalidPresenterException
	 */
	function getPresenterClass(& $name) {
		$this->defaultPresenterFactory->getPresenterClass($name);
	}
}
