<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            select 
                * 
            from 
                settings 
            limit 
                1
        ");
        $stmt->execute();
        
        if($stmt->rowCount() == 0) {
            $_SESSION["error"] = "No settings data available to display.";
            header("Location: ".ADMIN_URL."dashboard");
            exit;
        }

        $setting = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $err) {
        $error_message = $err->getMessage();
    }

    $errors=[];
    
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $hero_heading=htmlspecialchars(trim($_POST["hero_heading"]));
        $hero_subheading=htmlspecialchars(trim($_POST["hero_subheading"]));
        $featured_property_heading=htmlspecialchars(trim($_POST["featured_property_heading"]));
        $featured_property_subheading=htmlspecialchars(trim($_POST["featured_property_subheading"]));
        $agent_heading=htmlspecialchars(trim($_POST["agent_heading"]));
        $agent_subheading=htmlspecialchars(trim($_POST["agent_subheading"]));
        $location_heading=htmlspecialchars(trim($_POST["location_heading"]));
        $location_subheading=htmlspecialchars(trim($_POST["location_subheading"]));
        $address=htmlspecialchars(trim($_POST["address"]));
        $email=htmlspecialchars(trim($_POST["email"]));
        $phone=htmlspecialchars(trim($_POST["phone"]));
        $facebook=htmlspecialchars(trim($_POST["facebook"]));
        $twitter=htmlspecialchars(trim($_POST["twitter"]));
        $youtube=htmlspecialchars(trim($_POST["youtube"]));
        $linkedin=htmlspecialchars(trim($_POST["linkedin"]));
        $instagram=htmlspecialchars(trim($_POST["instagram"]));
        $copyright=htmlspecialchars(trim($_POST["copyright"]));
        $map=htmlspecialchars(trim($_POST["map"]));

        
        $favicon_name=$_FILES["favicon"]["name"];
        $favicon_tmp=$_FILES["favicon"]["tmp_name"];
        $favicon_extension=pathinfo($_FILES["favicon"]["name"],PATHINFO_EXTENSION);
        $favicon_size=$_FILES["favicon"]["size"];

        $logo_name=$_FILES["logo"]["name"];
        $logo_tmp=$_FILES["logo"]["tmp_name"];
        $logo_extension=pathinfo($_FILES["logo"]["name"],PATHINFO_EXTENSION);
        $logo_size=$_FILES["logo"]["size"];


        $banner_name=$_FILES["banner"]["name"];
        $banner_tmp=$_FILES["banner"]["tmp_name"];
        $banner_extension=pathinfo($_FILES["banner"]["name"],PATHINFO_EXTENSION);
        $banner_size=$_FILES["banner"]["size"];

        $hero_photo_name=$_FILES["hero_photo"]["name"];
        $hero_photo_tmp=$_FILES["hero_photo"]["tmp_name"];
        $hero_photo_extension=pathinfo($_FILES["hero_photo"]["name"],PATHINFO_EXTENSION);
        $hero_photo_size=$_FILES["hero_photo"]["size"];

        if(!empty($favicon_name)) {
            if(!in_array($favicon_extension,["png"]))
                $errors["favicon"][] = "<small class='form-text text-danger'>The favicon type is not allowed!</small>";

            if($favicon_size >= 1024*1024*1)
                $errors["favicon"][] = "<small class='form-text text-danger'>The favicon size exceeds the 1MB limit!</small>";
        }

        if(!empty($logo_name)){
            if(!in_array($logo_extension,["png","jpg","jpeg"]))
                $errors["logo"][] = "<small class='form-text text-danger'>The logo type is not allowed!</small>";

            if($logo_size >= 1024*1024*1)
                $errors["logo"][] = "<small class='form-text text-danger'>The ogo size exceeds the 1MB limit!</small>";
        }

        if(!empty($banner_name)){
            if(!in_array($banner_extension,["png","jpg","jpeg"]))
                $errors["banner"][] = "<small class='form-text text-danger'>The banner type is not allowed!</small>";

            if($banner_size >= 1024*1024*1)
                $errors["banner"][] = "<small class='form-text text-danger'>The banner size exceeds the 1MB limit!</small>";
        }

        if(!empty($hero_photo_name)){
            if(!in_array($hero_photo_extension,["png","jpg","jpeg"]))
                $errors["hero_photo"][] = "<small class='form-text text-danger'>The hero photo type is not allowed!</small>";

            if($hero_photo_size >= 1024*1024*1)
                $errors["hero_photo"][] = "<small class='form-text text-danger'>The hero photo size exceeds the 1MB limit!</small>";
        }

        if($hero_heading === "")
                $errors["hero_heading"][] = "<small class='form-text text-danger'>The hero heading field is required!</small>";

        if($hero_subheading === "")
            $errors["hero_subheading"][] = "<small class='form-text text-danger'>The hero subheading field is required!</small>";

        if($featured_property_heading === "")
            $errors["featured_property_heading"][] = "<small class='form-text text-danger'>The featured property heading field is required!</small>";

        if($featured_property_subheading === "")
            $errors["featured_property_subheading"][] = "<small class='form-text text-danger'>The featured property subheading field is required!</small>";

        if($agent_heading === "")
            $errors["agent_heading"][] = "<small class='form-text text-danger'>The agent heading field is required!</small>";
    
        if($agent_subheading === "")
            $errors["agent_subheading"][] = "<small class='form-text text-danger'>The agent subheading field is required!</small>";

        if($location_heading === "")
            $errors["location_heading"][] = "<small class='form-text text-danger'>The location heading field is required!</small>";

        if($location_subheading === "")
            $errors["location_subheading"][] = "<small class='form-text text-danger'>The location subheading field is required!</small>";

        if($address === "")
            $errors["address"][] = "<small class='form-text text-danger'>The address field is required!</small>";

        if($email === "")
            $errors["email"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors["email"][] = "<small class='form-text text-danger'>Email must be valid!</small>";

        if($phone === "")
            $errors["phone"][] = "<small class='form-text text-danger'>The phone field is required!</small>";

        if($copyright === "")
            $errors["copyright"][] = "<small class='form-text text-danger'>The copyright field is required!</small>";
            

        if(empty($errors)){
            try{

                /* favicon */
                if(empty($favicon_name)):
                    $favicon=$setting["favicon"];
                else:
                    $favicon=uniqid().".".$favicon_extension;
                endif;

                if(!empty($favicon_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["favicon"]))
                        unlink($path.$setting["favicon"]);

                    list($width,$height)=getimagesize($favicon_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    imagealphablending($thumbnail,false);
                    imagesavealpha($thumbnail,true);

                    switch($favicon_extension){
                        case "png": $source_image=imagecreatefrompng($favicon_tmp);break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    imagepng($thumbnail,$path.$favicon,6);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                /* logo */
                if(empty($logo_name)):
                    $logo=$setting["logo"];
                else:
                    $logo=uniqid().".".$logo_extension;
                endif;

                if(!empty($logo_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["logo"]))
                        unlink($path.$setting["logo"]);

                    list($width,$height)=getimagesize($logo_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);


                    switch($logo_extension){
                        case "jpg": case "jpeg": $source_image=imagecreatefromjpeg($logo_tmp);break;
                        case "png": 
                                imagealphablending($thumbnail,false);
                                imagesavealpha($thumbnail,true);
                                $source_image=imagecreatefrompng($logo_tmp);
                        break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    if($logo_extension == "png")
                        imagepng($thumbnail,$path.$logo,6);
                    else
                        imagejpeg($thumbnail,$path.$logo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                /* banner */
                if(empty($banner_name)):
                    $banner=$setting["banner"];
                else:
                    $banner=uniqid().".".$banner_extension;
                endif;

                if(!empty($banner_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["banner"]))
                        unlink($path.$setting["banner"]);

                    list($width,$height)=getimagesize($banner_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($banner_extension){
                        case "jpg": case "jpeg": $source_image=imagecreatefromjpeg($banner_tmp);break;
                        case "png": 
                                imagealphablending($thumbnail,false);
                                imagesavealpha($thumbnail,true);
                                $source_image=imagecreatefrompng($banner_tmp);
                        break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    if($banner_extension == "png")
                        imagepng($thumbnail,$path.$logo,6);
                    else
                        imagejpeg($thumbnail,$path.$banner,90);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                 /* hero photo */
                if(empty($hero_photo_name)):
                    $hero_photo=$setting["hero_photo"];
                else:
                    $hero_photo=uniqid().".".$hero_photo_extension;
                endif;

                if(!empty($hero_photo_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["hero_photo"]))
                        unlink($path.$setting["hero_photo"]);

                    list($width,$height)=getimagesize($hero_photo_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($hero_photo_extension){
                        case "jpg": case "jpeg": $source_image=imagecreatefromjpeg($hero_photo_tmp);break;
                        case "png": 
                                imagealphablending($thumbnail,false);
                                imagesavealpha($thumbnail,true);
                                $source_image=imagecreatefrompng($hero_photo_tmp);
                        break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    if($hero_photo_extension == "png")
                        imagepng($thumbnail,$path.$logo,6);
                    else
                        imagejpeg($thumbnail,$path.$hero_photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            settings
                        SET
                            favicon=?,
                            logo=?,
                            banner=?,
                            hero_photo=?,
                            hero_heading=?,
                            hero_subheading=?,
                            featured_property_heading=?,
                            featured_property_subheading=?,
                            agent_heading=?,
                            agent_subheading=?,
                            location_heading=?,
                            location_subheading=?,
                            address=?,
                            email=?,
                            phone=?,
                            facebook=?,
                            twitter=?,
                            youtube=?,
                            linkedin=?,
                            instagram=?,
                            copyright=?,
                            map=?
                    ");

                    if(!$stmt->execute([
                        $favicon,
                        $logo,
                        $banner,
                        $hero_photo,
                        $hero_heading,
                        $hero_subheading,
                        $featured_property_heading,
                        $featured_property_subheading,
                        $agent_heading,
                        $agent_subheading,
                        $location_heading,
                        $location_subheading,
                        $address,
                        $email,
                        $phone,
                        $facebook,
                        $twitter,
                        $youtube,
                        $linkedin,
                        $instagram,
                        $copyright,
                        $map,
                    ]))
                        throw new PDOException("An error occurred while updating. Please try again later!");

                    $_SESSION["success"]="The settings are updated successfully.";
                    header("Location: ".ADMIN_URL."setting");
                    exit();
                }catch(PDOException $err){
                    $error_message=$err->getMessage();
                }
            }catch(Exception $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Setting</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-eye"></i> Dashboard</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="partial-header">Logo & Favicon</div>
                                <div class="partial-item row py-5">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Existing Favicon*</label>
                                            <div>
                                                <?php if(empty($setting["favicon"])):?>
                                                    <img src="https://placehold.co/400x400?text=400x400\nFavicon" alt="" class="h_100">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["favicon"]?>" alt="" class="h_100">
                                                <?php endif?>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Change Favicon*</label>
                                            <div>
                                                <input type="file" name="favicon" class="form-control">
                                                <?php if(isset($errors["favicon"])) echo $errors["favicon"][0]?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Existing Logo*</label>
                                            <div>
                                                <?php if(empty($setting["logo"])):?>
                                                    <img src="https://placehold.co/600x200?text=600x200\nLogo" alt="" class="h_100">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["logo"]?>" alt="" class="h_100 mw-100">
                                                <?php endif?>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Change Logo*</label>
                                            <div>
                                                <input type="file" name="logo" class="form-control">
                                                <?php if(isset($errors["logo"])) echo $errors["logo"][0]?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Existing Banner*</label>
                                            <div>
                                                <?php if(empty($setting["banner"])):?>
                                                    <img src="https://placehold.co/600x200?text=1300x300\nBanner" alt="" class="h_100 mw-100">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["banner"]?>" alt="" class="h_100 mw-100">
                                                <?php endif?>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Change Banner*</label>
                                            <div>
                                                <input type="file" name="banner" class="form-control">
                                                <?php if(isset($errors["banner"])) echo $errors["banner"][0]?>
                                            </div>
                                        </div>
                                    </div>                                
                                </div>
                                <div class="partial-header">Hero</div>
                                <div class="partial-item row align-items-end py-5">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Existing Hero Photo*</label>
                                            <div>
                                                <?php if(empty($setting["hero_photo"])):?>
                                                    <img src="https://placehold.co/600x200?text=1200x600\nHero" alt="" class="h_100 mw-100">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["hero_photo"]?>" alt="" class="h_100 mw-100">
                                                <?php endif?>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Change Hero Photo*</label>
                                            <div>
                                                <input type="file" name="hero_photo" class="form-control">
                                                <?php if(isset($errors["hero_photo"])) echo $errors["hero_photo"][0]?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Hero Heading*</label>
                                            <input type="text" class="form-control" name="hero_heading" value="<?php echo $setting["hero_heading"]?>">
                                            <?php if(isset($errors["hero_heading"])) echo $errors["hero_heading"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Hero Subheading*</label>
                                            <input type="text" class="form-control" name="hero_subheading" value="<?php echo $setting["hero_subheading"]?>">
                                            <?php if(isset($errors["hero_subheading"])) echo $errors["hero_subheading"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-header">Featured</div>
                                <div class="partial-item row py-5">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <span class="badge badge-success" style="<?php if($setting["featured_property_status"] == 0) echo "display:none"?>">Yes</span>
                                            <span class="badge badge-danger" style="<?php if($setting["featured_property_status"] == 1) echo "display:none"?>">No</span>
                                        </div>
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="featured_property_status" 
                                                <?php if($setting["featured_property_status"] == 1) echo "checked"?> 
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Featured Property Heading*</label>
                                            <input type="text" class="form-control" name="featured_property_heading" value="<?php echo $setting["featured_property_heading"]?>">
                                            <?php if(isset($errors["featured_property_heading"])) echo $errors["featured_property_heading"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Featured Property Subheading*</label>
                                            <input type="text" class="form-control" name="featured_property_subheading" value="<?php echo $setting["featured_property_subheading"]?>">
                                            <?php if(isset($errors["featured_property_subheading"])) echo $errors["featured_property_subheading"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-header">Agent</div>
                                <div class="partial-item row py-5">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <span class="badge badge-success" style="<?php if($setting["agent_status"] == 0) echo "display:none"?>">Yes</span>
                                            <span class="badge badge-danger" style="<?php if($setting["agent_status"] == 1) echo "display:none"?>">No</span>
                                        </div>
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="agent_status" 
                                                <?php if($setting["agent_status"] == 1) echo "checked"?> 
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Agent Heading*</label>
                                            <input type="text" class="form-control" name="agent_heading" value="<?php echo $setting["agent_heading"]?>">
                                            <?php if(isset($errors["agent_heading"])) echo $errors["agent_heading"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Agent Subheading*</label>
                                            <input type="text" class="form-control" name="agent_subheading" value="<?php echo $setting["agent_subheading"]?>">
                                            <?php if(isset($errors["agent_subheading"])) echo $errors["agent_subheading"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-header">Location</div>
                                <div class="partial-item row py-5">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <span class="badge badge-success" style="<?php if($setting["location_status"] == 0) echo "display:none"?>">Yes</span>
                                            <span class="badge badge-danger" style="<?php if($setting["location_status"] == 1) echo "display:none"?>">No</span>
                                        </div>
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="location_status" 
                                                <?php if($setting["location_status"] == 1) echo "checked"?> 
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Location Heading*</label>
                                            <input type="text" class="form-control" name="location_heading" value="<?php echo $setting["location_heading"]?>">
                                            <?php if(isset($errors["location_heading"])) echo $errors["location_heading"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Location Subheading*</label>
                                            <input type="text" class="form-control" name="location_subheading" value="<?php echo $setting["location_subheading"]?>">
                                            <?php if(isset($errors["location_subheading"])) echo $errors["location_subheading"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-header">Address</div>
                                <div class="partial-item row py-5">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Address*</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo $setting["address"]?>">
                                            <?php if(isset($errors["address"])) echo $errors["address"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Email*</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo $setting["email"]?>">
                                            <?php if(isset($errors["email"])) echo $errors["email"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Phone*</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo $setting["phone"]?>">
                                            <?php if(isset($errors["phone"])) echo $errors["phone"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-header">Socials</div>
                                <div class="partial-item row py-5">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group mb-3">
                                            <label>Facebook</label>
                                            <input type="text" class="form-control" name="facebook" value="<?php echo $setting["facebook"]?>">
                                            <?php if(isset($errors["facebook"])) echo $errors["facebook"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group mb-3">
                                            <label>Twitter</label>
                                            <input type="text" class="form-control" name="twitter" value="<?php echo $setting["twitter"]?>">
                                            <?php if(isset($errors["twitter"])) echo $errors["twitter"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group mb-3">
                                            <label>Youtube</label>
                                            <input type="text" class="form-control" name="youtube" value="<?php echo $setting["youtube"]?>">
                                            <?php if(isset($errors["youtube"])) echo $errors["youtube"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group mb-3">
                                            <label>Linkedin</label>
                                            <input type="text" class="form-control" name="linkedin" value="<?php echo $setting["linkedin"]?>">
                                            <?php if(isset($errors["linkedin"])) echo $errors["linkedin"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group mb-3">
                                            <label>Instagram</label>
                                            <input type="text" class="form-control" name="instagram" value="<?php echo $setting["instagram"]?>">
                                            <?php if(isset($errors["instagram"])) echo $errors["instagram"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-5">
                                    <div class="form-group mb-3">
                                        <label>Copyright*</label>
                                        <input type="text" class="form-control" name="copyright" value="<?php echo $setting["copyright"]?>">
                                        <?php if(isset($errors["copyright"])) echo $errors["copyright"][0]?>
                                    </div>
                                </div>
                                <div class="py-5">
                                    <div class="form-group mb-3">
                                        <label>Map</label>
                                        <textarea name="map" class="form-control editor" cols="30" rows="10"><?php echo $setting["map"]?></textarea>
                                        <?php if(isset($errors["map"])) echo $errors["map"][0]?>
                                    </div>
                                </div>
                                <div class="form-group mt_30">
                                    <button type="submit" class="btn btn-primary" name="form">Update</button>
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
    /* update */
    $(document).ready(function(){
        $("input[name=featured_property_status]").change(async function(e){
            e.preventDefault()

            const el = $(this)

            const yes =el.closest(".col-md-2").find(".badge.badge-success")
            const no =el.closest(".col-md-2").find(".badge.badge-danger")

            const parent = el.closest(".wrapper-loader-btn")
            const status = el.prop("checked") ? 1 : 0
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("featured_property_status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_setting_featured_property_status_ajax.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(respense){
                    const res = JSON.parse(respense)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        if(status === 1){
                            yes.show()
                            no.hide()
                        }else{
                            yes.hide()
                            no.show()
                        }
                    }

                    parent.removeClass("pending")
                    parent.addClass("active")
                }
            })
        })
    })

    /* update */
    $(document).ready(function(){
        $("input[name=agent_status]").change(async function(e){
            e.preventDefault()

            const el = $(this)

            const yes =el.closest(".col-md-2").find(".badge.badge-success")
            const no =el.closest(".col-md-2").find(".badge.badge-danger")

            const parent = el.closest(".wrapper-loader-btn")
            const status = el.prop("checked") ? 1 : 0
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("agent_status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_setting_agent_status_ajax.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(respense){
                    const res = JSON.parse(respense)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        if(status === 1){
                            yes.show()
                            no.hide()
                        }else{
                            yes.hide()
                            no.show()
                        }
                    }

                    parent.removeClass("pending")
                    parent.addClass("active")
                }
            })
        })
    })

    /* update */
    $(document).ready(function(){
        $("input[name=location_status]").change(async function(e){
            e.preventDefault()

            const el = $(this)

            const yes =el.closest(".col-md-2").find(".badge.badge-success")
            const no =el.closest(".col-md-2").find(".badge.badge-danger")

            const parent = el.closest(".wrapper-loader-btn")
            const status = el.prop("checked") ? 1 : 0
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("location_status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_setting_location_status_ajax.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(respense){
                    const res = JSON.parse(respense)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        if(status === 1){
                            yes.show()
                            no.hide()
                        }else{
                            yes.hide()
                            no.show()
                        }
                    }

                    parent.removeClass("pending")
                    parent.addClass("active")
                }
            })
        })
    })

    /* img */
    $(document).ready(function(){
        $("input[type=file]").change(function(e){
            $(this).closest(".col-md-6").find("img").each(function(){
                $(this).attr("src",URL.createObjectURL(e.target.files[0]))
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>