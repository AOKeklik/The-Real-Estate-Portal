<?php
    include "./layout_top.php";

    try {
        $sql = "select * from packages";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $err) {
        $error_message = $err->getMessage();
    }
?>

<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Pricing</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content pricing">
    <div class="container">
        <div class="row pricing">
            <?php if($stmt->rowCount() > 0):?>
                <?php foreach($packages as $package):?>
                    <div class="col-lg-4 mb_30">
                        <div class="card mb-5 mb-lg-0">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo $package["name"]?></h2>
                                <h3 class="card-price"><?php echo $package["price"]?> PLN</h3>
                                <h4 class="card-day">(<?php echo $package["allowed_days"]?> Days)</h4>
                                <hr />
                                <ul class="fa-ul">
                                    <li>
                                        <?php if($package["allowed_properties"] == -1):?>
                                            <span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Property Allowed
                                        <?php else:?>
                                            <span class="fa-li"><i class="fas fa-check"></i></span><?php echo $package["allowed_properties"]?> Properties Allowed
                                        <?php endif?>
                                    </li>
                                    <li>
                                        <span class="fa-li"><i class="fas fa-<?php if($package["allowed_featured_properties"] == 0): echo "times"; else: echo "check";endif?>"></i></span>
                                        <?php echo $package["allowed_featured_properties"] == 0 ? "No" : $package["allowed_featured_properties"]?> Featured Properties
                                    </li>
                                    <li>
                                        <span class="fa-li"><i class="fas fa-check"></i></span><?php echo $package["allowed_photos"]?> Photos per Property
                                    </li>
                                    <li>
                                        <span class="fa-li"><i class="fas fa-check"></i></span><?php echo $package["allowed_videos"]?> Videos per Property
                                    </li>
                                </ul>
                                <div class="buy">
                                    <?php if(isset($_SESSION["agent"])):?>
                                        <a href="<?php echo BASE_URL?>agent-payment" class="btn btn-primary">Choose Plan</a>
                                    <?php elseif(isset($_SESSION["customer"])):?>
                                        <a href="<?php echo BASE_URL?>customer-payment" class="btn btn-primary">Choose Plan</a>
                                    <?php else:?>
                                        <a href="<?php echo BASE_URL?>login" class="btn btn-primary">Choose Plan</a>
                                    <?php endif?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
            <?php endif?>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>