<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                (SELECT count(id) FROM agents) AS agetns,
                (SELECT count(id) FROM customers) AS customers,
                (SELECT count(id) FROM properties) AS properties,
                (SELECT count(id) FROM packages) AS packages,
                (SELECT count(id) FROM locations) AS locations,
                (SELECT count(id) FROM types) AS types,
                (SELECT count(id) FROM amenities) AS amenities,
                (SELECT count(id) FROM posts) AS posts,
                (SELECT count(id) FROM faqs) AS faqs
        ");
        $stmt->execute();
        $count=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>    

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Agents</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["agetns"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon text-white" style="background-color: lightseagreen;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Customers</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["customers"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Properties</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["properties"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Packages</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["packages"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-secondary">
                        <i class="fas fa-map-marker"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Locations</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["locations"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Types</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["types"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Amenities</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["amenities"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon text-white" style="background-color: lightcoral;">
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Posts</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["posts"]?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon text-white" style="background-color: lightsalmon;">
                        <i class="fas fa-question"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Faqs</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $count["faqs"]?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "./layout_footer.php"?>