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
                agents
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $agents=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Agents</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-eye"></i> Dashboard</a>
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
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($agents as $agent):?>
                                            <tr>
                                                <th><?php echo $agent["id"]?></th>
                                                <th>
                                                    <?php if(is_null($agent["photo"])):?>
                                                        <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                    <?php else:?>
                                                        <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $agent["photo"]?>" alt="">
                                                    <?php endif?>
                                                </th>
                                                <th><?php echo $agent["full_name"]?></th>
                                                <th><?php echo $agent["email"]?></th>
                                                <th>
                                                    <?php if($agent["status"] == 1):?>
                                                        <span class="badge badge-success">On</span>
                                                    <?php else:?>
                                                        <span class="badge badge-danger">Off</span>
                                                    <?php endif?>
                                                </th>
                                                <td class="pt_10 pb_10">
                                                    <input 
                                                        data-agent-id="<?php echo $agent["id"]?>" 
                                                        <?php if($agent["status"] == 1) echo "checked"?>
                                                        class="toggle-event"
                                                        type="checkbox" 
                                                        data-toggle="toggle" 
                                                        data-onstyle="success" 
                                                        data-offstyle="danger" 
                                                    >
                                                    <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $agent["id"]?>"><i class="fas fa-eye"></i></a>
                                                    <a data-agent-id="<?php echo $agent["id"]?>" href="" class="btn btn-danger delete-event"><i class="fas fa-trash"></i></a>
                                                </td>
                                                <div class="modal fade modal-lg" id="modal_<?php echo $agent["id"]?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Detail</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <?php if(is_null($agent["photo"])):?>
                                                                                <img class="w_100" src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                                            <?php else:?>
                                                                                <img class="w_100" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $agent["photo"]?>" alt="">
                                                                            <?php endif?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>Name:</strong> <?php echo $agent["full_name"]?><br>
                                                                            <strong>Company:</strong> <?php echo $agent["company"]?><br>
                                                                            <strong>Designation:</strong> <?php echo $agent["designation"]?><br>
                                                                            <strong>Email:</strong> <?php echo $agent["email"]?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if(
                                                                    !is_null($agent["facebook"]) ||
                                                                    !is_null($agent["twitter"]) ||
                                                                    !is_null($agent["pinterest"]) ||
                                                                    !is_null($agent["instagram"]) ||
                                                                    !is_null($agent["linkedin"]) ||
                                                                    !is_null($agent["youtube"])
                                                                ):?>
                                                                    <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                        <ul class="agent-ul">
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
                                                                    </div>
                                                                <?php endif?>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">Phone:</div>
                                                                        <div class="col-md-9"><?php echo $agent["phone"]?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">Country:</div>
                                                                        <div class="col-md-9"><?php echo $agent["country"]?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">Address:</div>
                                                                        <div class="col-md-9"><?php echo $agent["address"]?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">State:</div>
                                                                        <div class="col-md-9"><?php echo $agent["state"]?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">City:</div>
                                                                        <div class="col-md-9"><?php echo $agent["city"]?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                    <div class="row">
                                                                        <div class="col-md-3">Zip Code:</div>
                                                                        <div class="col-md-9"><?php echo $agent["zip_code"]?></div>
                                                                    </div>
                                                                </div>
                                                                <?php if(!is_null($agent["biography"])):?>
                                                                    <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                        <div class="row">
                                                                            <div class="col-md-3">Biography:</div>
                                                                            <div class="col-md-9"><?php echo html_entity_decode($agent["biography"])?></div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif?>
                                                                <?php if(!is_null($agent["website"])):?>
                                                                    <div class="form-group bdb1 pt_10 mb_0 pb-3">
                                                                        <div class="row">
                                                                            <div class="col-md-3">Website:</div>
                                                                            <div class="col-md-9"><?php echo html_entity_decode($agent["website"])?></div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif?>
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
    /* update */
    $(document).ready(function(){
        $(document).on('change','.toggle-event',async function () {

            const el = $(this)
            const parent = el.closest("tr")
            const agentId = el.data("agent-id")
            const onElement = parent.find(".badge.badge-success")
            const offElement = parent.find(".badge.badge-danger")
            const isTrue = $(this).prop('checked') === true
            const formData = new FormData()

            formData.append("agent_id",btoa(agentId))
            formData.append("status",btoa(Number(isTrue)))
            parent.css("pointer-events","none")
            await new Promise(resolve => setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_agent_update_status_ajax.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    // console.log(response)
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success) {
                        if(isTrue){                
                            offElement.removeClass("badge-danger")
                            offElement.addClass("badge-success")
                        } else {
                            onElement.removeClass("badge-success")
                            onElement.addClass("badge-danger")
                        }
                    }

                    parent.css("pointer-events","")
                }
            })
        })
    })

    /* delete */
    $(document).ready(function(){
        $(document).on('click','.delete-event', async function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el = $(this)
            const parent = el.closest("tr")
            const agentId = el.data("agent-id")
            const formData = new FormData()

            formData.append("agent_id",btoa(agentId))
            parent.css("pointer-events","none")
            await new Promise(resolve => setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                data: formData,
                url: "<?php echo ADMIN_URL?>page_agent_delete_ajax.php",
                processData: false,
                contentType: false,
                success: function(response){
                    console.log(response)
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
                        parent.css("pointer-events","")
                    }
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>