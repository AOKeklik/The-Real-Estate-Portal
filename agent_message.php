<?php
    include "./layout_top.php";

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    include "./vendor/autoload.php";
    

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(!isset($_GET["message_id"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    $message_id=$_GET["message_id"];

    try{
        $stmtMainMessage = $pdo->prepare("
            SELECT
                messages.*,
                customers.id AS customer_id,
                customers.full_name AS customer_name,
                customers.photo AS customer_photo,
                customers.email AS customer_email,
                agents.id AS agent_id,
                agents.full_name AS agent_name
            FROM
                messages
            LEFT JOIN
                customers ON customers.id=messages.customer_id
            LEFT JOIN
                agents ON agents.id=messages.agent_id
            WHERE
                messages.id=?
            ORDER BY
                messages.id ASC
            LIMIT 
                1
        ");
        $stmtMainMessage->execute([$message_id]);

        if($stmtMainMessage->rowCount() == 0)
            throw new PDOException("The specified message could not be found.");

        $mainMessage=$stmtMainMessage->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".BASE_URL."agent-messages");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            UPDATE
                messages
            SET
                is_agent_read=?
            WHERE 
                id=?
            AND
                agent_id=?                
        ");
        $stmt->execute([1, $message_id, $_SESSION["agent"]["id"]]);
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtReplyMessages = $pdo->prepare("
            SELECT
                message_replies.*,
                customers.photo as customer_photo,
                customers.full_name as customer_name,
                agents.full_name as agent_name,
                agents.photo as agent_photo
            FROM
                message_replies
            LEFT JOIN
                agents ON agents.id=message_replies.agent_id
            LEFT JOIN
                customers on customers.id=message_replies.customer_id
            WHERE
                message_replies.message_id=?
            ORDER BY
                message_replies.id DESC
        ");
        $stmtReplyMessages->execute([$message_id]);
        $replyMessages=$stmtReplyMessages->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors=[];
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $reply=htmlspecialchars(trim($_POST["reply"]));

        if(empty($reply))
            $errors["reply"][] = "<small class='form-text text-danger'>The reply field is required!</small>";

        if(empty($errors)){
            try{
                $stmt=$pdo->prepare("
                    INSERT INTO message_replies
                        (message_id,customer_id,agent_id,sender,reply)
                    VALUES
                        (?,?,?,?,?)
                ");
                $stmt->execute([
                    $message_id,
                    $mainMessage["customer_id"],
                    $mainMessage["agent_id"],
                    "Agent",
                    $reply
                ]);

                if($stmt->rowCount() == 0)
                    throw new PDOException("Oops! Something went wrong. Please try again later.");

                $phpMailler=new PHPMailer(true);
                
                try{
                    $phpMailler->isSMTP();
                    $phpMailler->SMTPAuth=true;
                    $phpMailler->Host= SMTP_HOST;
                    $phpMailler->Port= SMTP_PORT;
                    $phpMailler->SMTPSecure= SMTP_SECURE;
                    $phpMailler->Username= SMTP_USERNAME;
                    $phpMailler->Password= SMTP_PASSWORD;
                    
                    $phpMailler->setFrom(SMTP_FROM);
                    $phpMailler->addAddress($mainMessage["customer_email"]);
                    
                    $link=BASE_URL."customer-message/".$message_id;

                    $phpMailler->isHTML(true);
                    $phpMailler->Subject="Agent Message";
                    $phpMailler->Body = "<p>A agent has sent you message. So please login to your account and check that.</p>";
                    $phpMailler->Body.="<a href='$link'>Click!</a>";

                    if(!$phpMailler->send())
                        throw new Exception("Oops! Something went wrong. Please try again later.");

                    $_SESSION["success"]="Thank you! Your message has been sent successfully.";
                    header("Location: ".BASE_URL."agent-message/".$message_id);
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
<div class="page-top" style="background-image: url('')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Subject: </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include './layout_nav_agent.php'?>
            </div>
            <div class="col-lg-9 col-md-12">


                <div class="message-reply">
                    <form method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Reply *</label>
                            <div class="form-group">
                                <textarea name="reply" class="form-control h-200" cols="30" rows="10"><?php if(isset($_POST["reply"])) echo $_POST["reply"]?></textarea>
                                <?php if(isset($errors["reply"])) echo $errors["reply"][0]?>
                            </div>
                            <div class="form-group">
                                <input name="form" type="submit" class="btn btn-primary btn-sm mt_10" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="message-heading">Main Message</div>
                <div class="message-item message-item-main">
                    <div class="message-top">
                        <div class="photo">
                            <?php if(is_null($mainMessage["customer_photo"])):?>
                                <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                            <?php else:?>
                                <img src="<?php echo PUBLIC_URL?>uploads/customer/<?php echo $mainMessage["customer_photo"]?>" alt="">
                            <?php endif?>
                        </div>
                        <div class="text">
                            <h6>
                                <?php if(!is_null($mainMessage["customer_name"])) echo $mainMessage["customer_name"]?> 
                                <span class="badge rounded-pill text-bg-primary">Customer</span>
                            </h6>
                            <p>Posted on:  <?php echo date("d M, Y - H:i:s",strtotime($mainMessage["posted_on"]))?></p>
                        </div>
                    </div>
                    <div class="message-bottom"><?php echo html_entity_decode($mainMessage["message"])?></div>
                </div>


                <div class="message-heading">
                    All Replies
                </div>

                <?php if($stmtReplyMessages->rowCount() > 0): foreach($replyMessages as $replyMessage):?>
                    <div class="message-item">
                        <div class="message-top">
                            <div class="photo">
                                <?php if(preg_match("/customer/i",$replyMessage["sender"])):?>
                                    <?php if(is_null($replyMessage["customer_photo"])):?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                    <?php else:?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/customer/<?php echo $replyMessage["customer_photo"]?>" alt="">
                                    <?php endif?>
                                <?php else:?>
                                    <?php if(is_null($replyMessage["agent_photo"])):?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                    <?php else:?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $replyMessage["agent_photo"]?>" alt="">
                                    <?php endif?>
                                <?php endif?>
                            </div>
                            <div class="text">
                                <h6>
                                    <?php if(preg_match("/customer/i",$replyMessage["sender"])):?>
                                        <span class="badge rounded-pill text-bg-primary">Customer</span>
                                    <?php else:?>
                                        <span class="badge rounded-pill text-bg-success">Agent</span>
                                    <?php endif?>
                                </h6>
                                <p>Posted on: <?php echo date("d M, Y - H:i:s",strtotime($replyMessage["reply_on"]))?></p>
                            </div>
                        </div>
                        <div class="message-bottom"><?php echo $replyMessage["reply"]?></div>
                    </div>  
                <?php endforeach;else:?>
                    <div class="message-item text-danger">No reply found</div>
                <?php endif?>             
            </div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php"?>