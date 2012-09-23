<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Forms\Controls;
/**
 * Description of DatePicker
 *
 * @author puty
 */
class PLUpload extends \Nette\Forms\Controls\BaseControl{
    
    public function __construct($caption = NULL, $options = array()) {
        parent::__construct($caption);
        $this->control = \Nette\Utils\Html::el('div');
        
        $this->control->addAttributes(array('data-ui-type' => 'plupload', 'data-ui-options' => \Nette\Utils\Json::encode($options)));
    }

}


