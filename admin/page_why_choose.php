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
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
        <h1>Why Choose</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>why-choose-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
</script>
<?php include "./layout_footer.php"?>