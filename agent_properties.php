<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."login");
        exit();
    }

    try{
        $stmt = $pdo->prepare("
            select 
                properties.id, properties.name, properties.featured_photo as photo, properties.status, properties.location_id as location,            
                agents.full_name as agent, types.name as type, locations.name as location, properties.purpose, 
                group_concat(concat(amenities.name,'|',amenities.icon) separator ',') as amenities,
                properties.map
            from properties 
            join types on properties.type_id=types.id
            join locations on properties.location_id=locations.id
            join agents on properties.agent_id=agents.id
            join amenities on find_in_set(amenities.id,properties.amenities)
            group by properties.id
            order by properties.id desc
        ");
        $stmt->execute();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>All Properties</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Thumbnail</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Active?</th>
                                <th class="w-100">Options</th>
                                <th class="w-60">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($stmt->rowCount() > 0): foreach($properties as $property):?>
                                <tr>
                                    <td><?php echo $property["id"]?></td>
                                    <td>
                                        <img width="110" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["photo"]?>" alt="">
                                    </td>
                                    <td><?php echo $property["name"]?></td>
                                    <td><?php echo $property["type"]?></td>
                                    <td><?php echo $property["location"]?></td>
                                    <td><?php echo $property["purpose"]?></td>
                                    <td>
                                        <?php if($property["status"] == 1):?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else:?>
                                            <span class="badge bg-danger">No</span>
                                        <?php endif?>
                                    </td>
                                    <td>
                                        <a 
                                            href="<?php echo BASE_URL?>agent-property-photos/<?php echo $property["id"]?>" 
                                            class="btn btn-primary btn-sm text-white"
                                        >
                                            <i class="fas fa-camera"></i>
                                        </a>
                                        <a 
                                            href="<?php echo BASE_URL?>agent-property-videos/<?php echo $property["id"]?>" 
                                            class="btn btn-primary btn-sm text-white"
                                        >
                                            <i class="fas fa-video"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $property["id"]?>"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo BASE_URL?>agent-property-edit/<?php echo $property["id"]?>" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i></a>
                                        <a data-id="<?php echo $property["id"]?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                    <div class="modal fade" id="modal_<?php echo $property["id"]?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if($stmt->rowCount() > 0): foreach($property as $key=>$val): if(!is_null($val)):?>
                                                        <div class="form-group row bdb1 pt_10 mb_0">
                                                            <div class="col-md-3"><label class="form-label">
                                                                <?php echo $key;?>
                                                            </label></div>
                                                            <div class="col-md-9">
                                                                <?php 
                                                                    if($key == "map") echo html_entity_decode($val);
                                                                    elseif($key == "photo") echo "<img style='width:100%' src='".PUBLIC_URL."uploads/property/".$val."' alt=''>";
                                                                    elseif($key == "amenities") {?> 
                                                                        <div class="row">
                                                                            <?php foreach(explode(",",$val) as $amenity) { list($name,$icon) = explode("|",$amenity);?>
                                                                                <div class="col-6 mb-2">
                                                                                    <i class="<?php echo $icon?>"></i>
                                                                                    <span><?php echo $name?></span>
                                                                                </div>
                                                                            <?php }?>
                                                                        </div>
                                                                    <?php } 
                                                                    else echo $val
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php endif;endforeach;endif?>
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
<script>
    $(".btn.btn-danger").click(function(e){
        e.preventDefault()

        if(!confirm('Are you sure?')) return

        $(e.target).closest("tr").css("pointer-events","none")

        const formData = new FormData()

        formData.append("id",$(e.target).closest("a").data("id"))

        $.ajax({
            type:"POST",
            url: "<?php echo BASE_URL?>agent_property_delete.php",
            contentType: false,
            processData: false,
            data: formData,
            success: function (response){
                const res = JSON.parse(response)

                console.log(res)

                iziToast.show({
                    message: res.success ?? res.error,
                    position: "topRight",
                    color: res.success ? "green" : "red",
                    onClosing: function () {
                        if(res.success)
                            $(e.target).closest("tr").slideUp()

                        if(res.error)
                            $(e.target).closest("tr").css("pointer-events","")
                    }
                })
            }
        })
    })
</script>
<?php include "./layout_footer.php"?>

