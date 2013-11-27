<?php
namespace Impressa\Config\Extensions;

use Doctrine\Common\Annotations\AnnotationReader,
	Doctrine\MongoDB\Connection,
	Doctrine\ODM\MongoDB\Configuration,
	Nette\DI\ContainerBuilder,
	Nette\Config\Configurator,
	Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

class OdmExtension extends \Nette\Config\CompilerExtension
{
	/**
	 * Processes configuration data
	 *
	 * @return void
	 */
	public function loadConfiguration() {

		$container = $this->getContainerBuilder();
		$this->initDefaultParameters($container);

		$container->addDefinition($this->prefix('connection'))
			->setClass('Doctrine\MongoDB\Connection')
			->setFactory('Impressa\Config\Extensions\OdmExtension::createConnection');

		$container->addDefinition($this->prefix('annotation'))
			->setClass('Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver')
			->setFactory('Impressa\Config\Extensions\OdmExtension::createAnnotationDriver', array('%mongo%'));

		$container->addDefinition($this->prefix('configuration'))
			->setClass('Doctrine\ODM\MongoDB\Configuration')
			->setFactory('Impressa\Config\Extensions\OdmExtension::createConfiguration', array('%mongo%', $this->prefix('@annotation')));

		$container->addDefinition($this->prefix("documentManager"))
			->setClass('\Doctrine\ODM\MongoDB\DocumentManager')
			->setFactory('Impressa\Config\Extensions\OdmExtension::createDocumentManager', array($this->prefix('@connection'),$this->prefix('@configuration'),'@doctrine.eventManager'));
		//->setAutowired(FALSE);
	}

	/**
	 * @param \Nette\DI\ContainerBuilder
	 */
	protected function initDefaultParameters(ContainerBuilder $container)
	{
		$container->parameters = \Nette\Utils\Arrays::mergeTree($container->parameters,
																array(
																	 'mongo' => array(
																		 'proxyDir'              	=> "%appDir%/model/Proxies",
																		 'proxyNamespace'         	=> 'App\Model\Proxies',
																		 'hydDir'					=> "%appDir%/model/Hydrators",
																		 'hydNamespace'         	=> 'App\Model\Hydrators',
																		 'docDir'         	 		=> "%appDir%/model/Documents"
																	 )
																));
	}

	public static function createDocumentManager($connection, $configuration, $eventManager)
	{
		return \Doctrine\ODM\MongoDB\DocumentManager::create($connection, $configuration, $eventManager);
	}

	public static function createConnection(){
		return new Connection();
	}

	public static function createConfiguration($config, $annotationDriver){

		//TODO WTF
		/*if (!file_exists($file = LIBS_DIR . '/autoload.php')) {
		throw new RuntimeException('Install dependencies to run this script.');
		}

		$loader = require_once $file;
		$loader->add('Documents', "%appDir%/model/");*/

		$configuration = new Configuration();

		$configuration->setProxyDir($config["proxyDir"]);
		$configuration->setProxyNamespace($config["proxyNamespace"]);

		$configuration->setHydratorDir($config["hydDir"]);
		$configuration->setHydratorNamespace($config["hydNamespace"]);

		$configuration->setDefaultDB($config["dbName"]);
		$configuration->setMetadataDriverImpl($annotationDriver);

		return $configuration;
	}

	public static function createAnnotationDriver($config){

		\Doctrine\Common\Annotations\AnnotationRegistry::registerFile( LIBS_DIR . '/doctrine/mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php');

		$reader = new AnnotationReader();
		return new AnnotationDriver($reader, $config["docDir"]);
	}

	/**
	 * Register extension to compiler.
	 *
	 * @param \Nette\Config\Configurator $configurator
	 */
	public static function register(Configurator $configurator)
	{
		$class = get_called_class();
		$configurator->onCompile[] = function (Configurator $configurator, \Nette\Config\Compiler $compiler) use ($class) {
			$compiler->addExtension('odm', new $class);
		};
	}
}