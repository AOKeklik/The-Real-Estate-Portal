<?php include "./layout/top.php"?> 
<?php
    if(Form::has_submit("form")) {
        if(Form::required("email"))
            Form::push_error("email","The email field is required!");

        if(Form::email("email"))
            Form::push_error("email","Email must be valid!");

        if(Form::required("password"))
            Form::push_error("password","The Password field is required!");

        if(Form::minmax("min:8|max:20","password"))
            Form::push_error("password","The Password must be between 8 and 20 characters!");

        if(!Form::exists($pdo,"admins",$_POST["email"],$_POST["password"]))
            Form::push_error("password","Admin does not exist!");

        if (!Form::has_error()) {
            try {                
                $sql = "select email,password,status from admins where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email",Form::get_data("email"));
                $stmt->execute();

                if($stmt->rowCount() > 0)  {
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);  
                    
                    if($admin["status"] == 0)
                        return Redirect::route()->with("error","Not valid user or password!");
    
                    if(password_verify(Form::get_data("password"), $admin["password"])) {
                        Session::put("admin", [
                            'email' => $admin['email'],
                            'password' => $admin['password'],
                        ]);
    
                        Redirect::route("dashboard.php")->with();
                    }
                } else
                    return Redirect::route()->with("error","Not valid user or password!");
            } catch (PDOException $err) {
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
                        <h4 class="text-center">Admin Panel Login</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email Address" value="<?php echo Form::get_old("email")?>" autofocus>
                                <?php if(Form::has_error("email")): echo Form::get_error("email"); endif?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password"  placeholder="Password">
                                <?php if(Form::has_error("password")): echo Form::get_error("password"); endif?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="form" class="btn btn-primary btn-lg w_100_p">Login</button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?php echo ADMIN_URL?>auth_forget.php">Forget Password?</a>
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