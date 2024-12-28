<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));
        $slug = htmlspecialchars(trim($_POST["slug"]));
        
        if(empty($name))
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if(empty($slug))
            $errors["slug"][] = "<small class='form-text text-danger'>The slug field is required!</small>";

        if(!preg_match("/^[a-z0-9-]+$/",$slug))
            $errors["slug"][] = "<small class='form-text text-danger'>Invalid slug format. Slug should only contain lowercase letters, numbers, and hyphens!</small>";

        if(empty($_FILES["photo"]["name"]))
            $errors["photo"][] = "<small class='form-text text-danger'>The photo field is required!</small>";

        $valid_size = 1000 * 1000;
        $valid_exts = ["jpg","jpeg","png"];

        if($_FILES["photo"]["size"] >= $valid_size)
            $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";

        if(!in_array(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION),$valid_exts))
            $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

        if(empty($errors)) {
            try {
                $photo = uniqid().".".pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);

                $sql = "insert into locations (photo,name,slug) values (:photo,:name,:slug)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":photo",$photo);
                $stmt->bindValue(":name",$name);
                $stmt->bindValue(":slug",$slug);
                $stmt->execute();
                
                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while updating. Please try again later!");

                if(!is_dir("../public/uploads/location"))
                    mkdir("../public/uploads/location");

                list($width,$height) = getimagesize($_FILES["photo"]["tmp_name"]);
                $thumbnail = imagecreatetruecolor($width,$height);
                
                switch(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION)) {
                    case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]);break;
                    case "png": $sourceimage = imagecreatefrompng($_FILES["photo"]["tmp_name"]);break;
                    default: throw new PDOException("");break;
                }

                imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,$width,$height,$width,$height);
                imagejpeg($thumbnail,"../public/uploads/location/$photo",90);
                imagedestroy($thumbnail);
                imagedestroy($sourceimage);

                unset($_POST["photo"]);
                unset($_POST["name"]);
                unset($_POST["slug"]);

                $_SESSION["success"] = "Location is added successfully!";
                header("Location: ".ADMIN_URL."locations");
                exit();
            } catch (PDOException $err) {
                $error_message = $err->getMessage();
            }
        }
    }
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Location</h1>
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
                                <div class="form-group mb-3">
                                    <label>Photo</label>
                                    <img src="https://placehold.co/1000x600" alt="" class="d-block p-2 w-25">
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                    <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name" value="<?php if(isset($_POST["name"])) echo $_POST["name"]?>">
                                            <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Slug *</label>
                                            <input type="text" class="form-control" name="slug" value="<?php if(isset($_POST["slug"])) echo $_POST["slug"]?>">
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
    $(document).ready(function () {
        $("input[name=photo]").change(function (e) {
            $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
        })
    })

    $(document).ready(function () {
        $("input[name=name]").change(function () {
            $("input[name=slug]").val(
                $(this)
                    .val()
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w ]/g,"")
                    .replace(/\s+/g,"-")
            )
        })
    })
</script>
<?php include "./layout_footer.php"?>