<?php

    include "./layout_top.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require "vendor/autoload.php";

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $full_name = htmlspecialchars(trim($_POST["full_name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));
        $designation = htmlspecialchars(trim($_POST["designation"]));
        $company = htmlspecialchars(trim($_POST["company"]));
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $country = htmlspecialchars(trim($_POST["country"]));
        $address = htmlspecialchars(trim($_POST["address"]));
        $state = htmlspecialchars(trim($_POST["state"]));
        $city = htmlspecialchars(trim($_POST["city"]));
        $zip_code = htmlspecialchars(trim($_POST["zip_code"]));

        if(empty($full_name))
            $errors["full_name"][] = "<small class='form-text text-danger'>The full ame field is required!</small>";

        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(empty($password))
            $errors["password"][] = "<small class='form-text text-danger'>The password field is required!</small>";

        if(strlen($password) < 8 || strlen($password) > 20)
            $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

        if($password != $confirm_password)
            $errors["confirm_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";

        if(empty($designation))
            $errors["designation"][] = "<small class='form-text text-danger'>The designation field is required!</small>";

        if(empty($company))
            $errors["company"][] = "<small class='form-text text-danger'>The company field is required!</small>";

        if(empty($phone))
            $errors["phone"][] = "<small class='form-text text-danger'>The phone field is required!</small>";

        if(!preg_match("/^\+?[1-9]\d{1,14}$/", $phone))
            $errors["phone"][] = "<small class='form-text text-danger'>Invalid phone number format. Please try again!</small>";

        if(empty($country))
            $errors["country"][] = "<small class='form-text text-danger'>The country field is required!</small>";

        if(empty($address))
            $errors["address"][] = "<small class='form-text text-danger'>The address field is required!</small>";

        if(empty($state))
            $errors["state"][] = "<small class='form-text text-danger'>The state field is required!</small>";

        if(empty($city))
            $errors["city"][] = "<small class='form-text text-danger'>The city field is required!</small>";

        if(empty($zip_code))
            $errors["zip_code"][] = "<small class='form-text text-danger'>The zip code field is required!</small>";

        if(!preg_match("/^\d{2}-\d{3}$/", $zip_code))
            $errors["zip_code"][] = "<small class='form-text text-danger'>Invalid postal code!</small>";

        if(empty($errors)) {
            try {
                $sql = "select * from agents where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                    throw new PDOException("Email address already in use!");

                $password = password_hash($password,PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(32/2));
                $hashed_token = password_hash($token,PASSWORD_DEFAULT);
                $link = BASE_URL."auth_agent_register_verify.php?token=$hashed_token&email=$email";

                $sql = "
                    insert into agents (full_name,email,password,designation,company,phone,country,address,state,city,zip_code,token) 
                    values (:full_name,:email,:password,:designation,:company,:phone,:country,:address,:state,:city,:zip_code,:token)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":full_name",$full_name);
                $stmt->bindValue(":email",$email);
                $stmt->bindValue(":password",$password);
                $stmt->bindValue(":designation",$designation);
                $stmt->bindValue(":company",$company);
                $stmt->bindValue(":phone",$phone);
                $stmt->bindValue(":country",$country);
                $stmt->bindValue(":address",$address);
                $stmt->bindValue(":state",$state);
                $stmt->bindValue(":city",$city);
                $stmt->bindValue(":zip_code",$zip_code);
                $stmt->bindValue(":token",$token);
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while creating the agent. Please try again later!");

                $phpmailler = new PHPMailer(true);

                try{
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
                    $phpmailler->Subject = "Registration Verification Email";
                    $phpmailler->Body = "<p>Please click on this link to verify registration:</p>";
                    $phpmailler->Body .= "<a href='".$link."'>Click Here</a>";

                    if($phpmailler->send()) {
                        unset($_POST["full_name"]);
                        unset($_POST["email"]);
                        unset($_POST["password"]);
                        unset($_POST["confirm_password"]);
                        unset($_POST["designation"]);
                        unset($_POST["company"]);
                        unset($_POST["phone"]);
                        unset($_POST["country"]);
                        unset($_POST["address"]);
                        unset($_POST["state"]);
                        unset($_POST["city"]);
                        unset($_POST["zip_code"]);

                        $success_message = "Registration is successful. Check your email and verify registration to login.";
                    }
                } catch(Exception $err) {
                    $error_message = $err->getMessage();                    
                }
            } catch(PDOException $err) {
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
                    <h2>Create Agent Account</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-sm-12">
                    <div class="login-form">
                        <form action="" method="post" class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" value="<?php if(isset($_POST["full_name"])) echo $_POST["full_name"]?>">
                                    <?php if(isset($errors["full_name"])) echo $errors["full_name"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Email Address *</label>
                                    <input type="text" name="email" class="form-control" value="<?php if(isset($_POST["email"])) echo $_POST["email"]?>">
                                    <?php if(isset($errors["email"])) echo $errors["email"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" value="">
                                    <?php if(isset($errors["password"])) echo $errors["password"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirm Password *</label>
                                    <input type="password" name="confirm_password" class="form-control" value="">
                                    <?php if(isset($errors["confirm_password"])) echo $errors["confirm_password"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Designation *</label>
                                    <input type="text" name="designation" class="form-control" value="<?php if(isset($_POST["designation"])) echo $_POST["designation"]?>">
                                    <?php if(isset($errors["designation"])) echo $errors["designation"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Company *</label>
                                    <input type="text" name="company" class="form-control" value="<?php if(isset($_POST["company"])) echo $_POST["company"]?>">
                                    <?php if(isset($errors["company"])) echo $errors["company"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Phone *</label>
                                    <input type="text" name="phone" class="form-control" value="<?php if(isset($_POST["phone"])) echo $_POST["phone"]?>">
                                    <?php if(isset($errors["phone"])) echo $errors["phone"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Country *</label>
                                    <input type="text" name="country" class="form-control" value="<?php if(isset($_POST["country"])) echo $_POST["country"]?>">
                                    <?php if(isset($errors["country"])) echo $errors["country"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Address *</label>
                                    <input type="text" name="address" class="form-control" value="<?php if(isset($_POST["address"])) echo $_POST["address"]?>">
                                    <?php if(isset($errors["address"])) echo $errors["address"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">State *</label>
                                    <input type="text" name="state" class="form-control" value="<?php if(isset($_POST["state"])) echo $_POST["state"]?>">
                                    <?php if(isset($errors["state"])) echo $errors["state"][0]?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">City *</label>
                                    <input type="text" name="city" class="form-control" value="<?php if(isset($_POST["city"])) echo $_POST["city"]?>">
                                    <?php if(isset($errors["city"])) echo $errors["state"][0]?>
                                </div>
                            </div>                 
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Zip Code *</label>
                                    <input type="text" name="zip_code" class="form-control" value="<?php if(isset($_POST["zip_code"])) echo $_POST["zip_code"]?>">
                                    <?php if(isset($errors["zip_code"])) echo $errors["zip_code"][0]?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="form" class="btn btn-primary bg-website">
                                    Create Account
                                </button>
                            </div>
                        </form>
                        <div class="mb-3">
                            <a href="<?php echo BASE_URL?>agent-login" class="primary-color">Existing User? Login Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "./layout_footer.php"?>