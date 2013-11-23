<?php
namespace Impressa\Config\Extensions;

use Doctrine\Common\ClassLoader,
	Doctrine\Common\Annotations\AnnotationReader,
	Doctrine\MongoDB\Connection,
	Doctrine\ODM\MongoDB\Configuration,
	Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;


class OdmExtension extends \Nette\Config\CompilerExtension
{
	/**
	 * Processes configuration data
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{

		$container = $this->getContainerBuilder();

		$config = $this->getConfig();

		// console application
		$container->addDefinition($this->prefix('odm'))
			->setClass('\Doctrine\ODM\MongoDB\DocumentManager')
			->setFactory('Extensions\DocumentManagerExtension::createDocumentManager', array('@container', $config))
			->setAutowired(FALSE);

		// aliases
		$container->addDefinition('documentManager')
			->setClass('\Doctrine\ODM\MongoDB\DocumentManager')
			->setFactory('@container::getService', array($this->prefix('documentManager')));
	}

	public static function createDocumentManager(\Nette\DI\Container $container, $config)
	{
		$params = $container->parameters;
		$configuration = new Configuration();

		$configuration->setProxyDir("%appDir%/model/Proxies");
		$configuration->setProxyNamespace('App\Model\Proxies');

		$configuration->setHydratorDir($config['hydratorDir']);
		$configuration->setHydratorNamespace('Hydrators');

		\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(VENDORS_DIR . '/doctrine/mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php');

		$reader = new AnnotationReader();
		$driverImpl = new AnnotationDriver($reader, '%appDir%/model/Documents');

		$configuration->setMetadataDriverImpl($driverImpl);

		$configuration->setDefaultDB('test');

		$mongo = new \Mongo('mongodb://localhost', array('connect' => true));
		$connection = new Connection($mongo);
		$dm = \Doctrine\ODM\MongoDB\DocumentManager::create($connection, $configuration);

		return $dm;
	}


}