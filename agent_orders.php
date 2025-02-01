<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
    }

    try{
        $stmt = $pdo->prepare("
            select * from orders
            join packages on orders.package_id=packages.id
            where agent_id=?
            order by orders.id desc
        ");
        $stmt->execute([$_SESSION["agent"]["id"]]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Agent Orders";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

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
                                <th>Payment Id</th>
                                <th>Plan Name</th>
                                <th>Price</th>
                                <th>Order Date</th>
                                <th>Expire Date</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($stmt->rowCount() > 0): foreach($orders as $order):?>
                                    <tr>
                                        <td><?php echo $order["id"]?></td>
                                        <td><?php echo $order["transaction_id"]?></td>
                                        <td><?php echo $order["name"]?></td>
                                        <td><?php echo $order["price"]?> PLN</td>
                                        <td><?php echo $order["purchase_date"]?></td>
                                        <td><?php echo $order["expire_date"]?></td>
                                        <td><?php echo $order["payment_method"]?></td>
                                        <td>
                                            <span class="badge <?php echo $order["currently_active"] == 1 ? "bg-success text-white" : "bg-warning text-secondary"?>">
                                                <?php echo  $order["currently_active"] == 1 ? "active" : "pending"?>
                                            </span>
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

<?php include "./layout_footer.php"?>