<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try {
        $stmtOrd = $pdo->prepare("
            select 
                * 
            from 
                orders 
            join 
                packages on orders.package_id=packages.id
            where 
                agent_id=? and currently_active=? 
            order by 
                orders.id DESC limit 1
        ");
        $stmtOrd->execute([$_SESSION["agent"]["id"],1]);
        $order = $stmtOrd->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $err) {
        $error_message = $err->getMessage();
    }

    try {
        $stmtPac = $pdo->prepare("select * from packages order by id asc");
        $stmtPac->execute();
        $packages = $stmtPac->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message = $err->getMessage();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form-paypal'])) {
        try {
            $transaction_id = bin2hex(random_bytes(32/2)); //test
            $package_id = htmlspecialchars(trim($_POST["package_id"]));

            $stmt = $pdo->prepare("
                select 
                    * 
                from 
                    packages 
                where 
                    id=? 
                limit 1
            ");
            $stmt->execute([$package_id]);
            $selected_package = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM
                    properties
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$_SESSION["agent"]["id"]]);

            if($stmt->rowCount() > $selected_package["allowed_properties"]) {
                unset($_POST["form-paypal"]);
                unset($_POST["packageid"]);
                throw new PDOException("You are going to downgrade your package. Please delete some properties first so that it does not exceed the selected package\'s total allowed properties limit!");
            }

            $_SESSION["order"] = [
                "agent_id"=> $_SESSION["agent"]["id"],
                "package_id"=> $selected_package["id"],
                "payment_method"=> "PayPal",
                "paid_amount"=> $selected_package["price"],
                "allowed_days"=> $selected_package["allowed_days"],
                "expire_date"=> date("Y-m-d",strtotime("+".$selected_package["allowed_days"]." days"))
            ];            

            unset($_POST["form-paypal"]);
            unset($_POST["packageid"]);  

            header("Location: ".BASE_URL."agent-payment-paypal-success/$transaction_id");
            exit();
        }catch(PDOException $err){
            $error_message = $err->getMessage();
        }
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['form-stripe'])) {
        try {
            $package_id = htmlspecialchars(trim($_POST["package_id"]));

            $stmt = $pdo->prepare("
                select 
                    * 
                from 
                    packages 
                where 
                    id=? 
                limit 1
            ");
            $stmt->execute([$package_id]);
            $selected_package = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM
                    properties
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$_SESSION["agent"]["id"]]);

            if($stmt->rowCount() > $selected_package["allowed_properties"]) {
                unset($_POST["form-paypal"]);
                unset($_POST["packageid"]);
                throw new PDOException("You are going to downgrade your package. Please delete some properties first so that it does not exceed the selected package\'s total allowed properties limit!");
            }

            $_SESSION["order"] = [
                "agent_id"=> $_SESSION["agent"]["id"],
                "package_id"=> $selected_package["id"],
                "payment_method"=> "Stripe",
                "paid_amount"=> $selected_package["price"],
                "allowed_days"=> $selected_package["allowed_days"],
                "expire_date"=> date("Y-m-d",strtotime("+".$selected_package["allowed_days"]." days"))
            ];        

            \Stripe\Stripe::setApiKey($_ENV["STRIPE_TEST_SK"]);
            $response = \Stripe\Checkout\Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $_ENV["STRIPE_CURRENCY"],
                            'product_data' => [
                                'name' => 'Package Name:'.$selected_package["name"]
                            ],
                            'unit_amount' => $selected_package['price'] * 100,
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

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Payment";
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
                                <select name="package_id" class="form-control select2">
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
                                <select name="package_id" class="form-control select2">
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