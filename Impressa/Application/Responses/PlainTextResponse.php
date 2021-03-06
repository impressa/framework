<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Impressa\Application\Responses;

use Nette;



/**
 * String output response.
 *
 * @author     David Grudl
 */
class PlainTextResponse extends Nette\Object implements Nette\Application\IResponse
{
	/** @var mixed */
	private $source;



	/**
	 * @param  mixed  renderable variable
	 */
	public function __construct($source)
	{
		$this->source = $source;
	}



	/**
	 * @return mixed
	 */
	final public function getSource()
	{
		return $this->source;
	}



	/**
	 * Sends response to output.
	 * @return void
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType("plain/text");
			echo $this->source;
		
	}

}
