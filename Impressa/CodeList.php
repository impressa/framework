<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa;

/**
 * Description of CodeList
 *
 * @author puty
 */
abstract class CodeList
{

    /**
     *
     * @param string $code
     */
    public static function getName($code, $strict = false) {
        if (is_null($code)) {
            return NULL;
        }
        $codes = static::getCodes();
        if(array_key_exists($code, $codes)){
            return $codes[$code];
        }
        if($strict){
            throw new \Nette\InvalidArgumentException("Constant does not exist!");
        }
        return null;
    }
}    