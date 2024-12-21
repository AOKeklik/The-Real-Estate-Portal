<div class="navbar-area" id="stickymenu">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="<?php echo BASE_URL?>" class="logo">
            <img src="https://placehold.co/600x200" alt="">
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="<?php echo BASE_URL?>">
                    <img src="https://placehold.co/600x200" alt="">
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a href="<?php echo BASE_URL?>" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="properties.html" class="nav-link">Properties</a>
                        </li>
                        <li class="nav-item">
                            <a href="agents.html" class="nav-link">Agents</a>
                        </li>
                        <li class="nav-item">
                            <a href="locations.html" class="nav-link">Locations</a>
                        </li>
                        <li class="nav-item">
                            <a href="pricing.html" class="nav-link">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a href="faq.html" class="nav-link">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a href="blog.html" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.html" class="nav-link">Contact</a>
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