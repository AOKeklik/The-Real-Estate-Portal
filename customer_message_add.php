<?php   
    include "./layout_top.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    include "vendor/autoload.php";

    if(!isset($_SESSION["customer"])){
        header("Location: ".BASE_URL."customer-login");
        exit();
    }

    try{
        $stmtAgents=$pdo->prepare("
            SELECT
                agents.*
            FROM
                agents
            INNER JOIN
                orders ON orders.agent_id=agents.id
            WHERE
                NOW() BETWEEN orders.purchase_date AND orders.expire_date
            AND
                orders.currently_active=?
            ORDER BY 
                rand()
        ");
        $stmtAgents->execute([1]);
        $agents=$stmtAgents->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        try{
            $subject=htmlspecialchars(trim($_POST["subject"]));
            $message=htmlspecialchars(trim($_POST["message"]));
            $agent_id=htmlspecialchars(trim($_POST["agent_id"]));

            if(empty($subject))
                $errors["subject"][] = "<small class='form-text text-danger'>The subject field is required!</small>";

            if($agent_id === "")
                $errors["agent_id"][] = "<small class='form-text text-danger'>The agent field is required!</small>";

            if(empty($message))
                $errors["message"][] = "<small class='form-text text-danger'>The message field is required!</small>";


            if(empty($errors)){
                $stmt=$pdo->prepare("
                    INSERT INTO  messages
                        (customer_id,agent_id,subject,message)
                    VALUES
                        (?,?,?,?)
                ");
                $stmt->execute([
                    $_SESSION["customer"]["id"],
                    $agent_id,
                    $subject,
                    $message
                ]);
                
                if($stmt->rowCount() == 0)
                    throw new PDOException("Failed to send the message.");

                $filteredAgent = current(array_filter($agents, fn ($agent) => $agent["id"] == $agent_id));
                $email = $filteredAgent["email"] ?? null;


                $link=BASE_URL."agent-message/".$pdo->lastInsertId();

                $phpMailler = new PHPMailer(true);

                try{
                    $phpMailler->isSMTP();
                    $phpMailler->Host = SMTP_HOST;
                    $phpMailler->SMTPAuth = true;
                    $phpMailler->Port = SMTP_PORT;
                    $phpMailler->SMTPSecure = SMTP_SECURE;
                    $phpMailler->Username = SMTP_USERNAME;
                    $phpMailler->Password=SMTP_PASSWORD;
                    
                    $phpMailler->setFrom(SMTP_FROM);
                    $phpMailler->addAddress($email);

                    $phpMailler->isHTML(true);
                    $phpMailler->Subject = "Customer Message";
                    $phpMailler->Body = "<p>A customer has sent you message. So please login to your account and click on this link:</p>";
                    $phpMailler->Body .="<a href='$link'>Click!</a>";

                    if(!$phpMailler->send())
                        throw new PDOException("An error occurred while sending the message.");

                    $_SESSION["success"] = "Message is sent successfully.";
                    header("Location: ".BASE_URL."customer-messages");
                    exit();
                } catch(Exception $err){
                    $error_message=$err->getMessage();
                }
            }
        }catch(PDOException $err){
            $error_message=$err->getMessage();
        }
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Message";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="page-content user-panel mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_customer.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Subject *</label>
                                                    <input type="text" class="form-control" name="subject" value="<?php if(isset($_POST["subject"])) echo $_POST["subject"]?>">
                                                    <?php if(isset($errors["subject"])) echo $errors["subject"][0]?>
                                                </div>
                                            </div>
                                            <?php if($stmtAgents->rowCount() > 0):?>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>Agents *</label>
                                                        <select 
                                                            placeholder="Pleas type 3 words.."
                                                            class="form-control select2" 
                                                            name="agent_id" 
                                                            id="" 
                                                        >
                                                            <option value="">-- Select Agent --</option>
                                                            <?php foreach($agents as $agent):?>
                                                                    <option 
                                                                        value="<?php echo $agent["id"]?>" 
                                                                        <?php if(isset($_POST["agent_id"]) && $_POST["agent_id"] == $agent["id"]) echo "selected"?>
                                                                    >
                                                                        <?php echo $agent["full_name"]?>
                                                                    </option>
                                                            <?php endforeach?>
                                                        </select>
                                                        <?php if(isset($errors["agent_id"])) echo $errors["agent_id"][0]?>
                                                    </div>
                                                </div>
                                            <?php endif?>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Message *</label>
                                            <textarea name="message" class="form-control h_100 editor" cols="30" rows="10">
                                                <?php if(isset($_POST["message"])) echo $_POST["message"]?>
                                            </textarea>
                                            <?php if(isset($errors["message"])) echo $errors["message"][0]?>
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
            </div>
        </div>
    </div>
</div>


<?php include "./layout_footer.php"?>