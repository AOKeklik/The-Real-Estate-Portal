<?php
    include "./layout_top.php";

    try {
        $stmtLocations = $pdo->prepare("
            SELECT
                locations.*,
                COUNT(properties.id) as property_count
            FROM
                locations
            INNER JOIN
                properties on properties.location_id=locations.id
            GROUP BY
                locations.id
            ORDER BY
                locations.name ASC
        ");
        $stmtLocations->execute();
        $locations = $stmtLocations->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Locations</h2>
            </div>
        </div>
    </div>
</div>

<div class="location pb_40">
    <div class="container">
        <div class="row">
            <?php if($stmtLocations->rowCount() > 0): foreach($locations as $loc):?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="item">
                            <div class="photo">
                                <a href="<?php echo BASE_URL?>location/<?php echo $loc["slug"]?>">
                                    <img src="<?php echo PUBLIC_URL?>uploads/location/<?php echo $loc["photo"]?>" alt="<?php $loc["name"]?>">
                                </a>
                            </div>
                            <div class="text">
                                <h2><a href="<?php echo BASE_URL?>location/<?php echo $loc["slug"]?>"><?php echo $loc["name"]?></a></h2>
                                <h4>(<?php echo $loc["property_count"]?> Properties)</h4>
                            </div>
                        </div>
                    </div>
            <?php endforeach;endif?>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>