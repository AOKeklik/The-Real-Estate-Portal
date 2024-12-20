<?php
    include "./layout_top.php";

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
        $email = htmlspecialchars(trim($_POST["email"]));

        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(empty($errors)) {
            try {
                $sql = "select * from customers where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt->rowCount() == 0)
                    throw new PDOException("No user found with the provided information!");

                $token = bin2hex(random_bytes(32/2));
                $hashed_token = password_hash($token, PASSWORD_DEFAULT);
                $link = BASE_URL."auth_customer_reset.php?token=$hashed_token&email=$email";

                $phpmailer = new PHPMailer(true);

                try {
                    $phpmailer->isSMTP();
                    $phpmailer->Host = SMTP_HOST;
                    $phpmailer->SMTPAuth = true;
                    $phpmailer->Port = SMTP_PORT;
                    $phpmailer->Username = SMTP_USERNAME;
                    $phpmailer->Password = SMTP_PASSWORD;
                    $phpmailer->SMTPSecure = SMTP_SECURE;

                    $phpmailer->setFrom(SMTP_FROM);
                    $phpmailer->addAddress($email);

                    $phpmailer->isHTML(true);
                    $phpmailer->Subject = "Reset Password";
                    $phpmailer->Body = "<p>Please click on this link to verify registration:</p>";
                    $phpmailer->Body .= "<a href='".$link."'>Click Here</a>";

                    if($phpmailer->send()) {

                        $sql = "update customers set token=:token,status=:status where id=:id";
                        $smtp = $pdo->prepare($sql);
                        $smtp->bindValue(":token",$token);
                        $smtp->bindValue(":status",0);
                        $smtp->bindValue(":id",$customer["id"]);
                        $smtp->execute();

                        if($smtp->rowCount() == 0)
                            throw new Exception("An error occurred while updating. Please try again later!");


                        unset($_POST["email"]);

                        $_SESSION["success"] = "Please check your email and follow the steps.";
                        header("Location: ".BASE_URL."customer-login");
                        exit;
                    }
                } catch (Exception $err) {
                    echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
			        return false;
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
                <h2>Forget Password</h2>
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
                            <label for="" class="form-label">Email Address</label>
                            <input type="text" name="email" class="form-control" value="<?php if(isset($_POST["email"])): echo $_POST["email"]; endif?>" autocomplete="on">
                            <?php if(isset($errors["email"])): echo $errors["email"][0]; endif?>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="form" class="btn btn-primary bg-website">
                                Submit
                            </button>
                            <a href="<?php echo BASE_URL?>customer-login" class="primary-color">Back to Login Page</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php" ?>