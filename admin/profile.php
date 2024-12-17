    <?php include "./layout/top.php"?> 
<?php
    if(Form::has_submit("form")) {
        if(Form::required("full_name"))
            Form::push_error("full_name","The full name field is required!");

        if(Form::required("email"))
            Form::push_error("email","The email field is required!");

        if(Form::email("email"))
            Form::push_error("email","Email must be valid!");

        if(!Form::required("password")) {
            if(Form::required("password"))
                Form::push_error("password","The password field is required!");

            if(Form::minmax("min:8|max:20","password"))
                Form::push_error("password","The password must be between 8 and 20 characters!");

            if(!Form::same("password", "retype_password"))
                Form::push_error("retype_password","passwords do not match!");
        }

        if(Form::has_file("photo")) {            
            if(!Form::has_file("photo"))
                Form::push_error("photo", "The image field is required!");

            if(!Form::has_extension("photo"))
                Form::push_error("photo","The file type is not allowed!");

            if(!Form::has_size("photo"))
                Form::push_error("photo","File size exceeds the 2MB limit.");
        }
       
        if(!Form::has_error()) {
            try {
                $sql = "select * from admins where email=:email limit 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", Form::get_data("email"));
                $stmt->execute();

                if($stmt->rowCount() == 0) 
                    return Redirect::route()->with("error","The entered information is incorrect!");

                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "update admins set full_name=:full_name,password=:password,photo=:photo where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id",$admin["id"]);
                $stmt->bindParam(":full_name",Form::get_data("full_name"));

                $password = $admin["password"];
                if(!Form::required("password")) {
                    $password = password_hash(Form::get_data("password"), PASSWORD_DEFAULT);
                    $_SESSION["admin"]["password"] = $password;
                }
                $stmt->bindParam(":password",$password);

                $image_name = $admin["photo"];
                if(Form::has_file("photo")) {
                    if(!is_dir("../public/uploads/admin"))
                        mkdir("../public/uploads/admin",0577,true);

                    if(is_file("../public/uploads/admin/".$admin["photo"]))
                        unlink("../public/uploads/admin/".$admin["photo"]);

                    $image_name = uniqid().".".Form::get_extension("photo");
                    list($width,$height) = getimagesize(Form::get_temp("photo"));
                    $thumbnail = imagecreatetruecolor(300,300);
                    switch(Form::get_extension("photo")){
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg(Form::get_temp("photo")); break;
                        case "png": $sourceimage = imagecreatefromjpeg(Form::get_temp("photo")); break;
                        default: Form::push_error("photo","Unsupported image type!"); break;
                    }

                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,300,300,$width,$height);
                    imagejpeg($thumbnail,"../public/uploads/admin/".$image_name,90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);

                    $_SESSION["admin"]["photo"] = $image_name;
                }
                $stmt->bindParam(":photo",$image_name);

                if(!$stmt->execute())
                    return Redirect::route()->with("error","An error occurred while updating. Please try again later!");

                $_SESSION["admin"]["full_name"] = Form::get_data("full_name");

                return Redirect::route("dashboard.php")->with("success","Your information has been updated successfully!");
            } catch (PDOException $err) {
                $error_message = $err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if(empty(Session::get("admin")["photo"])):?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="" class="profile-photo w_100_p">
                                        <?php else:?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/admin/<?php echo Session::get("admin")["photo"]?>" alt="" class="profile-photo w_100_p">
                                        <?php endif?>
                                        <input type="file" class="mt_10 js-update-photo" name="photo">
                                        <?php if(Form::has_error("photo")): echo Form::get_error("photo"); endif?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label class="form-label">Name *</label>
                                            <input type="text" class="form-control" name="full_name" value="<?php echo Session::get("admin")["full_name"]?>">
                                            <?php if(Form::has_error("full_name")): echo Form::get_error("full_name"); endif?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Email *</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo Session::get("admin")["email"]?>" readonly>
                                            <?php if(Form::has_error("email")): echo Form::get_error("email"); endif?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password">
                                            <?php if(Form::has_error("password")): echo Form::get_error("password"); endif?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Retype Password</label>
                                            <input type="password" class="form-control" name="retype_password">
                                            <?php if(Form::has_error("retype_password")): echo Form::get_error("retype_password"); endif?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label"></label>
                                            <button type="submit" name="form" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</div>
</div>

<?php include "../admin/layout/footer.php"?>