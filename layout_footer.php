<?php
    try{
        $stmtPrivacy=$pdo->prepare("
            SELECT
                *
            FROM
                privacy
            WHERE
                status=?
            LIMIT
                1
        "); 
        $stmtPrivacy->execute([1]);
        $privacy=$stmtPrivacy->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtTerms=$pdo->prepare("
            SELECT
                *
            FROM
                terms
            WHERE
                status=?
            LIMIT
                1
        "); 
        $stmtTerms->execute([1]);
        $terms=$stmtTerms->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
    
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="item">
                        <h2 class="heading">Important Links</h2>
                        <ul class="useful-links">
                            <li><a href="">Home</a></li>
                            <li><a href="">Properties</a></li>
                            <li><a href="">Agents</a></li>
                            <li><a href="">Blog</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="item">
                        <h2 class="heading">Locations</h2>
                        <ul class="useful-links">
                            <li><a href="">New York</a></li>
                            <li><a href="">Boston</a></li>
                            <li><a href="">Orlanco</a></li>
                            <li><a href="">Los Angeles</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="item">
                        <h2 class="heading">Contact</h2>
                        <div class="list-item">
                            <div class="left">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="right">
                                34 Antiger Lane, USA, 12937
                            </div>
                        </div>
                        <div class="list-item">
                            <div class="left">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="right">contact@arefindev.com</div>
                        </div>
                        <div class="list-item">
                            <div class="left">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="right">122-222-1212</div>
                        </div>
                        <ul class="social">
                            <li>
                                <a href=""><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href=""><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href=""><i class="fab fa-pinterest-p"></i></a>
                            </li>
                            <li>
                                <a href=""><i class="fab fa-linkedin-in"></i></a>
                            </li>
                            <li>
                                <a href=""><i class="fab fa-instagram"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="item">
                        <h2 class="heading">Newsletter</h2>
                        <p>
                            To get the latest news from our website, please
                            subscribe us here:
                        </p>
                        <form action="" method="post">
                            <div class="form-group">
                                <input type="text" name="email" class="form-control">
                                <small class='form-text text-danger input-error input-email'></small>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary bg-website" name="newsletter-form" style="width: 100%;margin-top:.5rem">
                                    <span class="button-loader" style="margin-left: 45%;"></span>
                                    <span>Subscribe Now</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="copyright">
                        Copyright 2023, ArefinDev. All Rights Reserved.
                    </div>
                </div>
                <?php if($terms || $privacy):?>
                    <div class="col-lg-6 col-md-6">
                        <div class="right">
                            <ul>
                                <?php if($terms):?>
                                    <li><a href="<?php echo BASE_URL?>terms-of-use">Terms of Use</a></li>
                                <?php endif?>
                                <?php if($privacy):?>
                                    <li><a href="<?php echo BASE_URL?>privacy-policy">Privacy Policy</a></li>
                                <?php endif?>
                            </ul>
                        </div>
                    </div>
                <?php endif?>
            </div>
        </div>
    </div>

    <div class="scroll-top">
        <i class="fas fa-angle-up"></i>
    </div>

    <script src="<?php echo PUBLIC_URL?>dist/js/custom.js"></script>

    <script>
        /* newsletter */
        $(document).ready(function(){
            $("form button[name=newsletter-form]").click(async function(e) {
                e.preventDefault()

                const el = $(this)
                const form = el.closest("form")
                const email = form.find("input[name=email]")
                const formData = new FormData()

                form.find(".input-error").each(function(){
                    $(this).html("")
                })
                el.addClass("pending")
                el.removeClass("active")
                
                await new Promise(resolve=>setTimeout(resolve,1000))
                formData.append("email",btoa(email.val()))

                $.ajax({
                    type: "POST",
                    url: "<?php echo BASE_URL?>page_newsletter_submit_ajax.php",
                    data: formData,
                    processData: false,
                    contentType:false,
                    success:function(response){
                        // console.log(response)
                        const res = JSON.parse(response)

                        if(res.success || res.error?.message) {
                            email.val("")

                            iziToast.show({
                                title: res.success?.message ?? res.error?.message,
                                position: "topRight",
                                color: res.success ? "green" : "red"
                            })
                        }

                        if(res.error) {
                            $.each(res.error, function (key,val) {
                                el.find(".input-error.input-"+key).text(val[0])
                            })    
                        }

                        el.removeClass("pending")
                        el.addClass("active")
                    }
                })
            })
        })
    </script>

        <!-- exception success -->
        <?php if(isset($success_message)):?>
            <script>
                iziToast.show({
                    message: "<?php echo $success_message?>",
                    position: "topRight",
                    color: "green"
                })
            </script>
        <?php endif?>

        <!-- exception error -->
        <?php if(isset($error_message)):?>
            <script>
                iziToast.show({
                    message: "<?php echo $error_message?>",
                    position: "topRight",
                    color: "red",
                })
            </script>
        <?php endif?>
        
        <!-- session success -->
        <?php if(isset($_SESSION["success"])):?>
            <script>
                iziToast.show({
                    message: "<?php echo $_SESSION["success"]?>",
                    position: "topRight",
                    color: "green",
                })
            </script>
        <?php unset($_SESSION["success"]); endif?>
        
        <!-- session error -->
        <?php if(isset($_SESSION["error"])):?>
            <script>
                iziToast.show({
                    message: "<?php echo $_SESSION["error"]?>",
                    position: "topRight",
                    color: "red"
                })
            </script>
        <?php unset($_SESSION["error"]); endif?>
</body>
</html>