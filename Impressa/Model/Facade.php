<?php
/**
 * Facade
 */
namespace Impressa\Model;

abstract class Facade extends \Nette\Object
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	function __construct(\Doctrine\ORM\EntityManager $em) {
		$this->em = $em;
	}

	/**
	 * @param Doctrine\ORM\EntityManager $em
	 */
	public function injectEntityManager(\Doctrine\ORM\EntityManager $em) {
		$this->em = $em;
	}




}
