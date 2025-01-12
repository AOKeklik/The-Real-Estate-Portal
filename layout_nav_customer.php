<div class="card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item <?php if($current_page == "customer_dashboard.php") echo "active"?>">
            <a href="<?php echo BASE_URL?>customer-dashboard">Dashboard</a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_wishlist.php") echo "active"?>">
            <a href="<?php BASE_URL?>customer-wishlist">Wishlist</a>
        </li>
        <li class="list-group-item <?php if($current_page == "customer_profile.php"): echo "active";endif?>">
            <a href="<?php echo BASE_URL?>customer-profile">Edit Profile</a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo BASE_URL?>auth_customer_logout.php">Logout</a>
        </li>
    </ul>
</div>