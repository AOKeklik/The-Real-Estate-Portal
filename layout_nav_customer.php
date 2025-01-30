<?php
    try{
        $stmt=$pdo->prepare("
            SELECT
                count(CASE WHEN is_customer_read = 0 THEN 1 END) AS unread_messages
            FROM
                messages
            WHERE 
                customer_id=?
        ");
        $stmt->execute([$_SESSION["customer"]["id"]]);
        $unread_messages=$stmt->fetch(pdo::FETCH_ASSOC);
        $unread_messages=$unread_messages["unread_messages"];
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item <?php if($current_page == "customer_dashboard.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>customer-dashboard">Dashboard</a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_wishlist.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>customer-wishlist">Wishlist</a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_messages.php" || $current_page == "customer_message.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>customer-messages">
                Messages
                <?php if($unread_messages > 0):?>
                    <span class="badge bg-danger"><?php echo $unread_messages?></span>
                <?php endif?>
            </a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_message_add.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>customer-message-add">New Message</a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_profile.php"): echo "active";endif?>">
            <a href="<?php echo BASE_URL?>customer-profile">Edit Profile</a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo BASE_URL?>auth_customer_logout.php">Logout</a>
        </li>
    </ul>
</div>