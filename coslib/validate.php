<?php

/**
 * @package coslib
 */

/**
 * class for validating most common thing: URL's and emails. 
 * 
 * @package coslib
 * class for validating email and and emailAndDomain
 */
class cosValidate {
    /**
     * method for validating email with php filter_var function
     * @param   string  $email email
     * @return  boolean $res true on success and false on failure 
     */
    public static function email ($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }

    /**
     * method for validating email with php filter_var function
     * @param   string  $url the url to validate
     * @return  boolean $res true on success and false on failure
     */
    public static function urlWithFilter ($url){
        require_once 'Validate.php';
        if (!filter_var($url, FILTER_VALIDATE_URL)){
            return false;
        }
        return true;
    }

    /**
     * method for validating url with PEAR::Validate filter 
     * @param   string  $url the url to validate
     * @return  boolean $res true on success and false on failure
     */
    public static function url ($url){
        require_once 'Validate.php';
        $schemes = array ('http', 'https');
        if (!Validate::uri($url, array('allowed_schemes' => $schemes))){
            return false;
        }
        return true;
    }

    /**
     * method for vaildating email and an emails domain with PEAR:Validate
     * @param   string  $email the email to validate email
     * @param array $options set some options
     * @return  boolean $res true on success and false on failure 
     */
    public static function validateEmailAndDomain ($email, $options = null){
        require_once 'Validate.php';

        if (!$options){
            $options = array('check_domain' => 'true');
        }
               
        if (Validate::email($email, $options)) {
            return true;
        }
        return false;
    }
}