<?php
    include "./layout_top.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require "../vendor/autoload.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL);
        exit();
    }

    $errors=[];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $subject=htmlspecialchars(trim($_POST["subject"]));
        $message=htmlspecialchars(trim($_POST["message"]));

        if($subject === "")
            $errors["subject"][] = "<small class='form-text text-danger'>The subject field is required!</small>";

        if($message === "")
            $errors["message"][] = "<small class='form-text text-danger'>The message field is required!</small>";

        if(empty($errors)){   
            try{
                $stmt=$pdo->prepare("
                    SELECT
                        *
                    FROM
                        subscribers
                    WHERE
                        status=?
                    ORDER BY
                        id DESC
                ");
                $stmt->execute([1]);

                if($stmt->rowCount() == 0)
                    throw new PDOException("No followers to send the newsletter to.");

                $subscribers=$stmt->fetchAll(pdo::FETCH_ASSOC);

                            
                $phpmailer = new PHPMailer(true);

                try{
                    $phpmailer->isSMTP();
                    $phpmailer->SMTPAuth = true;
                    $phpmailer->Host = SMTP_HOST;
                    $phpmailer->Port = SMTP_PORT;
                    $phpmailer->Username = SMTP_USERNAME;
                    $phpmailer->Password = SMTP_PASSWORD;
                    $phpmailer->SMTPSecure = SMTP_SECURE;
                    
                    $phpmailer->setFrom(SMTP_FROM);
                    $phpmailer->Subject = $subject;
                    $phpmailer->Body = nl2br($message);   

                    foreach($subscribers as $subscriber){
                        $phpmailer_clone = clone $phpmailer;
                        $phpmailer_clone->addAddress($subscriber["email"]);
                        $phpmailer_clone->isHTML(true);

                        if(!$phpmailer_clone->send())
                            throw new Exception("An error occurred while sending the email. Please try again.");
                    }      

                    $_SESSION["success"]="Email sent successfully.";
                    header("Location: ".ADMIN_URL."subscribers");
                    exit();
                }catch(Exception $err){
                    $error_message=$err->getMessage();
                }
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Subscribers</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>subscribers" class="btn btn-primary"><i class="fas fa-eye"></i> Subscribers</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label>Subject*</label>
                                    <input type="text" class="form-control" name="subject" value="<?php if(isset($_POST["subject"])) echo $_POST["subject"]?>">
                                    <?php if(isset($errors["subject"])) echo $errors["subject"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Message*</label>
                                    <textarea name="message" class="form-control h-100" cols="30" rows="10"><?php if(isset($_POST["message"])) echo $_POST["message"]?></textarea>
                                    <?php if(isset($errors["message"])) echo $errors["message"][0]?>
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
<?php include "./layout_footer.php"?>