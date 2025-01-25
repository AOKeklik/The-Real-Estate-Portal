<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html"></a>
        </div>

        <ul class="sidebar-menu">

            <li class="<?php if($current_page == "page_dashboard.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>dashboard"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            </li>
            <li class="<?php if($current_page == "page_setting.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>setting"><i class="fas fa-cog"></i><span>Setting</span></a>
            </li>
            <li class="<?php if($current_page == "page_properties.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>properties"><i class="fas fa-building"></i> <span>Properties</span></a>
            </li>
            <li class="<?php if($current_page == "page_orders.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>orders"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a>
            </li>
            <li class="<?php if(preg_match("/message/i",$current_page)) echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>messages"><i class="fas fa-envelope"></i> <span>Messages</span></a>
            </li>

            <li class="nav-item dropdown <?php if(preg_match("/(customer|agent|subscriber)/i", $current_page)) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-user"></i><span> Users</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_agents.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>agents"><i class="fas fa-angle-right"></i> <span> Agents</span></a>
                    </li>
                    <li class="<?php if($current_page == "page_customers.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>customers"><i class="fas fa-angle-right"></i> <span> Customers</span></a>
                    </li>
                    <li class="<?php if(preg_match("/subscriber/i",$current_page)) echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>subscribers"><i class="fas fa-angle-right"></i> <span> Subscribers</span></a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if($current_page=="page_packages.php" || $current_page=="page_package_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cubes"></i><span>Packages</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_packages.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>packages"><i class="fas fa-angle-right"></i> Packages</a>
                    </li>
                    <li class="<?php if($current_page == "page_package_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>package-add"><i class="fas fa-angle-right"></i> Add Package</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if($current_page == "page_locations.php" || $current_page == "page_location_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker"></i><span>Locations</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_locations.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>locations"><i class="fas fa-angle-right"></i> Locations</a>
                    </li>
                    <li class="<?php if($current_page == "page_location_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>location-add"><i class="fas fa-angle-right"></i> Add Location</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if($current_page == "page_types.php" || $current_page == "page_type_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder"></i><span> Types</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_types.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>types"><i class="fas fa-angle-right"></i> Types</a>
                    </li>
                    <li class="<?php if($current_page == "page_type_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>type-add"><i class="fas fa-angle-right"></i> Add Type</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(strpos($current_page, "amenit") !== false) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span> Amenities</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_amenities.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>amenities"><i class="fas fa-angle-right"></i> Amenities</a>
                    </li>
                    <li class="<?php if($current_page == "page_amenity_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>amenity-add"><i class="fas fa-angle-right"></i> Add Amenity</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(preg_match("/why_choose/i",$current_page)) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-check-circle"></i><span> Why Choose</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_why_choose.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>why-choose">
                            <i class="fas fa-angle-right"></i> Why Choose
                        </a>
                    </li>
                    <li class="<?php if($current_page == "page_why_choose_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>why-choose-add">
                            <i class="fas fa-angle-right"></i> Add
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(preg_match("/testimonial/i",$current_page)) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fa fa-quote-left"></i><span> Testimonials</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_testimonials.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>testimonials"><i class="fas fa-angle-right"></i> Testimonials</a>
                    </li>
                    <li class="<?php if($current_page == "php_testimonial_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>testimonial-add"><i class="fas fa-angle-right"></i> Testimonial Add</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(preg_match("/post/i",$current_page)) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fa fa-edit"></i><span> Posts</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_posts.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>posts"><i class="fas fa-angle-right"></i> Posts</a>
                    </li>
                    <li class="<?php if($current_page == "page_post_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>post-add"><i class="fas fa-angle-right"></i> Add Post</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(preg_match("/faq/i",$current_page)) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-question"></i><span> Faqs</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "page_faqs.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>faqs"><i class="fas fa-angle-right"></i> Faqs</a>
                    </li>
                    <li class="<?php if($current_page == "page_faq_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>faq-add"><i class="fas fa-angle-right"></i> Add Faq</a>
                    </li>
                </ul>
            </li>


            <!-- <li class="nav-item dropdown active">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i><span>Dropdown Items</span></a>
                <ul class="dropdown-menu">
                    <li class="active"><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 1</a></li>
                    <li class=""><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 2</a></li>
                </ul>
            </li>

            <li class=""><a class="nav-link" href="setting.html"><i class="fas fa-hand-point-right"></i> <span>Setting</span></a></li>

            <li class=""><a class="nav-link" href="form.html"><i class="fas fa-hand-point-right"></i> <span>Form</span></a></li>

            <li class=""><a class="nav-link" href="table.html"><i class="fas fa-hand-point-right"></i> <span>Table</span></a></li>

            <li class=""><a class="nav-link" href="invoice.html"><i class="fas fa-hand-point-right"></i> <span>Invoice</span></a></li> -->

        </ul>
    </aside>
</div>