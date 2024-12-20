<?php include "./layout_top.php"?>
<?php 

    if (isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-dashboard");
        exit;
    }

    if(!isset($_GET["token"]) || !isset($_GET["email"])) {
        $_SESSION["errror"] = "Invalid or missing email/token. Please try again!";
        header("Location: ".BASE_URL."customer-login");
        exit;
    }

    $token = htmlspecialchars(trim($_GET["token"]));
    $email = htmlspecialchars(trim($_GET["email"]));

    try {
        $sql = "select * from customers where email=:email limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email",$email);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0 || !password_verify($customer["token"],$token)) {
            throw new PDOException("Invalid or missing email/token. Please try again!");
        }
    } catch (PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".BASE_URL."customer-login");
        exit;
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));

        if(empty($password))
            $errors["password"][] = "<small class='form-text text-danger'>The password field is required!</small>";

        if(strlen($password) < 8 || strlen($password) > 20)
            $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

        if($confirm_password != $password)
            $errors["confirm_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";

        if(empty($errors)) {
            try {
                $sql = "update customers set token=:token,status=:status,password=:password where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":token",NULL);
                $stmt->bindValue(":status",1);
                $stmt->bindValue(":id",$customer["id"]);
                $stmt->bindValue(":password",password_hash($password,PASSWORD_DEFAULT));
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    throw new PDOException("Something went wrong. Please try again later!");

                unset($_POST["password"]);
                unset($_POST["confirm_password"]);                

                $_SESSION["success"] = "Password is reset successfully. You can login now!";
                header("Location: ".BASE_URL."customer-login");
                exit;
                
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
                <h2>Reset Password</h2>
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
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                            <?php if(isset($errors["password"])): echo $errors["password"][0];endif?>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Retype Password</label>
                            <input type="password" name="confirm_password" class="form-control">
                            <?php if(isset($errors["confirm_password"])): echo $errors["confirm_password"][0];endif?>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="form" class="btn btn-primary bg-website">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>