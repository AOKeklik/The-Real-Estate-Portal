<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try {
        $sql = "select * from locations";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Locations</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>location-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0):?>
                                            <?php foreach($locations as $location):?>
                                                <tr>
                                                    <td><?php echo $location["id"]?></td>
                                                    <td>
                                                        <img src="<?php echo PUBLIC_URL?>uploads/location/<?php echo $location["photo"]?>" alt="" class="w_50" style="min-width:100px">
                                                    </td>
                                                    <td><?php echo $location["name"]?></td>
                                                    <td><?php echo $location["slug"]?></td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="<?php echo ADMIN_URL?>location-edit/<?php echo $location["id"]?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="#" data-id="<?php echo $location["id"]?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif?>
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

            if(!confirm('Are you sure?')) return

            $(e.target).closest("tr").css("pointer-events","none")

            const formData = new FormData()

            formData.append("id",$(this).data("id"))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_location_delete_ajax.php",
                contentType: false,
                processData: false,
                data: formData,
                success: function (result) {
                    console.log(result)
                    const res = JSON.parse(result)
                    console.log(res)

                    iziToast.show({
                        message: res.success ? res.success.message : res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red",
                        onClosing: function(){
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