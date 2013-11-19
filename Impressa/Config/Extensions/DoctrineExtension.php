<?php

/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Impressa\Config\Extensions;

use Nette\Config\Configurator,
Nette\DI\ContainerBuilder,
Doctrine\Common\Cache\Cache,
Nette\Framework;

/**
 * Doctrine Nella Framework services.
 *
 * @author    Patrik Votoček
 */
class DoctrineExtension extends \Nette\Config\CompilerExtension
{

    /** @var bool */
    private $skipInitDefaultParameters;

    /**
     * @param bool
     */
    public function __construct($skipInitDefaultParameters = FALSE)
    {
        $this->skipInitDefaultParameters = $skipInitDefaultParameters;
    }

    /**
     * Processes configuration data
     *
     * @return void
     */
    public function loadConfiguration()
    {
        $container = $this->getContainerBuilder();

        if (!$this->skipInitDefaultParameters) {
            $this->initDefaultParameters($container);
        }

        // cache
        $container->addDefinition($this->prefix('cache'))
            ->setClass('Impressa\Doctrine\Cache', array('@cacheStorage'));

        // metadata driver
        $container->addDefinition($this->prefix('metadataDriver'))
            ->setClass('Doctrine\ORM\Mapping\Driver\AnnotationDriver')
            ->setFactory('Impressa\Config\Extensions\DoctrineExtension::createYamlMetadataDriver', array(
            '%database.mappings%'
        ));

        // logger
        $container->addDefinition($this->prefix('logger'))
            ->setClass('Doctrine\DBAL\Logging\SQLLogger')
            ->setFactory('Impressa\Doctrine\Panel::register');

        // configuration
        $container->addDefinition($this->prefix('configuration'))
            ->setClass('Doctrine\ORM\Configuration')
            ->setFactory('Impressa\Config\Extensions\DoctrineExtension::createConfiguration', array(
             $this->prefix('@cache'), $this->prefix('@cache'),
            '%database%', $this->prefix('@logger'), '%productionMode%'
        ));

        // event manager
        $container->addDefinition($this->prefix('eventManager'))
            ->setClass('Doctrine\Common\EventManager')
            ->setFactory('Impressa\Config\Extensions\DoctrineExtension::createEventManager');

        // connection factory
        $connectionFactory = $container->addDefinition($this->prefix('newConnection'))
            ->setClass('Doctrine\DBAL\Connection')
            ->setParameters(array('config', 'configuration', 'eventManager' => NULL))
            ->setFactory('Impressa\Config\Extensions\DoctrineExtension::createConnection', array(
            '%config%', '%configuration%', '%eventManager%'
        ))
            ->setShared(FALSE);

        // connection from factory
        $container->addDefinition($this->prefix('connection'))
            ->setClass('Doctrine\DBAL\Connection')
            ->setFactory($connectionFactory, array(
            '%database%', $this->prefix('@configuration'), $this->prefix('@eventManager')
        ));


        // entity manager factory
        $emFactory = $container->addDefinition($this->prefix('newEntityManager'))
            ->setClass('Doctrine\ORM\EntityManager')
            ->setParameters(array('connection', 'configuration', 'eventManager' => NULL))
            ->setFactory('Doctrine\ORM\EntityManager::create', array('%connection%', '%configuration%', '%eventManager%'))
            ->setShared(FALSE);

        // entity manager from factory
        $container->addDefinition($this->prefix('entityManager'))
            ->setClass('Doctrine\ORM\EntityManager')
            ->setFactory($emFactory, array(
            $this->prefix('@connection'), $this->prefix('@configuration'), $this->prefix('@eventManager')
        ))
            ->setAutowired(FALSE);

        //$drv = self::createYamlMetadataDriver(\Nette\DI\Helpers::expand('%database.mappings%', $container->parameters, true));
        $conf = new \Doctrine\ORM\Configuration;
        $drv = $conf->newDefaultAnnotationDriver(\Nette\DI\Helpers::expand('%database.entityDirs%', $container->parameters, TRUE));
        $conf->setMetadataDriverImpl($drv);
        $conf->setProxyDir(\Nette\DI\Helpers::expand('%database.proxyDir%', $container->parameters, TRUE));
        $conf->setProxyNamespace(\Nette\DI\Helpers::expand('%database.proxyNamespace%', $container->parameters, TRUE));

        $em = \Doctrine\ORM\EntityManager::create(self::createConnection($container->parameters['database'], $conf), $conf);




        foreach ($drv->getAllClassNames() as $className) {
            try {
                $meta = $em->getClassMetadata($className);

                $repositoryClassName = '\\Repositories\\' . $meta->getReflectionClass()->getShortName() . 'Repository';
                $repo = \Nette\Loaders\RobotLoader::load($repositoryClassName);
                $container->addDefinition($this->prefix(lcfirst($meta->getReflectionClass()->getShortName() . 'Repository')))
                    ->setClass($repositoryClassName)
                    ->setFactory("Impressa\Config\Extensions\DoctrineExtension::createRepository", array($this->prefix('@entityManager'), $className));
            } catch (\ReflectionException $e) {
                //TODO: zalogovat ze nie je vygenerovana entita
            } catch (\Nette\InvalidStateException $e) {
                //TODO: zalogovat, ze neexistuje repozitar
            }
        }


        // aliases
        $container->addDefinition('entityManager')
            ->setClass('Doctrine\ORM\EntityManager')
            ->setFactory('@container::getService', array($this->prefix('entityManager')));
//		$container->addDefinition('console')
//			->setClass('Symfony\Component\Console\Application')
//			->setFactory('@container::getService', array($this->prefix('console')));


		$container->addDefinition('facadeFactory')
			->setClass('Impressa\Model\FacadeFactory');


		foreach ($this->config['facades'] as $name => $class) {
			$container->addDefinition($name)
				->setFactory('@facadeFactory::createFacade', array($class, $this->prefix('@entityManager')))
				->setClass($class);


		}

    }

	public static function getSomething(){

	}

	/**
     * @param \Nette\DI\ContainerBuilder
     */
    protected function initDefaultParameters(ContainerBuilder $container)
    {
        $container->parameters = \Nette\Utils\Arrays::mergeTree($container->parameters, array(
            'database' => array(
                'proxyDir' => "%appDir%/model/Proxies",
                'proxyNamespace' => 'App\Model\Proxies',
                'entityDirs' => array('%appDir%/model/Entities'),
                'mappings' => array('%appDir%/model/Mappings'),
                'useAnnotationNamespace' => TRUE,
                'metadataDriver' => "yaml"
            )
        ));
    }

    public static function createRepository(\Doctrine\ORM\EntityManager $em, $entityClass)
    {
        return $em->getRepository($entityClass);
    }

    /**
     * @param \Doctrine\Common\Cache\Cache
     * @param array
     * @param bool
     * @return \Doctrine\ORM\Mapping\Driver\AnnotationDriver
     */
    public static function createXmlMetadataDriver(array $xmlDirs)
    {
        $driver = new \Doctrine\ORM\Mapping\Driver\XmlDriver($xmlDirs);
        return $driver;
    }

    public static function createYamlMetadataDriver(array $paths)
    {
        return new \Doctrine\ORM\Mapping\Driver\YamlDriver($paths);
    }



    /**
     *
     * @param \Doctrine\Common\Cache\Cache
     * @param \Doctrine\Common\Cache\Cache
     * @param array $config
     * @param \Doctrine\DBAL\Logging\SQLLogger|NULL
     * @param bool
     * @return \Doctrine\ORM\Configuration
     */
    public static function createConfiguration(Cache $metadataCache, Cache $queryCache, array $config, \Doctrine\DBAL\Logging\SQLLogger $logger = NULL, $productionMode = FALSE)
    {
        $configuration = new \Doctrine\ORM\Configuration;

        // Cache
        $configuration->setMetadataCacheImpl($metadataCache);
        $configuration->setQueryCacheImpl($queryCache);

        // Metadata
        $metadataDriver = $configuration->newDefaultAnnotationDriver($config['entityDirs']);
        $configuration->setMetadataDriverImpl($metadataDriver);

        // Proxies
        $configuration->setProxyDir($config['proxyDir']);
        $configuration->setProxyNamespace($config['proxyNamespace']);

        $configuration->addCustomHydrationMode('KeyValueHydrator', 'Impressa\Doctrine\KeyValueHydrator');
        $configuration->addCustomHydrationMode('ImpressaHydrator', 'Impressa\Doctrine\ObjectHydrator');

		$configuration->setDefaultRepositoryClassName('Impressa\Doctrine\BaseRepository');

        $configuration->addCustomNumericFunction('RAND', 'Impressa\Doctrine\Query\AST\MysqlRand');

        if ($productionMode) {
            $configuration->setAutoGenerateProxyClasses(FALSE);
        } else {
            if ($logger) {
                $configuration->setSQLLogger($logger);
            }
            $configuration->setAutoGenerateProxyClasses(TRUE);
        }

        return $configuration;
    }

    /**
     * @param \Nette\DI\Container
     * @return \Doctrine\Common\EventManager
     */
    public static function createEventManager(\Nette\DI\Container $container)
    {
        $evm = new \Doctrine\Common\EventManager;
        foreach (array_keys($container->findByTag('doctrineListener')) as $name) {
            $evm->addEventSubscriber($container->getService($name));
        }

        return $evm;
    }

    /**
     * @param array
     * @param \Doctrine\ORM\Configuration
     * @param \Doctrine\Common\EventManager|NULL
     * @return \Doctrine\DBAL\Connection
     */
    public static function createConnection(array $config, \Doctrine\ORM\Configuration $cfg, \Doctrine\Common\EventManager $evm = NULL)
    {
        if (!$evm) {
            $evm = new \Doctrine\Common\EventManager;
        }

        if (isset($config['driver']) && $config['driver'] == 'pdo_mysql' && isset($config['charset'])) {
            $evm->addEventSubscriber(
                new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit($config['charset'])
            );
        }

        return \Doctrine\DBAL\DriverManager::getConnection($config, $cfg, $evm);
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
            $compiler->addExtension('doctrine', new $class);
        };
    }



}