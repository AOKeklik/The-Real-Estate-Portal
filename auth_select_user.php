<?php 
    include "./layout_top.php";
    
    if (isset($_SESSION["customer"])) {
        header("Location: ".BASE_URL."customer-dashboard");
        exit;
    }    
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Login";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
            
                <div class="main-part-user">
                    <div class="left-part-user">
                        <h3>
                            <a href="<?php echo BASE_URL?>customer-login">Customer Login</a>
                        </h3>
                        <h3>
                            <a href="<?php echo BASE_URL?>customer-register">Customer Registration</a>
                        </h3>
                    </div>
                    <div class="right-part-user">
                        <h3>
                            <a href="<?php echo BASE_URL?>agent-login">Agent Login</a>
                        </h3>
                        <h3>
                            <a href="<?php echo BASE_URL?>agent-register">Agent Registration</a>
                        </h3>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>