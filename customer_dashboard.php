<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-login");
        exit();
    } 
    
    try{
        $stmt=$pdo->prepare("
            SELECT
                count(id) AS count
            FROM
                wishlists
            WHERE
                customer_id=?
        ");
        $stmt->execute([$_SESSION["customer"]["id"]]);
        $wishlist=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                count(CASE WHEN is_customer_read = 1 THEN 1 END) AS read_messages,
                count(CASE WHEN is_customer_read = 0 THEN 1 END) AS unread_messages
            FROM
                messages
            WHERE 
                customer_id=?
        ");
        $stmt->execute([$_SESSION["customer"]["id"]]);
        $message=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<div class="page-top" style="background-image: url('')">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dashboard</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content user-panel">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-12">
                    <?php include "./layout_nav_customer.php"?>
                </div>
                <div class="col-lg-9 col-md-12">
                    <h3>Hello, <?php echo $_SESSION["customer"]["full_name"]?></h3>
                    <p>See all the statistics at a glance:</p>

                    <div class="row box-items">
                        <div class="col-md-4">
                            <div class="box1">
                                <h4><?php echo $wishlist["count"]?></h4>
                                <p>Wishlist Items</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box2">
                                <h4><?php echo $message["unread_messages"]?></h4>
                                <p>Unread Messages</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box3">
                                <h4><?php echo $message["read_messages"]?></h4>
                                <p>Read Messages</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "./layout_footer.php"?>