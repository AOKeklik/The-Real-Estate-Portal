<?php
    include("./layout_top.php");


    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                faqs
            WHERE
                status=?
            ORDER BY
                id DESC
            LIMIT
                12
        ");
        $stmt->execute([1]);
        $faqs=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err;
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="FAQ";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

<div class="page-content faq">
    <div class="container">
        <div class="row">
            <?php if($stmt->rowCount() > 0):?>
                <div class="col-md-12 d-flex justify-content-center">
                    <div class="accordion" id="accordionExample">
                        <?php foreach($faqs as $faq):?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_<?php echo $faq["id"]?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $faq["id"]?>" aria-expanded="false" aria-controls="collapse_1">
                                        <?php echo $faq["question"]?>
                                    </button>
                                </h2>
                                <div id="collapse_<?php echo $faq["id"]?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $faq["id"]?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body"><?php echo $faq["answer"]?></div>
                                </div>
                            </div>
                        <?php endforeach?>
                    </div>
                </div>
            <?php else:?>
                <p class="alert alert-warning text-center">No FAQs are available to display.</p>
            <?php endif?>
        </div>
    </div>
</div>

<?php include("./layout_footer.php")?>