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

    /** @var string */
    protected $defaultFrom;

	function __construct($messageFactory, $application, $templatesPath, $defaultFrom = null) {
		$this->application = $application;
		$this->messageFactory = $messageFactory;
		$this->templatesPath = $templatesPath;
        $this->defaultFrom = $defaultFrom;
	}

	public function createEmailTemplate($name) {
		$template = $this->application->presenter->createTemplate();
		$template->setFile($this->templatesPath . '/' . $name . '.latte');
		return $template;
	}

	public function createMessage($useDefaultSender = true) {
        $message = $this->messageFactory->invoke();
        if($this->defaultFrom && $useDefaultSender){
            $message->setFrom($this->defaultFrom);
        }
        return $message;
	}
}
