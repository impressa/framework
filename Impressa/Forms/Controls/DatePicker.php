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
class DatePicker extends \Nette\Forms\Controls\TextBase{
    
    public function __construct($caption = NULL, $options = array()) {
        parent::__construct($caption);
        $this->control->addAttributes(array('data-ui-type' => 'datepicker', 'data-ui-options' => \Nette\Utils\Json::encode($options)));
        
    }

}


