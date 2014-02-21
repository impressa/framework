<?php
/**
 * Message
 */
namespace Impressa\Mail;

class Message extends \Nette\Mail\Message
{

	protected $defaultReceiver = NULL;


	/**
	 * Adds email recipient.
	 * @param  string  email or format "John Doe" <doe@example.com>
	 * @param  string
	 * @return self
	 */
	public function addTo($email, $name = NULL ){
		if ($this->defaultReceiver){
			// do nothing
			return $this;
		} else {
			parent::addTo($email, $name);
		}
	}

	public function send()
	{
		if ($this->defaultReceiver){
			foreach ($this->defaultReceiver as $mail){
				parent::addTo($mail);
			}
		}

		parent::send();
	}

	/**
	 * @param null $defaultReceiver
	 */
	public function setDefaultReceiver($defaultReceiver)
	{
		$this->defaultReceiver = $defaultReceiver;
	}

	/**
	 * @return null
	 */
	public function getDefaultReceiver()
	{
		return $this->defaultReceiver;
	}



}
 