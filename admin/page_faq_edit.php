<?php
    include("./layout_top.php");

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["faq_id"])){
        header("Location: ".ADMIN_URL."faqs");
        exit();
    }

    $faq_id=$_GET["faq_id"];

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                faqs
            WHERE
                id=?
            LIMIT
                1
        ");
        $stmt->execute([$faq_id]);

        if($stmt->rowCount() == 0){
            $_SESSION["error"]="The requested FAQ could not be found.";
            header("Location: ".ADMIN_URL."faqs");
            exit();
        }

        $faq=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err;
    }

    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $question=htmlspecialchars(trim($_POST["question"]));
        $answer=htmlspecialchars(trim($_POST["answer"]));

        if(empty($question))
            $errors["question"][] = "<small class='form-text text-danger'>The question field is required!</small>";

        if(strlen($question) <= 3 || strlen($question) >= 50)
            $errors["question"][] = "<small class='form-text text-danger'>The question must be between 3 and 50 characters!</small>";

        if(empty($answer))
            $errors["answer"][] = "<small class='form-text text-danger'>The answer field is required!</small>";

        if(strlen($answer) <= 3 || strlen($answer) >= 500)
            $errors["answer"][] = "<small class='form-text text-danger'>The answer must be between 3 and 500 characters!</small>";

        

        if(empty($errors)){
            try{
                $stmt=$pdo->prepare("
                    UPDATE 
                        faqs
                    SET
                        question=?,
                        answer=?
                    WHERE
                        id=?
                ");

                if(!$stmt->execute([$question,$answer,$faq_id]))
                    throw new PDOException("An error occurred while updating the FAQ. Please try again.");

                $_SESSION["success"]="The FAQ has been successfully updated.";
                header("Location: ".ADMIN_URL."faqs");
                exit();
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Form</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>faqs" class="btn btn-primary"><i class="fas fa-eye"></i> Faqs</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-group mb-3">
                                    <label>Question*</label>
                                    <input type="text" class="form-control" name="question" value="<?php echo $faq["question"]?>">
                                    <?php if(isset($errors["question"])) echo $errors["question"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Answer*</label>
                                    <textarea name="answer" class="form-control h_100" cols="30" rows="10"><?php echo $faq["answer"]?></textarea>
                                    <?php if(isset($errors["answer"])) echo $errors["answer"][0]?>
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
<?php include("./layout_footer.php")?>