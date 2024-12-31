<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])) {
        header("Location: ".ADMIN_URL."types");
        exit();
    }

    $id = $_GET["id"];

    $errors = [];

    try {
        $stmt = $pdo->prepare("select * from types where id=? limit 1");
        $stmt->execute([$id]);
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount() == 0)
            throw new PDOException("The selected type is not available!");

    } catch (PDOException $err) {
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".ADMIN_URL."types");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));

        if(empty($name)) 
            $errors["name"][] = "<small class='form-text text-danger'>The email field is required!</small>";

        if(empty($errors)) {
            try {
                $stmt = $pdo->prepare("select * from types where lower(name)=lower(?) and id!=? limit 1");
                $stmt->execute([$name,$id]);

                if($stmt->rowCount() > 0)
                    throw new PDOException("The type value must be unique!");

                $stmt = $pdo->prepare("update types set name=? where id=?");

                if(!$stmt->execute([$name,$id]))
                    throw new PDOException("An error occurred while updating. Please try again later!");

                unset($_POST["form"]);
                unset($_POST["name"]);

                $_SESSION["success"] = "The type is updated successfully!";
                header("Location: ".ADMIN_URL."types");
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
            <h1>Edit Type</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>types" class="btn btn-primary"><i class="fas fa-eye"></i> Types</a>
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
                                    <input type="text" class="form-control" name="name" value="<?php echo $type["name"]?>">
                                    <?php if(isset($errors["name"])) echo $errors["name"][0]?>
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