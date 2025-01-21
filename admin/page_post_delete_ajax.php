<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_id"])){
        try{
            $post_id = htmlspecialchars(trim(base64_decode($_POST["post_id"])));
            
            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized update attempt detected!");

            if(filter_var($post_id,FILTER_VALIDATE_INT) === false)
                throw new Exception("The selected post is not available!");

            try{
                $stmt=$pdo->prepare("
                    SELECT
                        *
                    FROM
                        posts
                    WHERE
                        id=?
                    LIMIT
                        1
                ");
                $stmt->execute([$post_id]);        
                
                if($stmt->rowCount() == 0)
                    throw new PDOException("The selected post is not available!");

                $post=$stmt->fetch(pdo::FETCH_ASSOC);
                $path="../public/uploads/post/";

                if(is_file($path.$post["photo"]))
                    unlink($path.$post["photo"]);

                $stmt = $pdo->prepare("
                    DELETE FROM
                        posts
                    WHERE 
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$post_id]);

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred during the deleting process. Please try again!");

                echo json_encode(["success"=>["message"=>"The post is deleted successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }        
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>