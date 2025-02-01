<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL);
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                testimonials
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $testimonials=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                settings
            LIMIT
                1
        ");
        $stmt->execute();
        $setting=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors=[];
    
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $testimonial_heading=htmlspecialchars(trim($_POST["testimonial_heading"]));

        $testimonial_photo_name=$_FILES["testimonial_photo"]["name"];
        $testimonial_photo_tmp=$_FILES["testimonial_photo"]["tmp_name"];
        $testimonial_photo_extension=pathinfo($_FILES["testimonial_photo"]["name"],PATHINFO_EXTENSION);
        $testimonial_photo_size=$_FILES["testimonial_photo"]["size"];

        if(!empty($testimonial_photo_name)){
            if(!in_array($testimonial_photo_extension,["png","jpg","jpeg"]))
                $errors["testimonial_photo"][] = "<small class='form-text text-danger'>The hero photo type is not allowed!</small>";

            if($testimonial_photo_size >= 1024*1024*1)
                $errors["testimonial_photo"][] = "<small class='form-text text-danger'>The hero photo size exceeds the 1MB limit!</small>";
        }

        if($testimonial_heading === "")
            $errors["testimonial_heading"][] = "<small class='form-text text-danger'>The testimonial heading field is required!</small>";

        if(empty($errors)){
            try{
                if(empty($testimonial_photo_name)):
                    $testimonial_photo=$setting["testimonial_photo"];
                else:
                    $testimonial_photo=uniqid().".".$testimonial_photo_extension;
                endif;

                if(!empty($testimonial_photo_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["testimonial_photo"]))
                        unlink($path.$setting["testimonial_photo"]);

                    list($width,$height)=getimagesize($testimonial_photo_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($testimonial_photo_extension){
                        case "jpg": case "jpeg": $source_image=imagecreatefromjpeg($testimonial_photo_tmp);break;
                        case "png": 
                                imagealphablending($thumbnail,false);
                                imagesavealpha($thumbnail,true);
                                $source_image=imagecreatefrompng($testimonial_photo_tmp);
                        break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    if($testimonial_photo_extension == "png")
                        imagepng($thumbnail,$path.$logo,6);
                    else
                        imagejpeg($thumbnail,$path.$testimonial_photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            settings
                        SET
                            testimonial_heading=?,
                            testimonial_photo=?
                    ");

                    if(!$stmt->execute([
                        $testimonial_heading,
                        $testimonial_photo,
                    ]))
                        throw new PDOException("An error occurred while updating. Please try again later!");

                    $_SESSION["success"]="The settings are updated successfully.";
                    header("Location: ".ADMIN_URL."testimonials");
                    exit();
                }catch(PDOException $err){
                    $error_message=$err->getMessage();
                }
            }catch(Exception $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1><?php echo setPageTitle($current_page)?></h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>testimonial-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-3 form-group mb-3">
                                        <label>Existing Photo</label>
                                        <div>
                                            <?php if(empty($setting["testimonial_photo"])):?>
                                                <img src="https://placehold.co/1000x600?text=1000x600\nBG Banner" alt="" class="mw-100">
                                            <?php else:?>
                                                <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["testimonial_photo"]?>" alt="" class="mw-100">
                                            <?php endif?>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">    
                                        <div class="form-group mb-3">
                                            <label>Change Photo</label>
                                            <div>
                                                <input type="file" name="testimonial_photo" class="form-control">
                                                <?php if(isset($errors["testimonial_photo"])) echo $errors["testimonial_photo"][0]?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <span class="badge badge-success" style="<?php if($setting["testimonial_status"] == 0) echo "display:none"?>">Yes</span>
                                            <span class="badge badge-danger" style="<?php if($setting["testimonial_status"] == 1) echo "display:none"?>">No</span>
                                        </div>
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="testimonial_status" 
                                                <?php if($setting["testimonial_status"] == 1) echo "checked"?> 
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div> 
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group mb-3">
                                            <label>Testimonial Heading</label>
                                            <input type="text" class="form-control" name="testimonial_heading" value="<?php echo $setting["testimonial_heading"]?>">
                                            <?php if(isset($errors["testimonial_heading"])) echo $errors["testimonial_heading"][0]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary" name="form">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                                            <th>Designation</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($testimonials as $testimonial):?>
                                            <tr>
                                                <td><?php echo $testimonial["id"]?></td>
                                                <td>
                                                    <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/testimonial/<?php echo $testimonial["photo"]?>" alt="">
                                                </td>
                                                <td><?php echo $testimonial["full_name"]?></td>
                                                <td><?php echo $testimonial["designation"]?></td>
                                                <td><?php echo substr($testimonial["comment"],0,30)?>...</td>
                                                <td>
                                                    <span class="badge badge-success" style="<?php if($testimonial["status"] == 0) echo "display:none"?>">Yes</span>
                                                    <span class="badge badge-danger" style="<?php if($testimonial["status"] == 1) echo "display:none"?>">No</span>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <div id="wrapper-loader-btn" style="display: inline-block;">
                                                        <span class="button-loader"></span>
                                                        <input 
                                                            name="status" 
                                                            <?php if($testimonial["status"] == 1) echo "checked"?> 
                                                            data-testimonial-id="<?php echo $testimonial["id"]?>"
                                                            type="checkbox" 
                                                            data-toggle="toggle" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger"
                                                        >
                                                    </div>
                                                    <a href="<?php ADMIN_URL?>testimonial-edit/<?php echo $testimonial["id"]?>" class="btn btn-primary">Edit</a>
                                                    <a data-testimonial-id="<?php echo $testimonial["id"]?>" href="" class="btn btn-danger">
                                                        <span class="button-loader"></span>
                                                        <span>Delete</span>
                                                    </a>
                                                </td>
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
    /* delete */
    $(document).ready(function(){
        $(document).on("click","a.btn.btn-danger", async function(e){
            e.preventDefault()
            
            if(!confirm('Are you sure?')) return

            const el = $(this)
            const parent = $(this).closest("tr")
            const testimonialId = $(this).data("testimonial-id")
            const formData = new FormData()

            el.addClass("pending")
            el.removeClass("active")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("testimonial_id",btoa(testimonialId))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_testimonial_delete_ajax.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success:function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        parent.slideUp()
                    }
                    
                    if(res.error){
                        el.addClass("active")
                        el.removeClass("pending")
                    }
                }
            })
        })
    })

    /* update */
    $(document).ready(function(){
        $(document).on("change","input[name=status]",async function(){

            const parentTr = $(this).closest("tr")
            const parentDiv = $(this).closest("div#wrapper-loader-btn")
            const dangerBadge = parentTr.find(".badge.badge-danger")
            const successBadge = parentTr.find(".badge.badge-success")
            const testimonialId = $(this).data("testimonial-id")
            const status = $(this).prop("checked") ? "on" : "off"
            const formData = new FormData()

            parentDiv.attr("class","pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("testimonial_id",btoa(testimonialId))
            formData.append("status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_testimonial_edit_status_ajax.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success:function(response){
                    console.log(response)
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        parentDiv.attr("class","active")

                        if(status === "on"){
                            successBadge.show()
                            dangerBadge.hide()
                        } else {
                            successBadge.hide()
                            dangerBadge.show()
                        }
                    }
                    
                    if(res.error){
                        parentDiv.attr("class","active")
                    }
                }
            })
        })
    })

    /* update */
    $(document).ready(function(){
        $("input[name=testimonial_status]").change(async function(e){
            e.preventDefault()

            const el = $(this)

            const yes =el.closest(".col-md-2").find(".badge.badge-success")
            const no =el.closest(".col-md-2").find(".badge.badge-danger")

            const parent = el.closest(".wrapper-loader-btn")
            const status = el.prop("checked") ? 1 : 0
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("testimonial_status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_testimonial_section_edit_status_ajax.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(respense){
                    const res = JSON.parse(respense)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        if(status === 1){
                            yes.show()
                            no.hide()
                        }else{
                            yes.hide()
                            no.show()
                        }
                    }

                    parent.removeClass("pending")
                    parent.addClass("active")
                }
            })
        })
    })

    /* img */
    $(document).ready(function(){
        $("input[type=file]").change(function(e){
            $(this).closest(".row").find("img").each(function(){
                $(this).attr("src",URL.createObjectURL(e.target.files[0]))
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>