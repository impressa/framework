<?php
/**
 * Mailer
 */
namespace Impressa\Mail;

class Mailer extends \Nette\Object
{
	/** @var \Nette\Application\Application */
	protected $application;

	/** @var \Nette\Callback */
	protected $messageFactory;

	/** @var string */
	protected $templatesPath;

	function __construct($messageFactory, $application, $templatesPath) {
		$this->application = $application;
		$this->messageFactory = $messageFactory;
		$this->templatesPath = $templatesPath;
	}

	public function createEmailTemplate($name) {
		$template = $this->application->presenter->createTemplate();
		$template->setFile($this->templatesPath . '/' . $name . '.latte');
		return $template;
	}

	public function createMessage() {
		return $this->messageFactory->invoke();
	}
}
