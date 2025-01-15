<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["message_id"])) {
        header("Location: ".ADMIN_URL."messages");
        exit();
    }

    $message_id=$_GET["message_id"];

    try{
        $stmtMessage=$pdo->prepare("
            SELECT
                messages.*,
                customers.full_name AS customer_name,
                customers.photo AS customer_photo
            FROM
                messages
            LEFT JOIN
                customers ON customers.id=messages.customer_id
            WHERE
                messages.id=?
            LIMIT
                1
        ");
        $stmtMessage->execute([$message_id]);
        $message=$stmtMessage->fetch(pdo::FETCH_ASSOC);

        if($stmtMessage->rowCount() == 0)
            throw new PDOException("The specified message could not be found.");


    }catch(PDOException $err){
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".ADMIN_URL."messages");
        exit();
    }

    try{
        $stmtReplyMessages=$pdo->prepare("
            SELECT
                message_replies.*,
                customers.full_name AS customer_name,
                customers.photo AS customer_photo,
                agents.full_name AS agent_name,
                agents.photo AS agent_photo
            FROM
                message_replies
            LEFT JOIN
                customers ON customers.id=message_replies.customer_id
            LEFT JOIN
                agents ON agents.id=message_replies.agent_id
            WHERE
                message_id=?
            ORDER BY
                id DESC
        ");
        $stmtReplyMessages->execute([$message_id]);
        $replyMessages=$stmtReplyMessages->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Subject: <?php echo $message["subject"]?></h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>messages" class="btn btn-primary"><i class="fas fa-plus"></i> Back to Previous</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="message-heading">
                                Main Message
                            </div>
                            <div class="message-item message-item-main">
                                <div class="message-top">
                                    <div class="photo">
                                        <?php if(is_null($message["customer_photo"])):?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                        <?php else:?>
                                            <img src="<?php echo PUBLIC_URL?>uploads/customer/<?php echo $message["customer_photo"]?>" alt="">
                                        <?php endif?>
                                    </div>
                                    <div class="text">
                                        <h6><?php echo $message["customer_name"]?> <span class="badge rounded-pill text-bg-primary">Customer</span></h6>
                                        <p>Posted on: <?php echo date("d M, Y - H:i:s",strtotime($message["posted_on"]))?></p>
                                    </div>
                                </div>
                                <div class="message-bottom"><?php echo html_entity_decode($message["message"])?></div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div class="message-heading">
                                All Replies
                            </div>
                            <?php if($stmtReplyMessages->rowCount() > 0): foreach($replyMessages as $message):?>
                                <div class="message-item">
                                    <div class="message-top">
                                        <div class="photo">
                                            <?php if(strpos("Customer",$message["sender"]) !== false):?>
                                                <?php if(is_null($message["customer_photo"])):?>
                                                    <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>uploads/customer/<?php echo $message["customer_photo"]?>" alt="">
                                                <?php endif?>
                                            <?php else:?>
                                                <?php if(is_null($message["agent_photo"])):?>
                                                    <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                <?php else:?>
                                                    <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $message["agent_photo"]?>" alt="">
                                                <?php endif?>
                                            <?php endif?>
                                        </div>
                                        <div class="text">
                                            <h6>
                                                <?php if(strpos("Customer",$message["sender"]) !== false):?>
                                                    <?php echo $message["customer_name"]?> <span class="badge rounded-pill text-bg-primary">Customer</span>
                                                <?php else:?>
                                                    <?php echo $message["agent_name"]?> <span class="badge rounded-pill text-bg-success">Agent</span>
                                                <?php endif?>
                                            </h6>
                                            <p>Posted on: <?php echo date("d M, Y - H:i:s",strtotime($message["reply_on"]))?></p>
                                        </div>
                                    </div>
                                    <div class="message-bottom"><?php echo html_entity_decode($message["reply"])?></div>
                                </div>
                            <?php endforeach;else: echo '<div class="message-item text-danger">No reply found</div>';endif?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<?php include "./layout_footer.php"?>

