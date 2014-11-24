<?php

namespace Impressa\Validator;

/**
 * VINformer 4.0 is decode only 17-digits VIN. Frame numbers of Japanese RHD-cars 
 * or 14-digits UAE identification numbers not are up to quality of ISO 3779-1983 standard 
 * and NOT recognized by VINformer. For Japanese cars is FRAME# DECODE service.
 *
 * @author rasto
 */
class VinValidator {

    public static function validate($value) {
        //$value = $control->getValue();

        if ($value != NULL) {
            $url = "http://www.stolencars24.eu/retrieve.php";
            $ch = curl_init();    // initialize curl handle 
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to 
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable 
            curl_setopt($ch, CURLOPT_TIMEOUT, 6); // times out after 4s 
            curl_setopt($ch, CURLOPT_POST, 1); // set POST method 
            curl_setopt($ch, CURLOPT_POSTFIELDS, "vid=$value&submit=Search"); // add POST fields 
            $result = curl_exec($ch); // run the whole process 
            curl_close($ch);

            if (strpos($result, 'License Plate Number:') || strpos($result, 'Make:') || strpos($result, 'Model:')) {
                return \Constants\VinCheck::STOLEN;
            }

            return \Constants\VinCheck::VALID;
        } else {
            return \Constants\VinCheck::NOT_CHECKED;
        }
    }

}

