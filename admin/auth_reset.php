<?php include "./layout/top.php"?> 
<?php
    if(Form::has_submit("form")) {
        if(Form::required("password"))
            Form::push_error("password","The password field is required!");

        if(Form::minmax("min:8|max:20","password"))
            Form::push_error("password","The Password must be between 8 and 20 characters!");

        if(Form::required("confirm_password"))
            Form::push_error("confirm_password","The confirm password field is required!");

        if(!Form::same("password", "confirm_password"))
            Form::push_error("confirm_password","Passwords do not match!");   

        if(!Form::has_error()) {
            try {
                $sql = "select id,email,token from admins where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", htmlspecialchars(trim($_REQUEST["email"])));
                $stmt->execute();
    
                if($stmt->rowCount() == 0)
                    return Redirect::route()->with("error","Invalid or missing reset credentials.");

                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!password_verify($admin["token"], htmlspecialchars(trim($_REQUEST["token"]))))
                    return Redirect::route()->with("error","Invalid or missing reset credentials.");

                $sql = "update admins set password=:password,status=:status,token=:token where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":id", $admin["id"]);
                $stmt->bindValue(":password", password_hash(Form::get_data("password"), PASSWORD_DEFAULT));
                $stmt->bindValue(":status", 1);
                $stmt->bindValue(":token", NULL);
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    return Redirect::route()->with("error","Unable to process your password reset request. Please try again!");

                Redirect::route("auth_login.php")->with("success","Your password has been successfully reset!");
            } catch(PDOException $err) {
                $error_message = $err->getmessage();
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
                                <input type="password" class="form-control" name="password" placeholder="Password" value="" autofocus>
                                <?php if(Form::has_error("password")): echo Form::get_error("password"); endif?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Retype Password" value="">
                                <?php if(Form::has_error("confirm_password")): echo Form::get_error("confirm_password"); endif?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="form" class="btn btn-primary btn-lg w_100_p">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "../admin/layout/footer.php"?>