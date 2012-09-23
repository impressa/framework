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

class ConsoleExtension extends \Nette\Config\CompilerExtension
{

    public $defaults = array();

    public function loadConfiguration()
    {

        $config = $this->getConfig();
        $container = $this->getContainerBuilder();

        $container->addDefinition('cli')->setClass('Symfony\Component\Console\Application')
            ->setFactory('Impressa\Config\Extensions\ConsoleExtension::createConsole', array('@entityManager'));

    }


    /**
     * Register extension to compiler.
     *
     * @param \Nette\Config\Configurator $configurator
     */
    public static function register(\Nette\Config\Configurator $configurator)
    {
        $class = get_called_class();
        $configurator->onCompile[] = function (Configurator $configurator, \Nette\Config\Compiler $compiler) use ($class) {
            $compiler->addExtension('console', new $class);
        };
    }

    public static function createConsole(\Doctrine\ORM\EntityManager $em)
    {
        $cli = new \Symfony\Component\Console\Application('Impressa Commad Line Interface');
        $cli->setCatchExceptions(true);

        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
            'dialog' => new \Symfony\Component\Console\Helper\DialogHelper()
        ));

        $cli->setHelperSet($helperSet);
        $cli->addCommands(array(

            new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
            new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
        ));
        \Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);


        return $cli;
    }


}
