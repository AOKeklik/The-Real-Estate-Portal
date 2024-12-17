<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
        <li class="nav-link">
            <a href="" target="_blank" class="btn btn-warning">Front End</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if(empty(Session::get("admin")["photo"])):?>
                <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="" class="rounded-circle-custom">
            <?php else:?>
                <img src="<?php echo PUBLIC_URL?>uploads/admin/<?php echo Session::get("admin")["photo"]?>" alt="" class="rounded-circle-custom">
            <?php endif?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL?>profile.php"><i class="far fa-user"></i> Edit Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL?>auth_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>