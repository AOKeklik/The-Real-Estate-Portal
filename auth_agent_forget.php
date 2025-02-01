<?php
    include "./layout_top.php";

    if(isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-dashboard");
        exit();
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require "vendor/autoload.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {

        $email = htmlspecialchars(trim($_POST["email"]));

        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(empty($errors)) {
            try {
                $sql = "select * from agents where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();
                $agent = $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt->rowCount() == 0)
                    throw new PDOException("No agent found with the provided information!");

                $token = bin2hex(random_bytes(32/2));
                $hashed_token = password_hash($token,PASSWORD_DEFAULT);
                $link = BASE_URL."auth_agent_reset.php?token=$hashed_token&email=$email";

                $sql = "update agents set token=:token,status=:status where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":token",$token);
                $stmt->bindValue(":status",0);
                $stmt->bindValue(":id",$agent["id"]);
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while updating. Please try again later!");

                $phpmailler = new PHPMailer(true);

                try {
                    $phpmailler->isSMTP();
                    $phpmailler->Host = SMTP_HOST;
                    $phpmailler->SMTPAuth = true;
                    $phpmailler->Port = SMTP_PORT;
                    $phpmailler->Username = SMTP_USERNAME;
                    $phpmailler->Password = SMTP_PASSWORD;
                    $phpmailler->SMTPSecure = SMTP_SECURE;

                    $phpmailler->setFrom(SMTP_FROM);
                    $phpmailler->addAddress($email);

                    $phpmailler->isHTML(true);
                    $phpmailler->Subject = "Reset Password";
                    $phpmailler->Body = "<p>Please click on the following link in order to reset the password:</p>";
                    $phpmailler->Body .= "<a href='$link'>Reset Password</a>";

                    if($phpmailler->send()) {
                        unset($_POST["email"]);
                        
                        $success_message = "Please check your email and follow the steps.";
                    }
                } catch (Exception $err) {
                    $error_message = "Message could not be sent. Mailer Error: ".$err->getMessage();
                }
            } catch(PDOException $err) {
                $error_message = $err->getMessage();
            }
        }
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Forget Agent Password";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <div class="login-form">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Email Address *</label>
                            <input type="text" name="email" class="form-control" value="<?php if(isset($_POST["email"])) echo $_POST["email"]?>" autocomplete="on">
                            <?php if(isset($errors["email"])) echo $errors["email"][0]?>
                        </div>
                        <div class="mb-3">
                            <button name="form" type="submit" class="btn btn-primary bg-website">
                                Submit
                            </button>
                            <a href="<?php echo BASE_URL?>agent-login" class="primary-color">Back to Login Page</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>