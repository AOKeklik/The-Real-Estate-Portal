<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require "./vendor/autoload.php";

    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(
        $_SERVER["REQUEST_METHOD"] === "POST" &&
        isset($_POST["name"]) &&
        isset($_POST["email"]) &&
        isset($_POST["email_agent"]) &&
        isset($_POST["subject"]) &&
        isset($_POST["message"])        
    ){
        try{
            $name = htmlspecialchars(trim($_POST["name"]));
            $email = htmlspecialchars(trim($_POST["email"]));
            $email_agent = htmlspecialchars(trim($_POST["email_agent"]));
            $subject = htmlspecialchars(trim($_POST["subject"]));
            $message = htmlspecialchars(trim($_POST["message"]));

            $errors = [];

            if($name === "")
                $errors["name"][] = ["message"=>"<small class='form-text text-danger'>The name field is required!</small>", "id" => "name"];
            
            if($email === "")
                $errors["email"][] = ["message"=>"<small class='form-text text-danger'>The email field is required!</small>", "id" => "email"];

            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
                $errors["email"][] = ["message"=>"<small class='form-text text-danger'>Email must be valid!</small>", "id" => "email"];
            
            if($subject === "")
                $errors["subject"][] = ["message"=>"<small class='form-text text-danger'>The subject field is required!</small>", "id" => "subject"];
            
            if($message === "")
                $errors["message"][] = ["message"=>"<small class='form-text text-danger'>The message field is required!</small>", "id" => "message"];


            if(!empty($errors))
                throw new Exception(json_encode(["error"=>$errors]));

            $phpmailler = new PHPMailer(true);

            $phpmailler->isSMTP();
            $phpmailler->Host = SMTP_HOST;
            $phpmailler->Port = SMTP_PORT;
            $phpmailler->SMTPSecure = SMTP_SECURE;
            $phpmailler->Password = SMTP_PASSWORD;
            $phpmailler->Username = SMTP_USERNAME;
            $phpmailler->SMTPAuth = true;

            $phpmailler->setFrom(SMTP_FROM);
            $phpmailler->addAddress($email_agent);

            $phpmailler->isHTML(true);
            $phpmailler->Subject = "Enquery Form Email from Customer";
            $phpmailler->Body = "<p><strong>Full Name:</strong>$name</p>";
            $phpmailler->Body .= "<p><strong>Email:</strong>$email</p>";
            $phpmailler->Body .= "<p><strong>Subject:</strong>$subject</p>";
            $phpmailler->Body .= "<p><strong>Message:</strong>$message</p>";

            if(!$phpmailler->send())
            throw new Exception(json_encode(["success"=>["message"=>"<div class='alert alert-danger'>Oops! Something went wrong. Please try again later.</div>"]]));
                        
            echo json_encode(["success"=>["message"=>"<div class='alert alert-success'>Thank you! Your message has been sent successfully.</div>"]]);
        }catch(Exception $err){
            echo $err->getMessage();
        }
    }