<?php
    include "./layout_config.php";


    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["why_choose_id"])) {
        try{
            $why_choose_id=htmlspecialchars(trim(base64_decode($_POST["why_choose_id"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to delete this item.");

            if(filter_var($why_choose_id,FILTER_VALIDATE_INT) === false)
                throw new PDOException("The item you are trying to delete could not be found.");

            try{
                $stmt=$pdo->prepare("
                    DELETE FROM 
                        why_choose_items
                    WHERE
                        id=?
                    LIMIT 
                        1
                ");
                $stmt->execute([$why_choose_id]);

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while attempting to delete the item.");

                echo json_encode(["success"=>["message"=>"The item has been deleted successfully."]]);
            }catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }