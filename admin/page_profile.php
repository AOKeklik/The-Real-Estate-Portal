<?php 
    include "./layout_top.php";
    
    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $full_name = htmlspecialchars(trim($_POST["full_name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $retype_password = htmlspecialchars(trim($_POST["retype_password"]));        

        if(empty($full_name))
            $errors["full_name"][] = "<small class='form-text text-danger'>The ful name field is required!</small>";

        if(empty($email))
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if(!empty($password)) {
            if(strlen($password) < 8 || strlen($password) > 20)
                $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

            if($password != $retype_password)
                $errors["retype_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";
        }

        if(!empty($_FILES["photo"]["name"])) {
            $valid_extensions = ["jpg","jpeg","pngg"];
            $valid_size = 300 * 1000;
            
            if(!in_array(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION),$valid_extensions))
                $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

            if($_FILES["photo"]["size"] >= $valid_size)
                $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";
        }

        if(empty($errors)) {
            try {
                $id = $_SESSION["admin"]["id"];

                if(empty($_FILES["photo"]["name"]))
                    $photo = $_SESSION["admin"]["photo"];
                else
                    $photo = uniqid().".".pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);

                if(empty($password))
                    $password = $_SESSION["admin"]["password"];
                else
                    $password = password_hash($password,PASSWORD_DEFAULT);

                $sql = "update admins set full_name=:full_name,email=:email,password=:password,photo=:photo where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":id",$id);
                $stmt->bindValue(":full_name",$full_name);
                $stmt->bindValue(":email",$email);
                $stmt->bindValue(":password",$password);
                $stmt->bindValue(":photo",$photo);

                if(!$stmt->execute())
                    throw new PDOException("An error occurred while updating. Please try again later!");

                if(!empty($_FILES["photo"]["name"])) {
                    if(!is_dir("../public/uploads/admin"))
                        mkdir("../public/uploads/admin",0577,true);

                    if(is_file("../public/uploads/admin/".$_SESSION["admin"]["photo"]))
                        unlink("../public/uploads/admin/".$_SESSION["admin"]["photo"]);

                    list($width,$height) = getimagesize($_FILES["photo"]["tmp_name"]);
                    $thumbnail = imagecreatetruecolor(300,300);

                    switch(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION)) {
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]);break;
                        case "png": $sourceimage = imagecreatefrompng($_FILES["photo"]["tmp_name"]);break;
                        default: throw new PDOException("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,300,300,$width,$height);
                    imagejpeg($thumbnail,"../public/uploads/admin/$photo",90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);
                }
                
                $_SESSION["admin"]["full_name"] = $full_name;
                $_SESSION["admin"]["email"] = $email;
                $_SESSION["admin"]["password"] = $password;
                $_SESSION["admin"]["photo"] = $photo;

                unset($_POST["full_name"]);
                unset($_POST["email"]);
                unset($_POST["password"]);
                unset($_POST["photo"]);
                unset($_POST["form"]);

                $_SESSION["success"] = "Your information has been updated successfully!";
                header("Location: ".ADMIN_URL."profile");
                exit();
            } catch(PDOException $err) {
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
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if(empty($_SESSION["admin"]["photo"])):?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="" class="profile-photo w_100_p">
                                        <?php else:?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/admin/<?php echo $_SESSION["admin"]["photo"]?>" alt="" class="profile-photo w_100_p">
                                        <?php endif?>
                                        <input type="file" class="mt_10 js-update-photo" name="photo">
                                        <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label class="form-label">Name *</label>
                                            <input type="text" class="form-control" name="full_name" value="<?php echo $_SESSION["admin"]["full_name"]?>">
                                            <?php if(isset($errors["full_name"])) echo $errors["full_name"][0]?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Email *</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo $_SESSION["admin"]["email"]?>">
                                            <?php if(isset($errors["email"])) echo $errors["email"][0]?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password">
                                            <?php if(isset($errors["password"])) echo $errors["password"][0]?>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Retype Password</label>
                                            <input type="password" class="form-control" name="retype_password">
                                            <?php if(isset($errors["retype_password"])) echo $errors["retype_password"][0]?>
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
<script>
    $("input[type=file]").change(function (e) {
        $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
    })
</script>
<?php include "./layout_footer.php"?>