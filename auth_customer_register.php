<?php include "./layout_top.php"?>
<?php
    if (isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-dashboard");
        exit;
    }
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require "vendor/autoload.php";

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {

        $email = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : "";
        $password = isset($_POST["password"]) ? htmlspecialchars(trim($_POST["password"])) : "";
        $confirm_password = isset($_POST["confirm_password"]) ? htmlspecialchars(trim($_POST["confirm_password"])) : "";

        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(empty($password))
            $errors["password"][] = "<small class='form-text text-danger'>The password field is required!</small>";

        if(strlen($password) < 8 || strlen($password) > 20)
            $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

        if($password != $confirm_password)
            $errors["confirm_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";


        if(empty($errors)) {
            try {
                $sql = "select * from customers where email=:email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$_POST["email"]);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                    throw new PDOException("Email address already in use!");

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(32/2));
                $hased_token = password_hash($token,PASSWORD_DEFAULT);
                $link = BASE_URL."auth_customer_register_verify.php?token=".$hased_token."&email=$email";

                $sql = "insert into customers (email,password,token) values (:email,:password,:token)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);                
                $stmt->bindValue(":password",$hashed_password);
                $stmt->bindValue(":token",$token);                
                $stmt->execute();

                if ($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while creating the customer. Please try again later!");

                try {
                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Port = SMTP_PORT;
                    $mail->Username = SMTP_USERNAME;
                    $mail->Password = SMTP_PASSWORD;
                    $mail->SMTPSecure = "tls";
    
                    $mail->setFrom(SMTP_FROM);
                    $mail->addAddress($email);
    
                    $mail->isHTML(true);
                    $mail->Subject = "Registration Verification Email";
                    $mail->Body = "<p>Please click on this link to verify registration:</p>";
                    $mail->Body .= "<a href='$link'>Click Here</a>";
    
                    if($mail->send()) {
                        unset($_POST["email"]);
                        unset($_POST["password"]);
                        unset($_POST["confirm_password"]);

                        $_SESSION["success"] = "Registration is successful. Check your email and verify registration to login.";
                        header("Location: ".BASE_URL."customer-login");
                        exit;
                    }
                } catch (Exception $err) {
                    $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";    
                }
            } catch (PDOException $err) {
                $error_message = $err->getMessage();
            }
        }
    }
?>

<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create Customer Account</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <div class="login-form">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Email Address *</label>
                            <input type="text" name="email" class="form-control" value="<?php if(isset($_POST["email"])): echo $_POST["email"]; endif?>" autocomplete="on">
                            <?php if(isset($errors["email"])): echo $errors["email"][0]; endif?>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" value="">
                            <?php if(isset($errors["password"])): echo $errors["password"][0]; endif?>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" value="">
                            <?php if(isset($errors["confirm_password"])): echo $errors["confirm_password"][0]; endif?>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="form" class="btn btn-primary bg-website">
                                Create Account
                            </button>
                        </div>
                    </form>
                    <div class="mb-3">
                        <a href="<?php BASE_URL?>customer-login" class="primary-color">Existing User? Login Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include "./layout_footer.php"?>