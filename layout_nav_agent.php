<div class="card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item <?php if($current_page=="agent_dashboard.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-dashboard">Dashboard</a>
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
            <a href="<?php BASE_URL?>agent-property-add">Add Property</a>
        </li>
        <li class="list-group-item">
            <a href="user-wishlist.html">Wishlist</a>
        </li>
        <li class="list-group-item <?php if($current_page == "agent_profile.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>agent-profile">Edit Profile</a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo BASE_URL?>auth_agent_logout.php">Logout</a>
        </li>
    </ul>
</div>