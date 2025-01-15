<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html"></a>
        </div>

        <ul class="sidebar-menu">

            <li class="<?php if($current_page == "dashboard.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>dashboard"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            </li>
            <li class="<?php if($current_page == "setting.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>setting"><i class="fas fa-cog"></i><span>Setting</span></a>
            </li>
            <li class="<?php if($current_page == "properties.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>properties"><i class="fas fa-building"></i> Properties</a>
            </li>
            <li class="<?php if($current_page == "orders.php") echo "active"?>">
                <a class="nav-link" href="<?php echo ADMIN_URL?>orders"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a>
            </li>

            <li class="nav-item dropdown <?php if($current_page=="packages.php" || $current_page=="package_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cubes"></i><span>Packages</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "packages.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>packages"><i class="fas fa-angle-right"></i> Packages</a>
                    </li>
                    <li class="<?php if($current_page == "package_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>package-add"><i class="fas fa-angle-right"></i> Add Package</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if($current_page == "locations.php" || $current_page == "location_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker"></i><span>Locations</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "locations.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>locations"><i class="fas fa-angle-right"></i> Locations</a>
                    </li>
                    <li class="<?php if($current_page == "location_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>location-add"><i class="fas fa-angle-right"></i> Add Location</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if($current_page == "types.php" || $current_page == "type_add.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder"></i><span> Types</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "types.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>types"><i class="fas fa-angle-right"></i> Types</a>
                    </li>
                    <li class="<?php if($current_page == "type_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>type-add"><i class="fas fa-angle-right"></i> Add Type</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php if(strpos($current_page, "amenit") !== false) echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span> Amenities</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "amenities.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>amenities"><i class="fas fa-angle-right"></i> Amenities</a>
                    </li>
                    <li class="<?php if($current_page == "amenity_add.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>amenity-add"><i class="fas fa-angle-right"></i> Add Amenity</a>
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