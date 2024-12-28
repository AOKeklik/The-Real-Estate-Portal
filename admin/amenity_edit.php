<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])){
        header("Location: ".ADMIN_URL."amenities");
        exit();
    }

    $id = $_GET["id"];

    try{
        $sql = "select * from amenities where id=:id limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0)
            throw new PDOException("The selected amenity is not available!");

    }catch(PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".ADMIN_URL."amenities");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));
        $icon = htmlspecialchars(trim($_POST["icon"]));

        if(empty($name))
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if(empty($icon))
            $errors["icon"][] = "<small class='form-text text-danger'>The icon field is required!</small>";

        if(empty($errors)){
            try{
                $sql = "update amenities set name=:name,icon=:icon where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":id",$id);
                $stmt->bindValue(":icon",$icon);
                $stmt->bindValue(":name",$name);

                if(!$stmt->execute())
                    throw new PDOException("An error occurred while updating. Please try again later!");

                $_SESSION["success"] = "The amenity is updated successfully!";
                header("Location: ".ADMIN_URL."amenities");
                exit();
            }catch(PDOException $err){
                $error_message = $err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Amenity</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>amenities" class="btn btn-primary"><i class="fas fa-eye"></i> Amenities</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label>Name *</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo $amenity["name"]?>">
                                    <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <i class="<?php echo $amenity["icon"]?>" style="font-size: 2rem;display: flex;justify-content: center;" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group mb-3">
                                            <label>Icon *</label>
                                            <input type="text" class="form-control" name="icon" value="<?php echo $amenity["icon"]?>">
                                            <?php if(isset($errors["icon"])) echo $errors["icon"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name ="form" class="btn btn-primary">Submit</button>
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
    $("input[name=icon]").change(function (e) {
        $("form i").attr("class", $(this).val())
    })
</script>
<?php include "./layout_footer.php"?>