<div class="navbar-area" id="stickymenu">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="<?php echo BASE_URL?>" class="logo">
            <?php if(ProviderSetting::get("logo")):?>
                <img src="<?php echo PUBLIC_URL?>uploads/setting/<?php echo ProviderSetting::get("logo")?>" alt="">
            <?php else:?>
                <img src="https://placehold.co/600x200?text=Logo" alt="">
            <?php endif?>
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="<?php echo BASE_URL?>">
                    <?php if(ProviderSetting::get("logo")):?>
                        <img src="<?php echo PUBLIC_URL?>uploads/setting/<?php echo ProviderSetting::get("logo")?>" alt="">
                    <?php else:?>
                        <img src="https://placehold.co/600x200?text=Logo" alt="">
                    <?php endif?>
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item <?php if($current_page == "index.php") echo "active"?>">
                            <a href="<?php echo BASE_URL?>" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item <?php if(preg_match("/(property|properties)/i", $current_page)) echo "active"?>">
                            <a href="<?php echo BASE_URL?>properties" class="nav-link">Properties</a>
                        </li>
                        <li class="nav-item <?php if($current_page == "page_type.php" || $current_page == "page_types.php") echo "active"?>">
                            <a href="<?php echo BASE_URL?>agents" class="nav-link">Agents</a>
                        </li>
                        <li class="nav-item <?php if($current_page == "page_location.php" || $current_page == "page_locations.php") echo "active"?>">
                            <a href="<?php echo BASE_URL?>locations" class="nav-link">Locations</a>
                        </li>
                        <li class="nav-item <?php if($current_page == "page_pricing.php") echo "active"?>">
                            <a href="<?php echo BASE_URL?>pricing" class="nav-link">Pricing</a>
                        </li>
                        <li class="nav-item <?php if($current_page == "page_faqs.php") echo "active"?>">
                            <a href="<?php echo BASE_URL?>faqs" class="nav-link">FAQ</a>
                        </li>
                        <li class="nav-item <?php if(preg_match("/post/i",$current_page)) echo "active"?>">
                            <a href="<?php echo BASE_URL?>posts" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item <?php if(preg_match("/contact/i",$current_page)) echo "active"?>">
                            <a href="<?php echo BASE_URL?>contact" class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item">
                            <?php if(isset($_SESSION["customer"])):?>
                                <a href="<?php echo BASE_URL?>customer-dashboard" class="nav-link">Dashboard</a>
                            <?php elseif(isset($_SESSION["agent"])):?>
                                <a href="<?php echo BASE_URL?>agent-dashboard" class="nav-link">Dashboard</a>
                            <?php else:?>
                                <a href="<?php echo BASE_URL?>select-user" class="nav-link">Login</a>
                            <?php endif?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>