<?php   
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                messages.*,
                customers.full_name
            FROM
                messages
            LEFT JOIN
                customers ON customers.id=messages.customer_id
            WHERE
                agent_id=?
            ORDER BY
                id DESC
        ");
        $stmt->execute([$_SESSION["agent"]["id"]]);
        $messages=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Messages";
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
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Posted</th>
                                <th class="w-100">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($stmt->rowCount() > 0): foreach($messages as $message):?>
                                <tr>
                                    <td><?php echo $message["id"]?></td>
                                    <td><?php echo $message["subject"]?></td>
                                    <td><?php echo $message["full_name"]?></td>
                                    <td><?php echo date("d M, Y - H:i:s", strtotime($message["posted_on"]))?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL?>agent-message/<?php echo $message["id"]?>" class="btn btn-primary btn-sm text-white">
                                            <i class="fas fa-eye"></i>
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

<?php include "./layout_footer.php"?>