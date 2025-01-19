<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try {
        $sql = "select * from settings where id=:id limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",1);
        $stmt->execute();
        
        if($stmt->rowCount() == 0) {
            $_SESSION["error"] = "No settings data available to display.";
            header("Location: ".ADMIN_URL."dashboard.php");
            exit;
        }

        $setting = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $err) {
        $error_message = $err->getMessage();
    }
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Setting</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-eye"></i> Dashboard</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="partial-item">
                                    <div class="form-group mb-3">
                                        <label>Existing Photo</label>
                                        <div>
                                            <?php if(empty($setting["logo"])):?>
                                                <img src="https://placehold.co/600x200" alt="" class="w_100">
                                            <?php else:?>
                                                <img src="<?php echo PUBLIC_URL?>/uploads/setting/<?php echo $setting["logo"]?>" alt="" class="w_100">
                                            <?php endif?>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Change Photo</label>
                                        <div>
                                            <input type="file" name="">
                                        </div>
                                    </div>
                                </div>
                                <div class="partial-item">
                                    <div class="form-group mb-3">
                                        <label>Heading</label>
                                        <input type="text" class="form-control" name="" value="Our Services">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Subheading</label>
                                        <input type="text" class="form-control" name="" value="You will get some awesome services from us">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <div class="toggle-container">
                                            <input type="checkbox" data-toggle="toggle" data-on="Show" data-off="Hide" data-onstyle="success" data-offstyle="danger" name="" value="Show" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt_30">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "./layout_footer.php"?>