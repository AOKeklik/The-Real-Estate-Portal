<?php
    include("./layout_top.php");

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                faqs
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $faqs=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err;
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Faqs</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>faq-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($faqs as $faq):?>
                                            <tr>
                                                <td><?php echo $faq["id"]?></td>
                                                <td><?php echo $faq["question"]?></td>
                                                <td><?php echo substr($faq["answer"],0,30)?>...</td>
                                                <td>
                                                    <span class="badge badge-danger" style="<?php if($faq["status"] == 1) echo "display:none"?>">No</span>
                                                    <span class="badge badge-success" style="<?php if($faq["status"] == 0) echo "display:none"?>">Yes</span>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <div class="wrapper-loader-btn" style="display: inline-block;">
                                                        <span class="button-loader"></span>
                                                        <input 
                                                            name="status" 
                                                            <?php if($faq["status"] == 1) echo "checked"?> 
                                                            data-faq-id="<?php echo $faq["id"]?>"
                                                            type="checkbox" 
                                                            data-toggle="toggle" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger"
                                                        >
                                                    </div> 
                                                    <a href="<?php echo ADMIN_URL?>faq-edit/<?php echo $faq["id"]?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <a data-faq-id="<?php echo $faq["id"]?>" href="" class="btn btn-danger delete-btn">
                                                        <span class="button-loader"></span>
                                                        <i class="fas fa-trash"></i>
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

            
            const no = $(this).closest("tr").find(".badge.badge-danger")
            const yes = $(this).closest("tr").find(".badge.badge-success")
            
            
            const parent = $(this).closest(".wrapper-loader-btn")
            const formData = new FormData()
            const status = $(this).prop("checked") ? 1 : 0
            
            parent.addClass("pending")
            parent.removeClass("active")

            formData.append("status",btoa(status))
            formData.append("faq_id",btoa($(this).data("faq-id")))
            await new Promise(resolve=>setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_faq_edit_status_ajax.php",
                contentType:false,
                processData:false,
                data:formData,
                success:function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })
                    if(res.success){
                        if(status === 1){
                            console.log(yes)
                            yes.show()
                            no.hide()
                        }else{
                            yes.hide()
                            no.show()
                        }
                    }


                    parent.addClass("active")
                    parent.removeClass("pending")
                }
            })
        })
    })

    /* delete */
    $(document).ready(function(){
        $(".delete-btn").click(async function(e){
            e.preventDefault()

            if(!confirm("Are you sure!")) return

            const el = $(this)
            const parent = $(this).closest("tr")
            const formData = new FormData()
            
            el.addClass("pending")
            el.removeClass("active")
            formData.append("faq_id",btoa($(this).data("faq-id")))
            await new Promise(resolve=>setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_faq_delete_ajax.php",
                contentType:false,
                processData:false,
                data:formData,
                success:function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
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
<?php include("./layout_footer.php")?>