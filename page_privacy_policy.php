<?php 
    include "./layout_top.php";

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                privacy
            WHERE
                status=?
            LIMIT
                1
        ");
        $stmt->execute([1]);

        if($stmt->rowCount() == 0){
            header("Location: ".BASE_URL."404");
            exit();
        }            

        $privacy=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title=$privacy["title"];
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><?php echo html_entity_decode($privacy["text"])?></div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php"?>