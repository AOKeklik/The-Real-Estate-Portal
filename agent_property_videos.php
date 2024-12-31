<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(!isset($_GET["id"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    $property_id=$_GET["id"];

    try{
        $stmt = $pdo->prepare("select * from property_videos where property_id=? order by id desc");
        $stmt->execute([$property_id]);
        $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Videos</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <h4>Add Video</h4>
                <form action="">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <img style="width:100%" src="https://placehold.co/1000x700" alt="">
                        </div>
                        <div class="col-md-10 mb-3">
                            <div class="form-group">
                                <input type="text" name="code" class="form-control" placeholder="Video Code" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <input data-id="<?php echo $property_id?>" type="submit" class="btn btn-primary btn-sm" name="form" value="Submit" />
                        </div>
                    </div>
                </form>

                <h4 class="mt-4">Existing Videos</h4>
                <div class="video-all">
                    <div class="row">
                        <?php if($stmt->rowCount() > 0): foreach($videos as $video):?>
                            <div class="col-md-6 col-lg-3">
                                <div class="item item-delete">
                                    <a class="video-button" href="http://www.youtube.com/watch?v=<?php echo $video["code"]?>">
                                        <img src="http://img.youtube.com/vi/<?php echo $video["code"]?>/0.jpg" alt="" />
                                        <div class="icon">
                                            <i class="far fa-play-circle"></i>
                                        </div>
                                        <div class="bg"></div>
                                    </a>
                                </div>
                                <a href="" data-id="<?php echo $video["id"]?>" class="badge bg-danger mb_20">Delete</a>
                            </div>
                        <?php endforeach;else: echo "<p>No records found!</p>";endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("input[name=code]").change(function(e){
        $("form img").attr("src",`http://img.youtube.com/vi/${$(e.target).val()}/0.jpg`)
    })
    $(document).on("click",".badge.bg-danger",function(e){
        e.preventDefault()

        if(!confirm('Are you sure?')) return

        $(".col-lg-9.col-md-12").css("pointer-events","none")

        const formData = new FormData()

        formData.append("id",$(e.target).data("id"))

        $.ajax({
            url: "<?php echo BASE_URL?>agent_property_video_delete.php",
            type: "POST",
            contentType: false,
            processData: false,
            data: formData,
            success: function(response){
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

                                if($(".video-all > .row").children().length === 0)
                                    $(".video-all > .row").append("<p>No any result!<p>")
                            })

                        $(".col-lg-9.col-md-12").css("pointer-events","")
                    }
                })
            }
        })
    })
    $("input[name=form]").click(function(e){
        e.preventDefault()

        $(".col-lg-9.col-md-12").css("pointer-events","none")

        const formData = new FormData()

        formData.append("property_id",$(e.target).data("id"))
        formData.append("code",$("input[name=code]").val())

        $.ajax({
            type: "POST",
            url: "<?php echo BASE_URL?>agent_property_video_add.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response){
                console.log(response)
                const res = JSON.parse(response)

                iziToast.show({
                    message: res.success ? res.success.message : res.error.message,
                    position: "topRight",
                    color: res.success ? "green" : "red",
                    onClosing: function(){
                        if(res.success) {
                            $(".video-all > .row > p").remove()
                            $(".video-all > .row").prepend(res.success.html).slideDown(500, function (){
                                $(".video-button").magnificPopup({
                                    type: "iframe",
                                    gallery: {
                                        enabled: true,
                                    },
                                })
                            })
                        }

                        $(".col-lg-9.col-md-12").css("pointer-events","")
                        $("form img").attr("src","https://placehold.co/1000x700")
                        $("input[name=code]").val("")
                    }
                })
            }
        })
    })
</script>
<?php include "./layout_footer.php"?>