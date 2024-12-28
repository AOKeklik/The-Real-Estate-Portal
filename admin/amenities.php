<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $sql = "select * from amenities order by id asc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $amenities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err) {
        $error_message = $err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Amenities</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>amenity-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
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
                                            <th>Icon</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0 ):
                                            foreach($amenities as $amenity):?>
                                                <tr>
                                                    <td><?php echo $amenity["id"]?></td>
                                                    <td><i class="<?php echo $amenity["icon"]?>" aria-hidden="true"></i></td>
                                                    <td><?php echo $amenity["name"]?></td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="<?php echo ADMIN_URL?>amenity-edit/<?php echo $amenity["id"]?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="<?php echo ADMIN_URL?>amenity-delete/<?php echo $amenity["id"]?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
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

            if(!confirm('Are you sure?')) return

            $(e.target).closest("tr").css("pointer-events","none")

            $.ajax({
                url: $(this).closest("a").attr("href"),
                type:"GET",
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
