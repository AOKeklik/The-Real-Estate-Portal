<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try{
        $stmtAgent=$pdo->prepare("
            SELECT
                COUNT(properties.id) as total_properties,
                COUNT(orders.id) as total_orders,
                agents.*
            FROM
                agents
            LEFT JOIN
                orders ON orders.agent_id=agents.id
            LEFT JOIN
                properties ON properties.agent_id=agents.id    
            WHERE
                agents.id=?
            GROUP BY
                agents.id
        ");
        $stmtAgent->execute([$_SESSION["agent"]["id"]]);
        $agent=$stmtAgent->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                count(CASE WHEN is_agent_read = 1 THEN 1 END) AS read_messages,
                count(CASE WHEN is_agent_read = 0 THEN 1 END) AS unread_messages
            FROM
                messages
            WHERE 
                agent_id=?
        ");
        $stmt->execute([$_SESSION["agent"]["id"]]);
        $message=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch (PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
<?php 
    $page_title="Agent Dashboard";
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
                <h3>Hello, <?php echo $agent["full_name"]?></h3>
                <p>See all the statistics at a glance:</p>

                <div class="row box-items">
                    <div class="col-md-4">
                        <div class="box1">
                            <h4><?php echo $agent["total_properties"]?></h4>
                            <p>Properties</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box2">
                            <h4><?php echo $agent["total_orders"]?></h4>
                            <p>Orders</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box3">
                            <h4><?php echo $message["unread_messages"]?></h4>
                            <p>Unread Messages</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box3 bg-warning">
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