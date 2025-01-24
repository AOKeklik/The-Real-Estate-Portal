<?php
    include("./layout_config.php");

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["why_choose_id"]) && isset($_POST["status"])){
        try{

            $why_choose_id=htmlspecialchars(trim(base64_decode($_POST["why_choose_id"])));
            $status=htmlspecialchars(trim(base64_decode($_POST["status"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized action request has been denied.");

            if(filter_var($why_choose_id,FILTER_VALIDATE_INT) === false || filter_var($status,FILTER_VALIDATE_INT) === false)
                throw new Exception("The requested item could not be found.");

            if(empty($errors)){
                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            why_choose_items
                        SET
                            status=?
                        WHERE
                            id=?
                    ");

                    if(!$stmt->execute([$status,$why_choose_id]))
                        throw new PDOException("An error occurred while updating the item. Please try again.");

                    echo json_encode(["success"=>["message"=>"The item has been successfully updated."]]);
                }catch(PDOException $err){
                    echo json_encode(["error"=>["message"=>$err->getMessage()]]);
                }
            }
        } catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>