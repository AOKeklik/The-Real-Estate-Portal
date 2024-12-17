<?php include "./layout/top.php"?>
<?php
    if(Form::has_submit("form")) {

        if(Form::required("email"))
            Form::push_error("email","The email field is required!");

        if(Form::email("email"))
            Form::push_error("email","Email must be valid!");

        if (!Form::has_error()) {
            try {                            
                $sql = "select email from admins where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",Form::get_data("email"));
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    return Redirect::route()->with("error","The provided email is not registered!");

                $sql = "update admins set token=:token,status=:status where email=:email";
                $stmt = $pdo->prepare($sql);

                $token = bin2hex(random_bytes(32 / 2));

                $stmt->bindValue(":email",Form::get_data("email"));
                $stmt->bindValue(":token",$token);
                $stmt->bindValue(":status",0);
                $stmt->execute();

                if($stmt->rowCount() == 0) 
                    return Redirect::route()->with("error","Unable to process your password reset request. Please try again!");

                $hashedToken = password_hash($token, PASSWORD_DEFAULT); 
                $message = "<p>Please click on the following link in order to redet the password.</p>";
                $message .= "<a href='".ADMIN_URL."auth_reset.php?email=".Form::get_data("email")."&token=".$hashedToken."'>Reset Password!</a>";
                if (
                    Mail::to(Form::get_data("email"))
                    ->subject("Reset Email")
                    ->message($message)
                    ->send()
                ) {
                    Redirect::route("auth_login.php")->with("success","Please check your email and follow the instruction to reset the password.");
                }
            } catch (Exception $err) {
                $error_message = $err->getMessage();
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
                        <h4 class="text-center">Reset Password</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email Address" value="<?php echo Form::get_old("email")?>" autofocus>
                                <?php if(Form::has_error("email")): echo Form::get_error("email"); endif?>
                            </div>
                            <div class="form-group">
                                <button name="form" type="submit" class="btn btn-primary btn-lg w_100_p">
                                    Send Password Reset Link
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?php echo ADMIN_URL?>auth_login.php">Back to login page</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../admin/layout/footer.php"?>