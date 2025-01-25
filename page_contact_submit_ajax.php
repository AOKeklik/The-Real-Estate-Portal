<?php
    include "./layout_config.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require "./vendor/autoload.php";
    
    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"]) && isset($_POST["email"])  && isset($_POST["message"])){

        $name = htmlspecialchars(trim(base64_decode($_POST["name"])));
        $email = htmlspecialchars(trim(base64_decode($_POST["email"])));
        $message = htmlspecialchars(trim(base64_decode($_POST["message"])));

        if(empty($name))
            $errors["name"][] = "The name field is required!";

        if(strlen($name) <= 3 || strlen($name) >=15)
            $errors["name"][] = "The name must be between 3 and 15 characters!";

        if(empty($email))
            $errors["email"][] = "The email field is required!";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "Email must be valid!";

        if(empty($message))
            $errors["message"][] = "The message field is required!";

        if(!empty($errors)){
            echo json_encode(["error"=>$errors]);
            return;
        }     

        $phpmailer = new PHPMailer(true);

        try{
            $phpmailer->isSMTP();
            $phpmailer->SMTPAuth = true;
            $phpmailer->Host = SMTP_HOST;
            $phpmailer->Port = SMTP_PORT;
            $phpmailer->Username = SMTP_USERNAME;
            $phpmailer->Password = SMTP_PASSWORD;
            $phpmailer->SMTPSecure = SMTP_SECURE;
            
            $phpmailer->setFrom(SMTP_FROM);
            $phpmailer->addAddress($email);
    
            $phpmailer->isHTML(true);
            $phpmailer->Subject = "Contact Form Message";
            $phpmailer->Body = "<p><strong>Name:</strong> $name <br>";
            $phpmailer->Body .= "<strong>Email:</strong> $email <br>";
            $phpmailer->Body .= "<strong>Message:</strong> ".nl2br($message)."</p>";
    
            if(!$phpmailer->send())
                throw new Exception("The mail could not be sent, please try again.");

            echo json_encode(["success"=>["message"=>"Mail has been sent successfully."]]);
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getmessage()]]);
        }
    }