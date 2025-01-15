<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                orders.*,
                packages.name as package_name
            FROM
                orders
            LEFT JOIN
                packages on packages.id=orders.package_id
            ORDER BY
                orders.currently_active DESC
        ");
        $stmt->execute();
        $orders=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Table</h1>
            <div class="ml-auto">
                <a href="" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Transaction Id</th>
                                            <th>Package Name</th>
                                            <th>Price</th>
                                            <th>Purchase Date</th>
                                            <th>Expire Date</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($orders as $order):?>
                                            <tr>
                                                <td><?php echo $order["id"]?></td>
                                                <td><?php echo $order["transaction_id"]?></td>
                                                <td><?php echo $order["package_name"]?></td>
                                                <td><?php echo $order["paid_amount"]?></td>
                                                <td><?php echo $order["purchase_date"]?></td>
                                                <td><?php echo $order["expire_date"]?></td>
                                                <td><?php echo $order["payment_method"]?></td>
                                                <td>
                                                    <?php if($order["currently_active"] == 1):?>
                                                        <span class="badge badge-danger">Yes</span>
                                                    <?php else:?>
                                                        <span class="badge badge-light">No</span>
                                                    <?php endif?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="" data-order-id="<?php echo $order["id"]?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
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
    $(document).ready(function(){
        $(document).on("click",".btn.btn-danger",function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el = $(this)
            const orderId = el.data("order-id")
            const parent = el.closest("tr")
            const formData = new FormData()

            formData.append("order_id",btoa(orderId))
            parent.css("pointer-events","none")

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>order_delete.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log(response)
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green",
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