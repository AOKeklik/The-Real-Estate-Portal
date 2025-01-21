<?php
    include "./layout_top.php";

    if(!isset($_GET["agent_id"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    if(!isset($_GET["slug"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    $agent_id =$_GET["agent_id"];
    $slug =$_GET["slug"];

    try{
        $stmtAgent=$pdo->prepare("
            SELECT
                agents.*
            FROM
                agents
            LEFT JOIN
                orders ON orders.agent_id=agents.id
            WHERE
            	NOW() BETWEEN orders.purchase_date AND orders.expire_date
            AND
                orders.currently_active=?
            AND
                agents.id=? 
            AND 
                agents.slug=? 
            AND 
                agents.status=?
            LIMIT
                1            
        ");
        $stmtAgent->execute([1,$agent_id,$slug,1]);
        $agent=$stmtAgent->fetch(pdo::FETCH_ASSOC);

        if($stmtAgent->rowCount() == 0){
            header("Location: ".BASE_URL."404");
            exit();
        }
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtProperties=$pdo->prepare("
            SELECT
                properties.*,
                locations.name as location_name,
                types.name as type_name,
                agents.id as agent_id,
                agents.slug as agent_slug,
                agents.full_name as agent_name,
                agents.photo as agent_photo
            FROM
                properties
            INNER JOIN
                orders ON orders.agent_id=properties.agent_id
            LEFT JOIN
                locations ON locations.id=properties.location_id
            LEFT JOIN
                types ON types.id=properties.type_id
            LEFT JOIN
                agents ON agents.id=properties.agent_id
            WHERE
                now() BETWEEN orders.purchase_date AND orders.expire_date 
            AND 
                orders.currently_active=?
            AND
                properties.agent_id=?
            ORDER BY
                rand()
        ");
        $stmtProperties->execute([1,$agent_id]);
        $properties=$stmtProperties->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $agent["full_name"]?> Agent Detail</h2>
            </div>
        </div>
    </div>
</div>

<div class="agent-detail">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner">
                    <div class="photo">
                        <?php if(is_null($agent["photo"])):?>
                            <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                        <?php else:?>
                            <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $agent["photo"]?>" alt="">
                        <?php endif?>
                    </div>
                    <div class="detail">
                        <h3><?php echo $agent["full_name"]?></h3>
                        <h4><?php echo $agent["company"]?>, <?php echo $agent["designation"]?></h4>
                        <?php if(!is_null($agent["biography"])):?>
                            <?php echo html_entity_decode($agent["biography"])?>
                        <?php endif?>
                        
                        <div class="contact d-flex justify-content-center">
                            <div class="item"><i class="fas fa-map-marker-alt"></i> <?php echo $agent["address"]?></div>
                            <div class="item"><i class="fas fa-phone"></i> <?php echo $agent["phone"]?></div>
                            <div class="item"><i class="far fa-envelope"></i> <?php echo $agent["email"]?></div>
                            <?php if(!is_null($agent["website"])):?>
                                <div class="item"><i class="fas fa-globe"></i> <?php echo $agent["website"]?></div>
                            <?php endif?>
                            
                        </div>
                        <?php if(
                            !is_null($agent["facebook"]) &&
                            !is_null($agent["twitter"]) &&
                            !is_null($agent["pinterest"]) &&
                            !is_null($agent["instagram"]) &&
                            !is_null($agent["linkedin"]) &&
                            !is_null($agent["youtube"])
                        ):?>
                            <ul class="agent-detail-ul">
                                <?php if(!is_null($agent["facebook"])):?>
                                    <li><a href="<?php echo $agent["facebook"]?>"><i class="fab fa-facebook-f"></i></a></li>
                                <?php endif?>                            
                                <?php if(!is_null($agent["twitter"])):?>
                                    <li><a href="<?php echo $agent["twitter"]?>"><i class="fab fa-twitter"></i></a></li>
                                <?php endif?>                            
                                <?php if(!is_null($agent["pinterest"])):?>
                                    <li><a href="<?php echo $agent["pinterest"]?>"><i class="fab fa-pinterest-p"></i></a></li>
                                <?php endif?>                            
                                <?php if(!is_null($agent["instagram"])):?>
                                    <li><a href="<?php echo $agent["instagram"]?>"><i class="fab fa-instagram"></i></a></li>
                                <?php endif?>                            
                                <?php if(!is_null($agent["linkedin"])):?>
                                    <li><a href="<?php echo $agent["linkedin"]?>"><i class="fab fa-linkedin-in"></i></a></li>
                                <?php endif?>                            
                                <?php if(!is_null($agent["youtube"])):?>
                                    <li><a href="<?php echo $agent["youtube"]?>"><i class="fab fa-youtube"></i></a></li>
                                <?php endif?>
                                
                            </ul>
                        <?php endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="property">
    <div class="container">
        <div class="row">
            <?php if($stmtProperties->rowCount() > 0): foreach($properties as $property):?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="item">
                        <div class="photo">
                            <img class="main" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                            <div class="top">
                                <?php if(preg_match("/sale/i",$property["purpose"])):?>
                                    <div class="status-sale">For Sale</div>
                                <?php else:?>
                                    <div class="status-rent">For Rent</div>
                                <?php endif?>                                    
                                <?php if($property["is_featured"] == 1):?>
                                    <div class="featured">Featured</div>
                                <?php endif?>
                            </div>
                            <div class="price"><?php echo $property["price"]?> PLN</div>
                            <div class="wishlist"><a href=""><i class="far fa-heart"></i></a></div>
                        </div>
                        <div class="text">
                            <h3>
                                <a href="<?php echo BASE_URL?>property/<?php echo $property["id"]?>/<?php echo $property["slug"]?>">
                                    <?php echo $property["name"]?>
                                </a>
                            </h3>
                            <div class="detail">
                                <div class="stat">
                                    <div class="i1"><?php echo $property["size"]?> sqft</div>
                                    <?php if(!is_null($property["bedroom"])):?>
                                        <div class="i2"><?php echo $property["bedroom"]?> Bed</div>
                                    <?php endif?>
                                    <?php if(!is_null($property["bathroom"])):?>
                                        <div class="i3"><?php echo $property["bathroom"]?> Bath</div>
                                    <?php endif?>
                                </div>
                                <div class="address">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $property["address"]?>
                                </div>
                                <div class="type-location">
                                    <div class="i1">
                                        <i class="fas fa-edit"></i> <?php echo $property["type_name"]?>
                                    </div>
                                    <div class="i2">
                                        <i class="fas fa-location-arrow"></i> <?php echo $property["location_name"]?>
                                    </div>
                                </div>
                                <div class="agent-section">
                                    <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["agent_photo"]?>" alt="">
                                    <a href="<?php echo BASE_URL?>agent/<?php echo $property["agent_id"]?>/<?php echo $property["agent_slug"]?>">
                                        <?php echo $property["agent_name"]?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;endif?>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>