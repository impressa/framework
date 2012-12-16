<?php
/**
 * Created by JetBrains PhpStorm.
 * User: puty
 * Date: 22.7.2012
 * Time: 14:08
 * To change this template use File | Settings | File Templates.
 */
namespace Impressa\Config\Extensions;

use Nette\Config\Configurator,
Nette\DI\ContainerBuilder,
Doctrine\Common\Cache\Cache,
Nette\Framework;

class ImpressaExtension extends \Nette\Config\CompilerExtension
{

    public $defaults = array(
        'imageManager' => array(
            'basePath' => '/files/images',
            'mapping' => array(),
        ),
		'mailer' => array(
			'templatesPath' => '%appDir%/templates/Emails',
            'defaultSender' => null
		)
    );

    public function loadConfiguration()
    {

			$config = $this->getConfig($this->defaults);
        $container = $this->getContainerBuilder();

//        $container->addDefinition('imageManager')->setClass('\Impressa\ImageManager\ImageService')
//            ->setFactory('Impressa\Config\Extensions\Impressa::createImageManager',array('%wwwDir%', $config['imageManager']));
//        $latte = $container->getDefinition('nette.latte');
//        $latte->addSetup('\Impressa\ImageManager\ImageService::installLatteMacros', array('@nette.latte'));
//
//        $container->addDefinition('ecomm')->setClass('\Impressa\Payments\Ecomm')
//            ->setFactory('Impressa\Config\Extensions\Impressa::createEcommService',array($config['ecomm']['url'], $config['ecomm']['keyStore'], $config['ecomm']['keyStorePassword']));

		$container->addDefinition($this->prefix('mailer'))->setClass('\Impressa\Mail\Mailer', array('@nette.mailFactory', '@application', $config['mailer']['templatesPath'], $config['mailer']['defaultSender']));
    }


    /**
     * Register extension to compiler.
     *
     * @param \Nette\Config\Configurator $configurator
     */
    public static function register(\Nette\Config\Configurator $configurator) {
        $class = get_called_class();
        $configurator->onCompile[] = function(Configurator $configurator, \Nette\Config\Compiler $compiler) use($class) {
            $compiler->addExtension('impressa', new $class);
        };
    }

    public static function createImageManager($wwwDir, $params){
        return new \Impressa\ImageManager\ImageService($wwwDir, $params['basePath'], $params['mapping']);
    }

    public static function createEcommService($url, $keystore, $keystorePassword, $verbose = false){
        return new \Impressa\Payments\Ecomm($url, $keystore, $keystorePassword, $verbose);
    }



}
