<?php 
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-url");
        exit();
    }

    if(!isset($_GET["id"])){
        header("Location: ".BASE_URL."agent-properties");
        exit();
    }

    $property_id =$_GET["id"];

    try {
        $stmt=$pdo->prepare("
            select
                *
            from
                properties
            where
                id=?
            limit
                1
        ");
        $stmt->execute([$property_id]);
        if($stmt->rowCount() == 0)
            throw new PDOException("Property not found.");
        
    }catch(PDOException $err){
        $_SESSION["error"]=$err->getMessage();
        header("Location: ".BASE_URL."agent-properties");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            select 
                * 
            from 
                property_photos 
            where 
                property_id=?
            order by 
                id desc
        ");
        $stmt->execute([$property_id]);
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Photos";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <h4>Add Photo</h4>
                <form>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <img style="width:100%;height:auto;" src="https://placehold.co/1000x700" alt="">
                        </div>
                        <div class="col-md-10 mb-3">
                            <div class="form-group">
                                <input type="file" class="form-control" name="photo" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input data-id="<?php echo $property_id?>" type="submit" name="form" class="btn btn-primary btn-sm" value="Submit" />
                        </div>
                    </div>
                </form>

                <h4 class="mt-4">Existing Photos</h4>
                <div class="photo-all">
                    <div class="row" id="photo-gallery">
                        <?php if($stmt->rowCount() > 0): foreach($photos as $photo):?>
                            <div class="col-md-6 col-lg-3">
                                <div class="item item-delete">
                                    <a href="<?php echo PUBLIC_URL?>uploads/property/photo/<?php echo $photo["photo"]?>" class="magnific">
                                        <img src="<?php echo PUBLIC_URL?>uploads/property/photo/<?php echo $photo["photo"]?>" alt="" />
                                        <div class="icon">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <div class="bg"></div>
                                    </a>
                                </div>
                                <a data-id="<?php echo $photo["id"]?>" href="#" class="badge bg-danger mb_20">Delete</a>
                            </div>
                        <?php endforeach;else: echo "<p>No any result!<p>";endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".badge.bg-danger", function(e){
        e.preventDefault();

        if(!confirm('Are you sure?')) return

        $(".col-lg-9.col-md-12").css("pointer-events","none")

        const formData = new FormData()

        formData.append("id",$(this).data("id"))

        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL?>agent_property_photo_delete.php",
            contentType: false,
            processData: false,
            data: formData,
            success: function (response){
                console.log(response)
                const res = JSON.parse(response)

                iziToast.show({
                    message: res.success ? res.success.message : res.error.message,
                    position: "topRight",
                    color: res.success ? "green" : "red",
                    onClosing: function(){
                        if(res.success)
                            $(e.target).closest(".col-md-6.col-lg-3").slideUp(500, function(){
                                $(this).remove()

                                if($(".photo-all > .row").children().length === 0)
                                    $(".photo-all > .row").append("<p>No any result!<p>")
                            })

                        $(".col-lg-9.col-md-12").css("pointer-events","")
                    }
                })  
            }
        })
    })
    $("input[type=file]").change(function(e){
        $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
    })
    $("input[name=form]").click(function(e){
        e.preventDefault()

        $(".col-lg-9.col-md-12").css("pointer-events","none")

        const formData = new FormData()

        formData.append("property_id",$(e.target).data("id"))
        formData.append("photo",$("input[type=file]")[0].files[0])

        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL?>agent_property_photo_add.php",
            contentType: false,
            processData: false,
            data: formData,
            success: function (response){
                console.log(response)
                const res = JSON.parse(response)

                iziToast.show({
                    message: res.success ? res.success.message : res.error.message,
                    position: "topRight",
                    color: res.success ? "green" : "red",
                    onClosing: function(){
                        if(res.success) {
                            $(".photo-all > .row > p").remove()
                            $("#photo-gallery").prepend(res.success.html).slideDown(500, function (){
                                $("#photo-gallery .magnific").magnificPopup({
                                    type: 'image',
                                    gallery: {
                                        enabled: true
                                    }
                                })
                            })
                        }
                            
                        $(".col-lg-9.col-md-12").css("pointer-events","")
                        $("input[type=file]").val("")
                        $("form img").attr("src","https://placehold.co/1000x700")
                    }
                })  
            }
        })
    })
</script>

<?php include "./layout_footer.php"?>