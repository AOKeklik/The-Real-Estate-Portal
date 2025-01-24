<?php
    include "./layout_top.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                posts.*,
                COUNT(post_views.id) AS views_count
            FROM
                posts
            LEFT JOIN
                post_views ON post_views.post_id = posts.id
            GROUP BY
                posts.id
            ORDER BY
                posts.id DESC;
        "); 
        $stmt->execute();
        $posts=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Posts</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL?>post-add" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
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
                                        <th>Slug</th>
                                        <th>Title</th>
                                        <th>Posted</th>
                                        <th>Views</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($stmt->rowCount() > 0): foreach($posts as $post):?>
                                            <tr>
                                                <td><?php echo $post["id"]?></td>
                                                <td>
                                                    <?php if(is_null($post["photo"])):?>
                                                        <img class="w_50" src="https://placehold.co/600x400" alt="">
                                                    <?php else:?>
                                                        <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/post/<?php echo $post["photo"]?>" alt="">
                                                    <?php endif?>
                                                </td>
                                                <td><?php echo $post["slug"]?></td>
                                                <td><?php echo $post["title"]?></td>
                                                <td><?php echo $post["posted_on"]?></td>
                                                <td><?php echo $post["views_count"]?></td>
                                                <td>
                                                    <span class="badge badge-success" style="<?php if($post["status"] == 0) echo "display:none"?>">Yes</span>
                                                    <span class="badge badge-danger" style="<?php if($post["status"] == 1) echo "display:none"?>">No</span>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <div id="wrapper-loader-btn" style="display: inline-block;">
                                                        <span class="button-loader"></span>
                                                        <input 
                                                            name="status" 
                                                            <?php if($post["status"] == 1) echo "checked"?> 
                                                            data-post-id="<?php echo $post["id"]?>"
                                                            type="checkbox" 
                                                            data-toggle="toggle" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger"
                                                        >
                                                    </div>                                                    
                                                    <a href="<?php echo ADMIN_URL?>post-edit/<?php echo $post["id"]?>" class="btn btn-warning">Edit</a>
                                                    <a  data-post-id="<?php echo $post["id"]?>" id="delete-btn" href="" class="btn btn-danger">
                                                        <span class="button-loader"></span>
                                                        <span>Delete</span>
                                                    </a>
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
    /* delete */
    $(document).ready(function(){
        $(document).on("click","#delete-btn",async function(e){
            e.preventDefault()

            if(!confirm('Are you sure?')) return

            const el =$(this)
            const parent = $(this).closest("tr")
            const postId = $(this).data("post-id")
            const formData = new FormData()

            el.addClass("pending")
            el.removeClass("active")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("post_id",btoa(postId))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_post_delete_ajax.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success){
                        parent.slideUp(500,function(){
                            el.addClass("active")
                            el.removeClass("pending")
                        })
                    }

                    if(res.error){
                        el.addClass("active")
                        el.removeClass("pending")
                    }
                }
            })
        })
    })

    /* update */
    $(document).ready(function(){
        $("input[name=status]").change( async function(e){
            const badgeSuccess = $(this).closest("tr").find(".badge.badge-success")
            const badgeDanger = $(this).closest("tr").find(".badge.badge-danger")
            
            const parent = $(this).closest("#wrapper-loader-btn")
            const status = $(this).prop("checked") ? 1 : 0
            const postId = $(this).data("post-id")
            const formData = new FormData()

            parent.attr("class","pending")

            await new Promise(resolve => setTimeout(resolve,1000))
            formData.append("post_id",btoa(postId))
            formData.append("status",btoa(status))

            $.ajax({
                type: "POST",
                url: "<?php echo ADMIN_URL?>page_post_edit_status_ajax.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const res = JSON.parse(response)

                    iziToast.show({
                        title: res.success?.message ?? res.error.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success){
                        parent.attr("class","active")
                        if(status === 1){
                            badgeSuccess.show()
                            badgeDanger.hide()
                        } else {
                            badgeSuccess.hide()
                            badgeDanger.show()
                        }
                    }

                    if(res.error){
                        parent.attr("class","active")
                    }
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>