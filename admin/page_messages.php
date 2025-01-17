<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                messages.*,
                customers.full_name AS customer_name,
                customers.email AS customer_email,
                agents.full_name AS agent_name,
                agents.email AS agent_email
            FROM
                messages
            LEFT JOIN
                customers ON customers.id=messages.customer_id
            LEFT JOIN
                agents ON agents.id=messages.agent_id
            ORDER BY
                messages.id DESC
        ");
        $stmt->execute();
        $messages=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Messages</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-eye"></i> Dashboard</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Subject</th>
                                            <th>Customer</th>
                                            <th>Agent</th>
                                            <th>Posted On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($messages as $message):?>
                                            <tr>
                                                <th><?php echo $message["id"]?></th>
                                                <th><?php echo $message["subject"]?></th>
                                                <th>
                                                    <?php echo $message["customer_name"]?><br>
                                                    <?php echo $message["customer_email"]?>
                                                </th>
                                                <th>
                                                    <?php echo $message["agent_name"]?><br>
                                                    <?php echo $message["agent_email"]?>
                                                </th>
                                                <th><?php echo date("d M, Y - H:i:s", strtotime($message["posted_on"]))?></th>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL?>message/<?php echo $message["id"]?>" class="btn btn-warning"><i class="fas fa-eye"></i></a>
                                                    <a href="" data-message-id="<?php echo $message["id"]?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
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
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        $(document).on("click", ".btn.btn-danger",function(e){
            e.preventDefault()

            if(!confirm("Are you sure?")) return

            const el = $(this)
            const parent = el.closest("tr")
            const messageId = el.data("message-id")
            const formData = new FormData()

            formData.append("message_id",btoa(messageId))
            parent.css("pointer-events","none")

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_message_delete.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green",
                    })

                    if(res.error){
                        parent.css("pointer-events","")
                    }

                    if(res.success){
                        parent.slideUp()
                    }
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>