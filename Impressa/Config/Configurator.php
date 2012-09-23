<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 13.9.2012
 * Time: 21:54
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Config;

class Configurator extends \Nette\Config\Configurator
{
    /**
     * @return bool
     */
    public function isConsole()
    {
        return PHP_SAPI == "cli";
    }

    protected function createCompiler()
    {
        $compiler =  parent::createCompiler();
        $compiler->addExtension('doctrine', new \Impressa\Config\Extensions\DoctrineExtension())
//                ->addExtension('impressa', new \Impressa\Config\Extensions\Impressa())
                ->addExtension('console', new \Impressa\Config\Extensions\ConsoleExtension());
        return $compiler;
    }

	public function createContainer() {
		$container = parent::createContainer();
		$cacheDir = $this->getCacheDirectory();
		$cache = new \Nette\Caching\Cache(new \Nette\Caching\Storages\PhpFileStorage($cacheDir), 'Impressa.Accessors');
		$cacheKey = array($this->parameters, $this->files);

		$cached = $cache->load($cacheKey);
		if(!$cached){
			$code = $this->buildAccessors($container);
			$cache->save($cacheKey, $code);
			$cached = $cache->load($cacheKey);
		}
		\Nette\Utils\LimitedScope::load($cached['file'], TRUE);
		return $container;

	}

	protected function buildAccessors($container){
		$code = "<?php\n";
		foreach ($container->getReflection()->getMethods() as $method) {
			/* @var $method  \Nette\Reflection\Method*/
			if(\Nette\Utils\Strings::startsWith($method->name, 'createService')){
				$type = $method->getAnnotation('return');
				$serviceName = substr($method->name,strlen('createService'));
				$interface = new \Nette\Utils\PhpGenerator\ClassType($serviceName . 'Accessor');
				$interface->type = 'interface';
				$interface->addMethod('get')->addDocument("@return $type");
				$code .= $interface . "\n";
			}

		}

		return $code;

	}

}
