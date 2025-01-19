<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["testimonial_id"]) && isset($_POST["status"])){
        try{
            $status=base64_decode($_POST["status"]) == "on" ? 1 : 0;
            $testimonial_id=htmlspecialchars(trim(base64_decode($_POST["testimonial_id"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("You are not allowed to update this item.");

            if(filter_var($status,FILTER_VALIDATE_INT) === false)
                throw new Exception("The item you want to update does not exist.");

            if(filter_var($testimonial_id,FILTER_VALIDATE_INT) === false)
                throw new Exception("The item you want to update does not exist.");

            try{
                $stmt=$pdo->prepare("
                    UPDATE
                        testimonials
                    SET
                        status=?
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$status,$testimonial_id]);
                
                if($stmt->rowCount() == 0)
                    throw new Exception("The item you want to update does not exist.");

                echo json_encode(["success"=>["message"=>"Testimonial updated successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["success"=>["message"=>$err->getMessage()]]);
            }
            
        }catch(Exception $err){
            echo json_encode(["success"=>["message"=>$err->getMessage()]]);
        }
    }