<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

class Redirect {
    private static $url;

    public static function route($url=null){
        $dir = self::isAdmin() ? ADMIN_URL : FRONTEND_URL;

        self::$url = $url ? $dir.$url : ($_SERVER["HTTP_REFERER"] ?? "/");

        return new self;
    }
    public static function with($key=null, $val=null){
        if(!is_null($key) && !is_null($val))
            Session::put($key,$val);

        header("Location: ".self::$url);
        exit;
    }
    public static function isAdmin () {
        return strpos($_SERVER["REQUEST_URI"], "/admin") !== false;
    }
}

class Session {
    static function put ($key, $val):void {
        $_SESSION[$key] = $val;
    }
    static function get ($key):string|array {
        if(!isset($_SESSION[$key]))
            return "";
        
        return $_SESSION[$key];
    }
    static function forget ($key):void {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    static function has ($key):bool {
        return isset($_SESSION[$key]) ? true : false ;
    }
    static function flash($key): string {
        if (!isset($_SESSION[$key]))
            return "";
        
        $msg = $_SESSION[$key];
        self::forget($key);
        return $msg;
    }
}

class Auth {
    static function isLoggedIn($role="admin"):bool {
        if ($role=="admin") {
            if(isset($_SESSION["admin"]))
                return true;
        }

        if ($role=="customer") {
            if(isset($_SESSION["customer"]))
                return true;
        }
        

        return false;
    }
    static function logout():void {
        Session::forget("admin");
        Redirect::route("auth_login.php")->with();
    }
    static function user($role="customers") {
    }
}

class Form {
    static $data = [];
    static $errors = [];

    static function get_extension($fileName){
        return pathinfo($_FILES[$fileName]["name"], PATHINFO_EXTENSION);
    }
    static function get_temp($fileName){
        return $_FILES[$fileName]["tmp_name"];
    }
    static function get_size($fileName){
        return $_FILES[$fileName]["size"];
    }

    static function value ($key) {
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST[$key])) 
            return htmlspecialchars(trim($_POST[$key]));

        if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET[$key])) 
            return htmlspecialchars(trim($_GET[$key]));

        return "";
    }
    static function same ($key,$confirmKey):bool {
        return self::value ($key) == self::value ($confirmKey);
    }
    static function required($key){

        if(empty(self::value($key)))
            return true;
        
        return false;
    }
    static function email ($key) {

        if(!filter_var(self::value($key), FILTER_VALIDATE_EMAIL))
            return true;
        
        return false;
    }
    static function minmax($rule,$key){
        $rules = explode("|",$rule);
        
        foreach($rules as $rule) {
            if(strpos($rule,"min:") === 0) {
                $minLength = (int) substr($rule,4);
                if (strlen(self::value($key)) < $minLength) {
                    return true;
                }
            }

            if(strpos($rule,"max:") === 0) {
                $maxLength = (int) substr($rule,4);
                if(strlen(self::value($key)) > $maxLength) {
                    return true;
                }
            }
        }

        return false;
    }
    static function has_file($key) {
        return isset($_FILES[$key]) && !empty($_FILES[$key]["name"]);
    }
    static function has_extension($key) {
        $allowedTypes = ["jpg","jpeg","png"];

        return in_array(Form::get_extension($key), $allowedTypes);
    }
    static function has_size ($key) {
        $allowedSize = 2 * 1024 * 1024;
        return $_FILES[$key]["size"] <= $allowedSize;
    }


    static function has_submit ($key) {
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST[$key])) 
            return true;

        if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET[$key])) 
            return true;

        return false;
    }
    static function get_old($key){            
    
        return  self::value($key);
    }
    static function get_data($input=null){            
        if (in_array($_SERVER["REQUEST_METHOD"], ["POST", "GET"])) {
            $requestData = $_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET;
    
            foreach ($requestData as $key => $val) {
                if (stripos($key, "form") !== false) continue;
                self::$data[$key] = htmlspecialchars(trim($val));
            }
    
            foreach ($requestData as $key => $val) {
                unset($requestData[$key]);
            }
        }

        if(!is_null($input))
            return  htmlspecialchars(trim(self::$data[$input]));

        return  self::$data;
    }
    static function get_error($fieldName=""){
        if(empty($fieldName))
            return empty(Form::$errors) ? false : Form::$errors;

        return empty(Form::$errors) || !isset(Form::$errors[$fieldName]) ? false : Form::$errors[$fieldName][0];
    }
    static function push_error($fieldName,$message){
        Form::$errors[$fieldName][] = "<small class='form-text text-danger'>$message</small>";
    }
    static function has_error($fieldName=null){
        if(array_key_exists($fieldName, Form::$errors))
            return Form::$errors[$fieldName];

        return count(Form::$errors) == 0 ? false : true;
    }
}