<?php


class HTML {

    public static $values = array();
    public static $formStr = '';
    public static $autoLoadTrigger;

    public static $br = "<br />";

    public static function init ($values = array ()) {

        if (!empty(self::$autoLoadTrigger)){
            $trigger = self::$autoLoadTrigger;
            if (isset($_POST[$trigger])) {
                self::$values = $_POST;
            } else if (isset($_GET[$trigger])){
                self::$values = $_GET;
            } else {
                self::$values = $values;
            }
        } 
    }

    public static function disableBr (){
        echo self::$br = '';
    }

    public static function enableBr (){
        self::$br = "<br />";
    }

    public static function setValues ($values) {
        self::$values = $values;
    }

    public static function formStart (
            $name = 'form', $method ='post', $action = '',
            $enctype = "multipart/form-data") {
        $str = "<form action=\"$action\" method=\"$method\" name=\"$name\" enctype = \"$enctype\">\n";
        $str.= "<fieldset>\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function legend ($legend){
        $str = "<legend>$legend</legend>\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function formEnd (){
        $str = "</fieldset></form>\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function label ($label_for, $label) {
        $str = "<label for=\"$label_for\">$label</label>" . self::$br . "\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function setValue ($name, $value){
        if (isset(self::$values[$name])){
            return self::$values[$name];
        } else {
            return '';
        }

    }

    public static function text ($name, $value = '', $extra = array()){
        if (!isset($extra['size'])){
            $extra['size'] = HTML_FORM_TEXT_SIZE;
        }

        $value = self::setValue($name, $value);
        $extra = self::parseExtra($extra);
        $str = "<input type=\"text\" name=\"$name\" $extra value=\"$value\" />" . self::$br . "\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function password ($name, $value = '', $extra = array()){
        if (!isset($extra['size'])){
            $extra['size'] = HTML_FORM_TEXT_SIZE;
        }

        $value = self::setValue($name, $value);
        $extra = self::parseExtra($extra);
        $str = "<input type=\"password\" name=\"$name\" $extra value=\"$value\" />" . self::$br . "\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function textarea ($name, $value = '', $extra = array()){
        if (!isset($extra['rows'])){
            $extra['rows'] = HTML_FORM_TEXTAREA_HT;
        }

        if (!isset($extra['cols'])){
            $extra['cols'] = HTML_FORM_TEXTAREA_WT;
        }

        $value = self::setValue($name, $value);
        $extra = self::parseExtra($extra);
        $str =  "<textarea name=\"$name\" $extra>$value</textarea>" . self::$br . "\n";
        self::$formStr.= $str;
        return $str;
    }

    public static function checkbox ($name, $value = '1', $extra = array ()) {
        $extra = self::parseExtra($extra);
        
        $value = self::setValue($name, $value);
        if ($value){
            $extra.= " checked=\"yes\" ";
        }

        $str = "<input type=\"checkbox\" name=\"$name\" value=\"$value\" $extra />" . self::$br . "\n";
        self::$formStr.= $str ;
        return $str;
    }

    public static function submit ($name, $value, $extra = array ()) {
        $extra = self::parseExtra($extra);
        $str =  "<input type=\"submit\" $extra name=\"$name\" value=\"$value\" />" . self::$br . "";
        self::$formStr.= $str ;
        return $str;
    }

    public static function parseExtra ($extra) {
        $str = '';
        
        foreach ($extra as $key => $val){
            $str.= " $key = \"$val\" ";
        }
        return $str;
    }

    public static function createLink ($url, $title, $options = array()) {
        $options = self::parseExtra($options);
        $str = "<a href=\"$url\" $options>$title</a>";
        return $str;
    }

    /**
     *
     * @param  string $url
     * @return string $url rewritten is rewrite url exists
     */
    public static function getUrl ($url) {
       if (class_exists('rewrite_manip')) {
            $alt_uri = rewrite_manip::getRowFromRequest($url);
            if (isset($alt_uri)){
                $url = $alt_uri;
            }
        }
        return $url;
    }

    public static function createImage ($src, $options = array()) {
        $options = self::parseExtra($options);
        $str = "<img src=\"$src\" $options />";

        return $str;
    }
    public static function entitiesEncode($values){
        if (is_array($values)){
            foreach($values as $key => $val){
                $values[$key] = htmlentities($val, ENT_COMPAT, 'UTF-8');
            }
        } else if (is_string($values)) {
            $values =  htmlentities($values, ENT_COMPAT, 'UTF-8');
        } else {
            $values = '';
        }
        return $values;
    }

    public static function entitiesDecode($values){
        if (is_array($values)){
            foreach($values as $key => $val){
                $values[$key] = html_entity_decode($val, ENT_COMPAT, 'UTF-8');
            }
        } else if (is_string($values)) {
            $values =  html_entity_decode($values, ENT_COMPAT, 'UTF-8');
        } else {
            $values = '';
        }
        return $values;
    }

    public static function widget ($class, $method, $name = null, $value = null){
        include_module ($class);
        $value = self::setValue($name, $value);
        $str = $class::$method($name, $value);
        self::$formStr.= $str ;
        return $str;
    }
    /*
    public static function getUl ($elements, $value_field, $options = null){
        if ($options) {
            $options = self::parseExtra($options);
        } else {
            $options = '';
        }
        $str = "<ul" . $options . ">\n";
        foreach ($elements as $key => $val) {
            $str.="<li>$val[$value_field]</li>\n";
        }
        $str.= "</ul>\n";
        return $str;
    }*/
}