<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                privacy
            LIMIT
                1
        ");
        $stmt->execute();
        $privacy=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
    
    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $title=htmlspecialchars(trim($_POST["title"]));
        $text=htmlspecialchars(trim($_POST["text"]));

        if($title === "")
            $errors["title"][] = "<small class='form-text text-danger'>The title field is required!</small>";

        if(strlen($title) <= 3 || strlen($title) >= 500)
            $errors["title"][] = "<small class='form-text text-danger'>The title must be between 3 and 500 characters!</small>";
        
        if($text === "")
            $errors["text"][] = "<small class='form-text text-danger'>The text field is required!</small>";

        if(strlen($text) <= 30 || strlen($text) >= 10000)
            $errors["text"][] = "<small class='form-text text-danger'>The text must be between 30 and 10000 characters!</small>";

        if(empty($errors)){
            try{
                $stmt=$pdo->prepare("
                    UPDATE 
                        privacy
                    SET
                        text=?,
                        title=?
                ");
                
                if(!$stmt->execute([$text,$title]))
                    throw new PDOException("An error occurred while updating the privacy policy text. Please try again.");

                $_SESSION["success"]="Privacy policy text has been successfully updated.";
                header("Location: ".ADMIN_URL."privacy-edit");
                exit();
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1><?php echo setPageTitle($current_page)?></h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>dashboard" class="btn btn-primary"><i class="fas fa-eye"></i> Dashboard</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">   
                                    <div class="col-md-3">
                                        <span class="badge badge-success" style="<?php if($privacy["status"] == 0) echo "display:none"?>">Yes</span>
                                        <span class="badge badge-danger" style="<?php if($privacy["status"] == 1) echo "display:none"?>">No</span>
                                    </div>                                         
                                    <div class="col-md-9">
                                        <div class="wrapper-loader-btn" style="display: inline-block;">
                                            <span class="button-loader"></span>
                                            <input 
                                                name="status" 
                                                <?php if($privacy["status"] == 1) echo "checked"?> 
                                                data-post-id="<?php echo $privacy["id"]?>"
                                                type="checkbox" 
                                                data-toggle="toggle" 
                                                data-onstyle="success" 
                                                data-offstyle="danger"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Title *</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo $privacy["title"]?>">
                                    <?php if(isset($errors["title"])) echo $errors["title"][0]?>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description *</label>
                                    <textarea name="text" class="form-control editor" cols="30" rows="10"><?php echo $privacy["text"]?></textarea>
                                    <?php if(isset($errors["text"])) echo $errors["text"][0]?>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        $("input[name=status]").change(async function(e){
            
            const el=$(this)
            const div=el.closest(".wrapper-loader-btn")
            const status=el.prop("checked") ? 1 : 0
            const on = el.closest("form").find(".badge.badge-success")
            const off = el.closest("form").find(".badge.badge-danger")
            const formData = new FormData()

            formData.append("status",btoa(status))
            div.addClass("pending")
            div.removeClass("active")
            await new Promise(resolve=>setTimeout(resolve,1000))
            
            $.ajax({
                url: "<?php echo ADMIN_URL?>page_privacy_edit_status_ajax.php",
                type: "POST",
                processData:false,
                contentType:false,
                data: formData,
                success:function(response){
                    console.log(response)
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success.message ?? res.error.message,
                        position: "topRight",
                        color: res.success ? "green" : "red"
                    })

                    if(res.success){
                        if(status === 1){
                            on.show()
                            off.hide()
                        }else{
                            on.hide()
                            off.show()
                        }
                    }

                    div.removeClass("pending")
                    div.addClass("active")
                }
            })

        })
    })
</script>    
<?php include "./layout_footer.php"?>