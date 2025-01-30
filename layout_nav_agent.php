<?php
    try{
        $stmt=$pdo->prepare("
            SELECT
                count(CASE WHEN is_agent_read = 0 THEN 1 END) AS unread_messages
            FROM
                messages
            WHERE 
                agent_id=?
        ");
        $stmt->execute([$_SESSION["agent"]["id"]]);
        $unread_messages=$stmt->fetch(pdo::FETCH_ASSOC);
        $unread_messages=$unread_messages["unread_messages"];
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item <?php if($current_page=="agent_dashboard.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-dashboard">Dashboard</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_messages.php" || $current_page == "agent_message.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-messages">
                Messages
                <?php if($unread_messages > 0):?>
                    <span class="badge bg-danger"><?php echo $unread_messages?></span>
                <?php endif?>
            </a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_payment.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-payment">Make Payment</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_orders.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-orders">Orders</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_properties.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-properties">All Properties</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_property_add.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-property-add">Add Property</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_profile.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-profile">Edit Profile</a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo BASE_URL?>auth_agent_logout.php">Logout</a>
        </li>
    </ul>
</div>