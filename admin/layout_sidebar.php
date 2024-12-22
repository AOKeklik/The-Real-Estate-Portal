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

            <li class="nav-item dropdown <?php if($current_page=="packages.php" || $current_page=="package_create.php") echo "active"?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cubes"></i><span>Packages</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($current_page == "packages.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>packages"><i class="fas fa-angle-right"></i> Packages</a>
                    </li>
                    <li class="<?php if($current_page == "package_create.php") echo "active"?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL?>package-create"><i class="fas fa-angle-right"></i> Create Package</a>
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