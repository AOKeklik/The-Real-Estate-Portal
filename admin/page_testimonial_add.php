<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL);
        exit();
    }

    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        try{
            $full_name=htmlspecialchars(trim($_POST["full_name"]));
            $designation=htmlspecialchars(trim($_POST["designation"]));
            $comment=htmlspecialchars(trim($_POST["comment"]));

            $file_name = $_FILES["photo"]["name"];
            $file_size = $_FILES["photo"]["size"];
            $file_tmp = $_FILES["photo"]["tmp_name"];
            $file_extension = pathinfo($file_name,PATHINFO_EXTENSION);

            if($full_name === "")
                $errors["full_name"][] = "<small class='form-text text-danger'>The full_name field is required!</small>";

            if($designation === "")
                $errors["designation"][] = "<small class='form-text text-danger'>The designation field is required!</small>";

            if($comment === "")
                $errors["comment"][] = "<small class='form-text text-danger'>The comment field is required!</small>";

            if(empty($file_name))
                $errors["photo"][] = "<small class='form-text text-danger'>The photo field is required!</small>";

            if(!in_array($file_extension, ["png","jpg","jpeg"]))
                $errors["photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

            if($file_size >= 1*1024*1024)
                $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";

            if(empty($errors)){
                $photo = uniqid().".".$file_extension;
                $path = "../public/uploads/testimonial/";

                if(!is_dir($path))
                    mkdir($path,0577,true);
                
                list($width,$height)=getimagesize($file_tmp);
                $thumbnail=imagecreatetruecolor($width,$height);

                switch($file_extension){
                    case "jpg": case "jpeg": $sourceImage = imagecreatefromjpeg($file_tmp);break;
                    case "png": $sourceImage = imagecreatefrompng($file_extension);break;
                    default: throw new Exception("The file type is not allowed!");
                }

                imagecopyresampled($thumbnail,$sourceImage,0,0,0,0,$width,$height,$width,$height);
                imagejpeg($thumbnail,$path.$photo,90);
                imagedestroy($thumbnail);
                imagedestroy($sourceImage);

                try{
                    $stmt=$pdo->prepare("
                        INSERT INTO  testimonials
                            (photo,full_name,designation,comment)
                        VALUES
                            (?,?,?,?)
                    ");
                    $stmt->execute([$photo,$full_name,$designation,$comment]);
                    
                    if($stmt->rowCount() == 0)
                        throw new PDOException("An error occurred while creating the testimonial. Please try again later.");

                    $_SESSION["success"] = "The testimonial was created successfully!";
                    header("Location: ".ADMIN_URL."testimonials");
                    exit();
                }catch(PDOException $err){
                    $error_message=$err->getMessage();
                }
            }
        }catch(Exception $err){
            $error_message=$err->getMessage();
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Testimonials</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>testimonials" class="btn btn-primary"><i class="fas fa-plus"></i> Testimonials</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img class="w-100" src="https://placehold.co/600x400" alt="">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group mb-4">
                                            <label>Photo</label>
                                            <div>
                                                <input class="form-control" type="file" name="photo">
                                            </div>
                                            <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="full_name" value="<?php if(isset($_POST["full_name"])) echo $_POST["full_name"]?>">
                                    <?php if(isset($errors["full_name"])) echo $errors["full_name"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Designation</label>
                                    <input type="text" class="form-control" name="designation" value="<?php if(isset($_POST["designation"])) echo $_POST["designation"]?>">
                                    <?php if(isset($errors["designation"])) echo $errors["designation"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Comment</label>
                                    <textarea name="comment" class="form-control h_100" cols="30" rows="10"><?php if(isset($_POST["comment"])) echo $_POST["comment"]?></textarea>
                                    <?php if(isset($errors["comment"])) echo $errors["comment"][0]?>
                                </div>                                
                                <div class="form-group">
                                    <button name="form" type="submit" class="btn btn-primary">Submit</button>
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
    /* delete */
    $(document).ready(function(){
        $(document).on("change","input[name=photo]",function(e){
            e.preventDefault()
            $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
        })
    })
</script>
<?php include "./layout_footer.php"?>