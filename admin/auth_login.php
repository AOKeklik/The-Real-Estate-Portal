<?php 
    include "./layout_top.php";

    if(isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."dashboard");
        exit;
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $email=htmlspecialchars(trim($_POST["email"]));
        $password=htmlspecialchars(trim($_POST["password"]));

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
                $sql="select * from admins where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",$email);
                $stmt->execute();
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if(
                    $stmt->rowCount() == 0 || 
                    !password_verify($password,$admin["password"]) ||
                    $admin["status"] == 0
                )
                    throw new PDOException("No user found with the provided information.");

                unset($_POST["form"]);
                unset($_POST["email"]);
                unset($_POST["password"]);

                $_SESSION["admin"] = $admin;

                header("Location: ".ADMIN_URL."dashboard");
                exit();
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }    
?> 

<section class="section">
    <div class="container container-login">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary border-box">
                    <div class="card-header card-header-auth">
                        <h4 class="text-center">Admin Panel Login</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email Address" value="<?php if(isset($_POST["email"])) echo $_POST["email"]?>" autofocus>
                                <?php if(isset($errors["email"])) echo $errors["email"][0]?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password"  placeholder="Password" value="<?php if(isset($_POST["password"])) echo $_POST["password"]?>">
                                <?php if(isset($errors["password"])) echo $errors["password"][0]?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="form" class="btn btn-primary btn-lg w_100_p">Login</button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="">Forget Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "./layout_footer.php"?>