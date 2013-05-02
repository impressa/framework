<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Impressa\Validator;
/**
 * Description of VatValidator
 *
 * @author puty
 */
class VatValidator {

   public static function validate($control){

        if(is_string($control)){
            $value = $control;
        }else{
            $value = $control->getValue();
        }
        if($value == '') return true;

        $cc = \Nette\Utils\Strings::upper(substr($value, 0, 2));
        $num = substr($value, 2);

        $url = "http://ec.europa.eu/taxation_customs/vies/viesquer.do";
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, "memberStateCode=$cc&number=$num"); // add POST fields
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        if(strpos($result, 'Yes, valid VAT number')){
            return true;
        }

        return false;
    }
}