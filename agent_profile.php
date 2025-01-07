<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $full_name = htmlspecialchars(trim($_POST["full_name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $company = htmlspecialchars(trim($_POST["company"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));
        $biography = htmlspecialchars(trim($_POST["biography"]));
        $designation = htmlspecialchars(trim($_POST["designation"]));
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $country = htmlspecialchars(trim($_POST["country"]));
        $address = htmlspecialchars(trim($_POST["address"]));
        $state = htmlspecialchars(trim($_POST["state"]));
        $city = htmlspecialchars(trim($_POST["city"]));
        $zip_code = htmlspecialchars(trim($_POST["zip_code"]));
        $website = htmlspecialchars(trim($_POST["website"]));
        $facebook = htmlspecialchars(trim($_POST["facebook"]));
        $twitter = htmlspecialchars(trim($_POST["twitter"]));
        $linkedin = htmlspecialchars(trim($_POST["linkedin"]));
        $pinterest = htmlspecialchars(trim($_POST["pinterest"]));
        $instagram = htmlspecialchars(trim($_POST["instagram"]));
        $youtube = htmlspecialchars(trim($_POST["youtube"]));

        if(empty($full_name))
            $errors["full_name"][] = "<small class='form-text text-danger'>The full name field is required!</small>";

        if(empty($company))
            $errors["company"][] = "<small class='form-text text-danger'>The company field is required!</small>";

        if(empty($designation))
            $errors["designation"][] = "<small class='form-text text-danger'>The designation field is required!</small>";

        if(empty($phone))
            $errors["phone"][] = "<small class='form-text text-danger'>The phone field is required!</small>";

        if(empty($country))
            $errors["country"][] = "<small class='form-text text-danger'>The country field is required!</small>";

        if(empty($address))
            $errors["address"][] = "<small class='form-text text-danger'>The address field is required!</small>";

        if(empty($state))
            $errors["state"][] = "<small class='form-text text-danger'>The state field is required!</small>";

        if(empty($city))
            $errors["city"][] = "<small class='form-text text-danger'>The city field is required!</small>";

        if(empty($zip_code))
            $errors["zip_code"][] = "<small class='form-text text-danger'>The zip_code field is required!</small>";

        if(!preg_match("/^\d{2}-\d{3}$/",$zip_code))
            $errors["zip_code"][] = "<small class='form-text text-danger'>Invalid postal code!</small>";

        if(!empty($password)) {
            if(strlen($password) < 8 || strlen($password) > 20)
                $errors["password"][] = "<small class='form-text text-danger'>The Password must be between 8 and 20 characters!</small>";

            if($password != $confirm_password)
                $errors["confirm_password"][] = "<small class='form-text text-danger'>Passwords do not match!</small>";
        }

         if(!empty($_FILES["photo"]["name"])) {
            $valid_extensions = ["jpg","jpeg","png"];
            $valid_size = 1000 * 1000;

            if(!in_array(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION), $valid_extensions))
                $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

            if($_FILES["photo"]["size"] >= $valid_size)
                $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";
         }

        if(empty($errors)) {
            try {
                $id = $_SESSION["agent"]["id"];

                if(empty($_FILES["photo"]["name"]))
                    $photo = $_SESSION["agent"]["photo"];
                else {
                    $photo = uniqid().".".pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);
                }

                if(empty($password))
                    $password = $_SESSION["agent"]["password"];
                else 
                    $password = password_hash($password,PASSWORD_DEFAULT);

                $sql = "
                    update agents set 
                    photo=:photo,full_name=:full_name,password=:password,company=:company,biography=:biography,designation=:designation,
                    phone=:phone,country=:country,address=:address,state=:state,city=:city,zip_code=:zip_code,website=:website,facebook=:facebook,
                    twitter=:twitter,linkedin=:linkedin,pinterest=:pinterest,instagram=:instagram,youtube=:youtube
                    where id=:id
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":id",$id);
                $stmt->bindValue(":photo",$photo);
                $stmt->bindValue(":password",$password);
                $stmt->bindValue(":full_name",$full_name);
                $stmt->bindValue(":company",$company);
                $stmt->bindValue(":biography",empty($biography) ? null : $biography);
                $stmt->bindValue(":designation",$designation);
                $stmt->bindValue(":phone",$phone);
                $stmt->bindValue(":country",$country);
                $stmt->bindValue(":address",$address);
                $stmt->bindValue(":state",$state);
                $stmt->bindValue(":city",$city);
                $stmt->bindValue(":zip_code",$zip_code);
                $stmt->bindValue(":website",empty($website) ? null : $website);
                $stmt->bindValue(":facebook",empty($facebook) ? null : $facebook);
                $stmt->bindValue(":twitter",empty($twitter) ? null : $twitter);
                $stmt->bindValue(":linkedin",empty($linkedin) ? null : $linkedin);
                $stmt->bindValue(":pinterest",empty($pinterest) ? null : $pinterest);
                $stmt->bindValue(":instagram",empty($instagram) ? null : $instagram);
                $stmt->bindValue(":youtube",empty($youtube) ? null : $youtube);

                if(!$stmt->execute())
                    throw new PDOException("An error occurred while updating. Please try again later!");

                if(!empty($_FILES["photo"]["name"])) { 
                    if(!is_dir("./public/uploads/agent"))
                        mkdir("./public/uploads/agent",0577,true);

                    if(is_file("./public/uploads/agent/".$_SESSION["agent"]["photo"]))
                        unlink("./public/uploads/agent/".$_SESSION["agent"]["photo"]);

                    list($width,$height) = getimagesize($_FILES["photo"]["tmp_name"]);
                    $thumbnail = imagecreatetruecolor(300,300);

                    switch(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION)) {
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]);break;
                        case "png": $sourceimage = imagecreatefrompng($_FILES["photo"]["tmp_name"]);break;
                        default: throw new PDOException("Unsupported image type!");break;
                    }
                    
                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,300,300,$width,$height);
                    imagejpeg($thumbnail,"./public/uploads/agent/$photo",90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);
                }

                $_SESSION["agent"]["photo"] = $photo;
                $_SESSION["agent"]["full_name"] = $full_name;
                $_SESSION["agent"]["password"] = $password;
                $_SESSION["agent"]["company"] = $company;
                $_SESSION["agent"]["biography"] = $biography;
                $_SESSION["agent"]["designation"] = $designation;
                $_SESSION["agent"]["phone"] = $phone;
                $_SESSION["agent"]["country"] = $country;
                $_SESSION["agent"]["address"] = $address;
                $_SESSION["agent"]["state"] = $state;
                $_SESSION["agent"]["city"] = $city;
                $_SESSION["agent"]["zip_code"] = $zip_code;
                $_SESSION["agent"]["website"] = $website;
                $_SESSION["agent"]["facebook"] = $facebook;
                $_SESSION["agent"]["twitter"] = $twitter;
                $_SESSION["agent"]["linkedin"] = $linkedin;
                $_SESSION["agent"]["pinterest"] = $pinterest;
                $_SESSION["agent"]["instagram"] = $instagram;
                $_SESSION["agent"]["youtube"] = $youtube;

                $_SESSION["success"] = "Your information has been updated successfully!";
                header("Location: ".BASE_URL."agent-profile");
                exit();
            } catch (PDOException $err) {
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
                <h2>Edit Agent Profile</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="">Existing Photo</label>
                            <div class="form-group">
                                <?php if(empty($_SESSION["agent"]["photo"])):?>
                                    <img src="https://placehold.co/300x300" alt="" class="user-photo">
                                <?php else:?>
                                    <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $_SESSION["agent"]["photo"]?>" alt="" class="user-photo">
                                <?php endif?>
                            </div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label for="">Change Photo</label>
                            <div class="form-group">
                                <input type="file" name="photo" class="form-control">
                            </div>
                            <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="">Name *</label>
                            <div class="form-group">
                                <input type="text" name="full_name" class="form-control" value="<?php echo $_SESSION["agent"]["full_name"]?>">
                            </div>
                            <?php if(isset($errors["full_name"])) echo $errors["full_name"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Email *</label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" value="<?php echo $_SESSION["agent"]["email"]?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Company *</label>
                            <div class="form-group">
                                <input type="text" name="company" class="form-control" value="<?php echo $_SESSION["agent"]["company"]?>">
                            </div>
                            <?php if(isset($errors["company"])) echo $errors["company"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Password</label>
                            <div class="form-group">
                                <input type="text" name="password" class="form-control" value="">
                            </div>
                            <?php if(isset($errors["password"])) echo $errors["password"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Confirm Password</label>
                            <div class="form-group">
                                <input type="text" name="confirm_password" class="form-control" value="">
                            </div>
                            <?php if(isset($errors["confirm_password"])) echo $errors["confirm_password"][0]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Biography</label>
                            <textarea name="biography" class="form-control editor" cols="30" rows="10"><?php echo $_SESSION["agent"]["biography"]?></textarea>
                            <?php if(isset($errors["biography"])) echo $errors["biography"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Designation *</label>
                            <div class="form-group">
                                <input type="text" name="designation" class="form-control" value="<?php echo $_SESSION["agent"]["designation"]?>">
                            </div>
                            <?php if(isset($errors["designation"])) echo $errors["designation"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Phone *</label>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" value="<?php echo $_SESSION["agent"]["phone"]?>">
                            </div>
                            <?php if(isset($errors["phone"])) echo $errors["phone"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Country *</label>
                            <div class="form-group">
                                <input type="text" name="country" class="form-control" value="<?php echo $_SESSION["agent"]["country"]?>">
                            </div>
                            <?php if(isset($errors["country"])) echo $errors["country"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Address *</label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control" value="<?php echo $_SESSION["agent"]["address"]?>">
                            </div>
                            <?php if(isset($errors["address"])) echo $errors["address"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">State *</label>
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" value="<?php echo $_SESSION["agent"]["state"]?>">
                            </div>
                            <?php if(isset($errors["state"])) echo $errors["state"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">City *</label>
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" value="<?php echo $_SESSION["agent"]["city"]?>">
                            </div>
                            <?php if(isset($errors["city"])) echo $errors["city"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Zip Code *</label>
                            <div class="form-group">
                                <input type="text" name="zip_code" class="form-control" value="<?php echo $_SESSION["agent"]["zip_code"]?>">
                            </div>
                            <?php if(isset($errors["zip_code"])) echo $errors["zip_code"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Website</label>
                            <div class="form-group">
                                <input type="text" name="website" class="form-control" value="<?php echo $_SESSION["agent"]["website"]?>">
                            </div>
                            <?php if(isset($errors["website"])) echo $errors["website"]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Facebook</label>
                            <div class="form-group">
                                <input type="text" name="facebook" class="form-control" value="<?php echo $_SESSION["agent"]["facebook"]?>">
                            </div>
                            <?php if(isset($errors["facebook"])) echo $errors["facebook"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Twitter</label>
                            <div class="form-group">
                                <input type="text" name="twitter" class="form-control" value="<?php echo $_SESSION["agent"]["twitter"]?>">
                            </div>
                            <?php if(isset($errors["twitter"])) echo $errors["twitter"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">LinkedIn</label>
                            <div class="form-group">
                                <input type="text" name="linkedin" class="form-control" value="<?php echo $_SESSION["agent"]["linkedin"]?>">
                            </div>
                            <?php if(isset($errors["linkedin"])) echo $errors["linkedin"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Pinterest</label>
                            <div class="form-group">
                                <input type="text" name="pinterest" class="form-control" value="<?php echo $_SESSION["agent"]["pinterest"]?>">
                            </div>
                            <?php if(isset($errors["pinterest"])) echo $errors["pinterest"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Instagram</label>
                            <div class="form-group">
                                <input type="text" name="instagram" class="form-control" value="<?php echo $_SESSION["agent"]["instagram"]?>">
                            </div>
                            <?php if(isset($errors["instagram"])) echo $errors["instagram"][0]?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Youtube</label>
                            <div class="form-group">
                                <input type="text" name="youtube" class="form-control" value="<?php echo $_SESSION["agent"]["youtube"]?>">
                            </div>
                            <?php if(isset($errors["youtube"])) echo $errors["youtube"][0]?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input name="form" type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("input[type=file]").change(function (e) {
            $("img").attr("src",URL.createObjectURL(e.target.files[0]))
        })
    })
</script>
<?php include "./layout_footer.php"?>