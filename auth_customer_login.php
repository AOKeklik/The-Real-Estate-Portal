<?php include "./layout_top.php"?>
<?php
    if (isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-dashboard");
        exit;
    }
    
    $errors = [];
    
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $email = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : "";
        $password = isset($_POST["password"]) ? htmlspecialchars(trim($_POST["password"])) : "";
        
        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(empty($password))
            $errors["password"][] = "<small class='form-text text-danger'>The password field is required!</small>";

        if(strlen($password) < 8 || strlen($password) > 20)
            $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

        if(empty($errors)) {
            try{
                $sql = "select * from customers where email=:email and status=:status limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->bindValue(":status",1);
                $stmt->execute();
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt->rowCount() == 0 || !password_verify($password,$customer["password"])) 
                    throw new PDOException("No customer found with the provided information.");

                $_SESSION["customer"] = $customer;

                unset($_POST["email"]);
                unset($_POST["password"]);

                header("Location: ".BASE_URL."customer-dashboard");
                exit;
            }catch(PDOException $err) {
                $_SESSION["error"]=$err->getMessage();
                header("Location: ".BASE_URL."customer-login");
                exit;
            }
        }
    }
?>
<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Customer Login</h2>
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
                                <label for="" class="form-label">Username</label>
                                <input type="text" name="email" class="form-control" value="<?php if(isset($_POST["email"])): echo $_POST["email"];endif?>" autocomplete="on">
                                <?php if(isset($errors["email"])): echo $errors["email"][0];endif?>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" values="">
                                <?php if(isset($errors["password"])): echo $errors["password"][0];endif?>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="form" class="btn btn-primary bg-website">
                                    Login
                                </button>
                                <a href="<?php echo BASE_URL?>customer-forget" class="primary-color">Forget Password?</a>
                            </div>
                            <div class="mb-3">
                                <a href="<?php echo BASE_URL?>customer-register" class="primary-color">Don't have an account? Create Account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "./layout_footer.php"?>