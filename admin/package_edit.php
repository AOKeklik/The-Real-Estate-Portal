<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])) {
        header("Location: ".ADMIN_URL."packages");
        exit();
    }

    $id = $_GET["id"];

    try {
        $sql = "select * from packages where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0)
            throw new PDOException("The selected package is not available.");

    } catch (PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".ADMIN_URL."packages");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));
        $price = htmlspecialchars(trim($_POST["price"]));
        $allowed_days = htmlspecialchars(trim($_POST["allowed_days"]));
        $allowed_properties = htmlspecialchars(trim($_POST["allowed_properties"]));
        $allowed_featured_properties = htmlspecialchars(trim($_POST["allowed_featured_properties"]));
        $allowed_photos = htmlspecialchars(trim($_POST["allowed_photos"]));
        $allowed_videos = htmlspecialchars(trim($_POST["allowed_videos"]));

        if($name === "")
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if($price === "")
            $errors["price"][] = "<small class='form-text text-danger'>The price field is required!</small>";

        if($allowed_days === "")
            $errors["allowed_days"][] = "<small class='form-text text-danger'>The allowed days field is required!</small>";

        if($allowed_properties === "")
            $errors["allowed_properties"][] = "<small class='form-text text-danger'>The allowed properties field is required!</small>";

        if($allowed_featured_properties === "")
            $errors["allowed_featured_properties"][] = "<small class='form-text text-danger'>The allowed featured properties field is required!</small>";

        if($allowed_photos === "")
            $errors["allowed_photos"][] = "<small class='form-text text-danger'>The allowed photos field is required!</small>";

        if($allowed_videos === "")
            $errors["allowed_videos"][] = "<small class='form-text text-danger'>The allowed videos field is required!</small>";

        if(!is_numeric($price))
            $errors["price"][] = "<small class='form-text text-danger'>The price field must be numeric!</small>";

        if(!is_numeric($allowed_days))
            $errors["allowed_days"][] = "<small class='form-text text-danger'>The allowed days field must be numeric!</small>";

        if(!is_numeric($allowed_properties))
            $errors["allowed_properties"][] = "<small class='form-text text-danger'>The allowed properties field must be numeric!</small>";

        if(!is_numeric($allowed_featured_properties))
            $errors["allowed_featured_properties"][] = "<small class='form-text text-danger'>The allowed featured properties field must be numeric!</small>";

        if(!is_numeric($allowed_photos))
            $errors["allowed_photos"][] = "<small class='form-text text-danger'>The allowed photos field must be numeric!</small>";

        if(!is_numeric($allowed_videos))
            $errors["allowed_videos"][] = "<small class='form-text text-danger'>The allowed videos field must be numeric!</small>";

        if(empty($errors)) {
            try {
                $sql = "
                    update packages set
                    name=:name,
                    price=:price,
                    allowed_days=:allowed_days,
                    allowed_properties=:allowed_properties,
                    allowed_featured_properties=:allowed_featured_properties,
                    allowed_photos=:allowed_photos,
                    allowed_videos=:allowed_videos
                    where id=:id
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":id",$id);
                $stmt->bindValue(":name",$name);
                $stmt->bindValue(":price",$price);
                $stmt->bindValue(":allowed_days",$allowed_days);
                $stmt->bindValue(":allowed_properties",$allowed_properties);
                $stmt->bindValue(":allowed_featured_properties",$allowed_featured_properties);
                $stmt->bindValue(":allowed_photos",$allowed_photos);
                $stmt->bindValue(":allowed_videos",$allowed_videos);
                
                if(!$stmt->execute())
                    throw new PDOException("An error occurred while updating. Please try again later!");

                unset($_POST["name"]);
                unset($_POST["price"]);
                unset($_POST["allowed_days"]);
                unset($_POST["allowed_properties"]);
                unset($_POST["allowed_featured_properties"]);
                unset($_POST["allowed_photos"]);
                unset($_POST["allowed_videos"]);

                $_SESSION["success"] = "The package has been updated successfully!";
                header("Location: ".ADMIN_URL."packages");
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
            <h1>Package Edit</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>packages" class="btn btn-primary"><i class="fas fa-eye"></i> Packages</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $package["name"]?>">
                                            <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Price *</label>
                                            <input type="text" class="form-control" name="price" value="<?php echo $package["price"]?>">
                                            <?php if(isset($errors["price"])) echo $errors["price"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Allowed Days *</label>
                                            <input type="text" class="form-control" name="allowed_days" value="<?php echo $package["allowed_days"]?>">
                                            <?php if(isset($errors["allowed_days"])) echo $errors["allowed_days"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Allowed Properties *</label>
                                            <input type="text" class="form-control" name="allowed_properties" value="<?php echo $package["allowed_properties"]?>">
                                            <?php if(isset($errors["allowed_properties"])) echo $errors["allowed_properties"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Allowed Featured Properties *</label>
                                            <input type="text" class="form-control" name="allowed_featured_properties" value="<?php echo $package["allowed_featured_properties"]?>">
                                            <?php if(isset($errors["allowed_featured_properties"])) echo $errors["allowed_featured_properties"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Allowed Photos *</label>
                                            <input type="text" class="form-control" name="allowed_photos" value="<?php echo $package["allowed_photos"]?>">
                                            <?php if(isset($errors["allowed_photos"])) echo $errors["allowed_photos"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Allowed Videos *</label>
                                            <input type="text" class="form-control" name="allowed_videos" value="<?php echo $package["allowed_videos"]?>">
                                            <?php if(isset($errors["allowed_videos"])) echo $errors["allowed_videos"][0]?>
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

<?php include "./layout_footer.php"?>