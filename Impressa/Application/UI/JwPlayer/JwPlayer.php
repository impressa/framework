<?php



namespace Impressa\Application\UI;

/**
 * Visual paginator control.
 *
 * @author     puty
 */
class JwPlayer extends Control {

    protected $options;
    

    function __construct($parent, $name, $options) {
        parent::__construct($parent, $name);
        $default = array(
            "flashplayer" => "/jwplayer/player.swf",
            "controlbar" => "bottom",            
        );
        $this->options = array_merge($default,$options);
    }

    

  

    public function render() {
        $this->template->setFile(dirname(__FILE__) . '/template.phtml');
        $this->template->options = \Nette\Utils\Json::encode($this->options);
        $this->template->id = $this->getUniqueId();
        $this->template->render();
    }

}