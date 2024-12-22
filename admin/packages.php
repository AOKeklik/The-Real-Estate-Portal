<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try {
        $sql = "select * from packages";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Packages</h1>
            <div class="ml-auto">
                <a href="<?php ADMIN_URL?>package-create" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Package Name</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0):?>
                                            <?php foreach($packages as $package):?>
                                                <tr>
                                                    <td><?php echo $package["id"]?></td>
                                                    <td><?php echo $package["name"]?></td>
                                                    <td><?php echo $package["price"]?></td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $package["id"]?>"><i class="fas fa-eye"></i></a>
                                                        <a href="<?php echo ADMIN_URL?>package-edit/<?php echo $package['id']?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a data-id="<?php echo $package["id"]?>" class="btn btn-danger" href="#" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                    <div class="modal fade" id="modal_<?php echo $package["id"]?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Detail</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Name</label></div>
                                                                        <div class="col-md-8"><?php echo $package["name"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Price</label></div>
                                                                        <div class="col-md-8"><?php echo $package["price"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Allowed Days</label></div>
                                                                        <div class="col-md-8"><?php echo $package["allowed_days"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Allowed Properties</label></div>
                                                                        <div class="col-md-8"><?php echo $package["allowed_properties"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Allowed Featured Properties</label></div>
                                                                        <div class="col-md-8"><?php echo $package["allowed_featured_properties"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Allowed Photos</label></div>
                                                                        <div class="col-md-8"><?php echo $package["allowed_photos"]?></div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Allowed Videos</label></div>
                                                                        <div class="col-md-8"><?php echo $package["allowed_videos"]?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            <?php endforeach?>
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
    $("a.btn-danger").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo ADMIN_URL?>package-delete/"+$(this).data("id"),
            type: "GET",
            contentType: false,
            processData: false,
            success: function (respnse) {
                const res = JSON.parse(respnse)
                
                if(res.success)
                    $(e.target).closest("tr").slideUp()

                iziToast.show({
                    message: res.error ?? res.success,
                    position: "topRight",
                    color: res.error ? "red" : "green",
                })
            },
            error: function (response) {
                console.log(res)
            }
        })
    })
</script>
<?php include "./layout_footer.php"?>