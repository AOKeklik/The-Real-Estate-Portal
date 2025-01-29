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
                subscribers
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $subscribers=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1><?php echo setPageTitle($current_page)?></h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>subscriber-add-message" class="btn btn-primary"><i class="fas fa-plus"></i> Send Message</a>
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
                                            <th>Email</th>
                                            <th>IP</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($subscribers as $subscriber):?>
                                            <tr>
                                                <td><?php echo $subscriber["id"]?></td>
                                                <td><?php echo $subscriber["email"]?></td>
                                                <td><?php echo $subscriber["ip_address"]?></td>
                                                <td><?php echo date("d M, Y",strtotime($subscriber["created_at"]))?></td>
                                                <td>
                                                    <span class="badge badge-success" style="<?php if($subscriber["status"] == 0) echo "display:none"?>">Yes</span>
                                                    <span class="badge badge-danger" style="<?php if($subscriber["status"] == 1) echo "display:none"?>">No</span>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <div class="wrapper-loader-btn" style="display: inline-block;">
                                                        <span class="button-loader"></span>
                                                        <input 
                                                            name="status" 
                                                            <?php if($subscriber["status"] == 1) echo "checked"?> 
                                                            data-subscriber-id="<?php echo $subscriber["id"]?>"
                                                            type="checkbox" 
                                                            data-toggle="toggle" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger"
                                                        >
                                                    </div>  
                                                    <a  data-subscriber-id="<?php echo $subscriber["id"]?>" href="" class="btn btn-danger delete-btn">
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
        $("input[name=status]").change( async function(e){
            const yes = $(this).closest("tr").find(".badge.badge-success")
            const no = $(this).closest("tr").find(".badge.badge-danger")
        
            const parent = $(this).closest(".wrapper-loader-btn")
            const status = $(this).prop("checked") ? 1 : 0
            const subscriberId = $(this).data("subscriber-id")
            const formData = new FormData()

            parent.removeClass("active")
            parent.addClass("pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("subscriber_id",btoa(subscriberId))
            formData.append("status",btoa(status))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_subscriber_edit_status_ajax.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success){
                        if(status === 1){
                            yes.show()
                            no.hide()
                        } else {
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
        $(document).on("click",".delete-btn",async function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el =$(this)
            const parent = $(this).closest("tr")
            const subscriberId = $(this).data("subscriber-id")
            const formData = new FormData()

            el.addClass("pending")
            el.removeClass("active")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("subscriber_id",btoa(subscriberId))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_subscriber_delete_ajax.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
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
<?php include "./layout_footer.php"?>