<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try {
        $sql = "
            select * from orders 
            join packages on orders.package_id=packages.id
            where agent_id=:agent_id and currently_active=:currently_active 
            order by orders.id DESC limit 1
        ";
        $stmtOrd = $pdo->prepare($sql);
        $stmtOrd->bindValue(":agent_id",$_SESSION["agent"]["id"]);
        $stmtOrd->bindValue(":currently_active",1);
        $stmtOrd->execute();
        $order = $stmtOrd->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $err) {
        $error_message = $err->getMessage();
    }

    try {
        $sql = "select * from packages order by id asc";
        $stmtPac = $pdo->prepare($sql);
        $stmtPac->execute();
        $packages = $stmtPac->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message = $err->getMessage();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form-paypal'])) {
        
        $transaction_id = bin2hex(random_bytes(32/2)); //test

        try {
            $sql = "select * from packages where id=:id limit 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":id",$_POST["packageid"]);
            $stmt->execute();
            $paypal_order = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["order"] = [
                "agent_id"=> $_SESSION["agent"]["id"],
                "package_id"=> $paypal_order["id"],
                "payment_method"=> "PayPal",
                "paid_amount"=> $paypal_order["price"],
                "allowed_days"=> $paypal_order["allowed_days"],
                "expire_date"=> date("Y-m-d",strtotime("+".$paypal_order["allowed_days"]." days"))
            ];            
        }catch(PDOException $err){
            $error_message = $err->getMessage();
        }

        unset($_POST["form-paypal"]);
        unset($_POST["packageid"]);  

        header("Location: ".BASE_URL."agent-payment-paypal-success/$transaction_id");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form-stripe'])) {

        try {
            $sql = "select * from packages where id=:id limit 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":id",$_POST["packageid"]);
            $stmt->execute();
            $paypal_order = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["order"] = [
                "agent_id"=> $_SESSION["agent"]["id"],
                "package_id"=> $paypal_order["id"],
                "payment_method"=> "Stripe",
                "paid_amount"=> $paypal_order["price"],
                "allowed_days"=> $paypal_order["allowed_days"],
                "expire_date"=> date("Y-m-d",strtotime("+".$paypal_order["allowed_days"]." days"))
            ];        
            
            // print_r($_SESSION["order"]);

            \Stripe\Stripe::setApiKey($_ENV["STRIPE_TEST_SK"]);
            $response = \Stripe\Checkout\Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Package Name:'.$paypal_order["name"]
                            ],
                            'unit_amount' => $paypal_order['price'] * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $_ENV["STRIPE_SUCCESS_URL"].'?session_id='.$_ENV["CHECKOUT_SESSION_ID"],
                'cancel_url' => $_ENV["STRIPE_CANCEL_URL"],
            ]);

            unset($_POST["form-stripe"]);
            unset($_POST["packageid"]);  

            header('location: '.$response->url);
        }catch(PDOException $err){
            $error_message = $err->getMessage();
        }
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Payment</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <h4>Active Current Plan</h4>
                <?php if($stmtOrd->rowCount() > 0):?>
                    <div class="row box-items mb-4">
                        <div class="col-md-4">
                            <div class="box1">
                                <h4><?php echo $order["price"]?> PLN</h4>
                                <p><?php echo $order["name"]?></p>
                            </div>
                        </div>
                    </div>
                <?php else:?> 
                    <div class="col-md-6 mb-5">No any results!</div>
                <?php endif?>
                <h4>Upgrade Plan (Make Payment)</h4>
                <div class="table-responsive">
                    <table class="table table-bordered upgrade-plan-table">
                        <tr>
                            <td>
                                <form action="" method="POST">
                                <select name="packageid" class="form-control select2">
                                    <?php if($stmtPac->rowCount() > 0):
                                        foreach($packages as $package):?>
                                            <option value="<?php echo $package["id"]?>"><?php echo $package["name"]?> (<?php echo $package["price"]?> PLN)</option>
                                    <?php endforeach;endif?>
                                </select>
                            </td>
                            <td>
                                <button type="submit" name="form-paypal" class="btn btn-secondary btn-sm buy-button">Pay with PayPal</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <form action="" method="POST">
                                <select name="packageid" class="form-control select2">
                                    <?php if($stmtPac->rowCount() > 0):
                                        foreach($packages as $package):?>
                                            <option value="<?php echo $package["id"]?>"><?php echo $package["name"]?> (<?php echo $package["price"]?> PLN)</option>
                                    <?php endforeach;endif?>
                                </select>
                            </td>
                            <td>
                                <button type="submit" name="form-stripe" class="btn btn-secondary btn-sm buy-button">Pay with Card</button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php"?>