<?php
/**
 * CallbackAccessor
 */
class CallbackAccessor extends \Nette\Object
{
	private $instance;
	private $callback;

	function __construct(/*callable*/ $callback)
	{
		$this->callback = $callback;
	}

	function get()
	{
		if (!$this->instance) {
			$this->instance = call_user_func($this->callback);
		}
		return $this->instance;
	}

}
