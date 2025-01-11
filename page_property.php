<?php 
    include "./layout_top.php";

    if(!isset($_GET["slug"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    if(!isset($_GET["id"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    $id = $_GET["id"];
    $slug = $_GET["slug"];


    try{
        $stmtProperty = $pdo->prepare("
            select
                properties.*,
                agents.*,
                locations.name as location_name,
                types.name as type_name,
                group_concat(concat(amenities.name,'|',amenities.icon) separator ',') as amenities_details
            from
                properties
            left join
                agents on agents.id=properties.agent_id
            left join
                locations on locations.id=properties.location_id
            left join
                types on types.id=properties.type_id
            left join
                amenities on find_in_set(amenities.id, properties.amenities)
            where
                properties.id=? and properties.slug=?
            limit
                1
        ");
        $stmtProperty->execute([$id,$slug]);

        if($stmtProperty->rowCount() == 0)
            throw new PDOException("");
        
        $property = $stmtProperty->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        header("Location: ".BASE_URL."404");
        exit();
    }

    try{
        $stmtPhotos = $pdo->prepare("
            select
                property_photos.*
            from
                properties
            inner join
                property_photos on property_photos.property_id=properties.id
            where
                properties.id=? and properties.slug=?
        ");
        $stmtPhotos->execute([$id,$slug]);
        
        $photos = $stmtPhotos->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtVideos = $pdo->prepare("
            select
                property_videos.*
            from
                properties
            inner join
                property_videos on property_videos.property_id=properties.id
            where
                properties.id=? and properties.slug=?
        ");
        $stmtVideos->execute([$id,$slug]);        
        $videos = $stmtVideos->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtRelatedProperties=$pdo->prepare("
            SELECT
                properties.*,
                types.name as type_name,
                locations.name as location_name,
                agents.full_name as agent_name,
                agents.photo as agent_photo
            FROM
                properties
            LEFT JOIN   
                types on types.id=properties.type_id
            LEFT JOIN
                locations on locations.id=properties.location_id
            LEFT JOIN
                agents on agents.id=properties.agent_id
            WHERE
                properties.id!=?
            AND
                properties.location_id=?
            AND
                properties.type_id=?
            ORDER BY
                ABS(properties.price - ?) DESC
            LIMIT
                2
        ");
        $stmtRelatedProperties->execute([
            $id,
            $property["location_id"],
            $property["type_id"],
            $property["price"]
        ]);
        $relatedProperties=$stmtRelatedProperties->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $property["name"]?></h2>
            </div>
        </div>
    </div>
</div>

<div class="property-result pt_50 pb_50">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="left-item">
                    <div class="main-photo">
                        <img src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                    </div>
                    <?php if(!is_null($property["description"])):?>
                        <h2>Description</h2>
                        <?php echo html_entity_decode($property["description"])?>
                    <?php endif?>
                </div>
                <?php if($stmtPhotos->rowCount() > 0):?>
                    <div class="left-item">
                        <h2>Photos</h2>
                        <div class="photo-all">
                            <div class="row">
                                <?php foreach($photos as $photo):?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="item">
                                            <a href="<?php echo PUBLIC_URL?>uploads/property/photo/<?php echo $photo["photo"]?>" class="magnific">
                                                <img src="<?php echo PUBLIC_URL?>uploads/property/photo/<?php echo $photo["photo"]?>" alt="" />
                                                <div class="icon">
                                                    <i class="fas fa-plus"></i>
                                                </div>
                                                <div class="bg"></div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <?php if($stmtVideos->rowCount() > 0):?>
                    <div class="left-item">
                        <h2>Videos</h2>
                        <div class="video-all">
                            <div class="row">
                                <?php foreach($videos as $video):?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="item">
                                            <a class="video-button" href="http://www.youtube.com/watch?v=<?php echo $video["code"]?>">
                                                <img src="http://img.youtube.com/vi/<?php echo $video["code"]?>/0.jpg" alt="" />
                                                <div class="icon">
                                                    <i class="far fa-play-circle"></i>
                                                </div>
                                                <div class="bg"></div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach?>
                            </div>
                        </div>
                    </div>
                <?php endif?>
                
                <div class="left-item mb_50">
                    <?php
                        $insert_url = BASE_URL."property/".$id."/".$slug;
                        $insert_photo = PUBLIC_URL."uploads/property/".$property["featured_photo"];
                        $insert_title = $property["name"];
                        $insert_text = $property["description"];
                    ?>
                    <h2>Share</h2>
                    <div class="share">
                        <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=[<?php echo $insert_url?>]&picture=[<?php echo $insert_photo?>]" target="_blank">
                            Facebook
                        </a>
                        <a class="twitter" href="https://twitter.com/share?url=[<?php echo $insert_url?>]&text=[<?php echo $insert_text?>]" target="_blank">
                            Twitter
                        </a>
                        <a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=[<?php echo $insert_url?>]&title=[<?php echo $insert_title?>]&summary=[<?php echo $insert_text?>]" target="_blank">
                            LinkedIn
                        </a>
                    </div>
                </div>

                <?php if($stmtRelatedProperties->rowCount() > 0):?>
                    <div class="left-item">
                        <h2>Related Properties</h2>
                        <div class="property related-property pt_0 pb_0">
                            <div class="row">
                                <?php foreach($relatedProperties as $relatedProperty):?>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="item">
                                            <div class="photo">
                                                <img class="main" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                                                <div class="top">
                                                    <?php if(strpos($relatedProperty["purpose"],"sale") !== false):?>
                                                        <div class="status-sale">For Sale</div>
                                                    <?php else:?>
                                                        <div class="status-rent">For Rent</div>
                                                    <?php endif?>
                                                    <?php if($relatedProperty["is_featured"] == 1):?>
                                                        <div class="featured">Featured</div>
                                                    <?php endif?>
                                                </div>
                                                <div class="price"><?php echo $relatedProperty["price"]?> PLN</div>
                                                <div class="wishlist"><a href=""><i class="far fa-heart"></i></a></div>
                                            </div>
                                            <div class="text">
                                                <h3><a href="property.html"><?php echo $relatedProperty["name"]?></a></h3>
                                                <div class="detail">
                                                    <div class="stat">
                                                        <div class="i1"><?php echo $relatedProperty["size"]?> sqft</div>
                                                        <?php if(!is_null($relatedProperty["bedroom"])):?>
                                                            <div class="i2"><?php echo $relatedProperty["bedroom"]?> Bed</div>
                                                        <?php endif?>
                                                        <?php if(!is_null($relatedProperty["bathroom"])):?>
                                                            <div class="i3"><?php echo $relatedProperty["bathroom"]?> Bath</div>
                                                        <?php endif?>                                        
                                                    </div>
                                                    <div class="address">
                                                        <i class="fas fa-map-marker-alt"></i> <?php echo $relatedProperty["address"]?>
                                                    </div>
                                                    <div class="type-location">
                                                        <div class="i1">
                                                            <i class="fas fa-edit"></i> <?php echo $relatedProperty["type_name"]?>
                                                        </div>
                                                        <div class="i2">
                                                            <i class="fas fa-location-arrow"></i> <?php echo $relatedProperty["location_name"]?>
                                                        </div>
                                                    </div>
                                                    <div class="agent-section">
                                                        <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $relatedProperty["agent_photo"]?>" alt="">
                                                        <a href=""><?php echo $relatedProperty["agent_name"]?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach?>
                            </div>
                        </div>
                    </div>
                <?php endif?>
            </div>
            <div class="col-lg-4 col-md-12">

                <div class="right-item">
                    <h2>Agent</h2>
                    <div class="agent-right d-flex justify-content-start">
                        <div class="left">
                            <?php if(is_null($property["photo"])):?>
                                <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                            <?php else:?>
                                    <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["photo"]?>" alt="">
                            <?php endif?>
                        </div>
                        <div class="right">
                            <h3><a href=""><?php echo $property["full_name"]?></a></h3>
                            <h4><?php echo $property["company"]?></h4>
                        </div>
                    </div>
                    <div class="table-responsive mt_25">
                        <table class="table table-bordered">
                            <tr>
                                <td>Posted On: </td>
                                <td><?php echo date("d M, Y", strtotime($property["email"]))?></td>
                            </tr>
                            <tr>
                                <td>Email: </td>
                                <td><?php echo $property["email"]?></td>
                            </tr>
                            <tr>
                                <td>Phone: </td>
                                <td><?php echo $property["phone"]?></td>
                            </tr>
                            <?php if(!is_null($property["website"])):?>
                                <tr>
                                    <td>Website: </td>
                                    <td><?php echo $property["website"]?></td>
                                </tr>
                            <?php endif?>
                            <?php if(
                                !is_null($property["facebook"]) ||
                                !is_null($property["twitter"]) ||
                                !is_null($property["pinterest"]) ||
                                !is_null($property["instagram"]) ||
                                !is_null($property["linkedin"]) ||
                                !is_null($property["youtube"])
                            ):?>
                                <tr>
                                    <td>Social: </td>
                                    <td>
                                        <ul class="agent-ul">
                                            <?php if(!is_null($property["facebook"])):?>
                                                <li><a href="<?php echo $property["facebook"]?>"><i class="fab fa-facebook-f"></i></a></li> 
                                            <?php endif?>
                                            <?php if(!is_null($property["twitter"])):?>
                                                <li><a href="<?php echo $property["twitter"]?>"><i class="fab fa-twitter"></i></a></li>
                                            <?php endif?>
                                            <?php if(!is_null($property["pinterest"])):?>
                                                <li><a href="<?php echo $property["pinterest"]?>"><i class="fab fa-pinterest-p"></i></a></li>
                                            <?php endif?>
                                            <?php if(!is_null($property["instagram"])):?>
                                                <li><a href="<?php echo $property["instagram"]?>"><i class="fab fa-instagram"></i></a></li>
                                            <?php endif?>
                                            <?php if(!is_null($property["linkedin"])):?>
                                                <li><a href="<?php echo $property["linkedin"]?>"><i class="fab fa-linkedin-in"></i></a></li>
                                            <?php endif?>
                                            <?php if(!is_null($property["youtube"])):?>   
                                                <li><a href="<?php echo $property["youtube"]?>"><i class="fab fa-youtube"></i></a></li>
                                            <?php endif?>                                    
                                        </ul>
                                    </td>
                                </tr>
                            <?php endif?>
                        </table>
                    </div>
                </div>

                <div class="right-item">
                    <h2>Features</h2>
                    <div class="summary">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td><b>Price</b></td>
                                    <td><?php echo $property["price"]?> PLN</td>
                                </tr>
                                <tr>
                                    <td><b>Location</b></td>
                                    <td><?php echo $property["location_name"]?></td>
                                </tr>
                                <tr>
                                    <td><b>Type</b></td>
                                    <td><?php echo $property["type_name"]?></td>
                                </tr>
                                <tr>
                                    <td><b>Purpose</b></td>
                                    <td><?php echo $property["purpose"]?></td>
                                </tr>
                                <?php if(!is_null($property["bedroom"])):?>
                                    <tr>
                                        <td><b>Bedroom:</b></td>
                                        <td><?php echo $property["bedroom"]?></td>
                                    </tr>
                                <?php endif?>
                                <?php if(!is_null($property["bathroom"])):?>
                                    <tr>
                                        <td><b>Bathroom:</b></td>
                                        <td><?php echo $property["bathroom"]?></td>
                                    </tr>
                                <?php endif?>
                                <tr>
                                    <td><b>Size:</b></td>
                                    <td><?php echo $property["size"]?> sqft</td>
                                </tr>
                                <?php if(!is_null($property["floor"])):?>
                                    <tr>
                                        <td><b>Floor:</b></td>
                                        <td><?php echo $property["floor"]?></td>
                                    </tr>
                                <?php endif?>                                
                                <?php if(!is_null($property["garage"])):?>
                                    <tr>
                                        <td><b>Garage:</b></td>
                                        <td><?php echo $property["garage"]?></td>
                                    </tr>
                                <?php endif?>
                                <?php if(!is_null($property["balcony"])):?>
                                    <tr>
                                        <td><b>Balcony:</b></td>
                                        <td><?php echo $property["balcony"]?></td>
                                    </tr>
                                <?php endif?>                                
                                <tr>
                                    <td><b>Address:</b></td>
                                    <td><?php echo $property["address"]?></td>
                                </tr>
                                <?php if(!is_null($property["built_year"])):?>
                                    <tr>
                                        <td><b>Built Year:</b></td>                                    
                                        <td><?php echo date("d M, Y", strtotime($property["built_year"]))?></td>
                                    </tr>
                                <?php endif?>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if(!is_null($property["amenities"])):?>
                    <div class="right-item">
                        <h2>Amenities</h2>
                        <div class="amenity">
                            <div class="row g-2">
                                <?php foreach(explode(",",$property["amenities_details"]) as $amenity): list($name,$icon)=explode("|",$amenity)?>
                                    <div class="col-md-6"><i class="<?php echo $icon?>"></i> <?php echo $name?></div>
                                <?php endforeach?>
                            </div>
                        </div>
                    </div>
                <?php endif?>

                <div class="right-item">
                    <h2>Location Map</h2>
                    <div class="location-map"><?php echo html_entity_decode($property["map"])?></div>
                </div>

                <div class="right-item">
                    <h2>Enquery Form</h2>
                    <div id="enquery-form">
                        <form>
                            <div class="mb-3">
                                <input name="form-name" type="text" class="form-control" placeholder="Full Name" />
                            </div>
                            <div class="mb-3">
                                <input name="form-email" type="text" class="form-control" placeholder="Email Address" />
                            </div>
                            <div class="mb-3">
                                <input name="form-subject" type="text" class="form-control" placeholder="Subject" />
                            </div>
                            <div class="mb-3">
                                <textarea name="form-message" class="form-control h-150" rows="3" placeholder="Message"></textarea>
                            </div>
                            <div class="d-flex gap-5 align-items-center">
                                <button name="form-enquery" type="button" class="btn btn-primary">
                                    Submit
                                </button>
                                <div class="form-loader"></div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("[name=form-enquery]").click( async function(e){
            e.preventDefault()

            $("#enquery-form form").css("pointer-events","none")
            $("#enquery-form .form-loader").slideDown()

            await new Promise((resolve) => setTimeout(resolve, 1000))


            const formData = new FormData()

            const name = $("#enquery-form [name=form-name]").val().trim()
            const email = $("#enquery-form [name=form-email]").val().trim()
            const subject = $("#enquery-form [name=form-subject]").val().trim()
            const message = $("#enquery-form [name=form-message]").val().trim()

            formData.append("name",name)
            formData.append("email",email)
            formData.append("email_agent", "<?php echo $property["email"]?>")
            formData.append("subject",subject)
            formData.append("message",message)

            $.ajax({
                url: "<?php echo BASE_URL ?>page_property_mail.php",
                type: "POST",
                contentType: false,
                processData:false,
                data: formData,
                success: function(responsive){

                    if(responsive.includes("table")) {
                        $("#enquery-form").parent().append("<div class='alert alert-danger'>Oops! Something went wrong. Please try again later.</div>")
                        $("#enquery-form form").slideUp()
                        return
                    }

                    const res=JSON.parse(responsive)

                    $("#enquery-form [name^=form-]").next("small").remove()
                    $("#enquery-form .form-loader").slideUp()
                    $("#enquery-form form").css("pointer-events","")

                    if(res.success){

                        $("#enquery-form").parent().append(res.success.message)
                        $("#enquery-form form").slideUp()

                        // console.log(res)
                    }

                    if(res.error){
                        const err = res.error

                        Object.keys(err).forEach(key => {
                            const message = err[key][0].message
                            const id = err[key][0].id
                            $("form [name=form-"+id+"]").after(message)
                        })
                            
                        // console.log(res)
                    }
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>