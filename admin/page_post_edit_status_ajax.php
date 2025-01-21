<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_id"]) && isset($_POST["status"])){
        try{
            $post_id = htmlspecialchars(trim(base64_decode($_POST["post_id"])));
            $status = htmlspecialchars(trim(base64_decode($_POST["status"])));
            
            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized update attempt detected!");

            if(filter_var($post_id,FILTER_VALIDATE_INT) === false || filter_var($status,FILTER_VALIDATE_INT) === false)
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
                

                $stmt = $pdo->prepare("
                    UPDATE 
                        posts 
                    SET 
                        status=?
                    WHERE 
                        id=?
                    LIMIT 
                        1
                ");
                if(!$stmt->execute([$status,$post_id]))
                    throw new PDOException("An error occurred during the updating process. Please try again!");

                echo json_encode(["success"=>["message"=>"The post is updated successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }        
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>