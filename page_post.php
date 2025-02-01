`<?php
    include "./layout_top.php";

    if(!isset($_GET["post_id"]) || !isset($_GET["post_slug"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    $post_id=$_GET["post_id"];
    $post_slug=$_GET["post_slug"];
    $ip_address;

    if(isset($_SERVER["HTTP_X_FORWARDER_FOR"]))
        $ip_address=$_SERVER["HTTP_X_FORWARDER_FOR"];
    elseif($_SERVER["REMOTE_ADDR"] === "::1")
        $ip_address="127.0.0.1";
    else
        $ip_address=$_SERVER["REMOTE_ADDR"];

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                posts
            WHERE
                id=?
            AND
                slug=?
            AND
                status=?
            LIMIT
                1
        ");
        $stmt->execute([$post_id,$post_slug,1]);

        if($stmt->rowCount() == 0){
            header("Location: ".BASE_URL."404");
            exit();
        }

        $post=$stmt->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                count(id) as views_count
            FROM
                post_views
            WHERE
                post_id=?
            AND
                ip_address=?
        ");
        $stmt->execute([$post_id,$ip_address]);
        $views_count=$stmt->fetch(pdo::FETCH_ASSOC);

        echo $views_count['views_count'];
        if($views_count['views_count'] == 0) {
            $stmt=$pdo->prepare("
                INSERT INTO  post_views
                    (post_id,ip_address)
                VALUES
                    (?,?)
            ");
            $stmt->execute([$post_id,$ip_address]);
        }

        $stmt=$pdo->prepare("
            SELECT
                count(id) as views_count
            FROM
                post_views
            WHERE
                post_id=?
            AND
                ip_address=?
        ");
        $stmt->execute([$post_id,$ip_address]);
        $views_count=$stmt->fetch(pdo::FETCH_ASSOC);

    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title=$post["title"];
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="featured-photo">
                    <?php if(is_null($post["photo"])):?>
                        <img src="https://placehold.co/600x400" alt="" />
                    <?php else:?>
                        <img src="<?php echo PUBLIC_URL?>uploads/post/<?php echo $post["photo"]?>" alt="" />
                    <?php endif?>
                </div>
                <div class="sub">
                    <div class="item">
                        <b><i class="fa fa-clock-o"></i></b>
                        <?php echo date("d M, Y - H:i:s",strtotime($post["posted_on"]))?>
                    </div>
                    <div class="item">
                        <b><i class="fa fa-eye"></i></b>
                        <?php echo $views_count["views_count"]?>
                    </div>
                </div>
                <div class="main-text">
                    <?php echo html_entity_decode($post["description"])?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "./layout_footer.php"?>`