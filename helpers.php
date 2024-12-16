<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

class Mail {
    private static $toAddress;
    private static $subject;
    private static $message;
    private static $attachment;

    private const SMTP_HOST = 'sandbox.smtp.mailtrap.io';
    private const SMTP_PORT = 2525;
    private const SMTP_USERNAME = '8c1c7fb7ffe625';
    private const SMTP_PASSWORD = '0673cb81e66408';
    private const SMTP_FROM = 'contact@mail.com';

    public static function to($address) {
        self::$toAddress = $address;
        return new static;
    }

    public static function subject($subject) {
        self::$subject = $subject;
        return new static;
    }

    public static function message($message) {
        self::$message = $message;
        return new static;
    }

    public static function attach($attachmentPath) {
        self::$attachment = $attachmentPath;
        return new static;
    }

    static function send () {
        $phpmailer = new PHPMailer(true);

        try { 
            $phpmailer->isSMTP();
            $phpmailer->Host = self::SMTP_HOST;
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = self::SMTP_PORT;
            $phpmailer->Username = self::SMTP_USERNAME;
            $phpmailer->Password = self::SMTP_PASSWORD;
            $phpmailer->SMTPSecure = 'tls';
        
            $phpmailer->setFrom(self::SMTP_FROM);
            $phpmailer->addAddress(self::$toAddress);

            // $phpmailer->addReplyTo('contact@mail.com');
            // $phpmailer->addCC('cc@mail.com');
            // $phpmailer->addBCC('bcc@mail.com');
            
            if (!empty(self::$attachment)) {
                $phpmailer->addAttachment(self::$attachment);
            }
        
            $phpmailer->isHTML(true);
            $phpmailer->Subject = self::$subject;
            $phpmailer->Body = self::$message;
        
            if($phpmailer->send())
                return true;
        } catch (Exception $err) {
            echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
            return false;
        }    
    }
}

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
    static function get ($key):string {
        if(!isset($_SESSION[$key]))
            return "";
        
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    static function forget ($key):void {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    static function has ($key):bool {
        return isset($_SESSION[$key]) ? true : false ;
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
    static function user() {}
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
    static function exists($pdo,$table,$email,$password){
        try {
            $table = htmlspecialchars(trim($table));
            $email = htmlspecialchars(trim($email));
            $password = htmlspecialchars(trim($password));
            
            if(!empty($table) && !empty($email) && !empty($password)) {
                $sql = "select email,password from $table where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();

                if($stmt->rowCount() > 0)  {
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);            

                    if(password_verify($password, $admin["password"]))
                        return true;

                    return false;
                } else
                    return false;
            }

            return false;
        } catch (PDOException $err) {
            error_log("PDO Exists: ".$err->getMessage());
            return false;
        }
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
    static function exist_email(PDO $pdo,$table,$key){
        try {
            $table = htmlspecialchars(trim($table));
            $email = self::value($key);
            
            if(!empty($table) && !empty($email)) {
                $sql = "select email from $table where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();

                if($stmt->execute())
                    if($stmt->rowCount() > 0)
                        return true;

                
                return false;
            }

            return false;
        } catch (PDOException $err) {
            error_log("PDO Exists: ".$err->getMessage());
            return false;
        }
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