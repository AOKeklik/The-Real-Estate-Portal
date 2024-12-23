<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])) {
        header("Location: ".ADMIN_URL."locations");
        exit();
    }

    $id = $_GET["id"];

    try {
        $sql = "select * from locations where id=:id limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $location = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0)
            throw new PDOException("The selected location is not available.");

    } catch (PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".ADMIN_URL."locations");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));
        $slug = htmlspecialchars(trim($_POST["slug"]));

        $path = "../public/uploads/location/";
        $img_tmp = $_FILES["photo"]["tmp_name"];
        $img_name = $_FILES["photo"]["name"];
        $img_size = $_FILES["photo"]["size"];
        $img_ext = pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);

        $valid_size = 1000*1000;
        $valid_ext = ["jpg","jpeg","png"];

        if(empty($name))
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if(empty($slug))
            $errors["slug"][] = "<small class='form-text text-danger'>The slug field is required!</small>";

        if(!preg_match("/^[a-z0-9-]+$/",$slug))
            $errors["slug"][] = "<small class='form-text text-danger'>Invalid slug format. Slug should only contain lowercase letters, numbers, and hyphens!</small>";

        if(!empty($img_name)) {
            if($img_size >= $valid_size)
                $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";

            if(!in_array($img_ext,$valid_ext))
                $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";
        }

        if(empty($errors)) {
            try {
                if(empty($img_name))
                    $photo = $location["photo"];
                else
                    $photo = uniqid().".".$img_ext;
    
                $sql = "update locations set photo=:photo,name=:name,slug=:slug where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":photo",$photo);
                $stmt->bindValue(":name",$name);
                $stmt->bindValue(":slug",$slug);
                $stmt->bindValue(":id",$id);
    
                if(!$stmt->execute())
                    throw new PDOException("An error occurred while updating. Please try again later!");
    
                if(!empty($img_name)) {
                    if(!is_dir($path))
                        mkdir($path,0577,true);
    
                    if(is_file($path.$location["photo"]))
                        unlink($path.$location["photo"]);
    
                    list($width,$height) = getimagesize($img_tmp);
                    $thumbnail = imagecreatetruecolor($width,$height);
    
                    switch($img_ext) {
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($img_tmp);break;
                        case "png": $sourceimage = imagecreatefrompng($img_tmp);break;
                        default: throw new PDOException("Unsupported image type!");
                    }
    
                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,$width,$height,$width,$height);
                    imagejpeg($thumbnail,$path.$photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);
                }
    
                unset($_POST["photo"]);
                unset($_POST["name"]);
                unset($_POST["slug"]);
                unset($_POST["form"]);
    
                $_SESSION["success"] = "$img_ext Location is updated successfully!";
                header("Location: ".ADMIN_URL."locations");
                exit();
            } catch (PDOException $err) {
                $_SESSION["error"] = $err->getMessage();
                header("Location: ".ADMIN_URL."locations");
                exit();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Location</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>locations" class="btn btn-primary"><i class="fas fa-eye"></i> Locations</a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">                               
                                    <div class="form-group mb-3">
                                        <label>Photo</label>
                                        <img src="<?php echo PUBLIC_URL?>uploads/location/<?php echo $location["photo"]?>" alt="" class="d-block p-2 w-25">
                                        <div>
                                            <input type="file" name="photo">
                                        </div>
                                        <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                                    </div>
                                </div>
                                <div class="row">                               
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $location["name"]?>">
                                            <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Slug *</label>
                                            <input type="text" class="form-control" name="slug" value="<?php echo $location["slug"]?>">
                                            <?php if(isset($errors["slug"])) echo $errors["slug"][0]?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <button type="submit" name="form" class="btn btn-primary">Submit</button>
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
    $("input[name=name]").change(function () {
        $("input[name=slug]").val(
            $(this)
                .val()
                .toLowerCase()
                .replace(/[^\w ]/g, "")
                .replace(/\s+/g,"-")
        )
    })
    $("input[name=photo]").change(function (e) {
        $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
    })
</script>

<?php include "./layout_footer.php"?>