<?php
    include("./layout_config.php");

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["location_status"])){
        try{
            $status=htmlspecialchars(trim(base64_decode($_POST["location_status"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized action request has been denied.");

            if(filter_var($status,FILTER_VALIDATE_INT) === false)
                throw new Exception("The requested setting could not be found.");

            if(empty($errors)){
                try{
                    $stmt=$pdo->prepare("
                        UPDATE 
                            settings
                        SET
                            location_status=?
                    ");

                    if(!$stmt->execute([$status]))
                        throw new PDOException("An error occurred while updating the setting. Please try again.");

                    echo json_encode(["success"=>["message"=>"The setting has been successfully updated."]]);
                }catch(PDOException $err){
                    echo json_encode(["error"=>["message"=>$err->getMessage()]]);
                }
            }
        } catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>