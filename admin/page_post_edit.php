<?php

    use Cocur\Slugify\Slugify;
    require "../vendor/autoload.php";


    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["post_id"])){
        header("Location: ".ADMIN_URL."posts");
        exit();
    }

    $post_id=$_GET["post_id"];

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                posts
            WHERE
                id=?
            LIMIT
                1
        ");
        $stmt->execute([$post_id]);

        if($stmt->rowCount() == 0){
            $_SESSION["success"]="The selected post is not available!";
            header("Location: ".ADMIN_URL."posts");
            exit();
        }                    
        
        $post=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        try{
            $title = htmlspecialchars(trim($_POST["title"]));
            $slug = htmlspecialchars(trim($_POST["slug"]));
            $description = htmlspecialchars(trim($_POST["description"]));
            $excerpt = htmlspecialchars(trim($_POST["excerpt"]));
            
            $file_name = $_FILES["photo"]["name"];
            $file_size = $_FILES["photo"]["size"];
            $file_tmp = $_FILES["photo"]["tmp_name"];
            $file_extension = pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);



            if(empty($title))
                $errors["title"][] = "<small class='form-text text-danger'>The title field is required!</small>";
            
            if(empty($slug))
                $errors["slug"][] = "<small class='form-text text-danger'>The slug field is required!</small>";

            if(!preg_match("/^[0-9a-z-]+$/", $slug))
                $errors["slug"][] = "<small class='form-text text-danger'>Invalid slug format. Slug should only contain lowercase letters, numbers, and hyphens!</small>";

            if(!empty($excerpt)) {
                if(strlen($excerpt) <= 10 || strlen($excerpt) >= 199)
                    $errors["excerpt"][] = "<small class='form-text text-danger'>The Password must be between 10 and 199 characters!</small>";
            }            

            if(!empty($file_name)) {
                if(!in_array($file_extension, ["png","jpeg","jpg"]))
                    $errors["photo"][] = "<small class='form-text text-danger'>The photo type is not allowed!</small>";

                if($file_size >= 1*1024*1024)
                    $errors["photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";
            }

            if(empty($errors)){
                if(!is_null($post["photo"])){
                    $photo=$post["photo"];
                }

                if(!empty($file_name)) {
                    $photo=uniqid().".".$file_extension;
                    $path="../public/uploads/post/";

                    if(is_file($path.$post["photo"]))
                        unlink($path.$post["photo"]);

                    list($width,$height)=getimagesize($file_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($file_extension){
                        case "jpeg": case "jpg": $sourceImage = imagecreatefromjpeg($file_tmp);break;
                        case "png": $sourceImage = imagecreatefrompng($file_tmp);break;
                        default: throw new Exception("The photo type is not allowed!");
                    }

                    imagecopyresampled($thumbnail,$sourceImage,0,0,0,0,$width,$height,$width,$height);
                    imagejpeg($thumbnail,$path.$photo,90);

                    imagedestroy($thumbnail);
                    imagedestroy($sourceImage);
                }

                $slugify = new Slugify();
                $slug=$slugify->slugify($title);
                
                try{
                    $sql="UPDATE posts SET ";

                    $params=[];
                    $condition=[];

                    $condition[]="slug = ?";
                    $params[]=$slug;

                    if(!empty($photo)){
                        $condition[]="photo = ?";
                        $params[]=$photo;
                    }

                    $condition[]="title = ?";
                    $params[]=$title;

                    if(!empty($description)){
                        $condition[]="description = ?";
                        $params[]=$description;
                    }

                    if(!empty($excerpt)){
                        $condition[]="excerpt = ?";
                        $params[]=$excerpt;
                    }

                    
                    $sql.=implode(",",$condition);

                    $sql.=" WHERE id=?";
                    $params[]=$post_id;

                    $stmt = $pdo->prepare($sql);

                    if(!$stmt->execute($params))
                        throw new PDOException("An error occurred during the updating process. Please try again!");

                    $_SESSION["success"]="The post is updated successfully.";
                    header("Location: ".ADMIN_URL."posts");
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
            <h1>Add Post</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>posts" class="btn btn-primary"><i class="fas fa-eye"></i> Posts</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if(is_null($post["photo"])):?>
                                            <img class="w-100" src="https://placehold.co/600x400" alt="">
                                        <?php else:?>
                                            <img class="w-100" src="<?php echo PUBLIC_URL?>uploads/post/<?php echo $post["photo"]?>" alt="">
                                        <?php endif?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group mb-3">
                                            <label>Photo</label>
                                            <div>
                                                <input type="file" name="photo" class="form-control">
                                            </div>
                                            <?php if(isset($errors["photo"])) echo $errors["photo"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" class="form-control" name="title" value="<?php echo $post["title"]?>">
                                            <?php if(isset($errors["title"])) echo $errors["title"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Slug *</label>
                                            <input type="text" class="form-control" name="slug" value="<?php echo $post["slug"]?>">
                                            <?php if(isset($errors["slug"])) echo $errors["slug"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control editor" cols="30" rows="10"><?php echo $post["description"]?></textarea>
                                    <?php if(isset($errors["description"])) echo $errors["description"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Excerpt</label>
                                    <textarea name="excerpt" class="form-control h_100" cols="30" rows="10"><?php echo $post["excerpt"]?></textarea>
                                    <?php if(isset($errors["excerpt"])) echo $errors["excerpt"][0]?>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form">Submit</button>
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
    /* slug */
    $(document).ready(function(){
        $("input[name=title]").change(function(e){
            $("input[name=slug]").val(
                $(this)
                .val()
                .toLowerCase()
                .trim()
                .replace(/[^\w ]/g,"")
                .replace(/[\s-]+/g,"-")
                .replace(/-$/, "")
            )
        })
    })

    /* img */
    $(document).ready(function(){
        $("input[name=photo]").change(function(e){
            $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
        })
    })
</script>
<?php include "./layout_footer.php"?>