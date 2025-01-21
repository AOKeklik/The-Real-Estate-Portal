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
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Testimonials</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>testimonial-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
</script>
<?php include "./layout_footer.php"?>