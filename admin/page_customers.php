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
                customers
            ORDER BY
                id DESC
        ");
        $stmt->execute();
        $customers=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1><?php echo setPageTitle($current_page)?></h1>
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
                                        <?php if($stmt->rowCount() > 0): foreach($customers as $customer):?>
                                            <tr>
                                                <th><?php echo $customer["id"]?></th>
                                                <th>
                                                    <?php if(is_null($customer["photo"])):?>
                                                        <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                    <?php else:?>
                                                        <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/customer/<?php echo $customer["photo"]?>" alt="">
                                                    <?php endif?>
                                                </th>
                                                <th><?php echo $customer["full_name"]?></th>
                                                <th><?php echo $customer["email"]?></th>
                                                <th>
                                                    <?php if($customer["status"] == 1):?>
                                                        <span class="badge badge-success">On</span>
                                                    <?php else:?>
                                                        <span class="badge badge-danger">Off</span>
                                                    <?php endif?>
                                                </th>
                                                <td class="pt_10 pb_10">
                                                    <input 
                                                        data-customer-id="<?php echo $customer["id"]?>" 
                                                        <?php if($customer["status"] == 1) echo "checked"?>
                                                        class="toggle-event"
                                                        type="checkbox" 
                                                        data-toggle="toggle" 
                                                        data-onstyle="success" 
                                                        data-offstyle="danger" 
                                                    >
                                                    <a data-customer-id="<?php echo $customer["id"]?>" href="" class="btn btn-danger delete-event"><i class="fas fa-trash"></i></a>
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
        $(document).on('change','.toggle-event',async function () {

            const el = $(this)
            const parent = el.closest("tr")
            const customerId = el.data("customer-id")
            const onElement = parent.find(".badge.badge-success")
            const offElement = parent.find(".badge.badge-danger")
            const isTrue = $(this).prop('checked') === true
            const formData = new FormData()

            formData.append("customer_id",btoa(customerId))
            formData.append("status",btoa(Number(isTrue)))
            parent.css("pointer-events","none")
            await new Promise(resolve => setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_customer_edit_status_ajax.php",
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
            const customerId = el.data("customer-id")
            const formData = new FormData()

            formData.append("customer_id",btoa(customerId))
            parent.css("pointer-events","none")
            await new Promise(resolve => setTimeout(resolve,1000))

            $.ajax({
                type: "POST",
                data: formData,
                url: "<?php echo ADMIN_URL?>page_customer_delete_ajax.php",
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