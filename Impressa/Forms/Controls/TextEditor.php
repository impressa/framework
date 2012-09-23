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
class TextEditor extends \Nette\Forms\Controls\TextArea{
    
    public function __construct($caption = NULL, $options = array()) {
        parent::__construct($caption);
        
        $this->control->addAttributes(array('data-ui-type' => 'texteditor', 'data-ui-options' => \Nette\Utils\Json::encode($options)));
    }

}


