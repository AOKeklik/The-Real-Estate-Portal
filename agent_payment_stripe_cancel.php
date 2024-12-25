<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-login");
        exit();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Payment Cancel</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content user-panel">
        <div class="container">
            <div class="text-center">
                <h3 class="text-danger">Payment is canceled!</h3>
            </div>
        </div>
    </div>
<?php include "./layout_footer.php"?>