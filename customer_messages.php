<?php   
    include "./layout_top.php";

    if(!isset($_SESSION["customer"])){
        header("Location: ".BASE_URL."customer-login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                messages.*,
                agents.company
            FROM
                messages
            LEFT JOIN
                agents ON agents.id=messages.agent_id
            WHERE
                customer_id=?
            ORDER BY
                id DESC
        ");
        $stmt->execute([$_SESSION["customer"]["id"]]);
        $messages=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Message";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_customer.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="d-flex justify-content-end mb-3">
                    <a href="<?php echo BASE_URL?>customer_message_add" class="btn btn-primary"><i class="fas fa-plus"></i> New Message</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Subject</th>
                                <th>Agent</th>
                                <th>Posted</th>
                                <th class="w-100">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($stmt->rowCount() > 0): foreach($messages as $message):?>
                                <tr>
                                    <td><?php echo $message["id"]?></td>
                                    <td><?php echo $message["subject"]?></td>
                                    <td><?php echo $message["company"]?></td>
                                    <td><?php echo date("d M, Y - H:i:s", strtotime($message["posted_on"]))?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL?>customer-message/<?php echo $message["id"]?>" class="btn btn-primary btn-sm text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a data-message-id="<?php echo $message["id"]?>" href="" class="btn btn-danger btn-sm text-white">
                                            <i class="fas fa-trash"></i>
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

<script>
    $(document).ready(function(){
        async function handlerClickRemoveButton(e){
            e.preventDefault()

            if(!confirm("Are you sure to remove the message!")) return

            const el = $(this)
            const parent = el.closest("tr")
            const formData = new FormData()            

            parent.css("pointer-events","none")
            formData.append("message-id",btoa($(this).data("message-id")))

            $.ajax({
                url: "<?php echo BASE_URL?>customer_message_delete.php",
                type: "POST",
                contentType: false,
                processData: false,
                data: formData,
                success: function (response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success){
                        parent.slideUp()
                    }

                    parent.css("pointer-events","")
                }
            })
        }

        $(document).on("click",".btn-danger",handlerClickRemoveButton)
    })
</script>

<?php include "./layout_footer.php"?>