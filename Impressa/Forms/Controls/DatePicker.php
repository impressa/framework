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

    protected $defaultFormat = "d.mm.yy";

    public function __construct($caption = NULL, $options = array()) {
        parent::__construct($caption);
        if(!isset($options['dateFormat'])){
            $options['dateFormat'] = $this->defaultFormat;
        }else{
            $this->defaultFormat = $options['dateFormat'];
        }
        $this->control->addAttributes(array('data-ui-type' => 'datepicker', 'data-ui-options' => \Nette\Utils\Json::encode($options)));

    }

    public function setDefaultValue($value) {
        if($value instanceof \DateTime){
            $value = $value->format($this->convertToPHPFormat($this->defaultFormat));
        }
        $this->control->addAttributes(array('data-ui-defaultValue' => $value));
        return parent::setDefaultValue($value);
    }

    public function getValue(){
        return parent::getValue() ? \DateTime::createFromFormat($this->convertToPHPFormat($this->defaultFormat), parent::getValue()) : null;
    }

    protected function convertToPHPFormat($format){
        $format = str_replace('d', 'j', $format);
        $format = str_replace('jj', 'd', $format);

        $format = str_replace('m', 'n', $format);
        $format = str_replace('nn', 'm', $format);

        $format = str_replace('yy', 'Y', $format);
        return $format;

    }

}