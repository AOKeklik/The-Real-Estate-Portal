<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }
    
    try {
        $sql = "select * from types";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Types</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>type-create" class="btn btn-primary"><i class="fas fa-plus"></i> Add Type</a>
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
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0):
                                            foreach($types as $type):?>
                                                <tr>
                                                    <td><?php echo $type["id"]?></td>
                                                    <td><?php echo $type["name"]?></td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="<?php echo ADMIN_URL?>type-edit/<?php echo $type["id"]?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="<?php echo ADMIN_URL?>type-delete/<?php echo $type["id"]?>" href="" class="btn btn-danger" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
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
    $(document).ready(function () {
        $(".btn-danger").click(function (e) {
            e.preventDefault()

            $(this).closest("tr").css("pointer-events","none")

            $.ajax({
                url: $(this).attr("href"),
                type: "GET",
                contentType: false,
                processData: false,
                success: function (response) {
                    const res = JSON.parse(response)

                    iziToast.show({
                        message: res.success ?? res.error,
                        position: "topRight",
                        color: res.success ? "green" : "red",
                        onClosing: function () {
                            if(res.success)
                                $(e.target).closest("tr").slideUp()

                            if(res.error)
                                $(e.target).closest("tr").css("pointer-events","")
                        }
                    })
                }
                
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>