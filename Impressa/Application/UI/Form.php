<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Impressa\UI;

/**
 * Description of Form
 *
 * @author puty
 */
class Form extends \Nette\Application\UI\Form {

    public function addDatePicker($name, $label, $options = array()) {
        return $this[$name] = new \Impressa\Forms\Controls\DatePicker($label, $options);
    }

    public function addTextEditor($name, $label, $options = array()) {
        return $this[$name] = new \Impressa\Forms\Controls\TextEditor($label, $options);
    }
    
    public function addMultipleUpload($name, $label, $options = array()) {
        return $this[$name] = new \Impressa\Forms\Controls\PLUpload($label, $options);
    }

    public function addCheckboxList($name, $label, $items){
        return $this[$name] = new \Impressa\Forms\Controls\CheckboxList($label, $items);
    }



}

