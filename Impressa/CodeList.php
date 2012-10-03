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
abstract class CodeList {

    /**
     *
     * @param string $code
     */
    public static function getName($code){
        if(is_null($code)){
            return null;
        }
        $codes = static::getCodes();
        return $codes[$code];
    }
}   