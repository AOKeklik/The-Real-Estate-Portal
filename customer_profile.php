<?php
    include "./layout_top.php";

    if(!isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-login");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["form"]) {
        
        $full_name = htmlspecialchars(trim($_POST["full_name"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));

        if(empty($full_name))
            $errors["full_name"][] = "<small class='form-text text-danger'>The full name field is required!</small>";

        if(!empty($password)) {
            if(empty($password))
                $errors["password"][] = "<small class='form-text text-danger'>The password field is required!</small>";

            if(strlen($password) < 8 || strlen($password) > 20)
                $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

            if($confirm_password != $password)
                $errors["confirm_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";
        }


        if(isset($_FILES["photo"]) && !empty($_FILES["photo"]["name"])) {

            if(!isset($_FILES["photo"]) && empty($_FILES["photo"]["name"]))
                $errors["photo"][] = "<small class='form-text text-danger'>The photo field is required!</small>";
            
            if($_FILES["photo"]["size"] >= 1000 * 1000) //1mb
                $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";

            $allowed = ["jpg","jpeg","pngg"];

            if(!in_array(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION),$allowed))
                $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";
        }

        try {
            if(empty($errors)) {
                if(empty($_FILES["photo"]["name"]))
                    $photo = $_SESSION["customer"]["photo"];
                else
                    $photo = uniqid().".".pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);

                if(empty($password))
                    $password = $_SESSION["customer"]["password"];    
                else
                    $password =  password_hash($password,PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("update customers set full_name=?,password=?,photo=? where id=?");
            
                if(!$stmt->execute([$full_name,$password,$photo,$_SESSION["customer"]["id"]]))
                    throw new PDOException("An error occurred while updating. Please try again later!");
                
                if(!empty($_FILES["photo"]["name"])) {
                    if(!is_dir("./public/uploads/customer"))
                        mkdir("./public/uploads/customer",0777,true);
    
                    if(is_file("./public/uploads/customer/".$_SESSION["customer"]["photo"]))
                        unlink("./public/uploads/customer/".$_SESSION["customer"]["photo"]);
    
                    list($width,$height) = getimagesize($_FILES["photo"]["tmp_name"]);
                    $thumbnail = imagecreatetruecolor($width,$height);
                    
                    switch(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION)){
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]); break;
                        case "png": $sourceimage = imagecreatefrompng($_FILES["photo"]["tmp_name"]); break;
                        default: throw new PDOException("Unsupported image type!");
                    }
    
                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,$width,$height,$width,$height);
                    imagejpeg($thumbnail,"./public/uploads/customer/".$photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);                   
                }


                unset($_POST["full_name"]);
                unset($_POST["password"]);
                unset($_POST["confirm_password"]);

                $_SESSION["customer"]["full_name"] = $full_name;
                $_SESSION["customer"]["password"] = $password;
                $_SESSION["customer"]["photo"] = $photo;

                $_SESSION["success"] = "Your information has been updated successfully!";
                header("Location: ".BASE_URL."customer-profile");
                exit();
            }
        }catch(PDOException $err) {
            $error_message = $err->getMessage();
        }
    }

?>

<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Profile</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_customer.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="">Existing Photo</label>
                                    <div class="form-group">
                                        <?php if(!is_null($_SESSION["customer"]["photo"])):?>
                                            <img src="<?php echo PUBLIC_URL."uploads/customer/".$_SESSION["customer"]["photo"]?>" alt="" style="width:100%">
                                        <?php else:?>
                                            <img src="https://placehold.co/300x300" alt="" class="user-photo">
                                        <?php endif?>
                                    </div>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label for="">Change Photo</label>
                                    <div class="form-group">
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                    <?php if(isset($errors["photo"])): echo $errors["photo"][0];endif?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Full Name *</label>
                            <div class="form-group">
                                <input type="text" name="full_name" class="form-control" value="<?php echo $_SESSION["customer"]["full_name"]?>">
                            </div>
                            <?php if(isset($errors["full_name"])): echo $errors["full_name"][0];endif?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Password</label>
                            <div class="form-group">
                                <input type="text" name="password" class="form-control" value="">
                            </div>
                            <?php if(isset($errors["password"])): echo $errors["password"][0];endif?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Confirm Password</label>
                            <div class="form-group">
                                <input type="text" name="confirm_password" class="form-control" value="">
                            </div>
                            <?php if(isset($errors["confirm_password"])): echo $errors["confirm_password"][0];endif?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Email</label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" value="<?php echo $_SESSION["customer"]["email"]?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" name="form" value="Update">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("input[type=file]").each(function () {
        $(this).change(function (e) {
            $(this).closest("form").find("img").attr("src",URL.createObjectURL(e.target.files[0]))
        })
    })
</script>

<?php include "./layout_footer.php"?>