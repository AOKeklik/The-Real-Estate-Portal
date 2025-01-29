<?php 
    include "./layout_top.php";

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                terms
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

        $terms=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $terms["title"]?></h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><?php echo html_entity_decode($terms["text"])?></div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php"?>