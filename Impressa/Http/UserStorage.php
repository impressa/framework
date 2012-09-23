<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Impressa\Http;

use Nette,
	Nette\Security\IIdentity;



/**
 * Session storage for user object.
 *
 * @author puty
 */
class UserStorage extends Nette\Http\UserStorage
{
        
        
        protected $em;
    
        public function  __construct(Nette\Http\Session $sessionHandler, \Doctrine\ORM\EntityManager $em)
	{
                $this->em = $em;
		parent::__construct($sessionHandler);
	}
	/**
	 * Sets the user identity.
	 * @param  IIdentity
	 * @return UserStorage Provides a fluent interface
	 */
	public function setIdentity(IIdentity $identity = NULL)
	{
		$this->getSessionSection(TRUE)->identity = $identity != null ? new \Impressa\Security\IdentityInternal($identity->id, $identity->reflection->name) : null;
		return $this;
	}



	/**
	 * Returns current user identity, if any.
	 * @return Nette\Security\IIdentity|NULL
	 */
	public function getIdentity()
	{
		$session = $this->getSessionSection(FALSE);
                if($session){
                    if($session->identity instanceof IIdentity){
                        return $this->em->find($session->identity->name,$session->identity->id);
                    }
                    
                }
                return null;
                
		
	}



	

}
