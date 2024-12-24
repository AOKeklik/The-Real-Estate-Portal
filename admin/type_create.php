<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Loation: ".ADMIN_URL."login");
        exit();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        $name = htmlspecialchars(trim($_POST["name"]));

        if(empty($name))
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if(empty($errors)) {
            try {
                $sql = "insert into types (name) values (:name)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":name",$name);
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while creating the type. Please try again later!");

                unset($_POST["name"]);
                unset($_POST["form"]);

                $_SESSION["success"] = "The type is added successfully!";
                header("Location: ".ADMIN_URL."types");
                exit();
            } catch (PDOException $err) {
                $_SESSION["error"] = $err->getMessage();
                header("Location: ".ADMIN_URL."types");
                exit();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Create Type</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>types" class="btn btn-primary"><i class="fas fa-eye"></i> Create Type</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php if(isset($_POST["name"])) echo $_POST["name"]?>">
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