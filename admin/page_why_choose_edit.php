<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["why_choose_id"])){
        header("Location: ".ADMIN_URL."why-choose");
        exit();
    }

    $why_choose_id=$_GET["why_choose_id"];

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                why_choose_items
            WHERE 
                id=?
            LIMIT
                1
        ");
        $stmt->execute([$why_choose_id]);
        
        if($stmt->rowCount() == 0){
            $_SESSION["error"]="Item Not Found";
            header("Location: ".ADMIN_URL."why-choose");
            exit();
        }

        $item=$stmt->fetch(pdo::FETCH_ASSOC);
        
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])) {
        try{
            $icon=htmlspecialchars(trim($_POST["icon"]));
            $heading=htmlspecialchars(trim($_POST["heading"]));
            $text=htmlspecialchars(trim($_POST["text"]));

            if(empty($icon))
                $errors["icon"][] = "<small class='form-text text-danger'>The icon field is required!</small>";

            if(empty($heading))
                $errors["heading"][] = "<small class='form-text text-danger'>The heading field is required!</small>";

            if(empty($text))
                $errors["text"][] = "<small class='form-text text-danger'>The text field is required!</small>";

            if(empty($errors)){
                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            why_choose_items
                        SET
                            icon=?,
                            heading=?,
                            text=?
                        WHERE
                            id=?
                        LIMIT 
                            1
                    ");
                    
                    if(!$stmt->execute([$icon,$heading,$text,$why_choose_id]))
                        throw new PDOException("Sorry, an error occurred while creating the item. Please try again.");

                    $_SESSION["success"] = "New item updated successfully.";
                    header("Location: ".ADMIN_URL."why-choose");
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
            <h1>Why Choose</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>why-choose" class="btn btn-primary"><i class="fas fa-eye"></i> Why Choose</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row mb-3">
                                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                                        <i id="update-icon" class="<?php echo $item["icon"]?> fs-4"></i>
                                    </div>
                                    <div class="col-md-10 form-group">
                                        <label>Icon</label>
                                        <input type="text" class="form-control" name="icon" value="<?php echo $item["icon"]?>">
                                        <?php if(isset($errors["icon"])) echo $errors["icon"][0]?>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Heading *</label>
                                    <input type="text" class="form-control" name="heading" value="<?php echo $item["heading"]?>">
                                    <?php if(isset($errors["heading"])) echo $errors["heading"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Text *</label>
                                    <textarea name="text" class="form-control h_100" cols="30" rows="10"><?php echo $item["text"]?></textarea>
                                    <?php if(isset($errors["text"])) echo $errors["text"][0]?>
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
    $(document).ready(function(){
        $("input[name=icon]").change(function(e){
            const el = $(this)
            const val = el.val()
            const icon = $("#update-icon")

            icon.attr("class", val+" fs-5")
        })
    })
</script>
<?php include "./layout_footer.php"?>