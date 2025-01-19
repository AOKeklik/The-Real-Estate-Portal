<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["testimonial_id"])){
        try{
            $testimonial_id=htmlspecialchars(trim(base64_decode($_POST["testimonial_id"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("You are not allowed to delete this item.");

            if(!filter_var($testimonial_id,FILTER_VALIDATE_INT))
                throw new Exception("The item you want to delete does not exist.");

            try{
                $stmt=$pdo->prepare("
                    SELECT
                        *
                    FROM
                        testimonials
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$testimonial_id]);
                $testimonial=$stmt->fetch(pdo::FETCH_ASSOC);

                $path="../public/uploads/testimonial/";
                if(is_file($path.$testimonial["photo"]))
                    unlink($path.$testimonial["photo"]);

                $stmt=$pdo->prepare("
                    DELETE FROM
                        testimonials
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$testimonial_id]);
                
                if($stmt->rowCount() == 0)
                    throw new Exception("The item you want to delete does not exist.");

                echo json_encode(["success"=>["message"=>"Testimonial deleted successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["success"=>["message"=>$err->getMessage()]]);
            }
            
        }catch(Exception $err){
            echo json_encode(["success"=>["message"=>$err->getMessage()]]);
        }
    }