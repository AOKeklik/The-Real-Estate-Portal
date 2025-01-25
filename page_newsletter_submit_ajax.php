<?php
    include "./layout_config.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require "./vendor/autoload.php";
    
    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])){

        $email = htmlspecialchars(trim(base64_decode($_POST["email"])));

        if(empty($email))
            $errors["email"][] = "The email field is required!";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "Email must be valid!";

        if(!empty($errors)){
            echo json_encode(["error"=>$errors]);
            return;
        }     

        try{

            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM
                    subscribers
                WHERE
                    email=?
                LIMIT 
                    1
            ");
            $stmt->execute([$email]);

            if($stmt->rowCount() > 0)
                throw new PDOException("This email is already subscribed to the newsletter.");

            if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                $ip_address=$_SERVER["HTTP_X_FORWARDED_FOR"];
            else
                $ip_address=$_SERVER["REMOTE_ADDR"];

            $token=bin2hex(random_bytes(32/2));
            $hased_toke=password_hash($token,PASSWORD_DEFAULT);
            $link=BASE_URL."page_newsletter_verify.php?token=".$hased_toke."&email=".$email;


            $stmt=$pdo->prepare("
                INSERT INTO subscribers
                    (email,ip_address,token)
                VALUES
                    (?,?,?)
            ");
            $stmt->execute([$email,$ip_address,$token]);

            if($stmt->rowCount() == 0)
                throw new Exception("The mail could not be sent, please try again.");

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
                $phpmailer->Subject = "Verify Subscription";
                $phpmailer->Body = "Please click on the following link to verify your subscription: <br>";
                $phpmailer->Body .= "<a href='$link'>Click</a>";

                if(!$phpmailer->send())
                    throw new Exception("The mail could not be sent, please try again.");

                echo json_encode(["success"=>["message"=>"Please check your email to confirm the email subscription.<br>Check your spam folder too if you do not receive the email in the normal email inbox."]]);
            }catch(Exception $err){
                echo json_encode(["error"=>["message"=>$err->getmessage()]]);
            }
        } catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getmessage()]]);
        }
    }