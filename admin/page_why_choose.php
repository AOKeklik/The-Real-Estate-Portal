<?php   
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                why_choose_items
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $why_choose_items=$stmt->fetchAll(pdo::FETCH_ASSOC);
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
        $why_choose_heading=htmlspecialchars(trim($_POST["why_choose_heading"]));
        $why_choose_subheading=htmlspecialchars(trim($_POST["why_choose_subheading"]));

        $why_choose_photo_name=$_FILES["why_choose_photo"]["name"];
        $why_choose_photo_tmp=$_FILES["why_choose_photo"]["tmp_name"];
        $why_choose_photo_extension=pathinfo($_FILES["why_choose_photo"]["name"],PATHINFO_EXTENSION);
        $why_choose_photo_size=$_FILES["why_choose_photo"]["size"];


        if(!empty($why_choose_photo_name)){
            if(!in_array($why_choose_photo_extension,["png","jpg","jpeg"]))
                $errors["why_choose_photo"][] = "<small class='form-text text-danger'>The hero photo type is not allowed!</small>";

            if($why_choose_photo_size >= 1024*1024*1)
                $errors["why_choose_photo"][] = "<small class='form-text text-danger'>The hero photo size exceeds the 1MB limit!</small>";
        }

        if($why_choose_heading === "")
            $errors["why_choose_heading"][] = "<small class='form-text text-danger'>The why choose heading field is required!</small>";

        if($why_choose_subheading === "")
            $errors["why_choose_subheading"][] = "<small class='form-text text-danger'>The why choose subheading field is required!</small>";

        if(empty($errors)){
            try{
                if(empty($why_choose_photo_name)):
                    $why_choose_photo=$setting["why_choose_photo"];
                else:
                    $why_choose_photo=uniqid().".".$why_choose_photo_extension;
                endif;

                if(!empty($why_choose_photo_name)){
                    $path="../public/uploads/setting/";

                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$setting["why_choose_photo"]))
                        unlink($path.$setting["why_choose_photo"]);

                    list($width,$height)=getimagesize($why_choose_photo_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($why_choose_photo_extension){
                        case "jpg": case "jpeg": $source_image=imagecreatefromjpeg($why_choose_photo_tmp);break;
                        case "png": 
                                imagealphablending($thumbnail,false);
                                imagesavealpha($thumbnail,true);
                                $source_image=imagecreatefrompng($why_choose_photo_tmp);
                        break;
                        default: throw new Exception("Unsupported image type!");break;
                    }

                    imagecopyresampled($thumbnail,$source_image,0,0,0,0,$width,$height,$width,$height);
                    if($why_choose_photo_extension == "png")
                        imagepng($thumbnail,$path.$logo,6);
                    else
                        imagejpeg($thumbnail,$path.$why_choose_photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($source_image);
                }

                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            settings
                        SET
                            why_choose_heading=?,
                            why_choose_subheading=?,
                            why_choose_photo=?
                    ");

                    if(!$stmt->execute([
                        $why_choose_heading,
                        $why_choose_subheading,
                        $why_choose_photo,
                    ]))
                        throw new PDOException("An error occurred while updating. Please try again later!");

                    $_SESSION["success"]="The settings are updated successfully.";
                    header("Location: ".ADMIN_URL."why-choose");
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
                <a href="<?php echo ADMIN_URL?>why-choose-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-3  form-group mb-3">
                                        <label>Existing Photo</label>
                                        <div>
                                            <?php if(empty($setting["why_choose_photo"])):?>
                                                <img src="https://placehold.co/1000x600?text=1000x600\nBG Banner" alt="" class="mw-100">
                                            <?php else:?>
                                                <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["why_choose_photo"]?>" alt="" class="mw-100">
                                            <?php endif?>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">    
                                        <div class="form-group mb-3">
                                            <label>Change Photo</label>
                                            <div>
                                                <input type="file" name="why_choose_photo" class="form-control">
                                                <?php if(isset($errors["why_choose_photo"])) echo $errors["why_choose_photo"][0]?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <span class="badge badge-success" style="<?php if($setting["why_choose_status"] == 0) echo "display:none"?>">Yes</span>
                                            <span class="badge badge-danger" style="<?php if($setting["why_choose_status"] == 1) echo "display:none"?>">No</span>
                                        </div>
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="why_choose_status" 
                                                <?php if($setting["why_choose_status"] == 1) echo "checked"?> 
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div> 
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Title</label>
                                            <input type="text" class="form-control" name="why_choose_heading" value="<?php echo $setting["why_choose_heading"]?>">
                                            <?php if(isset($errors["why_choose_heading"])) echo $errors["why_choose_heading"][0]?>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label>Description</label>
                                            <input type="text" class="form-control" name="why_choose_subheading" value="<?php echo $setting["why_choose_subheading"]?>">
                                            <?php if(isset($errors["why_choose_subheading"])) echo $errors["why_choose_subheading"][0]?>
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
                                        <th>Icon</th>
                                        <th>Heading</th>
                                        <th>Text</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($why_choose_items as $item):?>
                                            <tr class="active">
                                                <td><?php echo $item["id"]?></td>
                                                <td><i class="<?php echo $item["icon"]?> fs-4"></i></td>
                                                <td><?php echo $item["heading"]?></td>
                                                <td><?php echo substr($item["text"],0,30)?>...</td>
                                                <td>
                                                    <span class="badge badge-success" style="<?php if($item["status"] == 0) echo "display:none"?>">Yes</span>
                                                    <span class="badge badge-danger" style="<?php if($item["status"] == 1) echo "display:none"?>">No</span>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <div class="wrapper-loader-btn" style="display: inline-block;">
                                                        <span class="button-loader"></span>
                                                        <input 
                                                            name="status" 
                                                            <?php if($item["status"] == 1) echo "checked"?> 
                                                            data-why-choose-id="<?php echo $item["id"]?>"
                                                            type="checkbox" 
                                                            data-toggle="toggle" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger"
                                                        >
                                                    </div>  
                                                    <a href="<?php echo ADMIN_URL?>why-choose-edit/<?php echo $item["id"]?>" class="btn btn-primary">Edit</a>
                                                    <a href="" data-why-choose-id="<?php echo $item["id"]?>" class="btn btn-danger btn-delete">
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
    /* update */
    $(document).ready(function(){
        $("input[name=status]").change(async function(e){
            e.preventDefault()

            const el = $(this)

            const yes =el.closest("tr").find(".badge.badge-success")
            const no =el.closest("tr").find(".badge.badge-danger")

            const parent = el.closest(".wrapper-loader-btn")
            const whyChooseId = el.data("why-choose-id")
            const status = el.prop("checked") ? 1 : 0
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("why_choose_id",btoa(whyChooseId))
            formData.append("status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_why_choose_edit_status_ajax.php",
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

    /* delete */
    $(document).ready(function(){
        $(".btn-delete").click(async function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el = $(this)
            const parent = el.closest("tr")
            const div = el.cl
            const whyChooseId = el.data("why-choose-id")
            const formData = new FormData()

            el.addClass("pending")
            el.removeClass("active")

            formData.append("why_choose_id",btoa(whyChooseId))
            await new Promise(resolve => setTimeout(resolve,1000))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_why_choose_delete_ajax.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(respense){
                    console.log(respense)
                    const res = JSON.parse(respense)

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
        $("input[name=why_choose_status]").change(async function(e){
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
            formData.append("status",btoa(status))

            $.ajax({
                url: "<?php echo ADMIN_URL?>page_why_choose_section_edit_status_ajax.php",
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