<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                properties.*,
                group_concat(concat(amenities.icon, '|', amenities.name) separator ',') AS amenity_details,
                locations.name AS location_name,
                types.name AS type_name,
                agents.full_name AS agent_name,
                agents.email AS agent_email,
                agents.photo AS agent_photo,
                group_concat(distinct property_photos.photo separator ',') as photos,
                group_concat(distinct property_videos.code separator ',') as videos
            FROM
                properties
            LEFT JOIN
                agents ON agents.id=properties.agent_id
            LEFT JOIN
                locations ON locations.id=properties.location_id
            LEFT JOIN
                types ON types.id=properties.type_id
            LEFT JOIN
                amenities ON find_in_set(amenities.id,properties.amenities)
            LEFT JOIN
                property_photos ON property_photos.property_id=properties.id
            LEFT JOIN
                property_videos ON property_videos.property_id=properties.id
            GROUP BY
                properties.id
            ORDER BY
                properties.id DESC
        ");
        $stmt->execute();
        $properties=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Properties</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-plus"></i> Dashboard</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($properties as $property):?>
                                            <tr>
                                                <td><?php echo $property["id"]?></td>
                                                <td><img class="w_100" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt=""></td>
                                                <td>
                                                    <?php echo $property["name"]?><br>
                                                    <?php if($property["is_featured"] == 1):?>
                                                        <span class="badge bg-success">Featured</span>
                                                    <?php else:?>
                                                        <span class="badge bg-danger">No Featured</span>
                                                    <?php endif?>
                                                </td>
                                                <td><?php echo $property["location_name"]?></td>
                                                <td><?php echo $property["price"]?> PLN</td>
                                                <td class="pt_10 pb_10">
                                                    <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $property["id"]?>"><i class="fas fa-eye"></i></a>
                                                    <a href="" data-property-id="<?php echo $property["id"]?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                </td>
                                                <div class="modal fade modal-lg" id="modal_<?php echo $property["id"]?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Detail</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group row bdb1 pt_10 mb_0">
                                                                    <div class="col-md-3"><label class="form-label">Property Name</label></div>
                                                                    <div class="col-md-9"><?php echo $property["name"]?></div>
                                                                </div>
                                                                <div class="form-group row bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="col-md-3"><label class="form-label">Agent</label></div>
                                                                    <div class="col-md-9">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <img class="w_100" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["agent_photo"]?>" alt="">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <?php echo $property["agent_name"]?><br>
                                                                                <?php echo $property["agent_email"]?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if($property["is_featured"] == 1):?>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-12 alert alert-success"><?php if($property["is_featured"] == 1)?> Featured</div>
                                                                    </div>
                                                                <?php endif?>
                                                                <div class="form-group row bdb1 pt_10 p-3">
                                                                    <img src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" class="col-12" />
                                                                </div>
                                                                <div class="form-group row bdb1 pt_10 mb_0">
                                                                    <div class="col-md-3"><label class="form-label">Location</label></div>
                                                                    <div class="col-md-9"><?php echo $property["location_name"]?></div>
                                                                </div>
                                                                <div class="form-group row bdb1 pt_10 mb_0">
                                                                    <div class="col-md-3"><label class="form-label">Type</label></div>
                                                                    <div class="col-md-9"><?php echo $property["type_name"]?></div>
                                                                </div>
                                                                <?php if(!is_null($property["description"])):?>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-3"><label class="form-label">Description</label></div>
                                                                        <div class="col-md-9"><?php echo html_entity_decode($property["description"])?></div>
                                                                    </div>
                                                                <?php endif?>
                                                                <?php if(!is_null($property["amenities"])):?>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-3"><label class="form-label">Amenities</label></div>
                                                                        <div class="col-md-9">
                                                                            <div class="row">
                                                                                <?php foreach(explode(",",$property["amenity_details"]) as $amenity): list($icon,$name)=explode("|",$amenity)?>
                                                                                    <div class="col-md-6 mb-4">
                                                                                        <i class="<?php echo $icon?>"></i>
                                                                                        <?php echo $name?>
                                                                                    </div>
                                                                                <?php endforeach?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif?>
                                                                <?php if(!empty($property["photos"])):?>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <?php foreach(explode(",",$property["photos"]) as $photo):?>
                                                                                    <div class="col-md-6 mb-4">
                                                                                        <img class="img-fluid" src="<?php echo PUBLIC_URL?>uploads/property/photo/<?php echo $photo?>">
                                                                                    </div>
                                                                                <?php endforeach?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif?>
                                                                <?php if(!empty($property["videos"])):?>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <?php foreach(explode(",",$property["videos"]) as $video):?>
                                                                                    <a href="http://www.youtube.com/watch?v=<?php echo $video?>" class="col-md-6 mb-4">
                                                                                        <img class="img-fluid" src="http://img.youtube.com/vi/<?php echo $video?>/0.jpg">
                                                                                    </a>
                                                                                <?php endforeach?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif?>
                                                                <div class="form-group row bdb1 pt_10 mb_0 p-2">
                                                                    <div class="col-12"><?php echo html_entity_decode($property["map"])?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach;endif?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        $(".btn.btn-danger").click(function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el =  $(this)
            const parent = el.closest("tr")
            const propertyId = el.data("property-id")

            const formData = new FormData()
            formData.append("property_id", btoa(propertyId))

            parent.css("pointer-events","none")

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_property_delete_ajax.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response){
                    console.log(response)
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.error) {
                        parent.css("pointer-events","")
                    }

                    if(res.success){
                        parent.slideUp()
                    }
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>